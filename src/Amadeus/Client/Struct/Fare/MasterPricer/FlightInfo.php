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
 * FlightInfo
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FlightInfo
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

    /**
     * @var InclusionDetail[]
     */
    public $inclusionDetail = [];

    /**
     * @var ExclusionDetail[]
     */
    public $exclusionDetail = [];

    /**
     * @var UnitNumberDetail[]
     */
    public $unitNumberDetail = [];

    /**
     * FlightInfo constructor.
     *
     * @param array $airlineOptions
     * @param array $flightTypes
     * @param array $includedConnections
     * @param array $excludedConnections
     * @param int|null $connections
     * @param bool $noAirportChange
     * @param string|null $cabinCode CabinId::CABIN_*
     * @param string|null $cabinOption CabinId::CABINOPT_*
     */
    public function __construct(
        array $airlineOptions,
        array $flightTypes,
        array $includedConnections = [],
        array $excludedConnections = [],
        $connections = null,
        $noAirportChange = false,
        $cabinCode = null,
        $cabinOption = null
    ) {
        foreach ($airlineOptions as $qualifier => $airlines) {
            $this->companyIdentity[] = new CompanyIdentity(
                $qualifier,
                $airlines
            );
        }

        if (!empty($flightTypes)) {
            $this->flightDetail = new FlightDetail($flightTypes);
        }

        foreach ($includedConnections as $includedConnection) {
            $this->inclusionDetail[] = new InclusionDetail($includedConnection);
        }
        foreach ($excludedConnections as $excludedConnection) {
            $this->exclusionDetail[] = new ExclusionDetail($excludedConnection);
        }

        if (!is_null($connections)) {
            $this->unitNumberDetail[] = new UnitNumberDetail($connections, UnitNumberDetail::TYPE_NUM_OF_CONNECTIONS_ALLOWED);
        }

        if ($noAirportChange === true) {
            $this->unitNumberDetail[] = new UnitNumberDetail(1, UnitNumberDetail::TYPE_NO_AIRPORT_CHANGE);
        }
        if (!is_null($cabinCode) || !is_null($cabinOption)) {
            $this->cabinId = new CabinId($cabinCode, $cabinOption);
        }
    }
}
