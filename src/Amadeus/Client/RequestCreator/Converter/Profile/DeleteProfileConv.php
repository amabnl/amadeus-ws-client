<?php

namespace Amadeus\Client\RequestCreator\Converter\Profile;
use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\RequestOptions\ProfileDeleteProfileOptions;
use Amadeus\Client\Struct;

class DeleteProfileConv extends BaseConverter
{
    /**
     * @param ProfileDeleteProfileOptions $requestOptions
     * @param int|string $version
     * @return Struct\Profile\ProfileDeleteProfile
     */
    public function convert($requestOptions, $version)
    {
        return new Struct\Profile\ProfileDeleteProfile($requestOptions);
    }
}