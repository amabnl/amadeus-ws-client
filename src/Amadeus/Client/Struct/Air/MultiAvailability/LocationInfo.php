<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * LocationInfo
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class LocationInfo
{
    /**
     * @var string
     */
    public $cityAirport;

    /**
     * 701 Force request to Airport/City code (depending of type of display)
     *
     * @var string
     */
    public $modifier;

    /**
     * LocationInfo constructor.
     *
     * @param string $code
     */
    public function __construct($code)
    {
        $this->cityAirport = $code;
    }
}
