<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * TransportPoint
 *
 * Departure/Arrival point of a segment (TransportDepType / TransportArrivalType,
 * which share the same element structure).
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class TransportPoint
{
    /**
     * @var string
     */
    public $IATA_LocationCode;

    /**
     * @var string|null
     */
    public $AircraftScheduledDateTime;

    /**
     * @param string $iataLocationCode
     * @param \DateTime|string|null $dateTime
     */
    public function __construct($iataLocationCode, $dateTime = null)
    {
        $this->IATA_LocationCode = $iataLocationCode;

        if ($dateTime !== null) {
            $this->AircraftScheduledDateTime = $dateTime instanceof \DateTimeInterface
                ? $dateTime->format('Y-m-d\TH:i:s')
                : $dateTime;
        }
    }
}
