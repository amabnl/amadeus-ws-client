<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 29/10/2018
 * Time: 15:36
 */

namespace Amadeus\Client\RequestCreator\Converter\Service;
use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\Struct;

class BookPriceProductConv extends BaseConverter
{
    public function convert($requestOptions, $version)
    {
        return new Struct\Service\BookPriceProduct($requestOptions);
    }
}