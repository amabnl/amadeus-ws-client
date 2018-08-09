<?php
/**
 * amadeus-ws-client
 *
 * Copyright 2015 Amadeus Benelux NV
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package Amadeus
 * @license https://opensource.org/licenses/Apache-2.0 Apache 2.0
 */

namespace Amadeus\Client\RequestCreator\Converter\Fare;

use Amadeus\Client\RequestCreator\Converter\BaseConverter;
use Amadeus\Client\RequestOptions\FareInformativeBestPricingWithoutPnrOptions;
use Amadeus\Client\Struct;

/**
 * Fare_InformativeBestPricingWithoutPNR Request converter
 *
 * @package Amadeus\Client\RequestCreator\Converter\Fare
 * @author Leonardo Travel <dermikagh@gmail.com>
 */
class PriceUpsellWithoutPNRConv extends BaseConverter
{
    /**
     * @param FareInformativeBestPricingWithoutPnrOptions $requestOptions
     * @param int|string $version
     * @return Struct\Fare\InformativePriceUpsellWithoutPNR15|Struct\Fare\InformativePriceUpsellWithoutPNR16
     */
    public function convert($requestOptions, $version)
    {
        if (floatval($version) < floatval(16)) {
            return new Struct\Fare\InformativePriceUpsellWithoutPNR15($requestOptions);
        } else {
            return new Struct\Fare\InformativePriceUpsellWithoutPNR16($requestOptions);
        }
    }
}
