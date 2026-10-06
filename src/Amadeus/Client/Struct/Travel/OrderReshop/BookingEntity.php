<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * BookingEntity
 *
 * Airline or organization assigning the booking information.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class BookingEntity
{
    /**
     * @var Carrier
     */
    public $Carrier;

    /**
     * @param string $airlineDesigCode
     */
    public function __construct($airlineDesigCode)
    {
        $this->Carrier = new Carrier($airlineDesigCode);
    }
}
