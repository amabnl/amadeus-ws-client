<?php
/**
 * A fix solution for amadeus seatmap
 */

namespace Amadeus\Client\Struct\Air\RetrieveSeatMap;

use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FlightInfo;
use Amadeus\Client\Struct\Air\FlightDate;
use Amadeus\Client\Struct\Air\FlightIdentification;
use Amadeus\Client\Struct\Air\FlightTypeDetails;
use Amadeus\Client\Struct\Air\PointDetails;
use Amadeus\Client\RequestOptions\BoardingpointDetail;
use Amadeus\Client\RequestOptions\CompanyIdentification;
use Amadeus\Client\RequestOptions\OffPointDetail;

/**
 * TravelProductIdent
 *
 * @package Amadeus\Client\Struct\Air\RetrieveSeatMap
 * @author  Dieter Devlieghere <dermikagh@gmail.com>
 */
class TravelProductIdent
{
    /**
     * @var FlightDate
     */
    public $productDetails;

    /**
     * @var PointDetails
     */
    public $boardpointDetail;

    /**
     * @var PointDetails
     */
    public $offPointDetail;

    /**
     * @var CompanyIdentification
     */
    public $companyIdentification;

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
        $this->productDetails = new FlightDate($flightInfo->departureDate);
        $this->boardpointDetail = new BoardingpointDetail($flightInfo->departure);
        $this->offPointDetail = new OffPointDetail($flightInfo->arrival);
        $this->companyIdentification = new CompanyIdentification($flightInfo->airline);
        $this->flightIdentification = new FlightIdentification(
            $flightInfo->flightNumber,
            $flightInfo->bookingClass
        );
    }
}
