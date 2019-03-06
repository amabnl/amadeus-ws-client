<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 20/02/2019
 * Time: 13:21
 */

namespace Amadeus\Client\RequestCreator\Converter\Hotel;
use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\RequestOptions\HotelDescriptiveInfoOptions;
use Amadeus\Client\Struct;

class DescriptiveInfoConv extends BaseConverter
{
    public function convert($requestOptions, $version)
    {
        return new Struct\Hotel\HotelDescriptiveInfo($requestOptions);
    }
}