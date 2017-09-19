<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * NumberOfSeatsInfo
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class NumberOfSeatsInfo
{
    /**
     * @var int
     */
    public $numberOfPassengers;

    /**
     * NumberOfSeatsInfo constructor.
     *
     * @param int $numberOfPassengers
     */
    public function __construct($numberOfPassengers)
    {
        $this->numberOfPassengers = $numberOfPassengers;
    }
}
