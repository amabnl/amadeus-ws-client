<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * OriginDepRequest
 *
 * Requested origin departure location/date (OriginDepRequestType). Date is
 * mandatory per the XSD.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class OriginDepRequest
{
    /**
     * @var string
     */
    public $IATA_LocationCode;

    /**
     * @var string
     */
    public $Date;

    /**
     * @param string $iataLocationCode
     * @param \DateTime|string $date
     */
    public function __construct($iataLocationCode, $date)
    {
        $this->IATA_LocationCode = $iataLocationCode;
        $this->Date = $date instanceof \DateTimeInterface ? $date->format('Y-m-d') : $date;
    }
}
