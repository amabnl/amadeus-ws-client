<?php

namespace Amadeus\Client\RequestOptions\Travel\OrderReshop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * PaxSegment - request options
 *
 * Transportation of a passenger on a dated operating segment (PaxSegmentType).
 *
 * @package Amadeus\Client\RequestOptions\Travel\OrderReshop
 */
class PaxSegment extends LoadParamsFromArray
{
    /**
     * Segment ID (PaxSegmentID) - optional.
     *
     * @var string|null
     */
    public $paxSegmentId;

    /**
     * Departure IATA location code.
     *
     * @var string
     */
    public $departureLocationCode;

    /**
     * Departure scheduled date/time (Y-m-d\TH:i:s) - optional.
     *
     * @var \DateTime|string|null
     */
    public $departureDateTime;

    /**
     * Arrival IATA location code.
     *
     * @var string
     */
    public $arrivalLocationCode;

    /**
     * Arrival scheduled date/time (Y-m-d\TH:i:s) - optional.
     *
     * @var \DateTime|string|null
     */
    public $arrivalDateTime;

    /**
     * Marketing carrier code (MarketingCarrierInfo/CarrierDesigCode).
     *
     * @var string
     */
    public $marketingCarrierCode;

    /**
     * Marketing carrier flight number.
     *
     * @var string
     */
    public $marketingFlightNumber;

    /**
     * Reservation booking designator code (RBD_Code) - optional.
     *
     * @var string|null
     */
    public $rbdCode;
}
