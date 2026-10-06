<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * Carrier
 *
 * The carrier issuing the ticket.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class Carrier
{
    /**
     * @var string
     */
    public $AirlineDesigCode;

    /**
     * @param string $airlineDesigCode
     */
    public function __construct($airlineDesigCode)
    {
        $this->AirlineDesigCode = $airlineDesigCode;
    }
}
