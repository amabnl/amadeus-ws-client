<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\RetrieveSeatMap;

use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FlightInfo;
use Amadeus\Client\Struct\Air\CompanyDetails;
use Amadeus\Client\Struct\Air\FlightDate;
use Amadeus\Client\Struct\Air\FlightIdentification;
use Amadeus\Client\Struct\Air\FlightTypeDetails;
use Amadeus\Client\Struct\Air\PointDetails;

/**
 * TravelProductIdent
 *
 * @package Amadeus\Client\Struct\Air\RetrieveSeatMap
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TravelProductIdent
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
     * TravelProductIdent constructor.
     *
     * @param FlightInfo $flightInfo
     */
    public function __construct(FlightInfo $flightInfo)
    {
        $this->flightDate = new FlightDate($flightInfo->departureDate);
        $this->boardPointDetails = new PointDetails($flightInfo->departure);
        $this->offpointDetails = new PointDetails($flightInfo->arrival);
        $this->companyDetails = new CompanyDetails($flightInfo->airline);
        $this->flightIdentification = new FlightIdentification(
            $flightInfo->flightNumber,
            $flightInfo->bookingClass
        );
    }
}
