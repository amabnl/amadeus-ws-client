<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\RequestOptions\Air\MultiAvailability;

use Amadeus\Client\LoadParamsFromArray;

/**
 * FrequentTraveller
 *
 * @package Amadeus\Client\RequestOptions\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FrequentTraveller extends LoadParamsFromArray
{
    const REF_FREQUENT_FLYER = "FF";

    const REF_CORPORATE_FREQUENT_FLYER = "CFF";

    /**
     * First Name
     *
     * @var string
     */
    public $firstName;

    /**
     * Last Name
     *
     * @var string
     */
    public $lastName;

    /**
     * Airline
     *
     * @var string
     */
    public $carrier;

    /**
     * Frequent flyer number
     *
     * @var string
     */
    public $number;

    /**
     * What type of frequent flyer?
     *
     * self::REF_*
     *
     * @var string
     */
    public $referenceType;
}
