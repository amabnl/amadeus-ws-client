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
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CustomerReferences
{
    const QUAL_701 = 701;

    /**
     * self::QUAL_*
     *
     * @var int|string
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
