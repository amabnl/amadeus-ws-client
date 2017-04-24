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

namespace Amadeus\Client\RequestOptions\Hotel\MultiSingleAvail;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Rates
 *
 * @package Amadeus\Client\RequestOptions\Hotel\MultiSingleAvail
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Rates extends LoadParamsFromArray
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
     * @var double
     */
    public $min;

    /**
     * @var double
     */
    public $max;

    /**
     * self::TIMEUNIT_*
     *
     * @var string
     */
    public $timeUnit;

    /**
     * 3-character ISO currency code
     *
     * @var string
     */
    public $currency;
}
