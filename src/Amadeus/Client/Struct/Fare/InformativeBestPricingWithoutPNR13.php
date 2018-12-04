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

namespace Amadeus\Client\Struct\Fare;

use Amadeus\Client\RequestOptions\Fare\InformativeBestPricingWithoutPnr\CabinOptions;
use Amadeus\Client\RequestOptions\FareInformativeBestPricingWithoutPnrOptions;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionGroup;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionKey;

/**
 * InformativeBestPricingWithoutPNR13
 *
 * @package Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class InformativeBestPricingWithoutPNR13 extends InformativePricingWithoutPNR13
{
    /**
     * InformativeBestPricingWithoutPNR13 constructor.
     *
     * @param FareInformativeBestPricingWithoutPnrOptions|null $options
     */
    public function __construct(FareInformativeBestPricingWithoutPnrOptions $options = null)
    {
        parent::__construct($options);

        if (!empty($options->cabin)) {
            $this->loadCabin($options->cabin);
        }
    }

    /**
     * @param CabinOptions $cabinOptions
     *
     * @return InformativeBestPricingWithoutPNR13
     */
    protected function loadCabin($cabinOptions)
    {
        if ($cabinOptions instanceof CabinOptions) {
            $optionDetails = [];
            foreach ($cabinOptions->getOptions() as $cabinOption) {
                $optionDetails[] = [$cabinOption->getType() =>$cabinOption->getClass()];
            }

            $this->pricingOptionGroup = array_merge(
                $this->pricingOptionGroup,
                [
                    new PricingOptionGroup(
                        PricingOptionKey::OPTION_CABIN,
                        $optionDetails
                    )
                ]
            );
        }

        return $this;
    }
}
