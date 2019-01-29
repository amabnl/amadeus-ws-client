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
 * Hotel_MultiSingleAvailability request options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class HotelMultiSingleAvailOptions extends Base
{
    const SORT_NONE = "N";
    const SORT_CHEAPEST_FIRST = "RA";
    const SORT_CHEAPEST_LAST = "RD";
    const SORT_PROPERTY_CODE_ALPHABETIC = "CA";
    const SORT_PROPERTY_CODE_REVERSE = "CD";
    const SORT_PROPERTY_NAME_ALPHABETIC = "PA";
    const SORT_PROPERTY_NAME_REVERSE = "PD";

    const CACHE_LIVE = "Live";
    const CACHE_ONLY = "LessRecent";
    const CACHE_OR_AGGREGATOR = "VeryRecent";

    /**
     * Hotel segments availability requested
     *
     * @var Hotel\MultiSingleAvail\Segment[]
     */
    public $segments = [];

    /**
     * Return only rates that are available within the range of the minimum and maximum amount specified
     *
     * @var bool
     */
    public $rateRangeOnly = true;

    /**
     * Summary information in the response?
     *
     * @var bool
     */
    public $summaryOnly = true;

    /**
     * Should response contain room rate details?
     *
     * @var bool
     */
    public $rateDetails = true;

    /**
     * 2-character currency code
     *
     * @var string
     */
    public $requestedCurrency;

    /**
     * 2-character language code
     *
     * @var string
     */
    public $languageCode;

    /**
     * Include ONLY those rates that are available in the date range specified?
     *
     * @var bool
     */
    public $availableRatesOnly;

    /**
     * @var string
     */
    public $version = "4.000";

    /**
     * Show only those rates that are an exact match to the requested criteria?
     *
     * @var bool
     */
    public $exactMatchOnly;

    /**
     * Sort order of the returned property information
     *
     * self::SORT_*
     *
     * @var string
     */
    public $sortOrder;

    /**
     * How many results?
     *
     * @var int
     */
    public $maxResponses;

    /**
     * What caching level to be used
     *
     * self::CACHE_*
     *
     * @var string
     */
    public $searchCacheLevel;
}
