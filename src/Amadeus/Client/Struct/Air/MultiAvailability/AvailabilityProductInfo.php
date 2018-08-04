<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * AvailabilityProductInfo
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AvailabilityProductInfo
{
    /**
     * @var AvailabilityDetails[]
     */
    public $availabilityDetails = [];

    /**
     * @var LocationInfo
     */
    public $departureLocationInfo;

    /**
     * @var LocationInfo
     */
    public $arrivalLocationInfo;

    /**
     * AvailabilityProductInfo constructor.
     *
     * @param string $from
     * @param string $to
     * @param \DateTime $departureDate
     * @param \DateTime|null $arrivalDate
     */
    public function __construct($from, $to, $departureDate, $arrivalDate = null)
    {
        $this->availabilityDetails[] = new AvailabilityDetails(
            $departureDate,
            $arrivalDate
        );

        $this->departureLocationInfo = new LocationInfo($from);
        $this->arrivalLocationInfo = new LocationInfo($to);
    }
}
