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
 * MessageResponseHandler
 *
 * The interface used to implement the analysis of a response from a specific Web Service message response.
 *
 * @package Amadeus\Client\ResponseHandler
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
interface MessageResponseHandler
{
    /**
     * Analyze the result from the message operation and check for any error messages
     *
     * @param SendResult $response
     * @return Result
     * @throws Exception
     */
    public function analyze(SendResult $response);
}
