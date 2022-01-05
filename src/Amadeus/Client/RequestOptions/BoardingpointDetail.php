<?php

namespace Amadeus\Client\RequestOptions;

class BoardingpointDetail
{
    /**
     * @var string
     */
    public $departureCityCode;

    /**
     * BoardingpointDetail constructor.
     *
     * @param string $departureCityCode
     */
    public function __construct(string $departureCityCode)
    {
        $this->departureCityCode = $departureCityCode;
    }
}
