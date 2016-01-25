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

namespace Amadeus\Client\RequestOptions;

use Amadeus\Client\LoadParamsFromArray;

/**
 * MessageOptions are meta options when sending messages
 *
 * @todo use this class instead of messageOptions array in Client.
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class MessageOptions extends LoadParamsFromArray
{
    /**
     * Get the response as a string (true) or a PHP Object (false)
     *
     * If true, we'll get the response message from the soap body in the soapclient's __getLastResponse()
     *
     * @var bool
     */
    public $asString = false;
    /**
     * If you want to end a stateful session, set this to true.
     * @var bool
     */
    public $endSession = false;

    public function __construct()
    {
        parent::__construct([]);
        throw new \RuntimeException('NOT YET IMPLEMENTED');
    }
}
