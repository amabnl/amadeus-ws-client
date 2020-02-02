<?php

namespace Amadeus\Client\RequestCreator\Converter\Profile;

use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\RequestOptions\ProfileCreateProfileOptions;
use Amadeus\Client\Struct;

class CreateProfileConv extends BaseConverter
{
    /**
     * @param ProfileCreateProfileOptions $requestOptions
     * @param int|string $version
     * @return Struct\Profile\ProfileCreateProfile
     */
    public function convert($requestOptions, $version)
    {
        return new Struct\Profile\ProfileCreateProfile($requestOptions);
    }
}
