<?php

namespace Amadeus\Client\RequestCreator\Converter\Profile;
use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\RequestOptions\ProfileUpdateProfileOptions;
use Amadeus\Client\Struct;

class UpdateProfileConv extends BaseConverter
{
    /**
     * @param ProfileUpdateProfileOptions $requestOptions
     * @param int|string $version
     * @return Struct\Profile\ProfileUpdateProfile
     */
    public function convert($requestOptions, $version)
    {
        return new Struct\Profile\ProfileUpdateProfile($requestOptions);
    }
}