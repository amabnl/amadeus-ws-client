<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * DestArrivalRequest
 *
 * Requested destination arrival location/time (DestArrivalRequestType).
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class DestArrivalRequest
{
    /**
     * @var string
     */
    public $IATA_LocationCode;

    /**
     * @var string|null
     */
    public $Date;

    /**
     * @param string $iataLocationCode
     * @param \DateTime|string|null $date
     */
    public function __construct($iataLocationCode, $date = null)
    {
        $this->IATA_LocationCode = $iataLocationCode;

        if ($date !== null) {
            $this->Date = $date instanceof \DateTimeInterface ? $date->format('Y-m-d') : $date;
        }
    }
}
