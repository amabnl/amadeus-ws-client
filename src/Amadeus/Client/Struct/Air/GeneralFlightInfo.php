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

namespace Amadeus\Client\Struct\Air;

use Amadeus\Client\RequestOptions\AirFlightInfoOptions;

/**
 * GeneralFlightInfo
 *
 * @package Amadeus\Client\Struct\Air
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class GeneralFlightInfo
{
    /**
     * @var FlightDate
     */
    public $flightDate;
    /**
     * @var PointDetails
     */
    public $boardPointDetails;
    /**
     * @var PointDetails
     */
    public $offPointDetails;
    /**
     * @var CompanyDetails
     */
    public $companyDetails;
    /**
     * @var FlightIdentification
     */
    public $flightIdentification;
    /**
     * @var FlightTypeDetails
     */
    public $flightTypeDetails;
    /**
     * @var MarriageDetails[]
     */
    public $marriageDetails = [];

    /**
     * @param AirFlightInfoOptions $params
     */
    public function __construct(AirFlightInfoOptions $params)
    {
        $this->loadAirlineCode($params);

        $this->loadFlightNumber($params);

        $this->loadDepartureDate($params);
        $this->loadDepartureLocation($params);
        $this->loadArrivalLocation($params);
    }

    /**
     * @param AirFlightInfoOptions $params
     * @return void
     */
    protected function loadAirlineCode(AirFlightInfoOptions $params)
    {
        if ($params->airlineCode !== null) {
            $this->companyDetails = new CompanyDetails($params->airlineCode);
        }
    }

    /**
     * @param AirFlightInfoOptions $params
     * @return void
     */
    protected function loadFlightNumber(AirFlightInfoOptions $params)
    {
        if ($params->flightNumber !== null) {
            $this->flightIdentification = new FlightIdentification(
                $params->flightNumber,
                null,
                $params->flightNumberSuffix
            );
        }
    }

    /**
     * @param AirFlightInfoOptions $params
     * @return void
     */
    protected function loadDepartureDate(AirFlightInfoOptions $params)
    {
        if ($params->departureDate instanceof \DateTime) {
            $this->flightDate = new FlightDate($params->departureDate->format('dmy'));
        }
    }

    /**
     * @param AirFlightInfoOptions $params
     * @return void
     */
    protected function loadDepartureLocation(AirFlightInfoOptions $params)
    {
        if ($params->departureLocation !== null && strlen($params->departureLocation) === 3) {
            $this->boardPointDetails = new PointDetails($params->departureLocation);
        }
    }

    /**
     * @param AirFlightInfoOptions $params
     * @return void
     */
    protected function loadArrivalLocation(AirFlightInfoOptions $params)
    {
        if ($params->arrivalLocation !== null && strlen($params->arrivalLocation) === 3) {
            $this->offPointDetails = new PointDetails($params->arrivalLocation);
        }
    }
}
