<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * FrequentTravellerDetails
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class FrequentTravellerDetails
{
    /**
     * @var string
     */
    public $carrier;
    /**
     * @var string
     */
    public $number;
    /**
     * @var string
     */
    public $customerReference;
    /**
     * @var string
     */
    public $status;
    /**
     * @var string
     */
    public $tierLevel;
    /**
     * @var string
     */
    public $priorityCode;
    /**
     * @var string
     */
    public $tierDescription;
    /**
     * @var string
     */
    public $companyCode;
    /**
     * @var string
     */
    public $customerValue;
    /**
     * @var string
     */
    public $referenceType;

    /**
     * FrequentTravellerDetails constructor.
     *
     * @param string $carrier
     * @param string $number
     * @param string|null $refType
     */
    public function __construct($carrier, $number, $refType = null)
    {
        $this->carrier = $carrier;
        $this->number = $number;
        $this->referenceType = $refType;
    }
}
