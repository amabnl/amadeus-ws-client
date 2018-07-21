<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * MessageActionDetails
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MessageActionDetails
{
    /**
     * @var FunctionDetails
     */
    public $functionDetails;

    /**
     * MessageActionDetails constructor.
     *
     * @param int|string|null $actionCode
     * @param int|string|null $businessFunction
     */
    public function __construct($actionCode, $businessFunction = null)
    {
        $this->functionDetails = new FunctionDetails($actionCode, $businessFunction);
    }
}
