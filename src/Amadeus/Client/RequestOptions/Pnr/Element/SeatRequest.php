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

namespace Amadeus\Client\RequestOptions\Pnr\Element;

use Amadeus\Client\RequestOptions\Pnr\Element;

/**
 * Seat Request
 *
 * @package Amadeus\Client\RequestOptions\Pnr\Element
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SeatRequest extends Element
{
    const TYPE_NO_SMOKING_SEAT = "NSST";
    const TYPE_NO_SMOKING_BULKHEAD_SEAT = "NSSB";
    const TYPE_NO_SMOKING_AISLE_SEAT = "NSSA";
    const TYPE_NO_SMOKING_WINDOW_SEAT = "NSSW";
    const TYPE_SMOKING_WINDOW_SEAT = "SMSW";
    const TYPE_SMOKING_SEAT = "SMST";
    const TYPE_SMOKING_BULKHEAD_SEAT = "SMSB";
    const TYPE_SMOKING_AISLE_SEAT = "SMSA";
    const TYPE_PRE_RESERVED_SEAT = "SEAT";
    const TYPE_SEAT_REQUEST = "RQST";

    /**
     * Seat type
     *
     * self::TYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * Seat Number
     *
     * @var string
     */
    public $seatNumber;
}
