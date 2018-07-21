<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * FlightIdentification
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FlightIdentification
{
    /**
     * IATA code for the airline
     *
     * @var string
     */
    public $airlineCode;

    /**
     * Flight number
     *
     * @var string
     */
    public $number;

    /**
     * Flight suffix
     *
     * @var string
     */
    public $suffix;

    /**
     * FlightIdentification constructor.
     *
     * @param string $airline Airline code
     * @param string|null $flightNumber
     */
    public function __construct($airline, $flightNumber = null)
    {
        $this->airlineCode = $airline;
        $this->number = $flightNumber;
    }
}
