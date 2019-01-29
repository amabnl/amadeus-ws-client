<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 05/12/2018
 * Time: 08:37
 */
namespace Amadeus\Client\RequestCreator\Converter\PAY;
use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\RequestOptions\PAYGetVirtualCreditCardOptions;
use Amadeus\Client\Struct;

class GetVirtualCreditCardConv extends BaseConverter
{
    public function convert($requestOptions, $version)
    {
        return new Struct\Pay\PAYGetVirtualCreditCard($requestOptions);
    }
}