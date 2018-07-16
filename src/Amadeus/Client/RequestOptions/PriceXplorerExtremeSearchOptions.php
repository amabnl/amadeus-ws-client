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
 * PriceXplorer_ExtremeSearch Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PriceXplorerExtremeSearchOptions extends Base
{
    const CURRENCY_EURO = 'EUR';

    const AGGR_DEST = 'D';
    const AGGR_DEST_WEEK = 'DW';
    const AGGR_DEST_WEEK_DEPART = 'DWDP';
    const AGGR_DEST_WEEK_DEPART_STAY = 'DWDPS';
    const AGGR_COUNTRY = 'C';

    /**
     * Departure location - airport or city code
     *
     * @var string
     */
    public $origin;

    /**
     * Destination locations - array of airport or city codes
     *
     * @var array
     */
    public $destinations = [];

    /**
     * Destination countries - array of country codes
     *
     * @var array
     */
    public $destinationCountries = [];

    /**
     * Currency for max/min budget options
     *
     * @var string
     */
    public $currency;

    /**
     * Maximum budget, expressed in $this->currency currency.
     *
     * @var int
     */
    public $maxBudget;

    /**
     * Minimum budget, expressed in $this->currency currency.
     *
     * @var int
     */
    public $minBudget;

    /**
     * Earliest possible departure date.
     *
     * @var \DateTime
     */
    public $earliestDepartureDate;

    /**
     * Latest possible departure date.
     *
     * @var \DateTime
     */
    public $latestDepartureDate;

    /**
     * (Consecutive) departure days of week for outbound flight
     *
     * 1: Monday
     * 2: Tuesday
     * 3: Wednesday
     * 4: Thursday
     * 5: Friday
     * 6: Saturday
     * 7: Sunday
     *
     * @var array
     */
    public $departureDaysOutbound = [];

    /**
     * (Consecutive) departure days of week for inbound flight
     *
     * 1: Monday
     * 2: Tuesday
     * 3: Wednesday
     * 4: Thursday
     * 5: Friday
     * 6: Saturday
     * 7: Sunday
     *
     * @var array
     */
    public $departureDaysInbound = [];

    /**
     * Stay Duration in days
     *
     * @var int
     */
    public $stayDurationDays;

    /**
     * Flexibility of Stay Duration in days
     *
     * @var int
     */
    public $stayDurationFlexibilityDays;

    /**
     * Set to True to return cheapest overall price
     *
     * @var boolean
     */
    public $returnCheapestOverall = false;

    /**
     * Set to True to return cheapest nonstop price
     *
     * @var boolean
     */
    public $returnCheapestNonStop = false;

    /**
     * Price result aggregation option - use one of the self::AGGR_* constants
     *
     * @var string
     */
    public $resultAggregationOption;

    /**
     * Which Office ID to use when finding prices
     *
     * @var string Amadeus Office ID
     */
    public $searchOffice;
}
