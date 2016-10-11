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
 * FareMasterPricerTbSearch
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class FareMasterPricerTbSearch extends Base
{
    const FLIGHTTYPE_DIRECT = "D";
    const FLIGHTTYPE_NONSTOP = "N";
    const FLIGHTTYPE_CONNECTING = "C";

    const CABIN_ECONOMY = "Y";
    const CABIN_ECONOMY_STANDARD = "M";
    const CABIN_ECONOMY_PREMIUM = "W";
    const CABIN_BUSINESS = "C";
    const CABIN_FIRST_SUPERSONIC = "F";

    const AIRLINEOPT_PREFERRED = "F";
    const AIRLINEOPT_MANDATORY = "M";
    const AIRLINEOPT_NIGHT_CLASS = "N";
    const AIRLINEOPT_FORCE_FULLAIRLINE_RECOMMENDATION = "O";
    const AIRLINEOPT_CARRIERS_LIST_BYPASS_BSP_CHECKS = "T";
    const AIRLINEOPT_MANDATORY_VALIDATING_CARRIER = "V";
    const AIRLINEOPT_EXCLUDED_VALIDATING_CARRIER = "W";
    const AIRLINEOPT_EXCLUDED = "X";

    const FLIGHTOPT_PUBLISHED = "RP";
    const FLIGHTOPT_UNIFARES = "RU";
    const FLIGHTOPT_CORPORATE_UNIFARES = "RW";
    const FLIGHTOPT_NO_RESTRICTION = "NR";
    const FLIGHTOPT_REFUNDABLE = "RF";
    const FLIGHTOPT_NO_ADVANCE_PURCHASE = "NAP";
    const FLIGHTOPT_NO_PENALTIES = "NPE";
    const FLIGHTOPT_NO_LOWCOST = "XLC";
    const FLIGHTOPT_ELECTRONIC_TICKET = "ET";
    const FLIGHTOPT_PAPER_TICKET = "PT";
    const FLIGHTOPT_ELECTRONIC_PAPER_TICKET = "EP";
    const FLIGHTOPT_FORCE_NEUTRAL_FARE_SEARCH = "NPF";
    const FLIGHTOPT_NO_SLICE_AND_DICE = "NSD";
    const FLIGHTOPT_DISPLAY_MIN_MAX_STAY = "MST";

    /**
     * Major cabin
     */
    const CABINOPT_MAJOR = "MC";
    /**
     * Mandatory cabin for all segments
     */
    const CABINOPT_MANDATORY = "MD";
    /**
     * Recommended cabin to be used at least one segment
     */
    const CABINOPT_RECOMMENDED = "RC";


    /**
     * @var int
     */
    public $nrOfRequestedPassengers;

    /**
     * Maximum number of recommendations requested
     *
     * @var int
     */
    public $nrOfRequestedResults;

    /**
     * Whether to perform a ticketability pre-check
     *
     * @var bool
     */
    public $doTicketabilityPreCheck = false;

    /**
     * List of airline options.
     *
     * Keys are the option to be used (self::AIRLINEOPT_*), values are the airline codes:
     *
     * e.g.
     * 'airlineOptions' => [
     *     self::AIRLINEOPT_PREFERRED => [
     *         'LH',
     *         'BA'
     *     ]
     * ]
     *
     * @var array
     */
    public $airlineOptions = [];

    /**
     * Itinerary-level flight options
     *
     * Choose from self::FLIGHTTYPE_*
     *
     * @var string[]
     */
    public $requestedFlightTypes = [];

    /**
     * Provide extra fare & flight options
     *
     * self::FLIGHTOPT_*
     *
     * @var string[]
     */
    public $flightOptions = [];

    /**
     * Corporate numbers for returning Corporate Unifares
     *
     * In combination with fareType self::FARETYPE::CORPORATE_UNIFARES
     *
     * @var string[]
     */
    public $corporateCodesUnifares = [];

    /**
     * Cabin class requested for the entire itinerary
     *
     * self::CABIN_*
     *
     * @var string
     */
    public $cabinClass;

    /**
     * Cabin option - how to interpret the cabin class
     *
     * self::CABINOPT_*
     *
     * @var string
     */
    public $cabinOption;

    /**
     * Passenger info
     *
     * @var Fare\MPPassenger[]
     */
    public $passengers = [];

    /**
     * Requested flight itinerary
     *
     * @var Fare\MPItinerary[]
     */
    public $itinerary = [];

    /**
     * The maximum price to be returned in the recommendations
     *
     * @var int|null
     */
    public $priceToBeat;

    /**
     * Currency of the maximum price to beat
     *
     * @var string|null
     */
    public $priceToBeatCurrency;
}
