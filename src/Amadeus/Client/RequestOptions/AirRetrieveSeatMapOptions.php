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

/**
 * Air_RetrieveSeatMap Request Options
 *
 * Options available for the Air_RetrieveSeatMap message.
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AirRetrieveSeatMapOptions extends Base
{
    /**
     * Flight information
     *
     * @var Air\RetrieveSeatMap\FlightInfo
     */
    public $flight;

    /**
     * Frequent flyer information
     *
     * @var Air\RetrieveSeatMap\FrequentFlyer
     */
    public $frequentFlyer;

    /**
     * Traveller information
     *
     * @var Air\RetrieveSeatMap\Traveller[]
     */
    public $travellers = [];

    /**
     * Cabin code
     *
     * When both a cabin code and a booking class are specified,
     * the cabin code information takes precedence over the booking class information.
     *
     * @var string
     */
    public $cabinCode;

    /**
     * Set to true to retrieve prices
     *
     * (processing indicator FT)
     *
     * @var bool
     */
    public $requestPrices = false;

    /**
     * Currency code - override pricing currency
     *
     * @var string
     */
    public $currency;

    /**
     * Record locator of PNR
     *
     * @var string
     */
    public $recordLocator;

    /**
     * Airline code - to be provided in combination with Record Locator
     *
     * @var string
     */
    public $company;

    /**
     * Date (and optionally time) - to be provided in combination with Record Locator
     *
     * @var \DateTime
     */
    public $date;

    /**
     * Number of passengers
     *
     * @var int
     */
    public $nrOfPassengers;

    /**
     * Booking status code (e.g. HK)
     *
     * @var string
     */
    public $bookingStatus;

    /**
     * Set to true to request the most restrictive seat map display
     *
     * @var bool
     */
    public $mostRestrictive = false;
}
