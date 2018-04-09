<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * UmRequest
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class UmRequest
{
    /**
     * @var int
     */
    public $umAge;

    /**
     * UmRequest constructor.
     *
     * @param int $umAge
     */
    public function __construct($umAge)
    {
        $this->umAge = $umAge;
    }
}
