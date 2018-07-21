<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * OptionClass
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class OptionClass
{
    /**
     * @var ProductClassDetails[]
     */
    public $productClassDetails = [];

    /**
     * 703 Request for class is expanded to include non matching classes in the connections.
     * 704 All classes are mandatory to select the flight.
     *
     * @var string
     */
    public $expandClassRequest;

    /**
     * OptionClass constructor.
     *
     * @param string[] $bookingClasses
     */
    public function __construct($bookingClasses)
    {
        foreach ($bookingClasses as $bookingClass) {
            $this->productClassDetails[] = new ProductClassDetails($bookingClass);
        }
    }
}
