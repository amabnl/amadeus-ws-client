<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

use Amadeus\Client\RequestOptions\Travel\OrderReshop\PaxSegment as RequestPaxSegment;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\SpecificOriginDestRequest as RequestSpecificOriginDestRequest;

/**
 * SpecificOriginDestRequest
 *
 * Segment-detailed origin/destination query
 * (FlightRequest/SpecificOriginDestRequest).
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class SpecificOriginDestRequest
{
    /**
     * @var string
     */
    public $OriginStationCode;

    /**
     * @var string
     */
    public $DestStationCode;

    /**
     * @var PaxJourney
     */
    public $PaxJourney;

    /**
     * @param RequestSpecificOriginDestRequest $req
     */
    public function __construct(RequestSpecificOriginDestRequest $req)
    {
        $this->OriginStationCode = $req->originStationCode;
        $this->DestStationCode = $req->destStationCode;

        $paxSegments = array_map(
            static function (RequestPaxSegment $segment) {
                return new PaxSegment($segment);
            },
            $req->paxSegments
        );

        $this->PaxJourney = new PaxJourney($req->paxJourneyId, $paxSegments);
    }
}
