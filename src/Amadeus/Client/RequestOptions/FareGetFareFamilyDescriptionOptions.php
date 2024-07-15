<?php

namespace Amadeus\Client\RequestOptions;

use Amadeus\Client\RequestOptions\Fare\GetFareFamilyDescription\StandaloneDescriptionRequest;

/**
 * Class FareGetFareFamilyDescriptionOptions
 * @package Amadeus\Client\RequestOptions
 */
class FareGetFareFamilyDescriptionOptions extends Base
{
    /**
     * @var ReferenceGroup[]|array
     */
    public $referenceGroups = [];

    /** @var \DateTime|null */
    public $bookingDateInformation;

    /** @var StandaloneDescriptionRequest|null */
    public $standaloneDescriptionRequest;
}
