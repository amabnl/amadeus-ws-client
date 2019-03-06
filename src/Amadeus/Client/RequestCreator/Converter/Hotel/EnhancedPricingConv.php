<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 22/02/2019
 * Time: 10:34
 */

namespace Amadeus\Client\RequestCreator\Converter\Hotel;
use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\RequestOptions\HotelEnhancedPricingOptions;
use Amadeus\Client\Struct;

class EnhancedPricingConv extends BaseConverter
{
    public function convert($requestOptions, $version)
    {
        return new Struct\Hotel\HotelEnhancedPricing($requestOptions);
    }
}