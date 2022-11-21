<?php

namespace Amadeus\Client\Struct\Fare\TLAGetFareRules;

class FareBasisDetails
{
    /**
     * @var string
     */
    public $tariffClassId;

    /**
     * @var CompanyDetails
     */
    public $companyDetails;

    /**
     * @param string $tariffClassId
     * @param string $marketingCompany
     */
    public function __construct($tariffClassId, $marketingCompany)
    {
        $this->tariffClassId = $tariffClassId;
        $this->companyDetails = new CompanyDetails($marketingCompany);
    }
}
