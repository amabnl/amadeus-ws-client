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

namespace Amadeus\Client\RequestOptions\Ticket;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Changed ticket requested segments.
 *
 *
 * @package Amadeus\Client\RequestOptions\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ReqSegOptions extends LoadParamsFromArray
{
    const REQUEST_CODE_ADD = "A";
    const REQUEST_CODE_CHANGE_REQUESTED_SEGMENT = "C";
    const REQUEST_CODE_IGNORE_ONEWAYCOMBINEABLE = "I";
    const REQUEST_CODE_KEEP_FLIGHTS = "K";
    const REQUEST_CODE_KEEP_FLIGHTS_AND_FARES = "KF";
    const REQUEST_CODE_IGNORE_OTHER = "O";
    const REQUEST_CODE_REMOVE = "R";

    /**
     * What action to perform
     *
     * choose from self::REQUEST_CODE_*
     *
     * @var string
     */
    public $requestCode;

    /**
     * @var string[]
     */
    public $connectionLocations = [];
}
