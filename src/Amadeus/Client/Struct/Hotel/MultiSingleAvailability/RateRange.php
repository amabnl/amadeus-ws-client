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

namespace Amadeus\Client\Struct\Hotel\MultiSingleAvailability;

use Amadeus\Client\RequestOptions\Hotel\MultiSingleAvail\Rates;

/**
 * RateRange
 *
 * @package Amadeus\Client\Struct\Hotel\MultiSingleAvailability
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class RateRange
{
    const TIMEUNIT_YEAR = "Year";
    const TIMEUNIT_MONTH = "Month";
    const TIMEUNIT_WEEK = "Week";
    const TIMEUNIT_DAY = "Day";
    const TIMEUNIT_HOUR = "Hour";
    const TIMEUNIT_SECOND = "Second";
    const TIMEUNIT_FULL_DURATION = "FullDuration";
    const TIMEUNIT_MINUTE = "Minute";

    /**
     * @var double|string
     */
    public $MinRate;

    /**
     * @var double|string
     */
    public $MaxRate;

    /**
     * @var double|string
     */
    public $FixedRate;

    /**
     * self::TIMEUNIT_*
     *
     * @var string
     */
    public $RateTimeUnit;

    /**
     * @var string
     */
    public $CurrencyCode;

    /**
     * @var int
     */
    public $DecimalPlaces;

    /**
     * RateRange constructor.
     *
     * @param Rates $rates
     */
    public function __construct(Rates $rates)
    {
        $this->MinRate = $rates->min;
        $this->MaxRate = $rates->max;
        $this->CurrencyCode = $rates->currency;
        $this->RateTimeUnit = $rates->timeUnit;
    }
}
