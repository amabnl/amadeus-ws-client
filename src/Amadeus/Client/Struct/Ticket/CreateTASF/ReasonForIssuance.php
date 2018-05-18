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
     * @param CriteriaDetails $criteriaDetails
     */
    public function __construct(CriteriaDetails $criteriaDetails)
    {
        $this->criteriaDetails = $criteriaDetails;
    }
}
