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

    const SPECIAL_AISLE_SEAT = 'A';
    const SPECIAL_CHARGEABLE_SEAT = 'CH';
    const SPECIAL_EXIT_ROW_SEAT = 'E';
    const SPECIAL_SEAT_HANDICAPPED_INCAPACITATED = 'H';
    const SPECIAL_SEAT_ADULT_WITH_INFANT = 'I';
    const SPECIAL_BULKHEAD_SEAT = 'K';
    const SPECIAL_MEDICALLY_OK = 'MA';
    const SPECIAL_NON_SMOKING_SEAT = 'N';
    const SPECIAL_SMOKING_SEAT = 'S';
    const SPECIAL_SEAT_UNACCOMPANIED_MINOR = 'U';
    const SPECIAL_WINDOW_SEAT = 'W';

    /**
     * Seat type
     *
     * self::TYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * Special Seat Type
     *
     * self::SPECIAL_*
     *
     * @var string
     */
    public $specialType;

    /**
     * Seat Number
     *
     * @var string|array
     */
    public $seatNumber;
}
