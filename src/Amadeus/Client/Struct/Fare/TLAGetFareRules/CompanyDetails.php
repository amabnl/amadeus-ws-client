<?php

namespace Amadeus\Client\Struct\Fare\TLAGetFareRules;

class CompanyDetails
{
    const COMPANY_INDUSTRY_CAR_RENTAL = '7CC';
    const COMPANY_INDUSTRY_HOTEL_CHAINS = '7HH';

    /**
     * @var string
     */
    public $marketingCompany;

    /**
     * CompanyDetails constructor.
     *
     * @param string $marketingCompany
     */
    public function __construct($marketingCompany)
    {
        $this->marketingCompany = $marketingCompany;
    }
}
