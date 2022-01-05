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

class OffPointDetail
{
    /**
     * @var string
     */
    public $arrivalCityCode;

    /**
     * OffPointDetail constructor.
     *
     * @param string $arrivalCityCode
     */
    public function __construct(string $arrivalCityCode)
    {
        $this->arrivalCityCode = $arrivalCityCode;
    }
}
