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

namespace Amadeus\Client\RequestOptions\Fare\InformativePricing;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Segment
 *
 * @package Amadeus\Client\RequestOptions\Fare\InformativePricing
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Segment extends LoadParamsFromArray
{
    /**
     * Departure date & time
     *
     * @var \DateTime
     */
    public $departureDate;

    /**
     * Arrival date & time
     *
     * @var \DateTime
     */
    public $arrivalDate;

    /**
     * Departure IATA code
     *
     * @var string
     */
    public $from;

    /**
     * Destination IATA code
     *
     * @var string
     */
    public $to;

    /**
     * Marketing airline code
     *
     * @var string
     */
    public $marketingCompany;

    /**
     * Operating airline code
     *
     * @var string
     */
    public $operatingCompany;

    /**
     * Flight number
     *
     * @var string
     */
    public $flightNumber;

    /**
     * Booking Class code
     *
     * @var string
     */
    public $bookingClass;

    /**
     * Code for the airplane type
     *
     * @var string
     */
    public $airplaneCode;

    /**
     * The number of stops
     *
     * @var int
     */
    public $nrOfStops;

    /**
     * Unique segment tattoo ID.
     *
     * @var int
     */
    public $segmentTattoo;

    /**
     * To group several segments into connected flights, use the same groupNumber
     *
     * The "flightTypeDetails/flightIndicator" is a group ID.
     * Two flights having the same flightIndicator will be considered as being connected.
     *
     * @var int
     */
    public $groupNumber;

    /**
     * Inventory of availability.
     *
     * Array keys are booking classes, values are how many available seats there are.
     *
     * e.g. ['Y' => 9, 'I' => 8]
     *
     * @var array
     */
    public $inventory = [];
}
