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
     * XPATH query to retrieve all operations from the WSDL
     *
     * @var string
     */
    const XPATH_ALL_OPERATIONS = '/wsdl:definitions/wsdl:portType/wsdl:operation/@name';
    /**
     * XPATH query to retrieve the full operation name + version from the WSDL for a given operation.
     *
     * @var string
     */
    const XPATH_VERSION_FOR_OPERATION = "string(/wsdl:definitions/wsdl:message[contains(./@name, '%s_')]/@name)";


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
     * @var \SoapClient
     */
    protected $soapClient;


    /**
     * Default SoapClient options used during initialisation
     *
     * Can be overridden by providing custom options in
     * Amadeus\Client\Params\SessionHandlerParams::$soapClientOptions
     *
     * @var array
     */
    protected $soapClientOptions = [
        'trace' 		=> 1,
        'exceptions' 	=> 1,
        'soap_version' 	=> SOAP_1_1
    ];


    /**
     * @var SessionHandlerParams
     */
    protected $params;


    /**
     * Dom Document where the WSDL's contents will be loaded
     *
     * @var \DOMDocument
     */
    protected $wsdlDomDoc;
    /**
     * To query the WSDL contents
     *
     * @var \DOMXpath
     */
    protected $wsdlDomXpath;

    /**
     * @var array
     */
    protected $messagesAndVersions;

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
        if($params->logger instanceof LoggerInterface) {
            $this->setLogger($params->logger);
            $this->log(LogLevel::INFO, __METHOD__. "(): Logger started.");
        }
        if ($params->overrideSoapClient instanceof \SoapClient) {
            $this->soapClient = $params->overrideSoapClient;
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
            $result->responseObject = $this->getSoapClient()->$messageName($messageBody);

            $this->logRequestAndResponse($messageName);

            $this->handlePostMessage($messageName, $this->getLastResponse(), $messageOptions, $result);

        } catch(\SoapFault $ex) {
            $this->log(
                LogLevel::ERROR,
                "SOAPFAULT while sending message " . $messageName . ": " .
                $ex->getMessage() . " code: " .$ex->getCode() . " at " . $ex->getFile() .
                " line " . $ex->getLine() . ": \n" . $ex->getTraceAsString()
            );
            $this->logRequestAndResponse($messageName);
            $result->exception = $ex;
        } catch (\Exception $ex) {
            // We should only come here when the XSL extension is not enabled or the XSLT transformation file
            // is unreadable
            $this->log(
                LogLevel::ERROR,
                "EXCEPTION while sending message " . $messageName . ": " .
                $ex->getMessage() . " at " . $ex->getFile() . " line " . $ex->getLine() . ": \n" .
                $ex->getTraceAsString()
            );
            $this->logRequestAndResponse($messageName);
            //TODO We must be able to handle certain exceptions inside the client, so maybe pass through after logging?
            throw new Client\Exception($ex->getMessage(), $ex->getCode(), $ex);
        }

        $result->responseXml = Client\Util\MsgBodyExtractor::extract($this->getLastResponse());

        return $result;
    }

    /**
     * Prepare to send a next message and checks if authenticated
     *
     * @param string $messageName
     * @param array $messageOptions
     */
    protected abstract function prepareForNextMessage($messageName, $messageOptions);

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
    protected abstract function handlePostMessage($messageName, $lastResponse, $messageOptions, $result);

    /**
     * Get the last raw XML message that was sent out
     *
     * @return string|null
     */
    public function getLastRequest()
    {
        return $this->getSoapClient()->__getLastRequest();
    }

    /**
     * Get the last raw XML message that was received
     *
     * @return string|null
     */
    public function getLastResponse()
    {
        return $this->getSoapClient()->__getLastResponse();
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
        $this->loadWsdlXpath($this->params->wsdl);

        $msgAndVer = [];
        $operations = $this->wsdlDomXpath->query(self::XPATH_ALL_OPERATIONS);

        foreach ($operations as $operation) {
            if (!empty($operation->value)) {
                $fullVersion = $this->wsdlDomXpath->evaluate(sprintf(self::XPATH_VERSION_FOR_OPERATION, $operation->value));
                if (!empty($fullVersion)) {
                    $extractedVersion = $this->extractMessageVersion($fullVersion);
                    $msgAndVer[$operation->value] = $extractedVersion;
                }
            }
        }

        return $msgAndVer;
    }

    /**
     * extractMessageVersion
     *
     * extracts "4.1" from a string like "Security_SignOut_4_1"
     *
     * @param string $fullVersionString
     * @return string
     */
    protected function extractMessageVersion($fullVersionString)
    {
        $secondUnderscore = strpos($fullVersionString, '_', strpos($fullVersionString, '_')+1);
        $num = substr($fullVersionString, $secondUnderscore+1);

        return str_replace('_', '.', $num);
    }

    /**
     * Load the WSDL contents to a queryable DOMXpath.
     *
     * @param string $wsdlFilePath
     * @uses $this->wsdlDomDoc
     * @uses $this->wsdlDomXpath
     */
    protected function loadWsdlXpath($wsdlFilePath)
    {
        if (is_null($this->wsdlDomXpath)) {
            $wsdlContent = file_get_contents($wsdlFilePath);

            $this->wsdlDomDoc = new \DOMDocument('1.0', 'UTF-8');
            $this->wsdlDomDoc->loadXML($wsdlContent);
            $this->wsdlDomXpath = new \DOMXPath($this->wsdlDomDoc);
            $this->wsdlDomXpath->registerNamespace(
                'wsdl',
                'http://schemas.xmlsoap.org/wsdl/'
            );
            $this->wsdlDomXpath->registerNamespace(
                'soap',
                'http://schemas.xmlsoap.org/wsdl/soap/'
            );
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

        if (isset($msgAndVer[$messageName])) {
            return $msgAndVer[$messageName];
        }

        return null;
    }


    /**
     * @return \SoapClient
     */
    protected function getSoapClient()
    {
        if (!$this->soapClient instanceof \SoapClient) {
            $this->soapClient = $this->initSoapClient();
        }

        return $this->soapClient;
    }

    /**
     * @return \SoapClient
     */
    protected abstract function initSoapClient();


    /**
     * @param string $messageName
     * @uses $this->log
     */
    protected function logRequestAndResponse($messageName)
    {
        $this->log(
            LogLevel::INFO,
            'Called ' . $messageName . ' with request: ' . $this->getSoapClient()->__getLastRequest()
        );
        $this->log(
            LogLevel::INFO,
            'Response:  ' . $this->getSoapClient()->__getLastResponse()
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
