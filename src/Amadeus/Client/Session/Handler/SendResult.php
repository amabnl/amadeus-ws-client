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

/**
 * SendResult
 *
 * An object used to return the result of the Session Handler sendMessage()
 *
 * @package Amadeus\Client\Session\Handler
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SendResult
{
    /**
     * The response as an XML string
     *
     * @var string
     */
    public $responseXml;

    /**
     * The response as returned by PHP's \SoapClient
     *
     * @var \stdClass|array
     */
    public $responseObject;

    /**
     * Which version of the message was called
     *
     * @var string|float
     */
    public $messageVersion;

    /**
     * Exception that occurred while sending
     * @var \Exception
     */
    public $exception;

    /**
     * SendResult constructor.
     *
     * @param string|float|null $messageVersion
     */
    public function __construct($messageVersion = null)
    {
        $this->messageVersion = $messageVersion;
    }
}
