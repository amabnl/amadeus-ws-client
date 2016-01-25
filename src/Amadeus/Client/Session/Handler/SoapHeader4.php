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

use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;


/**
 * SoapHeader4: Session Handler for web service applications using Amadeus WS Soap Header v4.
 *
 * @package Amadeus\Client\Session\Handler
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class SoapHeader4 extends Base
{
    /**
     * XPATH query to retrieve the SOAPAction from the WSDL for a given message.
     *
     * @var string
     */
    const XPATH_OPERATION_ACTION = 'string(//wsdl:operation[./@name="%s"]/soap:operation/@soapAction)';
    /**
     * XPATH query to retrieve the server endpoint from the WSDL.
     *
     * @var string
     */
    const XPATH_ENDPOINT = 'string(/wsdl:definitions/wsdl:service/wsdl:port/soap:address/@location)';

    /**
     * Status variable to know if the session is currently logged in
     *
     * @var bool
     */
    protected $isAuthenticated = false;
    /**
     * Status variable to know wether the given session is in a certain context.
     *
     * @var bool
     */
    protected $hasContext = false;
    /**
     * Switch between stateful & stateless sessions. Default: stateful
     *
     * @var bool
     */
    protected $isStateful = true;
    /**
     * The context
     *
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
     * SoapClient options used during initialisation
     * @var array
     */
    protected $soapClientOptions = [
        'trace' 		=> 1,
        'exceptions' 	=> 1,
        'soap_version' 	=> SOAP_1_1
    ];

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
     * @var Client\Params\SessionHandlerParams
     */
    protected $params;

    /**
     * @param Client\Params\SessionHandlerParams $params
     */
    public function __construct(Client\Params\SessionHandlerParams $params)
    {
        $this->params = $params;
        if($params->logger instanceof LoggerInterface) {
            $this->setlogger($params->logger);
            $this->log(LogLevel::INFO, __METHOD__. "(): Logger started.");
        }
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
     * @param bool $stateful
     */
    public function setStateful($stateful)
    {
        $this->isStateful = $stateful;
    }

    /**
     * @return bool
     */
    public function getStateful()
    {
        return $this->isStateful;
    }


    /**
     * @param string $messageName Method Operation name as defined in the WSDL.
     * @param BaseWsMessage $messageBody
     * @param array $messageOptions options: bool 'asString', bool 'endSession'
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws Client\Exception
     */
    public function sendMessage($messageName, BaseWsMessage $messageBody, $messageOptions = [])
    {
        $result = null;

        $this->prepareForNextMessage($messageName, $messageOptions);

        try {
            $result = $this->getSoapClient()->$messageName($messageBody);

            $this->logRequestAndResponse($messageName);

            $this->handlePostMessage($messageName, $messageOptions, $result);

        } catch(\SoapFault $ex) {
            $this->log(
                LogLevel::ERROR,
                "SOAPFAULT while sending message " . $messageName . ": " .
                $ex->getMessage() . " code: " .$ex->getCode() . " at " . $ex->getFile() .
                " line " . $ex->getLine() . ": \n" . $ex->getTraceAsString()
            );
            $this->logRequestAndResponse($messageName);
            //TODO We must be able to handle certain soapfaults inside the client, so maybe pass through after logging?
            throw $ex;
        } catch (\Exception $ex) {
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

        return $result;
    }

    /**
     * Handles authentication & sessions
     *
     * If authenticated, increment sequence number for next message and set session info to soapheader
     * If not, set auth info to soapheader
     *
     * @todo decide if you want to end the session
     * @uses $this->isAuthenticated
     * @uses $this->sessionData
     * @param string $messageName
     * @param array $messageOptions
     */
    protected function prepareForNextMessage($messageName, $messageOptions)
    {
        if ($this->isAuthenticated && is_int($this->sessionData['sequenceNumber'])) {
            $this->sessionData['sequenceNumber']++;
        }

        $headers = $this->createSoapHeaders($this->sessionData, $this->params, $messageName, $messageOptions);

        $this->getSoapClient()->__setSoapHeaders($headers);
    }


    /**
     * Handles post message actions
     *
     * - look for session info and set status variables
     * - checks for message errors?
     * - ends terminated sessions
     *
     * @param string $messageName
     * @param array $messageOptions
     * @param mixed $result
     */
    protected function handlePostMessage($messageName, $messageOptions, $result)
    {

        if ($messageName === "Security_Authenticate") {
            //You really don't need the Security_Authenticate call anymore with SoapHeader 4!
            //TODO
            throw new \RuntimeException('NOT YET IMPLEMENTED: Extract session data from Security_AuthenticateReply');
        }

        if ($this->getStateful() === true) {
            //We need to extract session info
            $this->sessionData = $this->getSessionDataFromHeader(
                $this->getSoapClient()->__getLastResponseHeaders()
            );
            $this->isAuthenticated = !empty($this->sessionData);


        } else {
            $this->isAuthenticated = false;
        }


    }

    /**
     * @param $responseHeaders
     * @return array
     */
    protected function getSessionDataFromHeader($responseHeaders)
    {
        $this->log(LogLevel::INFO, $responseHeaders);
    }

    /**
     * Create the Soap Headers to be used on the subsequent request.
     *
     * This depends on the current Session Data (if there is an active session) and
     * the Session Handler parameters (to create a new or stateless session)
     *
     * You can also terminate the session with $doEndSession = true
     *
     * @param array $sessionData
     * @param Client\Params\SessionHandlerParams $params
     * @param string $messageName
     * @param array $messageOptions
     * @return \SoapHeader[]|null
     */
    protected function createSoapHeaders($sessionData, $params, $messageName, $messageOptions)
    {
        $headersToSet = [];

        if (isset($messageOptions['endSession']) && $messageOptions['endSession'] === true) {
            //TODO set headers for end session
        } else {
            //TODO: Handle new or existing session
            //TODO: Handle stateful & stateless.

            array_push(
                $headersToSet,
                new \SoapHeader(
                    'http://www.w3.org/2005/08/addressing',
                    'MessageID',
                    $this->generateGuid()
                )
            );

            array_push(
                $headersToSet,
                new \SoapHeader(
                    'http://www.w3.org/2005/08/addressing',
                    'Action',
                    $this->getActionFromWsdl($params->wsdl, $messageName)
                )
            );

            array_push(
                $headersToSet,
                new \SoapHeader(
                    'http://www.w3.org/2005/08/addressing',
                    'To',
                    $this->getEndpointFromWsdl($params->wsdl)
                )
            );

            $password = base64_decode($params->authParams->passwordData);
            $creation = new \DateTime('now', new \DateTimeZone('UTC'));
            $t = microtime(true);
            $micro = sprintf("%03d",($t - floor($t)) * 1000);
            $creationString = $this->createDateTimeStringForAuth($creation, $micro);
            $messageNonce = $this->generateUniqueNonce($params->authParams->nonceBase, $creationString);
            $digest = $this->generatePasswordDigest($password, $creationString, $messageNonce);

            $xml = '
<oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsswssecurity-secext-1.0.xsd">
    <oas:UsernameToken xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" oas1:Id="UsernameToken-1">
		<oas:Username>' . $params->authParams->originator . '</oas:Username>
		<oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsssoap-message-security-1.0#Base64Binary">' . base64_encode($messageNonce) . '</oas:Nonce>
		<oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wssusername-token-profile-1.0#PasswordDigest">' . $digest . '</oas:Password>
		<oas1:Created>' . $creationString . '</oas1:Created>
    </oas:UsernameToken>
</oas:Security>';

            array_push(
                $headersToSet,
                new \SoapHeader(
                    'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsswssecurity-secext-1.0.xsd',
                    'Security',
                    new \SoapVar($xml, XSD_ANYXML),
                    true
                )
            );

            /*array_push(
                $headersToSet,
                new \SoapHeader(
                    'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd',
                    'Security',
                    new Client\Struct\HeaderV4\Security(
                        $params->authParams->originator,
                        $digest,
                        base64_encode($messageNonce),
                        $creationString
                    )
                )
            );*/



            array_push(
                $headersToSet,
                new \SoapHeader(
                    'http://xml.amadeus.com/2010/06/Security_v1',
                    'AMA_SecurityHostedUser',
                    new Client\Struct\HeaderV4\SecurityHostedUser(
                        $params->authParams->officeId,
                        $params->authParams->originatorTypeCode,
                        1,
                        $params->authParams->dutyCode
                    )
                )
            );
        }

        return $headersToSet;
    }

    /**
     * Get the SOAPAction for a given message from the WSDL contents.
     *
     * @param string $wsdlFilePath
     * @param string $messageName
     * @return string
     */
    protected function getActionFromWsdl($wsdlFilePath, $messageName)
    {
        $this->loadWsdlXpath($wsdlFilePath);

        $action = $this->wsdlDomXpath->evaluate(sprintf(self::XPATH_OPERATION_ACTION, $messageName));

        return $action;
    }

    /**
     * Get the Web Services server Endpoint from the WSDL.
     *
     * @param string $wsdlFilePath
     * @return string
     */
    protected function getEndpointFromWsdl($wsdlFilePath)
    {
        $this->loadWsdlXpath($wsdlFilePath);

        return $this->wsdlDomXpath->evaluate(self::XPATH_ENDPOINT);
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
     * Generate a GUID
     *
     * @todo use composer package ramsey/uuid instead?
     * @return string
     */
    protected function generateGuid()
    {
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);
            $charId = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"

            /*$uuid = chr(123)// "{"
                .substr($charId, 0, 8).$hyphen
                .substr($charId, 8, 4).$hyphen
                .substr($charId,12, 4).$hyphen
                .substr($charId,16, 4).$hyphen
                .substr($charId,20,12)
                .chr(125);// "}"*/

            $uuid = substr($charId, 0, 8).$hyphen
                .substr($charId, 8, 4).$hyphen
                .substr($charId,12, 4).$hyphen
                .substr($charId,16, 4).$hyphen
                .substr($charId,20,12);

            return $uuid;
        }
    }

    /**
     * @param string $nonceBase
     * @param string $creationString
     * @return string
     */
    protected function generateUniqueNonce($nonceBase, $creationString)
    {
        return sha1(
            $nonceBase . $creationString,
            true
        );
    }

    /**
     * Generates a Password Digest following this algorithm:
     * HashedPassword = Base64(SHA-1( nonce + created + SHA-1 ( password )))
     * as defined in
     * https://webservices.amadeus.com/extranet/kdbViewDocument.do?externalId=wikidoc_web_services_embedded_security_implementation_guide_header_entries_ws-security_usernametoken&docStatus=Published&mpId=fla__1__technical
     *
     * EXAMPLE: with:
     *  Nonce in Base 64 = 'PZgFvh5439plJpKpIyf5ucmXhNU='
     *  Timestamp = '2013-01-11T09:41:03Z'
     *  Clear Password = 'WBSPassword'
     * The digest algorithm returns the Encrypted Password in Base 64:
     *  HshPwd = 'ic3AOJElVpvkz9ZBKd105Siry28='
     *
     * @param string $password CLEARTEXT password (NOT the base64 encoded password used in Security_Authenticate)
     * @param string $creationString message creation datetime UTC Format: yyyy-mm-ddTHH:MM:SSZ or yyyy-mm-ddTHH:MM:SS.sssZ
     * @param string $messageNonce Random unique string
     * @return string The generated Password Digest
     */
    protected function generatePasswordDigest($password, $creationString, $messageNonce)
    {
        return base64_encode(sha1($messageNonce . $creationString . sha1($password, true), true));
    }

    /**
     * @param \DateTime $creationDateTime
     * @param string $micro
     * @return string
     */
    protected function createDateTimeStringForAuth($creationDateTime, $micro)
    {
        $creationDateTime->setTimezone(new \DateTimeZone('UTC'));
        //return $creationDateTime->format("Y-m-d\TH:i:s:") . $micro . 'Z';
        return $creationDateTime->format("Y-m-d\TH:i:s") . 'Z';
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
    protected function initSoapClient()
    {
        $client = new Client\SoapClient(
            $this->params->wsdl,
            $this->makeSoapClientOptions(),
            $this->params->logger
        );

        return $client;
    }

    /**
     * @return array
     */
    protected function makeSoapClientOptions()
    {
        $options = $this->soapClientOptions;
        $options['classmap'] = Classmap::$map;

        return $options;
    }


    /**
     * @param string $messageName
     * @uses $this->log
     */
    protected function logRequestAndResponse($messageName)
    {
        $this->log(
            LogLevel::INFO,
            'Called ' . $messageName . 'with request: ' . $this->getSoapClient()->__getLastRequest()
        );
        $this->log(
            LogLevel::INFO,
            'Response:  ' . $this->getSoapClient()->__getLastResponse()
        );
    }
}
