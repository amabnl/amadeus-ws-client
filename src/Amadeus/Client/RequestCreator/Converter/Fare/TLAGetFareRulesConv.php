<?php

namespace Amadeus\Client\RequestCreator\Converter\Fare;

use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\RequestOptions\FareTLAGetFareRulesOptions;
use Amadeus\Client\Struct;

class TLAGetFareRulesConv extends BaseConverter
{
    /**
     * @param FareTLAGetFareRulesOptions $requestOptions
     * @param int|string $version
     * @return Struct\Fare\TLAGetFareRules
     */

    /**
     * @param FareTLAGetFareRulesOptions $requestOptions
     * @param string|int $version
     * @return Struct\Fare\TLAGetFareRules
     */
    public function convert($requestOptions, $version)
    {
        return new Struct\Fare\TLAGetFareRules($requestOptions);
    }
}
