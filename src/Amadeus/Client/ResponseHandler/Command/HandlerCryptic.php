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

namespace Amadeus\Client\ResponseHandler\Command;

use Amadeus\Client\ResponseHandler\StandardResponseHandler;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * HandlerCryptic
 *
 * Unknown response for Command_Cryptic because you need to analyse the cryptic response yourself
 *
 * @package Amadeus\Client\ResponseHandler\Command
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class HandlerCryptic extends StandardResponseHandler
{
    /**
     * @param SendResult $response
     * @return Result
     */
    public function analyze(SendResult $response)
    {
        $ccResult = new Result($response, Result::STATUS_UNKNOWN);
        $ccResult->messages[] = new Result\NotOk(
            0,
            "Response handling not supported for cryptic entries"
        );

        return $ccResult;
    }
}
