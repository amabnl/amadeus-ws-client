<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * AvailabilityOptions
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AvailabilityOptions
{
    /**
     * @var ProductTypeDetails
     */
    public $productTypeDetails;

    /**
     * @var OptionInfo[]
     */
    public $optionInfo = [];

    /**
     * @var ProductAvailability[]
     */
    public $productAvailability = [];

    /**
     * @var string
     */
    public $typeOfAircraft;

    /**
     * AvailabilityOptions constructor.
     *
     * @param string $requestType ProductTypeDetails::REQ_TYPE_*
     */
    public function __construct($requestType)
    {
        $this->productTypeDetails = new ProductTypeDetails($requestType);
    }
}
