<?php

namespace Amadeus\Client\Struct\Ticket\CreateTASF;

class ReasonForIssuance
{
    /**
     * @var CriteriaDetails
     */
    public $criteriaDetails;

    /**
     * ReasonForIssuance constructor.
     *
     * @param string $attributeType
     */
    public function __construct($attributeType)
    {
        $this->criteriaDetails = new CriteriaDetails($attributeType);
    }
}
