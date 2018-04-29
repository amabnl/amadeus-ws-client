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
 * SoapHeader2: Session Handler for web service applications using Amadeus WS Soap Header v2.
 *
 * @package Amadeus\Client\Session\Handler
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SoapHeader2 extends Base
{
    /**
     * Namespace of SoapHeader V2
     */
    const CORE_WS_V2_SESSION_NS = 'http://xml.amadeus.com/ws/2009/01/WBS_Session-2.0.xsd';

    /**
     * Node for Session
     */
    const NODENAME_SESSION = "Session";

    /**
     * Node for Session ID
     */
    const NODENAME_SESSIONID = "SessionId";
    /**
     * Node for Session Sequence Number
     */
    const NODENAME_SEQENCENR = "SequenceNumber";
    /**
     * Node for Session Security Token
     */
    const NODENAME_SECURITYTOKEN = "SecurityToken";

    /**
     * Prepare to send a next message and checks if authenticated
     *
     * @param string $messageName
     * @param array $messageOptions
     * @throws InvalidSessionException When trying to send a message without session.
     */
    protected function prepareForNextMessage($messageName, $messageOptions)
    {
        if (!$this->isAuthenticated && $messageName !== 'Security_Authenticate') {
            throw new InvalidSessionException('No active session');
        }

        $this->getSoapClient($messageName)->__setSoapHeaders(null);

        if ($this->isAuthenticated === true && is_int($this->sessionData['sequenceNumber'])) {
            $this->sessionData['sequenceNumber']++;

            $session = new Client\Struct\HeaderV2\Session(
                $this->sessionData['sessionId'],
                $this->sessionData['sequenceNumber'],
                $this->sessionData['securityToken']
            );

            $this->getSoapClient($messageName)->__setSoapHeaders(
                new \SoapHeader(self::CORE_WS_V2_SESSION_NS, self::NODENAME_SESSION, $session)
            );
        }
    }

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
    protected function handlePostMessage($messageName, $lastResponse, $messageOptions, $result)
    {
        if ($messageName === "Security_Authenticate") {
            $this->sessionData = $this->getSessionDataFromHeader($lastResponse);
            $this->isAuthenticated = (!empty($this->sessionData['sessionId']) &&
                !empty($this->sessionData['sequenceNumber']) &&
                !empty($this->sessionData['securityToken']));
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
        $ok = $responseDomDoc->loadXML($responseMsg);

        if ($ok) {
            $sessionId = $responseDomDoc->getElementsByTagName(self::NODENAME_SESSIONID)->item(0)->nodeValue;
            if ($sessionId) {
                $newSessionData['sessionId'] = $sessionId;
            }
            $sequence = (int) $responseDomDoc->getElementsByTagName(self::NODENAME_SEQENCENR)->item(0)->nodeValue;
            if ($sequence) {
                $newSessionData['sequenceNumber'] = $sequence;
            }
            $securityToken = $responseDomDoc->getElementsByTagName(self::NODENAME_SECURITYTOKEN)->item(0)->nodeValue;
            if ($securityToken) {
                $newSessionData['securityToken'] = $securityToken;
            }
        }

        unset($responseDomDoc);

        return $newSessionData;
    }

    /**
     * Cannot set stateless on Soap Header v2
     *
     * @param bool $stateful
     * @throws UnsupportedOperationException
     */
    public function setStateful($stateful)
    {
        if ($stateful === false) {
            throw new UnsupportedOperationException('Stateful messages are mandatory on SoapHeader 2');
        }
    }

    /**
     * Soap Header 2 sessions are always stateful
     *
     * @return bool
     */
    public function isStateful()
    {
        return true;
    }

    /**
     * Is the TransactionFlowLink header enabled?
     *
     * @return bool
     */
    public function isTransactionFlowLinkEnabled()
    {
        return false; //Not supported
    }

    /**
     * Enable or disable TransactionFlowLink header
     *
     * @throws UnsupportedOperationException when used on unsupported WSAP versions
     * @param bool $enabled
     */
    public function setTransactionFlowLink($enabled)
    {
        if ($enabled === true) {
            throw new UnsupportedOperationException('TransactionFlowLink header is not supported on SoapHeader 2');
        }
    }

    /**
     * Get the TransactionFlowLink Consumer ID
     *
     * @return string|null
     */
    public function getConsumerId()
    {
        return null; //Not supported
    }

    /**
     * Set the TransactionFlowLink Consumer ID
     *
     * @throws UnsupportedOperationException when used on unsupported WSAP versions
     * @param string $id
     * @return void
     */
    public function setConsumerId($id)
    {
        if (!is_null($id)) {
            throw new UnsupportedOperationException('TransactionFlowLink header is not supported on SoapHeader 2');
        }
    }


    /**
     * Make SoapClient options for Soap Header 2 handler
     *
     * @return array
     */
    protected function makeSoapClientOptions()
    {
        $options = $this->soapClientOptions;
        $options['classmap'] = array_merge(Classmap::$soapheader2map, Classmap::$map);

        if (!empty($this->params->soapClientOptions)) {
            $options = array_merge($options, $this->params->soapClientOptions);
        }

        return $options;
    }
}
