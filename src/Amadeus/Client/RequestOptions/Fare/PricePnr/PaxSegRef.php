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

namespace Amadeus\Client\RequestOptions\Fare\PricePnr;

use Amadeus\Client\LoadParamsFromArray;

/**
 * PaxSegRef
 *
 * @package Amadeus\Client\RequestOptions\Fare\PricePnr
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PaxSegRef extends LoadParamsFromArray
{
    const TYPE_PASSENGER = 'P';
    const TYPE_PASSENGER_INFANT = 'PI';
    const TYPE_PASSENGER_ADULT = 'PA';
    const TYPE_SEGMENT = 'S';
    const TYPE_CONNECTING = 'X';
    const TYPE_TST = 'T';
    const TYPE_SERVICE = 'E'; //Service_IntegratedPricing & Ticket_RepricePNRWithBookingClass
    const TYPE_ORIGINAL_PRICING = 'O'; //Ticket_RepricePNRWithBookingClass

    /**
     * Identifier of the reference (segment/passenger number)
     *
     * @var int
     */
    public $reference;

    /**
     * Segment or passenger ref
     *
     * self::TYPE_*
     *
     * @var string
     */
    public $type;
}
