<?php

namespace Amadeus\Client\Struct\Fare\TLAGetFareRules;

class FareBasisInfo
{
    /**
     * @var FareBasisDetails
     */
    public $fareBasisDetails;

    /**
     * @param string $tariffClassId
     * @param string $marketingCompany
     */
    public function __construct($tariffClassId, $marketingCompany)
    {
        $this->fareBasisDetails = new FareBasisDetails($tariffClassId, $marketingCompany);
    }
}
