<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 13/09/2018
 * Time: 10:11
 */

namespace Amadeus\Client\RequestCreator\Converter\Profile;
use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\RequestOptions\Profile;
use Amadeus\Client\Struct;

class UpdateProfileConv extends BaseConverter
{
    /**
     * @param Profile_UpdateProfile $requestOptions
     * @param int|string $version
     * @return Struct\Profile\ProfileUpdateProfile
     */
    public function convert($requestOptions, $version)
    {
        return new Struct\Profile\ProfileUpdateProfile($requestOptions);
    }
}