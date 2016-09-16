<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * CustomerReferences
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class CustomerReferences
{
    const QUAL_701 = 701;
    /**
     * @var int
     */
    public $referenceQualifier = self::QUAL_701;

    /**
     * @var string
     */
    public $referenceNumber;

    /**
     * CustomerReferences constructor.
     *
     * @param string $number
     */
    public function __construct($number)
    {
        $this->referenceNumber = $number;
    }
}
