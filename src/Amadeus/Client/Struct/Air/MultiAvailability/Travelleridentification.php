<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * Travelleridentification
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Travelleridentification
{
    /**
     * @var FrequentTravellerDetails
     */
    public $frequentTravellerDetails;

    /**
     * @var FrequentTravellerDetails[]
     */
    public $otherFrequentTravellerDetails = [];

    /**
     * Travelleridentification constructor.
     *
     * @param string $carrier
     * @param string $number
     * @param string|null $refType
     */
    public function __construct($carrier, $number, $refType = null)
    {
        $this->frequentTravellerDetails = new FrequentTravellerDetails(
            $carrier,
            $number,
            $refType
        );
    }
}
