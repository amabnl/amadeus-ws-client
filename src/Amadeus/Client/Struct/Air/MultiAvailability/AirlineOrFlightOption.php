<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * AirlineOrFlightOption
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AirlineOrFlightOption
{
    const INDICATOR_EXCLUDE = 701;
    const INDICATOR_OPERATIONAL_CARRIER = 705;

    /**
     * @var FlightIdentification[]
     */
    public $flightIdentification = [];

    /**
     * self::INDICATOR_*
     *
     * @var int
     */
    public $excludeAirlineIndicator;

    /**
     * AirlineOrFlightOption constructor.
     * @param string[] $airlines
     * @param string|null $flightNumber
     * @param int|null $indicator
     */
    public function __construct($airlines, $flightNumber = null, $indicator = null)
    {
        foreach ($airlines as $count => $airline) {
            if ($count === 0) {
                $this->flightIdentification[] = new FlightIdentification($airline, $flightNumber);
            } else {
                $this->flightIdentification[] = new FlightIdentification($airline);
            }
        }

        $this->excludeAirlineIndicator = $indicator;
    }
}
