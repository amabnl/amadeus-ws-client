<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * PaxDetails
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PaxDetails
{
    /**
     * @var string
     */
    public $surname;

    /**
     * @var string
     */
    public $type;

    /**
     * @var int
     */
    public $quantity;

    /**
     * @var string
     */
    public $status;

    /**
     * PaxDetails constructor.
     *
     * @param string $surname
     */
    public function __construct($surname)
    {
        $this->surname = $surname;
    }
}
