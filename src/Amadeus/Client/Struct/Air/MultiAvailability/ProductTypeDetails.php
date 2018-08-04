<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * ProductTypeDetails
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ProductTypeDetails
{
    const REQ_TYPE_SPECIFIC_FLIGHT = "SF";
    const REQ_TYPE_BY_ARRIVAL_TIME = "TA";
    const REQ_TYPE_BY_DEPARTURE_TIME = "TD";
    const REQ_TYPE_BY_ELAPSED_TIME = "TE";
    const REQ_TYPE_GROUP_AVAILABILITY = "TG";
    const REQ_TYPE_NEUTRAL_ORDER = "TN";
    const REQ_TYPE_NEGOTIATED_SPACE = "TT";

    /**
     * self::REQ_TYPE_*
     *
     * @var string
     */
    public $typeOfRequest;

    /**
     * ProductTypeDetails constructor.
     *
     * @param string $type self::REQ_TYPE_*
     */
    public function __construct($type)
    {
        $this->typeOfRequest = $type;
    }
}
