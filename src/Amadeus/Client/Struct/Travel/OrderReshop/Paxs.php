<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * Paxs
 *
 * Passenger information wrapper (PaxsType).
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class Paxs
{
    /**
     * @var Pax[]
     */
    public $Pax;

    /**
     * @param Pax[] $pax
     */
    public function __construct(array $pax)
    {
        $this->Pax = $pax;
    }
}
