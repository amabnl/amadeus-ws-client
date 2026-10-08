<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

use Amadeus\Client\RequestOptions\Travel\OrderReshop\OriginDestRequest as RequestOriginDestRequest;

/**
 * OriginDestRequest
 *
 * Origin/Destination shopping request (OriginDestType). Element order follows
 * the XSD: DestArrivalRequest then OriginDepRequest.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class OriginDestRequest
{
    /**
     * @var DestArrivalRequest
     */
    public $DestArrivalRequest;

    /**
     * @var OriginDepRequest
     */
    public $OriginDepRequest;

    /**
     * @param RequestOriginDestRequest $req
     */
    public function __construct(RequestOriginDestRequest $req)
    {
        $this->DestArrivalRequest = new DestArrivalRequest(
            $req->destStationCode,
            $req->destDate
        );
        $this->OriginDepRequest = new OriginDepRequest(
            $req->originStationCode,
            $req->originDate
        );
    }
}
