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
 * MPTripDetails
 *
 * Details of the trip duration
 *
 * @package Amadeus\Client\RequestOptions\Fare
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class MPTripDetails extends LoadParamsFromArray
{
    const FLEXIBILITY_COMBINED = 'C';
    const FLEXIBILITY_MINUS = 'M';
    const FLEXIBILITY_PLUS = 'P';
    const FLEXIBILITY_ARRIVAL_BY = 'TA';
    const FLEXIBILITY_DEPART_FROM = 'TD';

    /**
     * self::FLEXIBILITY_*
     *
     * @var string
     */
    public $flexibilityQualifier;

    /**
     * Number of days added or/and retrieved to the trip duration.
     *
     * @var int
     */
    public $tripInterval;

    /**
     * Period between date of departure and date of arrival.
     *
     * @var int
     */
    public $tripDuration;
}
