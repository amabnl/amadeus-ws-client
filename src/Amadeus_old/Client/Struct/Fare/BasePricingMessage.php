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

use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionGroup;
use Amadeus\Client\Struct\Service\IntegratedPricing\PricingOption as ServicePriceOpt;

/**
 * BasePricingMessage
 *
 * Provides some basic functionality for all pricing messages which work with 'pricingOptionGroup's
 *
 * @package Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class BasePricingMessage extends BaseWsMessage
{
    /**
     * Avoid double pricing groups when combining an explicitly provided override option with a specific parameter
     * that uses the same override option.
     *
     * Backwards compatibility with PricePnrWithBookingClass12
     *
     * @param string $optionKey
     * @param PricingOptionGroup[]|ServicePriceOpt[] $priceOptions
     * @return bool
     */
    protected static function hasPricingGroup($optionKey, $priceOptions)
    {
        $found = false;

        foreach ($priceOptions as $pog) {
            if ($pog->pricingOptionKey->pricingOptionKey === $optionKey) {
                $found = true;
            }
        }

        return $found;
    }

    /**
     * Merges Pricing options
     *
     * @param PricingOptionGroup[] $existingOptions
     * @param PricingOptionGroup[] $newOptions
     * @return PricingOptionGroup[] merged array
     */
    protected static function mergeOptions($existingOptions, $newOptions)
    {
        if (!empty($newOptions)) {
            $existingOptions = array_merge(
                $existingOptions,
                $newOptions
            );
        }

        return $existingOptions;
    }
}
