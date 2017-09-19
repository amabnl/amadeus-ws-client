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

namespace Amadeus\Client\Struct\Fare\MasterPricer;

/**
 * FirstDateTimeDetail
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FirstDateTimeDetail
{
    const TIMEQUAL_MINUS = "M";
    const TIMEQUAL_PLUS = "P";
    const TIMEQUAL_PLUS_MINUS_COMBINED = "C";
    const TIMEQUAL_ARRIVAL_BY = "TA";
    const TIMEQUAL_DEPART_FROM = "TD";

    /**
     * self::TIMEQUAL_*
     *
     * @var string
     */
    public $timeQualifier;

    /**
     * DDMMYY
     *
     * @var string
     */
    public $date;

    /**
     * HHMM
     *
     * @var string
     */
    public $time;

    /**
     * Nr of Hours
     *
     * @var int
     */
    public $timeWindow;

    /**
     * FirstDateTimeDetail constructor.
     *
     * @param string $date DDMMYY
     */
    public function __construct($date)
    {
        $this->date = $date;
    }
}
