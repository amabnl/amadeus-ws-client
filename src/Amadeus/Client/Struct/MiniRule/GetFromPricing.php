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

namespace Amadeus\Client\Struct\MiniRule;

use Amadeus\Client\RequestOptions\MiniRuleGetFromPricingOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * GetFromPricing
 *
 * @package Amadeus\Client\Struct\MiniRule
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class GetFromPricing extends BaseWsMessage
{
    /**
     * Fare Recommendation ID
     *
     * @var FareRecommendationId[]
     */
    public $fareRecommendationId = [];

    /**
     * GetFromPricing constructor.
     *
     * @param MiniRuleGetFromPricingOptions $options
     */
    public function __construct(MiniRuleGetFromPricingOptions $options)
    {
        if (empty($options->pricings)) {
            $this->fareRecommendationId[] = new FareRecommendationId(
                FareRecommendationId::PRICING_ID_ALL
            );
        } else {
            foreach ($options->pricings as $pricing) {
                $this->fareRecommendationId[] = new FareRecommendationId($pricing);
            }
        }
    }
}
