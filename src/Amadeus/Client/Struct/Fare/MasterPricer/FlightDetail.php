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
 * FlightDetail
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FlightDetail
{
    const FLIGHT_TYPE_CONNECTING = 'C';
    const FLIGHT_TYPE_DIRECT = 'D';
    const FLIGHT_TYPE_DISABLE_NEGO_SPACE = 'DN';
    const FLIGHT_TYPE_NON_STOP = 'N';
    const FLIGHT_TYPE_RETURN_CHEAPEST_ONLINE = 'OL';
    const FLIGHT_TYPE_OVERNIGHT_NOT_ALLOWED = 'OV';

    /**
     * self::FLIGHT_TYPE_*
     *
     * @var string[]
     */
    public $flightType = [];

    /**
     * FlightDetail constructor.
     *
     * @param string[] $flightType
     */
    public function __construct(array $flightType = [])
    {
        $this->flightType = $flightType;
    }
}
