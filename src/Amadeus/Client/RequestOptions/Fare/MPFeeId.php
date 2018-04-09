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

namespace Amadeus\Client\RequestOptions\Fare;

use Amadeus\Client\LoadParamsFromArray;

/**
 * MasterPricer Fee Id request options.
 *
 * @package Amadeus\Client\RequestOptions\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MPFeeId extends LoadParamsFromArray
{
    const FEETYPE_ARP_NUMBER = "ARP";
    const FEETYPE_FREE_BAGGAGE_ALLOWANCE = "FBA";
    const FEETYPE_FARE_FAMILY_INFORMATION = "FFI";
    const FEETYPE_NO_PNR_SPLIT = "NPS";
    const FEETYPE_PRICE_TO_REACH_AMOUNT_TYPE = "PTRAM";
    const FEETYPE_HAL_ROUND_TRIP_COMBINATION = "RTC";
    const FEETYPE_SEARCH_BY_FBA = "SBF";
    const FEETYPE_SORTING_OPTION = "SORT";
    const FEETYPE_TOKEN = "TOKEN";
    const FEETYPE_UPSELL_PER_BOUND = "UPB";
    const FEETYPE_HOMOGENOUS_UPSELL = "UPH";
    
    /**
     * Fee Type
     *
     * self::FEETYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * Fee Id
     *
     * @var int
     */
    public $number;
}
