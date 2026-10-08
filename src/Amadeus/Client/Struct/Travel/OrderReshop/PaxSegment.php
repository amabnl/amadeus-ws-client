<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

use Amadeus\Client\RequestOptions\Travel\OrderReshop\PaxSegment as RequestPaxSegment;

/**
 * PaxSegment
 *
 * Transportation of a passenger on a dated operating segment (PaxSegmentType).
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class PaxSegment
{
    /**
     * @var string|null
     */
    public $PaxSegmentID;

    /**
     * @var TransportPoint
     */
    public $Departure;

    /**
     * @var TransportPoint
     */
    public $Arrival;

    /**
     * @var MarketingCarrierInfo
     */
    public $MarketingCarrierInfo;

    /**
     * @param RequestPaxSegment $segment
     */
    public function __construct(RequestPaxSegment $segment)
    {
        if ($segment->paxSegmentId !== null) {
            $this->PaxSegmentID = $segment->paxSegmentId;
        }

        $this->Departure = new TransportPoint(
            $segment->departureLocationCode,
            $segment->departureDateTime
        );
        $this->Arrival = new TransportPoint(
            $segment->arrivalLocationCode,
            $segment->arrivalDateTime
        );
        $this->MarketingCarrierInfo = new MarketingCarrierInfo(
            $segment->marketingCarrierCode,
            $segment->marketingFlightNumber,
            $segment->rbdCode
        );
    }
}
