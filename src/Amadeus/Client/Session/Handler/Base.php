<?php
/**
 * amadeus-ws-client
 *
 * Copyright 2015 Amadeus Benelux NV
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package Amadeus
 * @license https://opensource.org/licenses/Apache-2.0 Apache 2.0
 */

namespace Amadeus\Client\Session\Handler;

use Amadeus\Client;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Params\SessionHandlerParams;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

/**
 * Base Session Handler
 *
 * Session handler will manage everything related to the session with the Amadeus Web Services server:
 * - be configurable to handle different versions of authentication mechanisms depending on the WSDL received
 * - decide if a separate authentication message is needed and if so, send it
 *
 * @package Amadeus\Client\Session\Handler
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
abstract class Base implements HandlerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * XPATH query to retrieve all operations from a WSDL
     *
     * @var string
     */
    const XPATH_ALL_OPERATIONS = '/wsdl:definitions/wsdl:portType/wsdl:operation/@name';

    /**
     * XPATH query to retrieve WSDL imports from a WSDL.
     */
    const XPATH_IMPORTS = '/wsdl:definitions/wsdl:import/@location';

    /**
     * XPATH query to retrieve the full operation name + version from a WSDL for a given operation.
     *
     * @var string
     */
    const XPATH_VERSION_FOR_OPERATION = "string(/wsdl:definitions/wsdl:message[contains(./@name, '%s_')]/@name)";

    const XPATH_ALT_VERSION_FOR_OPERATION = "string(//wsdl:operation[contains(./@name, '%s')]/wsdl:input/@message)";


    /**
     * Status variable to know wether the given session is in a certain context.
     *
     * @todo implement this feature - currently the application using the client must know the context itself.
     * @var bool
     */
    protected $hasContext = false;

    /**
     * The context of the currently active session
     *
     * @todo implement this feature - currently the application using the client must know the context itself.
     * @var mixed
     */
    protected $context;

    /**
     * Session information:
     * - session ID
     * - sequence number
     * - security Token
     *
     * @var array
     */
    protected $sessionData = [
        'sessionId' => null,
        'sequenceNumber' => null,
        'securityToken' => null
    ];

    /**
     * Status variable to know if the session is currently logged in
     *
     * @var bool
     */
    protected $isAuthenticated = false;

    /**
     * @var array[string]\SoapClient
     */
    protected $soapClients = [];

    /**
     * Default SoapClient options used during initialisation
     *
     * Can be overridden by providing custom options in
     * Amadeus\Client\Params\SessionHandlerParams::$soapClientOptions
     *
     * @var array
     */
    protected $soapClientOptions = [
        'trace' => 1,
        'exceptions' => 1,
        'soap_version' => SOAP_1_1
    ];

    /**
     * @var SessionHandlerParams
     */
    protected $params;

    /**
     * Dom Document array where the WSDL's contents will be loaded
     *
     * format:
     * [
     *     '7d36c7b8' => \DOMDocument,
     *     '7e84f2537' => \DOMDocument
     * ]
     *
     * @var array
     */
    protected $wsdlDomDoc;
    /**
     * To query the WSDL contents
     *
     * format:
     * [
     *     '7d36c7b8' => \DOMXpath,
     *     '7e84f2537' => \DOMXpath
     * ]
     *
     * @var array
     */
    protected $wsdlDomXpath;

    /**
     * A map of which messages are available in the provided WSDL's
     *
     * format:
     * [
     *      'PNR_Retrieve' => [
     *          'version' => '14.1',
     *          'wsdl' => '7d36c7b8'
     *      ],
     *      'Media_GetMedia' => [
     *          'version' => '14.1',
     *          'wsdl' => '7e84f2537'
     *      ]
     * ]
     *
     * @var array
     */
    protected $messagesAndVersions = [];

    /**
     * List of WSDL ID's and their path
     *
     * format:
     * [
     *      '7d36c7b8' => '/path/to/wsdl.wsdl'
     * ]
     *
     * @var array
     */
    protected $wsdlIds = [];


    /**
     * Get the session parameters of the active session
     *
     * @return array|null
     */
    public function getSessionData()
    {
        return $this->sessionData;
    }

    /**
     * Set the session data to continue a previously started session.
     *
     * @param array $sessionData
     * @return bool
     */
    public function setSessionData(array $sessionData)
    {
        if (isset($sessionData['sessionId'], $sessionData['sequenceNumber'], $sessionData['securityToken'])) {
            $this->sessionData['sessionId'] = $sessionData['sessionId'];
            $this->sessionData['sequenceNumber'] = $sessionData['sequenceNumber'];
            $this->sessionData['securityToken'] = $sessionData['securityToken'];
            $this->isAuthenticated = true;
        } else {
            $this->isAuthenticated = false;
        }

        return $this->isAuthenticated;
    }


    /**
     * @param SessionHandlerParams $params
     */
    public function __construct(SessionHandlerParams $params)
    {
        $this->params = $params;
        if ($params->logger instanceof LoggerInterface) {
            $this->setLogger($params->logger);
            $this->log(LogLevel::INFO, __METHOD__."(): Logger started.");
        }
        if ($params->overrideSoapClient instanceof \SoapClient) {
            $this->soapClients[$params->overrideSoapClientWsdlName] = $params->overrideSoapClient;
        }
        $this->setStateful($params->stateful);
    }


    /**
     * @param string $messageName Method Operation name as defined in the WSDL.
     * @param BaseWsMessage $messageBody
     * @param array $messageOptions options: bool 'asString', bool 'endSession'
     * @return SendResult
     * @throws \InvalidArgumentException
     * @throws Client\Exception
     * @throws \SoapFault
     */
    public function sendMessage($messageName, Client\Struct\BaseWsMessage $messageBody, $messageOptions = [])
    {
        $result = new SendResult(
            $this->getActiveVersionFor($messageName)
        );

        $this->prepareForNextMessage($messageName, $messageOptions);

        try {
            $result->responseObject = $this->getSoapClient($messageName)->$messageName($messageBody);

            $this->logRequestAndResponse($messageName);

            $this->handlePostMessage($messageName, $this->getLastResponse($messageName), $messageOptions, $result);
        } catch (\SoapFault $ex) {
            $this->log(
                LogLevel::ERROR,
                "SOAPFAULT while sending message ".$messageName.": ".$ex->getMessage().
                " code: ".$ex->getCode()." at ".$ex->getFile()." line ".$ex->getLine().
                ": \n".$ex->getTraceAsString()
            );
            $this->logRequestAndResponse($messageName);
            $result->exception = $ex;
        } catch (\Exception $ex) {
            // We should only come here when the XSL extension is not enabled
            // or the XSLT transformation file is unreadable
            $this->log(
                LogLevel::ERROR,
                "EXCEPTION while sending message ".$messageName.": ".$ex->getMessage().
                " at ".$ex->getFile()." line ".$ex->getLine().": \n".$ex->getTraceAsString()
            );
            $this->logRequestAndResponse($messageName);
            throw new Client\Exception($ex->getMessage(), $ex->getCode(), $ex);
        }

        $result->responseXml = Client\Util\MsgBodyExtractor::extract($this->getLastResponse($messageName));

        return $result;
    }

    /**
     * Prepare to send a next message and checks if authenticated
     *
     * @param string $messageName
     * @param array $messageOptions
     */
    abstract protected function prepareForNextMessage($messageName, $messageOptions);

    /**
     * Handles post message actions
     *
     * Handles session state based on received response
     *
     * @param string $messageName
     * @param string $lastResponse
     * @param array $messageOptions
     * @param mixed $result
     */
    abstract protected function handlePostMessage($messageName, $lastResponse, $messageOptions, $result);

    /**
     * Get the last raw XML message that was sent out
     *
     * @param string $msgName
     * @return string|null
     */
    public function getLastRequest($msgName)
    {
        $lastRequest = null;
        $soapClient = $this->getSoapClient($msgName);

        if ($soapClient instanceof \SoapClient) {
            $lastRequest = $soapClient->__getLastRequest();
        }

        return $lastRequest;
    }

    /**
     * Get the last raw XML message that was received
     *
     * @param string $msgName
     * @return string|null
     */
    public function getLastResponse($msgName)
    {
        $lastResponse = null;
        $soapClient = $this->getSoapClient($msgName);

        if ($soapClient instanceof \SoapClient) {
            $lastResponse = $soapClient->__getLastResponse();
        }

        return $lastResponse;
    }

    /**
     * Get the office that we are using to sign in to.
     *
     * @return string
     */
    public function getOriginatorOffice()
    {
        return $this->params->authParams->officeId;
    }


    /**
     * Extract the Messages and versions from the loaded WSDL file.
     *
     * Result is an associative array: keys are message names, values are versions.
     *
     * @return array
     */
    public function getMessagesAndVersions()
    {
        if (empty($this->messagesAndVersions)) {
            $this->messagesAndVersions = $this->loadMessagesAndVersions();
        }

        return $this->messagesAndVersions;
    }

    /**
     * Loads messages & versions from WSDL.
     *
     * @return array
     */
    protected function loadMessagesAndVersions()
    {
        $msgAndVer = [];

        $wsdls = $this->params->wsdl;

        foreach ($wsdls as $wsdl) {
            $wsdlIdentifier = $this->makeWsdlIdentifier($wsdl);

            $this->wsdlIds[$wsdlIdentifier] = $wsdl;

            $this->loadWsdlXpath($wsdl, $wsdlIdentifier);

            $operations = $this->wsdlDomXpath[$wsdlIdentifier]->query(self::XPATH_ALL_OPERATIONS);
            if ($operations->length === 0) {
                //No operations found - are there any external WSDLs being imported?
                $imports = $this->wsdlDomXpath[$wsdlIdentifier]->query(self::XPATH_IMPORTS);
                $operations = [];

                foreach ($imports as $import) {
                    if (!empty($import->value)) {
                        $tmpMsg = $this->getMessagesAndVersionsFromImportedWsdl(
                            $import->value,
                            $wsdl,
                            $wsdlIdentifier
                        );
                        foreach ($tmpMsg as $msgName => $msgInfo) {
                            $msgAndVer[$msgName] = $msgInfo;
                        }
                    }
                }
            }

            foreach ($operations as $operation) {
                if (!empty($operation->value)) {
                    $fullVersion = $this->wsdlDomXpath[$wsdlIdentifier]->evaluate(
                        sprintf(self::XPATH_VERSION_FOR_OPERATION, $operation->value)
                    );

                    if (!empty($fullVersion)) {
                        $extractedVersion = $this->extractMessageVersion($fullVersion);
                        $msgAndVer[$operation->value] = [
                            'version' => $extractedVersion,
                            'wsdl' => $wsdlIdentifier
                        ];
                    }
                }
            }
        }

        return $msgAndVer;
    }

    /**
     * Generates a unique identifier for a wsdl based on its path.
     *
     * @param string $wsdlPath
     *
     * @return string
     */
    protected function makeWsdlIdentifier($wsdlPath)
    {
        return sprintf('%x', crc32($wsdlPath));
    }

    /**
     * extractMessageVersion
     *
     * extracts "4.1" from a string like "Security_SignOut_4_1"
     * or "1.00" from a string like "tns:AMA_MediaGetMediaRQ_1.000"
     *
     * @param string $fullVersionString
     * @return string
     */
    protected function extractMessageVersion($fullVersionString)
    {
        $marker = strpos($fullVersionString, '_', strpos($fullVersionString, '_')+1);

        $num = substr($fullVersionString, $marker+1);

        return str_replace('_', '.', $num);
    }

    /**
     * Load the WSDL contents to a queryable DOMXpath.
     *
     * @param string $wsdlFilePath
     * @param string $wsdlId
     * @uses $this->wsdlDomDoc
     * @uses $this->wsdlDomXpath
     * @throws Client\InvalidWsdlFileException when WSDL cannot be found.
     */
    protected function loadWsdlXpath($wsdlFilePath, $wsdlId)
    {
        if (!isset($this->wsdlDomXpath[$wsdlId]) || is_null($this->wsdlDomXpath[$wsdlId])) {
            $wsdlContent = file_get_contents($wsdlFilePath);

            if ($wsdlContent !== false) {
                $this->wsdlDomDoc[$wsdlId] = new \DOMDocument('1.0', 'UTF-8');
                $this->wsdlDomDoc[$wsdlId]->loadXML($wsdlContent);
                $this->wsdlDomXpath[$wsdlId] = new \DOMXPath($this->wsdlDomDoc[$wsdlId]);
                $this->wsdlDomXpath[$wsdlId]->registerNamespace(
                    'wsdl',
                    'http://schemas.xmlsoap.org/wsdl/'
                );
                $this->wsdlDomXpath[$wsdlId]->registerNamespace(
                    'soap',
                    'http://schemas.xmlsoap.org/wsdl/soap/'
                );
            } else {
                throw new Client\InvalidWsdlFileException('WSDL ' . $wsdlFilePath . ' could not be loaded');
            }
        }
    }



    /**
     * Get the version number active in the WSDL for the given message
     *
     * @param $messageName
     * @return float|string|null
     */
    protected function getActiveVersionFor($messageName)
    {
        $msgAndVer = $this->getMessagesAndVersions();

        $found = null;

        if (isset($msgAndVer[$messageName]) && isset($msgAndVer[$messageName]['version'])) {
            $found = $msgAndVer[$messageName]['version'];
        }

        return $found;
    }

    /**
     * Get the WSDL ID for the given message
     *
     * @param $messageName
     * @return string|null
     */
    protected function getWsdlIdFor($messageName)
    {
        $msgAndVer = $this->getMessagesAndVersions();

        if (isset($msgAndVer[$messageName]) && isset($msgAndVer[$messageName]['wsdl'])) {
            return $msgAndVer[$messageName]['wsdl'];
        }

        return null;
    }


    /**
     * @param string $msgName
     * @return \SoapClient
     */
    protected function getSoapClient($msgName)
    {
        $wsdlId = $this->getWsdlIdFor($msgName);

        if (!empty($msgName)) {
            if (!isset($this->soapClients[$wsdlId]) || !($this->soapClients[$wsdlId] instanceof \SoapClient)) {
                $this->soapClients[$wsdlId] = $this->initSoapClient($wsdlId);
            }

            return $this->soapClients[$wsdlId];
        } else {
            return null;
        }
    }

    /**
     * @param string $wsdlId
     * @return \SoapClient
     */
    protected function initSoapClient($wsdlId)
    {
        $wsdlPath = $this->wsdlIds[$wsdlId];

        $client = new Client\SoapClient(
            $wsdlPath,
            $this->makeSoapClientOptions(),
            $this->params->logger
        );

        return $client;
    }

    /**
     * Make Soap Header specific SoapClient options
     *
     * @return array
     */
    abstract protected function makeSoapClientOptions();

    /**
     * Get Messages & Versions from an imported WSDL file
     *
     * Imported wsdl's are a little different, they require a different query
     * to extract the version nrs.
     *
     * @param string $import
     * @param string $wsdlPath
     * @param string $wsdlIdentifier
     * @return array
     * @throws Client\InvalidWsdlFileException when the WSDL import could not be loaded.
     */
    protected function getMessagesAndVersionsFromImportedWsdl($import, $wsdlPath, $wsdlIdentifier)
    {
        $msgAndVer = [];
        $domDoc = null;
        $domXpath = null;

        $importPath = realpath(dirname($wsdlPath)) . DIRECTORY_SEPARATOR . $import;
        $wsdlContent = file_get_contents($importPath);

        if ($wsdlContent !== false) {
            $domDoc = new \DOMDocument('1.0', 'UTF-8');
            $ok = $domDoc->loadXML($wsdlContent);

            if ($ok === true) {
                $domXpath = new \DOMXPath($domDoc);
                $domXpath->registerNamespace(
                    'wsdl',
                    'http://schemas.xmlsoap.org/wsdl/'
                );
                $domXpath->registerNamespace(
                    'soap',
                    'http://schemas.xmlsoap.org/wsdl/soap/'
                );
            }
        } else {
            throw new Client\InvalidWsdlFileException('WSDL ' . $importPath . ' import could not be loaded');
        }

        if ($domXpath instanceof \DOMXPath) {
            $nodeList = $domXpath->query(self::XPATH_ALL_OPERATIONS);

            foreach ($nodeList as $operation) {
                if (!empty($operation->value)) {
                    $fullVersion = $domXpath->evaluate(
                        sprintf(self::XPATH_ALT_VERSION_FOR_OPERATION, $operation->value)
                    );

                    if (!empty($fullVersion)) {
                        $extractedVersion = $this->extractMessageVersion($fullVersion);
                        $msgAndVer[$operation->value] = [
                            'version' => $extractedVersion,
                            'wsdl' => $wsdlIdentifier
                        ];
                    }
                }
            }
        }

        return $msgAndVer;
    }

    /**
     * @param string $messageName
     * @uses $this->log
     */
    protected function logRequestAndResponse($messageName)
    {
        $this->log(
            LogLevel::INFO,
            'Called ' . $messageName . ' with request: ' . $this->getSoapClient($messageName)->__getLastRequest()
        );
        $this->log(
            LogLevel::INFO,
            'Response:  ' . $this->getSoapClient($messageName)->__getLastResponse()
        );
    }

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    protected function log($level, $message, $context = [])
    {
        if (is_null($this->logger)) {
            $this->setLogger(new NullLogger());
        }

        return $this->logger->log($level, $message, $context);
    }
}
