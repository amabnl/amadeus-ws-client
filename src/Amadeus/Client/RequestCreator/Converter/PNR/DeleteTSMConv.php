<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 14/11/2018
 * Time: 10:55
 */

namespace Amadeus\Client\RequestCreator\Converter\PNR;

use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\RequestOptions\PnrCancelOptions;
use Amadeus\Client\Struct;

class DeleteTSMConv extends BaseConverter
{
    public function convert($requestOptions, $version)
    {
        return new Struct\Pnr\DeleteTsm($requestOptions);
    }
}