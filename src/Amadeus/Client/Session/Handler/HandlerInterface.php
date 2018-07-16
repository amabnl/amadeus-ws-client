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

use Amadeus\Client\Params\SessionHandlerParams;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * HandlerInterface
 *
 * Interface for session handlers
 *
 * @package Amadeus\Client\Session\Handler
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
interface HandlerInterface
{
    /**
     * @param SessionHandlerParams $params
     */
    public function __construct(SessionHandlerParams $params);

    /**
     * Method to send a message to the web services server
     *
     * @param string $messageName The Method name to be called (from the WSDL)
     * @param BaseWsMessage $messageBody The message's body to be sent to the server
     * @param array $messageOptions Optional options on how to handle this particular message.
     * @return SendResult
     */
    public function sendMessage($messageName, BaseWsMessage $messageBody, $messageOptions);

    /**
     * Get the office that we are using to authenticate.
     *
     * @return string
     */
    public function getOriginatorOffice();

    /**
     * Extract the Messages and versions from the loaded WSDL file.
     *
     * Result is an associative array: keys are message names, values are versions.
     *
     * @return array
     */
    public function getMessagesAndVersions();

    /**
     * Set the Stateful mode off or on.
     *
     * @param bool $stateful
     */
    public function setStateful($stateful);

    /**
     * Get the session parameters of the active session
     *
     * @return array|null
     */
    public function getSessionData();

    /**
     * Set the session data to continue a previously started session.
     *
     * @param array $sessionData
     * @return bool success or failure
     */
    public function setSessionData(array $sessionData);

    /**
     * Get the current stateful mode (true is stateful, false is stateless)
     *
     * @return bool
     */
    public function isStateful();

    /**
     * Get the last raw XML message that was sent out
     *
     * @param string $msgName
     * @return string|null
     */
    public function getLastRequest($msgName);

    /**
     * Get the last raw XML message that was received
     *
     * @param string $msgName
     * @return string|null
     */
    public function getLastResponse($msgName);

    /**
     * Get the request headers for the last SOAP message that was sent out
     *
     * @param string $msgName
     * @return string|null
     */
    public function getLastRequestHeaders($msgName);

    /**
     * Get the response headers for the last SOAP message that was received
     *
     * @param string $msgName
     * @return string|null
     */
    public function getLastResponseHeaders($msgName);

    /**
     * Is the TransactionFlowLink header enabled?
     *
     * @return bool
     */
    public function isTransactionFlowLinkEnabled();

    /**
     * Enable or disable TransactionFlowLink header
     *
     * @throws UnsupportedOperationException when used on unsupported WSAP versions
     * @param bool $enabled
     */
    public function setTransactionFlowLink($enabled);

    /**
     * Get the TransactionFlowLink Consumer ID
     *
     * @return string|null
     */
    public function getConsumerId();

    /**
     * Set the TransactionFlowLink Consumer ID
     *
     * @throws UnsupportedOperationException when used on unsupported WSAP versions
     * @param string $id
     * @return void
     */
    public function setConsumerId($id);
}
