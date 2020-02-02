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
 * Request Options for Fare_MasterPricerTravelboardSearch
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FareMasterPricerExSearchOptions extends MpBaseOptions
{
    const FLIGHTTYPE_DIRECT = 'D';
    const FLIGHTTYPE_NONSTOP = 'N';
    const FLIGHTTYPE_CONNECTING = 'C';

    const CABIN_ECONOMY = 'Y';
    const CABIN_ECONOMY_STANDARD = 'M';
    const CABIN_ECONOMY_PREMIUM = 'W';
    const CABIN_BUSINESS = 'C';
    const CABIN_FIRST_SUPERSONIC = 'F';

    const AIRLINEOPT_PREFERRED = 'F';
    const AIRLINEOPT_MANDATORY = 'M';
    const AIRLINEOPT_NIGHT_CLASS = 'N';
    const AIRLINEOPT_FORCE_FULLAIRLINE_RECOMMENDATION = 'O';
    const AIRLINEOPT_CARRIERS_LIST_BYPASS_BSP_CHECKS = 'T';
    const AIRLINEOPT_MANDATORY_VALIDATING_CARRIER = 'V';
    const AIRLINEOPT_EXCLUDED_VALIDATING_CARRIER = 'W';
    const AIRLINEOPT_EXCLUDED = 'X';

    /**
     * Major cabin
     */
    const CABINOPT_MAJOR = 'MC';
    /**
     * Mandatory cabin for all segments
     */
    const CABINOPT_MANDATORY = 'MD';
    /**
     * Recommended cabin to be used at least one segment
     */
    const CABINOPT_RECOMMENDED = 'RC';

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

    /**
     * Fare Families
     *
     * @var Fare\MPFareFamily[]
     */
    public $fareFamilies = [];

    /**
     * Office IDs
     *
     * @var string[]
     */
    public $officeIds = [];

    /**
     * Progressive legs enables to request a range of number of connections
     * relative to the minimum connections that exist on Journey Server.
     *
     * Enter the minimum amount of allowed progressive legs here.
     *
     * @var int
     */
    public $progressiveLegsMin;

    /**
     * Progressive legs enables to request a range of number of connections
     * relative to the minimum connections that exist on Journey Server.
     *
     * Enter the maximum amount of allowed progressive legs here.
     *
     * @var int
     */
    public $progressiveLegsMax;

    /**
     * "DK" number / customer identification number
     *
     * @var string
     */
    public $dkNumber;

    /**
     * Each connection of each requested segment has a layover limited to X hours.
     *
     * @var int
     */
    public $maxLayoverPerConnectionHours;

    /**
     * Each connection of each requested segment has a layover limited to Y minutes.
     *
     * @var int
     */
    public $maxLayoverPerConnectionMinutes;

    /**
     * Fee Options
     *
     * @var Fare\MPFeeOption[]
     */
    public $feeOption = [];
}
