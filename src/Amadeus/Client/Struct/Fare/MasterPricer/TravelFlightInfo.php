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

use Amadeus\Client\Struct\WsMessageUtility;

/**
 * TravelFlightInfo
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 */
class TravelFlightInfo extends WsMessageUtility
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

    /**
     * @var UnitNumberDetail[]
     */
    public $unitNumberDetail = [];

    /**
     * TravelFlightInfo constructor.
     *
     * @param string|null $cabinCode CabinId::CABIN_*
     * @param string|null $cabinOption CabinId::CABINOPT_*
     * @param string[]|null $flightTypes
     * @param array|null $airlineOptions
     * @param int|null $progressiveLegsMin
     * @param int|null $progressiveLegsMax
     * @param int|null $maxLayoverPerConnectionHours
     * @param int|null $maxLayoverPerConnectionMinutes
     * @param bool|null $noAirportChange
     * @param int|null $maxElapsedFlyingTime
     */
    public function __construct(
        $cabinCode = null,
        $cabinOption = null,
        $flightTypes = null,
        $airlineOptions = null,
        $progressiveLegsMin = null,
        $progressiveLegsMax = null,
        $maxLayoverPerConnectionHours = null,
        $maxLayoverPerConnectionMinutes = null,
        $noAirportChange = null,
        $maxElapsedFlyingTime = null
    ) {
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

        $this->loadUnitNumberDetails(
            $progressiveLegsMin,
            $progressiveLegsMax,
            $maxLayoverPerConnectionHours,
            $maxLayoverPerConnectionMinutes,
            $noAirportChange,
            $maxElapsedFlyingTime
        );
    }

    /**
     * @param int|null $progressiveLegsMin
     * @param int|null $progressiveLegsMax
     * @param int|null $maxLayoverPerConnectionHours
     * @param int|null $maxLayoverPerConnectionMinutes
     * @param bool|null $noAirportChange
     * @param int|null $maxElapsedFlyingTime
     */
    protected function loadUnitNumberDetails(
        $progressiveLegsMin,
        $progressiveLegsMax,
        $maxLayoverPerConnectionHours,
        $maxLayoverPerConnectionMinutes,
        $noAirportChange,
        $maxElapsedFlyingTime
    ) {
        if ($this->checkAllIntegers($progressiveLegsMin, $progressiveLegsMax)) {
            $this->unitNumberDetail[] = new UnitNumberDetail(
                $progressiveLegsMin,
                UnitNumberDetail::TYPE_MINIMUM_PROGRESSIVE_CONNECTIONS
            );
            $this->unitNumberDetail[] = new UnitNumberDetail(
                $progressiveLegsMax,
                UnitNumberDetail::TYPE_MAXIMUM_PROGRESSIVE_CONNECTIONS
            );
        }

        if (is_int($maxLayoverPerConnectionHours)) {
            $this->unitNumberDetail[] = new UnitNumberDetail(
                $maxLayoverPerConnectionHours,
                UnitNumberDetail::TYPE_MAX_LAYOVER_PER_CONNECTION_REQUESTED_SEGMENT_HOURS
            );
        }

        if (is_int($maxLayoverPerConnectionMinutes)) {
            $this->unitNumberDetail[] = new UnitNumberDetail(
                $maxLayoverPerConnectionMinutes,
                UnitNumberDetail::TYPE_MAX_LAYOVER_PER_CONNECTION_REQUESTED_SEGMENT_MINUTES
            );
        }

        if ($noAirportChange === true) {
            $this->unitNumberDetail[] = new UnitNumberDetail(
                1,
                UnitNumberDetail::TYPE_NO_AIRPORT_CHANGE
            );
        }

        if (is_int($maxElapsedFlyingTime)) {
            $this->unitNumberDetail[] = new UnitNumberDetail(
                $maxElapsedFlyingTime,
                UnitNumberDetail::TYPE_PERCENTAGE_OF_SHORTEST_ELAPSED_FLYING_TIME
            );
        }
    }
}
