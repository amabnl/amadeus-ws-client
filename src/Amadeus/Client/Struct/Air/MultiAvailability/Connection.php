<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * Connection
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Connection
{
    const INDICATOR_EXCLUDE = 700;
    const INDICATOR_FORCE_AIRPORT_CITY = 701;
    const INDICATOR_AIRPORT_ASSOCIATION = "NAA";
    const INDICATOR_NONSTOP_DIRECT_CONNECTION = "NDC";

    /**
     * @var string
     */
    public $location;

    /**
     * @var string
     */
    public $time;

    /**
     * self::INDICATOR_*
     *
     * @var string|int
     */
    public $indicatorList;

    /**
     * Connection constructor.
     *
     * @param string $location
     * @param string|int|null $indicator self::INDICATOR_*
     */
    public function __construct($location, $indicator = null)
    {
        $this->location = $location;
        $this->indicatorList = $indicator;
    }
}
