<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * CabinDesignation
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CabinDesignation
{
    const CABIN_FIRST = 1;
    const CABIN_BUSINESS = 2;
    const CABIN_ECONOMY_PREMIUM_MAIN = 3;
    const CABIN_ECONOMY_MAIN = 4;
    const CABIN_ECONOMY_PREMIUM = 5;

    /**
     * 700 Request for cabin is expanded to include non matching cabins in the connections
     *
     * @var string
     */
    public $expandCabinIndicator;

    /**
     * self::CABIN_*
     *
     * @var int
     */
    public $cabinClassOfServiceList;

    /**
     * CabinDesignation constructor.
     *
     * @param int $cabinCode self::CABIN_*
     */
    public function __construct($cabinCode)
    {
        $this->cabinClassOfServiceList = $cabinCode;
    }
}
