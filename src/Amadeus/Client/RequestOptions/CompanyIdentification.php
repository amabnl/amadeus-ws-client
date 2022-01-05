<?php

namespace Amadeus\Client\RequestOptions;

class CompanyIdentification
{
    /**
     * @var string
     */
    public $marketingAirlineCode;

    /**
     * @param string $marketingAirlineCode
     */
    public function __construct(string $marketingAirlineCode)
    {
        $this->marketingAirlineCode = $marketingAirlineCode;
    }
}
