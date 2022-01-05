<?php
/**
 * @copyright C UAB WAAVO 2022
 *
 * This Software is the property of UAB WAAVO
 * and is protected by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact UAB WAAVO:
 * E-mail: info@waavo.com
 * https://promo.waavo.com
 */

namespace Amadeus\Client\RequestOptions;

class BoardingpointDetail
{
    /**
     * @var string
     */
    public $departureCityCode;

    /**
     * BoardingpointDetail constructor.
     *
     * @param string $departureCityCode
     */
    public function __construct(string $departureCityCode)
    {
        $this->departureCityCode = $departureCityCode;
    }
}
