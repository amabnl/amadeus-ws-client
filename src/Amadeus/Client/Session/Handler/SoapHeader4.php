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

/**
 * SoapHeader4: Session Handler for web service applications using Amadeus WS Soap Header v4.
 *
 * @package Amadeus\Client\Session\Handler
 * @author Dieter Devlieghere <dermikagh@gmail.com>
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
     * Switch between stateful & stateless sessions. Default: stateful
     *
     * @var bool
     */
    protected $isStateful = true;

    protected $enableTransactionFlowLink = false;

    /**
     * TransactionFlowLink Consumer ID
     *
     * @var string|null
     */
    protected $consumerId;

    /**
     * @param bool $stateful
     */
    public function setStateful($stateful)
    {
        $this->isStateful = $stateful;
    }

    /**
     * Check whether we are running in stateful mode (true) or in stateless mode (false)
     *
     * @return bool
     */
    public function isStateful()
    {
        return $this->isStateful;
    }

    /**
     * Is the TransactionFlowLink header enabled?
     *
     * @return bool
     */
    public function isTransactionFlowLinkEnabled()
    {
        return $this->enableTransactionFlowLink;
    }

    /**
     * Enable or disable TransactionFlowLink header
     *
     * @param bool $enabled
     */
    public function setTransactionFlowLink($enabled)
    {
        $this->enableTransactionFlowLink = $enabled;
    }

    /**
     * Get the TransactionFlowLink Consumer ID
     *
     * @param bool $generate Whether to generate a consumer ID
     * @return string|null
     */
    public function getConsumerId($generate = false)
    {
        if (is_null($this->consumerId) && $generate) {
            $this->consumerId = $this->generateGuid();
        }

        return $this->consumerId;
    }

    /**
     * Set the TransactionFlowLink Consumer ID
     *
     * @param string $id
     * @return void
     */
    public function setConsumerId($id)
    {
        $this->consumerId = $id;
    }

    /**
     * Handles authentication & sessions
     *
     * If authenticated, increment sequence number for next message and set session info to soapheader
     * If not, set auth info to soapheader
     *
     * @uses $this->isAuthenticated
     * @uses $this->sessionData
     * @param string $messageName
     * @param array $messageOptions
     */
    protected function prepareForNextMessage($messageName, $messageOptions)
    {
        if ($this->isAuthenticated === true && is_int($this->sessionData['sequenceNumber'])) {
            $this->sessionData['sequenceNumber']++;
        }

        $headers = $this->createSoapHeaders($this->sessionData, $this->params, $messageName, $messageOptions);

        $this->getSoapClient($messageName)->__setSoapHeaders(null);
        $this->getSoapClient($messageName)->__setSoapHeaders($headers);
    }


    /**
     * Handles post message actions
     *
     * - look for session info and set status variables
     * - checks for message errors?
     * - ends terminated sessions
     *
     * @param string $messageName
     * @param string $lastResponse
     * @param array $messageOptions
     * @param mixed $result
     * @return void
     */
    protected function handlePostMessage($messageName, $lastResponse, $messageOptions, $result)
    {
        //CHECK FOR SESSION DATA:
        if ($this->isStateful() === true) {
            //We need to extract session info
            $this->sessionData = $this->getSessionDataFromHeader($lastResponse);
            $this->isAuthenticated = (!empty($this->sessionData['sessionId']) &&
                !empty($this->sessionData['sequenceNumber']) &&
                !empty($this->sessionData['securityToken']));
        } else {
            $this->isAuthenticated = false;
        }
    }

    /**
     * @param string $responseMsg the full response XML received.
     * @return array
     */
    protected function getSessionDataFromHeader($responseMsg)
    {
        $newSessionData = [
            'sessionId' => null,
            'sequenceNumber' => null,
            'securityToken' => null
        ];

        $responseDomDoc = new \DOMDocument('1.0', 'UTF-8');
        $responseDomDoc->loadXML($responseMsg);
        $responseDomXpath = new \DOMXPath($responseDomDoc);
        $responseDomXpath->registerNamespace('awsse', 'http://xml.amadeus.com/2010/06/Session_v3');

        $queryTransactionStatusCode = "string(//awsse:Session/@TransactionStatusCode)";

        $transactionStatusCode = $responseDomXpath->evaluate($queryTransactionStatusCode);

        if (mb_strtolower($transactionStatusCode) !== "end") {
            $querySessionId = "string(//awsse:Session/awsse:SessionId/text())";
            $querySequenceNumber = "string(//awsse:Session/awsse:SequenceNumber/text())";
            $querySecurityToken = "string(//awsse:Session/awsse:SecurityToken/text())";

            $newSessionData['sessionId'] = $responseDomXpath->evaluate($querySessionId);
            $newSessionData['sequenceNumber'] = $responseDomXpath->evaluate($querySequenceNumber);
            if (!empty($newSessionData['sequenceNumber'])) {
                $newSessionData['sequenceNumber'] = (int) $newSessionData['sequenceNumber'];
            }
            $newSessionData['securityToken'] = $responseDomXpath->evaluate($querySecurityToken);
        }

        unset($responseDomDoc, $responseDomXpath);

        return $newSessionData;
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

        $wsdlId = $this->getWsdlIdFor($messageName);
        $wsdl = WsdlAnalyser::$wsdlIds[$wsdlId];

        //CHECK STATEFUL
        $stateful = $this->isStateful();

        //Message ID header
        array_push(
            $headersToSet,
            new \SoapHeader(
                'http://www.w3.org/2005/08/addressing',
                'MessageID',
                $this->generateGuid()
            )
        );

        //Action header
        array_push(
            $headersToSet,
            new \SoapHeader(
                'http://www.w3.org/2005/08/addressing',
                'Action',
                $this->getActionFromWsdl($wsdl, $messageName)
            )
        );

        //To header
        array_push(
            $headersToSet,
            new \SoapHeader(
                'http://www.w3.org/2005/08/addressing',
                'To',
                $this->getEndpointFromWsdl($wsdl, $messageName)
            )
        );

        //TransactionFlowLink header
        $tfl = $this->isTransactionFlowLinkEnabled();
        if ($tfl) {
            $consumerId = $this->getConsumerId(true);

            array_push(
                $headersToSet,
                new \SoapHeader(
                    'http://wsdl.amadeus.com/2010/06/ws/Link_v1',
                    'TransactionFlowLink',
                    new Client\Struct\HeaderV4\TransactionFlowLink($consumerId)
                )
            );
        }

        //Send authentication info headers if not authenticated and not Security_Authenticate message call
        if ($this->isAuthenticated === false && $this->isNotSecurityAuthenticateMessage($messageName)) {
            //Generate nonce, msg creation string & password digest:
            $password = base64_decode($params->authParams->passwordData);
            $creation = new \DateTime('now', new \DateTimeZone('UTC'));
            $t = microtime(true);
            $micro = sprintf("%03d", ($t - floor($t)) * 1000);
            $creationString = $this->createDateTimeStringForAuth($creation, $micro);
            $messageNonce = $this->generateUniqueNonce($params->authParams->nonceBase, $creationString);
            $encodedNonce = base64_encode($messageNonce);
            $digest = $this->generatePasswordDigest($password, $creationString, $messageNonce);

            $securityHeaderXml = $this->generateSecurityHeaderRawXml(
                $params->authParams->userId,
                $encodedNonce,
                $digest,
                $creationString
            );

            //Authentication header
            array_push(
                $headersToSet,
                new \SoapHeader(
                    'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsswssecurity-secext-1.0.xsd',
                    'Security',
                    new \SoapVar($securityHeaderXml, XSD_ANYXML)
                )
            );

            if ($stateful === true) {
                //Not authenticated but stateful: start session!
                array_push(
                    $headersToSet,
                    new \SoapHeader(
                        'http://xml.amadeus.com/2010/06/Session_v3',
                        'Session',
                        new Client\Struct\HeaderV4\Session(
                            null,
                            "Start"
                        )
                    )
                );
            }

            //AMA_SecurityHostedUser header
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
        } elseif ($stateful === true) {
            //We are authenticated and stateful: provide session header to continue or terminate session
            $statusCode =
                (isset($messageOptions['endSession']) && $messageOptions['endSession'] === true) ?
                    "End" : "InSeries";

            array_push(
                $headersToSet,
                new \SoapHeader(
                    'http://xml.amadeus.com/2010/06/Session_v3',
                    'Session',
                    new Client\Struct\HeaderV4\Session(
                        $sessionData,
                        $statusCode
                    )
                )
            );
        }

        return $headersToSet;
    }

    /**
     * Get the Web Services server Endpoint from the WSDL.
     *
     * @param string $wsdlFilePath
     * @param string $messageName
     * @return string|null
     */
    protected function getEndpointFromWsdl($wsdlFilePath, $messageName)
    {
        $wsdlId = $this->getWsdlIdFor($messageName);

        return WsdlAnalyser::exaluateXpathQueryOnWsdl(
            $wsdlId,
            $wsdlFilePath,
            self::XPATH_ENDPOINT
        );
    }

    /**
     * Get the SOAPAction for a given message from the WSDL contents.
     *
     * @param string $wsdlFilePath
     * @param string $messageName
     * @return string|null
     */
    protected function getActionFromWsdl($wsdlFilePath, $messageName)
    {
        $wsdlId = $this->getWsdlIdFor($messageName);

        return WsdlAnalyser::exaluateXpathQueryOnWsdl(
            $wsdlId,
            $wsdlFilePath,
            sprintf(self::XPATH_OPERATION_ACTION, $messageName)
        );
    }

    /**
     * Generate a GUID
     *
     * @return string
     */
    protected function generateGuid()
    {
        mt_srand((double) microtime() * 10000);
        $charId = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"

        $uuid = substr($charId, 0, 8) . $hyphen
            . substr($charId, 8, 4) . $hyphen
            . substr($charId, 12, 4) . $hyphen
            . substr($charId, 16, 4) . $hyphen
            . substr($charId, 20, 12);

        return $uuid;
    }

    /**
     * @param string $originator
     * @param string $nonce
     * @param string $pwDigest
     * @param string $creationTimeString
     * @return string
     */
    protected function generateSecurityHeaderRawXml($originator, $nonce, $pwDigest, $creationTimeString)
    {
        return $xml = '<oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
	<oas:UsernameToken xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" oas1:Id="UsernameToken-1">
		<oas:Username>' . $originator . '</oas:Username>
		<oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">' . $nonce . '</oas:Nonce>
		<oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">' . $pwDigest . '</oas:Password>
		<oas1:Created>' . $creationTimeString . '</oas1:Created>
	</oas:UsernameToken>
</oas:Security>';
    }


    /**
     * @param string $nonceBase
     * @param string $creationString
     * @return string
     */
    protected function generateUniqueNonce($nonceBase, $creationString)
    {
        return substr(
            sha1(
                $nonceBase . $creationString,
                true
            ),
            0,
            16
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
     * @param string $creationString message creation datetime
     *                               UTC Format: yyyy-mm-ddTHH:MM:SSZ or yyyy-mm-ddTHH:MM:SS.sssZ
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

        return $creationDateTime->format("Y-m-d\TH:i:s:") . $micro . 'Z';
    }

    /**
     * Make SoapClient options for Soap Header 4 handler
     *
     * @return array
     */
    protected function makeSoapClientOptions()
    {
        $options = $this->soapClientOptions;
        $options['classmap'] = array_merge(Classmap::$soapheader4map, Classmap::$map);

        if (!empty($this->params->soapClientOptions)) {
            $options = array_merge($options, $this->params->soapClientOptions);
        }

        return $options;
    }

    /**
     * Check is called message is not Security_Authenticate.
     *
     * @param $messageName
     * @return bool
     */
    protected function isNotSecurityAuthenticateMessage($messageName)
    {
        return 'Security_Authenticate' !== $messageName;
    }
}
