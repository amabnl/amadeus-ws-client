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
 * TravelFlightInfo
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 */
class TravelFlightInfo
{
    /**
     * @var CabinId
     */
    public $cabinId;

    /**
     * @var CompanyIdentity[]
     */
    public $companyIdentity = [];

    /**
     * @var FlightDetail
     */
    public $flightDetail;

    public $inclusionDetail = [];

    public $exclusionDetail = [];

    public $unitNumberDetail = [];

    /**
     * TravelFlightInfo constructor.
     *
     * @param string|null $cabinCode CabinId::CABIN_*
     * @param string|null $cabinOption CabinId::CABINOPT_*
     * @param string[]|null $flightTypes
     * @param array|null $airlineOptions
     */
    public function __construct($cabinCode = null, $cabinOption = null, $flightTypes = null, $airlineOptions = null)
    {
        if (!is_null($cabinCode) || !is_null($cabinOption)) {
            $this->cabinId = new CabinId($cabinCode, $cabinOption);
        }

        if (is_array($flightTypes)) {
            $this->flightDetail = new FlightDetail($flightTypes);
        }

        if (!empty($airlineOptions)) {
            foreach ($airlineOptions as $qualifier => $airlines) {
                $this->companyIdentity[] = new CompanyIdentity(
                    $qualifier,
                    $airlines
                );
            }
        }
    }
}
