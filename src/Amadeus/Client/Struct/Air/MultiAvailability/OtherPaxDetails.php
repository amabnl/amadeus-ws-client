<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * OtherPaxDetails
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class OtherPaxDetails
{
    const INFANT_INDICATOR = 1;
    /**
     * @var string
     */
    public $givenName;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string|int
     */
    public $uniqueCustomerIdentifier;

    /**
     * @var string|int
     */
    public $infantIndicator;

    /**
     * @var string
     */
    public $title;

    /**
     * OtherPaxDetails constructor.
     *
     * @param string $givenName
     */
    public function __construct($givenName)
    {
        $this->givenName = $givenName;
    }
}
