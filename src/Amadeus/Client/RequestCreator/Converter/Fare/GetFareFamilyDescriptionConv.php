<?php

namespace Amadeus\Client\RequestCreator\Converter\Fare;

use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\RequestOptions\FareGetFareFamilyDescriptionOptions;
use Amadeus\Client\Struct\Fare\GetFareFamilyDescription;

/**
 * Fare_GetFareFamilyDescription Request converter
 *
 * Class GetFareFamilyDescriptionConv
 * @package Amadeus\Client\RequestCreator\Converter\Fare
 */
class GetFareFamilyDescriptionConv extends BaseConverter
{
    /**
     * @param FareGetFareFamilyDescriptionOptions $requestOptions
     * @param int|string $version
     * @return GetFareFamilyDescription
     */
    public function convert($requestOptions, $version)
    {
        return new GetFareFamilyDescription($requestOptions);
    }
}
