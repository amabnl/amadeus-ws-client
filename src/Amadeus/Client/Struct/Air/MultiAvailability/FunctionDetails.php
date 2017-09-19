<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * FunctionDetails
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FunctionDetails
{
    const ACTION_AVAILABILITY = 44;

    const ACTION_SCHEDULE = 48;

    const ACTION_TIMETABLE = 51;

    const ACTION_SUBSEQUENT_BACKWARD = 54;

    const ACTION_SUBSEQUENT_FORWARD = 55;

    const ACTION_SUBSEQUENT_ORIGINAL = 61;

    const BUSINESS_AIR_PROVIDER = 1;


    /**
     * @var int|string
     */
    public $businessFunction;

    /**
     * @var int|string
     */
    public $actionCode;

    /**
     * @var string
     */
    public $subActionCode;

    /**
     * FunctionDetails constructor.
     *
     * @param int|string|null $actionCode
     * @param int|string|null $businessFunction
     */
    public function __construct($actionCode, $businessFunction = null)
    {
        $this->actionCode = $actionCode;
        $this->businessFunction = $businessFunction;
    }
}
