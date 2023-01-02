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
 * MasterPricer request date settings
 *
 * @package Amadeus\Client\RequestOptions\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MPDate extends LoadParamsFromArray
{
    const RANGEMODE_MINUS_PLUS = "C";
    const RANGEMODE_MINUS = "M";
    const RANGEMODE_PLUS = "P";

    /**
     * Departure or arrival date & time.
     *
     * The time part is only used if it is not "00:00" when converted to string.
     *
     * @var \DateTime
     */
    public $dateTime;

    /**
     * Departure or arrival date
     *
     * We only use the date portion!
     *
     * @deprecated use dateTime instead. When using both, dateTime property has priority
     * @var \DateTime
     */
    public $date;

    /**
     * Departure or arrival time
     *
     * We only use the time portion!
     *
     * @deprecated use dateTime instead.  When using both, dateTime property has priority
     * @var \DateTime
     */

    public $time;

    /**
     * Whether time is for specifying departure time or arrival time
     *
     * if true: departure time
     * if false: arrival time
     *
     * @var bool
     */
    public $isDeparture = true;

    /**
     * Allowed time window (expressed in hours) before/after specified time.
     *
     * @var int
     */
    public $timeWindow;

    /**
     * If you want a range of dates, provide the range mode here
     *
     * self::RANGEMODE_*
     *
     * @var string
     */
    public $rangeMode;

    /**
     * Date range expressed in days
     *
     * For MasterPricerTravelBoardSearch, this can be 1 or 0.
     * For MasterPricerCalendar, higher values are supported.
     *
     * @var int
     */
    public $range;

    /**
     * Details of the trip duration.
     *
     * Amadeus currently not uses this node, but may be used in future versions.
     *
     * @var MPTripDetails
     */
    public $tripDetails;
}
