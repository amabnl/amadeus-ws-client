<?php

namespace Amadeus\Client\Struct\Fare\GetFareFamilyDescription;

/**
 * Class ReferenceInformation
 * @package Amadeus\Client\Struct\Fare\GetFareFamilyDescription
 */
class ReferenceInformation
{
    /**
     * @var ReferenceDetails[]
     */
    public $referenceDetails = [];

    /**
     * ReferenceInformation constructor.
     * @param ReferenceDetails[] $referenceDetails
     */
    public function __construct(array $referenceDetails)
    {
        $this->referenceDetails = $referenceDetails;
    }
}
