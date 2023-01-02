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
 * PaxReference
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PaxReference
{
    /**
     * Specify requested passenger type
     *
     * @var string[]
     */
    public $ptc = [];
    /**
     * Traveller details
     *
     * @var Traveller[]
     */
    public $traveller = [];

    /**
     * @param int $mainTravellerRef
     * @param boolean $isInfant (OPTIONAL)
     * @param string|null $passengerType (OPTIONAL)
     */
    public function __construct($mainTravellerRef, $isInfant = false, $passengerType = null)
    {
        $this->traveller[] = new Traveller($mainTravellerRef, $isInfant);
        if (is_array($passengerType)) {
            $types = $passengerType;
        } else {
            $types = [$passengerType];
        }

        foreach ($types as $type) {
            $this->ptc[] = $type;
        }
    }
}
