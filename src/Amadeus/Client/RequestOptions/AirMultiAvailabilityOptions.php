<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\RequestOptions;

/**
 * Air_MultiAvailability Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AirMultiAvailabilityOptions extends Base
{
    const ACTION_AVAILABILITY = 44;
    const ACTION_SCHEDULE = 48;
    const ACTION_TIMETABLE = 51;
    const ACTION_SUBSEQUENT_BACKWARD = 54;
    const ACTION_SUBSEQUENT_FORWARD = 55;
    const ACTION_SUBSEQUENT_ORIGINAL = 61;

    const BUSINESS_AIR_PROVIDER = 1;

    /**
     * self::ACTION_*
     *
     * @var int
     */
    public $actionCode = self::ACTION_AVAILABILITY;

    /**
     * self::BUSINESS_*
     *
     * @var int
     */
    public $businessFunction;

    /**
     * @var Air\MultiAvailability\RequestOptions[]
     */
    public $requestOptions = [];

    /**
     * @var Air\MultiAvailability\FrequentTraveller
     */
    public $frequentTraveller;

    /**
     * Corporation number - to target the proper Amadeus Air Preference and Inventory Mask rule
     *
     * @var string
     */
    public $corporationNumber;

    /**
     * IATA code for the point of commencement
     *
     * @var string
     */
    public $commencementPoint;

    /**
     * @var \DateTime
     */
    public $commencementDate;
}
