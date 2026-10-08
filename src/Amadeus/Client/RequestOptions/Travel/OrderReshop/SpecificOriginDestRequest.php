<?php

namespace Amadeus\Client\RequestOptions\Travel\OrderReshop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * SpecificOriginDestRequest - request options
 *
 * Origin/Destination specific query, including detailed Journey and Segment
 * information (FlightRequest/SpecificOriginDestRequest).
 *
 * @package Amadeus\Client\RequestOptions\Travel\OrderReshop
 */
class SpecificOriginDestRequest extends LoadParamsFromArray
{
    /**
     * IATA code identifying the origin city or station.
     *
     * @var string
     */
    public $originStationCode;

    /**
     * IATA code identifying the destination city or station.
     *
     * @var string
     */
    public $destStationCode;

    /**
     * Journey ID (PaxJourney/PaxJourneyID) - optional.
     *
     * @var string|null
     */
    public $paxJourneyId;

    /**
     * The segments of this journey.
     *
     * @var PaxSegment[]
     */
    public $paxSegments;
}
