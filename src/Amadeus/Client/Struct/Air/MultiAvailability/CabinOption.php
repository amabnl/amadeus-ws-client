<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * CabinOption
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CabinOption
{
    /**
     * @var CabinDesignation
     */
    public $cabinDesignation;

    /**
     * @var string
     */
    public $orderClassesByCabin;

    /**
     * CabinOption constructor.
     *
     * @param int $cabinCode CabinDesignation::CABIN_*
     */
    public function __construct($cabinCode)
    {
        $this->cabinDesignation = new CabinDesignation($cabinCode);
    }
}
