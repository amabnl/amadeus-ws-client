<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * TravellerDetails
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TravellerDetails
{
    /**
     * @var PaxDetails
     */
    public $paxDetails;

    /**
     * @var OtherPaxDetails[]
     */
    public $otherPaxDetails = [];

    /**
     * TravellerDetails constructor.
     *
     * @param string $lastName
     * @param string $firstName
     */
    public function __construct($lastName, $firstName)
    {
        $this->paxDetails = new PaxDetails($lastName);
        $this->otherPaxDetails[] = new OtherPaxDetails($firstName);
    }
}
