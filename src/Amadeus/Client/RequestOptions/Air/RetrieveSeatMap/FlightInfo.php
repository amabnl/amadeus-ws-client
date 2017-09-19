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

namespace Amadeus\Client\RequestOptions\Air\RetrieveSeatMap;

use Amadeus\Client\LoadParamsFromArray;

/**
 * FlightInfo
 *
 * @package Amadeus\Client\RequestOptions\Air\RetrieveSeatMap
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FlightInfo extends LoadParamsFromArray
{
    /**
     * Departure date & time of flight
     *
     * @var \DateTime
     */
    public $departureDate;

    /**
     * Departure location
     *
     * 3-character IATA code
     *
     * @var string
     */
    public $departure;

    /**
     * Arrival location
     *
     * 3-character IATA code
     *
     * @var string
     */
    public $arrival;

    /**
     * Airline code
     *
     * 2-character IATA airline code
     *
     * @var string
     */
    public $airline;

    /**
     * The flight number
     *
     * @var string
     */
    public $flightNumber;

    /**
     * (Optional) Booking class
     *
     * @var string
     */
    public $bookingClass;
}
