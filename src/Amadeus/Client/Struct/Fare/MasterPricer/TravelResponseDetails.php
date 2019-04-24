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

use Amadeus\Client\Struct\Air\PointDetails;
use Amadeus\Client\Struct\Air\CompanyDetails;
use Amadeus\Client\Struct\Air\FlightIdentification;
use Amadeus\Client\Struct\Air\FlightTypeDetails;

/**
 * TravelResponseDetails
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Mike Hernas <mike@ahoy.io>
 */
class TravelResponseDetails
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
    public $offpointDetails;
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
     * TravelProductInformation constructor.
     *
     * @param \DateTime $departureDate
     * @param string $from
     * @param string $to
     * @param string $company
     * @param string $flightNumber
     * @param string $bookingClass
     * @param \DateTime|null $arrivalDate
     * @param string|\DateTime|null $arrivalTime
     * @param int|null $dateVariation
     * @param string|null $flightTypeDetails
     */
    public function __construct(
        $departureDate,
        $from,
        $to,
        $company,
        $flightNumber,
        $bookingClass,
        $arrivalDate = null,
        $arrivalTime = null,
        $dateVariation = null,
        $flightTypeDetails = null
    ) {
        $this->flightDate = new FlightDate($departureDate, $arrivalDate, $arrivalTime, $dateVariation);
        $this->boardPointDetails = new PointDetails($from);
        $this->offpointDetails = new PointDetails($to);
        $this->companyDetails = new CompanyDetails($company);
        $this->flightIdentification = new FlightIdentification($flightNumber, $bookingClass);
        if (!is_null($flightTypeDetails)) {
            $this->flightTypeDetails = new FlightTypeDetails($flightTypeDetails);
        }
    }
}
