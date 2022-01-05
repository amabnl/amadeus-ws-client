<?php

namespace Amadeus\Client\RequestOptions;

class OffPointDetail
{
    /**
     * @var string
     */
    public $arrivalCityCode;

    /**
     * OffPointDetail constructor.
     *
     * @param string $arrivalCityCode
     */
    public function __construct(string $arrivalCityCode)
    {
        $this->arrivalCityCode = $arrivalCityCode;
    }
}
