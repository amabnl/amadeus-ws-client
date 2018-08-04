<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * ProductClassDetails
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ProductClassDetails
{
    /**
     * @var string
     */
    public $serviceClass;

    /**
     * N night class
     *
     * @var string
     */
    public $nightModifierOption;

    /**
     * ProductClassDetails constructor.
     *
     * @param string $bookingClass
     */
    public function __construct($bookingClass)
    {
        $this->serviceClass = $bookingClass;
    }
}
