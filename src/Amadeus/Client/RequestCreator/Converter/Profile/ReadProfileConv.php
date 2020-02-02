<?php

namespace Amadeus\Client\RequestCreator\Converter\Profile;
use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\RequestOptions\ProfileReadProfileOptions;
use Amadeus\Client\Struct;
class ReadProfileConv extends BaseConverter
{
    /**
     * @param ProfileReadProfileOptions $requestOptions
     * @param int|string $version
     * @return Struct\Profile\ProfileReadProfile
     */
    public function convert($requestOptions, $version)
    {
        return new Struct\Profile\ProfileReadProfile($requestOptions);
    }
}