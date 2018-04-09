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
 * Fare_GetFareRules Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FareGetFareRulesOptions extends Base
{
    const DIRECTION_ORIGIN_TO_DESTINATION = 'OD';
    const DIRECTION_DESTINATION_TO_ORIGIN = 'DO';
    const DIRECTION_BOTH = 'BD';

    /**
     * @var \DateTime
     */
    public $ticketingDate;

    /**
     * List of Unifares
     *
     * @var string[]
     */
    public $uniFares = [];

    /**
     * List of Amadeus Negofares
     *
     * @var string[]
     */
    public $negoFares = [];

    /**
     * Airline IATA code
     *
     * @var string
     */
    public $airline;

    /**
     * Fare Basis
     *
     * @var string
     */
    public $fareBasis;

    /**
     * Ticket Designator
     *
     * @var string
     */
    public $ticketDesignator;

    /**
     * self::DIRECTION_*
     *
     * @var string
     */
    public $directionality;

    /**
     * Flight Board point IATA code
     *
     * @var string
     */
    public $origin;

    /**
     * Flight Off point IATA code
     *
     * @var string
     */
    public $destination;

    /**
     * Travel date
     *
     * @var \DateTime
     */
    public $travelDate;
}
