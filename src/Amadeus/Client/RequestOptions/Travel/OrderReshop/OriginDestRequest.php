<?php

namespace Amadeus\Client\RequestOptions\Travel\OrderReshop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * OriginDestRequest - request options
 *
 * The origin/destination pair for a shopping request (OriginDestType).
 *
 * @package Amadeus\Client\RequestOptions\Travel\OrderReshop
 */
class OriginDestRequest extends LoadParamsFromArray
{
    /**
     * Destination IATA location code (DestArrivalRequest/IATA_LocationCode).
     *
     * @var string
     */
    public $destStationCode;

    /**
     * Requested destination arrival date (Y-m-d) - optional.
     *
     * @var \DateTime|string|null
     */
    public $destDate;

    /**
     * Origin IATA location code (OriginDepRequest/IATA_LocationCode).
     *
     * @var string
     */
    public $originStationCode;

    /**
     * Requested origin departure date (Y-m-d) - mandatory in OriginDepRequest.
     *
     * @var \DateTime|string
     */
    public $originDate;
}
