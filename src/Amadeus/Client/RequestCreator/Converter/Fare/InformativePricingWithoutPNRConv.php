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
use Amadeus\Client\RequestOptions\FareInformativePricingWithoutPnrOptions;
use Amadeus\Client\Struct;

/**
 * Fare_InformativePricingWithoutPNR Request converter
 *
 * @package Amadeus\Client\RequestCreator\Converter\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class InformativePricingWithoutPNRConv extends BaseConverter
{
    /**
     * @param FareInformativePricingWithoutPnrOptions $requestOptions
     * @param int|string $version
     * @return Struct\Fare\InformativePricingWithoutPNR12|Struct\Fare\InformativePricingWithoutPNR13
     */
    public function convert($requestOptions, $version)
    {
        if (floatval($version) < floatval(13)) {
            return new Struct\Fare\InformativePricingWithoutPNR12($requestOptions);
        } else {
            return new Struct\Fare\InformativePricingWithoutPNR13($requestOptions);
        }
    }
}
