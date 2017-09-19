<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

use Amadeus\Client\RequestOptions\Air\MultiAvailability\FrequentTraveller as FrequentTravellerOptions;

/**
 * FrequentTraveller
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FrequentTraveller
{
    /**
     * @var TravellerDetails
     */
    public $travellerDetails;
    /**
     * @var Travelleridentification
     */
    public $travelleridentification;

    /**
     * FrequentTraveller constructor.
     *
     * @param FrequentTravellerOptions $frequentTraveller
     */
    public function __construct(FrequentTravellerOptions $frequentTraveller)
    {
        $this->travellerDetails = new TravellerDetails(
            $frequentTraveller->lastName,
            $frequentTraveller->firstName
        );

        $this->travelleridentification = new Travelleridentification(
            $frequentTraveller->carrier,
            $frequentTraveller->number,
            $frequentTraveller->referenceType
        );
    }
}
