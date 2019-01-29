<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 15/10/2018
 * Time: 09:53
 */

namespace Amadeus\Client\RequestCreator\Converter\PNR;

use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\RequestOptions\PnrCancelOptions;
use Amadeus\Client\Struct;

class CreateTSMConv extends BaseConverter
{
    public function convert($requestOptions, $version)
    {
        return new Struct\Pnr\CreateTsm($requestOptions);
    }
}