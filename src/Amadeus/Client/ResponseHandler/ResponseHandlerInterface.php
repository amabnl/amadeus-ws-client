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

namespace Amadeus\Client\ResponseHandler;

use Amadeus\Client\Exception;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * ResponseHandlerInterface
 *
 * @package Amadeus\Client\ResponseHandler
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
interface ResponseHandlerInterface
{
    /**
     * Analyze the response from the server and throw an exception when an error has been detected.
     *
     * @param SendResult $sendResult The Send Result from the Session Handler
     * @param string $messageName The message that was called
     *
     * @throws Exception When an error is detected
     * @throws \RuntimeException When there is a problem calling the response handler
     * @return Result
     */
    public function analyzeResponse($sendResult, $messageName);
}
