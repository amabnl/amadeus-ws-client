<?php

namespace Amadeus\Client\RequestCreator\Converter\Fare;

use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\Struct;
use Amadeus\Client\Struct\Fare\InstantTravelBoardSearch;

class InstantTravelBoardSearchConv extends BaseConverter
{
    /**
     * @param $requestOptions
     * @param $version
     * @return InstantTravelBoardSearch
     */
    public function convert($requestOptions, $version)
    {
        return new Struct\Fare\InstantTravelBoardSearch($requestOptions);
    }
}
