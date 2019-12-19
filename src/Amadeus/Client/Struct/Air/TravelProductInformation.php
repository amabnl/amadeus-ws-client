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

/**
 * TravelProductInformation
 *
 * @package Amadeus\Client\Struct\Air
 * @author dieter <dermikagh@gmail.com>
 */
class TravelProductInformation
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
     * 700 Request is expanded to exclude connection point(s)
     * 701 Request is expanded to exclude carriers
     * 702 Before connection
     * 703 After connection
     * 704 Board point used as airport code only
     * 705 Off point used as airport code only
     * 706 Board point and off point used as airport code only
     * 7SR Stateless/refugee/etc
     * 7TR Transit visa
     * ACK Acknowledgment
     * AF All flights to be processed
     * AI Additional information
     * AT Alternate flight
     * B Boarding pass may not be issued until the mutually agreed time period.
     * BS Blind sell
     * C Contact information
     * CD Change of date
     * CM1 Change date minus 1 day
     * CN Cascading not allowed
     * CP2 Change date plus 2 days
     * CY Cascading allowed
     * DP Diplomatic
     * EC Excess bags charged
     * EI Excess bags identified
     * EW Excess bags waived
     * F Form of payment details
     * FA First available
     * FE Bagtag issuance required by querying system
     * FN No, seat request not fulfilled
     * FT Fare/tax/total details
     * FY Yes, seat request fulfilled
     * GC Green card/alien resident permit
     * HP Head of Baggage Pool
     * J Action based on journey
     * MH Bagtag issuance required by responding system
     * MI Military ID
     * MP Member of Baggage Pool
     * MPP Multi passenger passport
     * N No action required
     * NB No a boarding pass may not be issued
     * NP Not pooled
     * NS Requested city pair, no seat data applies
     * P Action required
     * PC Purchaser ticketing restriction/conditions
     * PI Partial passenger indicator
     * PP Partial passenger/partial segment indicator
     * PS Partial segment indicator
     * PT Passport
     * R Routing information
     * RD Reservations details
     * SA Seat assignment association - desires seating together
     * SS Seaman/ Sailor
     * T Total amount collected
     * TF This flight only to be processed
     * TP Action required and candidate for special Yield Management processing
     * VI Visa
     * Y Yes a boarding pass may be issued
     * YP Pooled
     *
     * @var string
     */
    public $specialSegment;
    /**
     * @var MarriageDetails
     */
    public $marriageDetails;

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
        $this->flightDate = $this->parseFlightDate($departureDate, $arrivalDate, $arrivalTime, $dateVariation);
        $this->boardPointDetails = new PointDetails($from);
        $this->offpointDetails = new PointDetails($to);
        $this->companyDetails = new CompanyDetails($company);
        $this->flightIdentification = new FlightIdentification($flightNumber, $bookingClass);
        if (!is_null($flightTypeDetails)) {
            $this->flightTypeDetails = new FlightTypeDetails($flightTypeDetails);
        }
    }

    protected function parseFlightDate($departureDate, $arrivalDate, $arrivalTime, $dateVariation)
    {
        return new FlightDate($departureDate, $arrivalDate, $arrivalTime, $dateVariation);
    }
}
