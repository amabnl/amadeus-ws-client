<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 03/09/2018
 * Time: 14:13
 */
namespace Amadeus\Client\RequestCreator\Converter\Profile;
use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\RequestOptions\Profile;
use Amadeus\Client\Struct;
class ReadProfileConv extends BaseConverter
{
    /**
     * @param Profile_ReadProfile $requestOptions
     * @param int|string $version
     * @return Struct\Profile\ProfileReadProfile
     */
    public function convert($requestOptions, $version)
    {
        return new Struct\Profile\ProfileReadProfile($requestOptions);
    }
}