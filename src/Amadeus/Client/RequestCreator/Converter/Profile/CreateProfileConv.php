<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 12/09/2018
 * Time: 10:19
 */

namespace Amadeus\Client\RequestCreator\Converter\Profile;
use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\RequestOptions\Profile;
use Amadeus\Client\Struct;

class CreateProfileConv extends BaseConverter
{
    /**
     * @param Profile_DeleteProfile $requestOptions
     * @param int|string $version
     * @return Struct\Profile\ProfileCreateProfile
     */
    public function convert($requestOptions, $version)
    {
        return new Struct\Profile\ProfileCreateProfile($requestOptions);
    }
}