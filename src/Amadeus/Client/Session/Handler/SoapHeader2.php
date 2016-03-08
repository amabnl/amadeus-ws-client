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
use Amadeus\Client\Params\SessionHandlerParams;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * SoapHeader2: Session Handler for web service applications using Amadeus WS Soap Header v2.
 *
 * @package Amadeus\Client\Session\Handler
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class SoapHeader2 extends Base
{
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
     * @param SessionHandlerParams $params
     */
    public function __construct(SessionHandlerParams $params)
    {
        parent::__construct($params);
    }

    /**
     * Method to send a message to the web services server
     *
     * @param string $messageName The Method name to be called (from the WSDL)
     * @param BaseWsMessage $messageBody The message's body to be sent to the server
     * @param array $messageOptions Optional options on how to handle this particular message.
     * @return string|\stdClass
     */
    public function sendMessage($messageName, BaseWsMessage $messageBody, $messageOptions)
    {
        // TODO: Implement sendMessage() method.
    }

    /**
     * Prepare to send a next message and checks if authenticated
     *
     * @param string $messageName
     * @param array $messageOptions
     */
    protected function prepareForNextMessage($messageName, $messageOptions)
    {
        // TODO: Implement prepareForNextMessage() method.
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
        // TODO: Implement handlePostMessage() method.
    }


    /**
     * Cannot set stateless on Soap Header v2
     *
     * @param bool $stateful
     * @throws UnsupportedOperationException
     */
    public function setStateful($stateful)
    {
        throw new UnsupportedOperationException('Stateful messages are mandatory on SoapHeader 2');
    }

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
     * Soap Header 2 sessions are always stateful
     *
     * @return bool
     */
    public function isStateful()
    {
        return true;
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
        $options['classmap'] = array_merge(Classmap::$soapheader2map, Classmap::$map);

        return $options;
    }
}
