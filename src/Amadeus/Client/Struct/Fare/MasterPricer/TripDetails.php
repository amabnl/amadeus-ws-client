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

use Amadeus\Client\RequestOptions\Fare\MPTripDetails;

/**
 * TripDetails
 *
 * Amadeus currently not uses this node, but may be used in future versions.
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TripDetails
{
    const FLEXIBILITY_COMBINED = 'C';
    const FLEXIBILITY_MINUS = 'M';
    const FLEXIBILITY_PLUS = 'P';
    const FLEXIBILITY_ARRIVAL_BY = 'TA';
    const FLEXIBILITY_DEPART_FROM = 'TD';

    /**
     * @var string
     */
    public $flexibilityQualifier;

    /**
     * @var int
     */
    public $tripInterval;

    /**
     * @var int
     */
    public $tripDuration;

    /**
     * TripDetails constructor.
     *
     * @param MPTripDetails $tripDetails
     */
    public function __construct(MPTripDetails $tripDetails)
    {
        $this->flexibilityQualifier = $tripDetails->flexibilityQualifier;
        $this->tripInterval = $tripDetails->tripInterval;
        $this->tripDuration = $tripDetails->tripDuration;
    }
}
