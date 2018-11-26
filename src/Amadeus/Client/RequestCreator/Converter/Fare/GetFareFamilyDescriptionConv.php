<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 26/11/2018
 * Time: 09:22
 */

namespace Amadeus\Client\RequestCreator\Converter\Fare;

use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\RequestOptions\GetFareFamilyDescriptionOptions;
use Amadeus\Client\Struct;


class GetFareFamilyDescriptionConv extends BaseConverter
{
    public function convert($requestOptions, $version)
    {
        return new Struct\Fare\GetFareFamilyDescriptionOptions($requestOptions);
    }
}