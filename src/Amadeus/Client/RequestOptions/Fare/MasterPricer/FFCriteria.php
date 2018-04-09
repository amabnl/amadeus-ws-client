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

namespace Amadeus\Client\RequestOptions\Fare\MasterPricer;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Fare Family Criteria
 *
 * @package Amadeus\Client\RequestOptions\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FFCriteria extends LoadParamsFromArray
{
    const FARETYPE_ATPCO_NEGO_FARES_CAT35 = "RA";
    const FARETYPE_DDF_BASED_ON_PUBLIC_FARES = "RD";
    const FARETYPE_DDF_BASED_ON_PRIVATE_FARES = "RDV";
    const FARETYPE_AMADEUS_NEGO_FARES = "RN";
    const FARETYPE_PUBLISHED_FARES = "RP";
    const FARETYPE_UNIFARES = "RU";
    const FARETYPE_ATPCO_PRIVATE_FARES_CAT15 = "RV";

    /**
     * Fare Family Combinability
     *
     * @var bool
     */
    public $combinable = true;

    /**
     * Fare Family Alternate Price mode
     *
     * For each recommendation returns the cheapest
     * available alternate recommendation for the exact same journey and cabin.
     *
     * @var bool
     */
    public $alternatePrice = false;

    /**
     * Fare Publishing carrier(s)
     *
     * @var string[]
     */
    public $carriers = [];

    /**
     * Prime Booking Code(s)
     *
     * @var string[]
     */
    public $bookingCode = [];

    /**
     * Type of Fare
     *
     * self::FARETYPE_*
     *
     * @var string[]
     */
    public $fareType = [];

    /**
     * Fare Basis
     *
     * @var string[]
     */
    public $fareBasis = [];

    /**
     * CORP / NONCORP or CORPORATE CODE
     *
     * @var string[]
     */
    public $corporateCodes = [];

    /**
     * CORPORATE NAME
     *
     * @var string[]
     */
    public $corporateNames = [];

    /**
     * Cabin designators
     * @var string[]
     */
    public $cabins = [];

    /**
     * Expanded Parameters
     *
     * @var string[]
     */
    public $expandedParameters = [];
}
