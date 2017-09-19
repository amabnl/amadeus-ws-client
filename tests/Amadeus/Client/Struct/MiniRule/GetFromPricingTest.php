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

namespace Test\Amadeus\Client\Struct\MiniRule;

use Amadeus\Client\RequestOptions\MiniRuleGetFromPricingOptions;
use Amadeus\Client\Struct\MiniRule\FareRecommendationId;
use Amadeus\Client\Struct\MiniRule\GetFromPricing;
use Test\Amadeus\BaseTestCase;

/**
 * GetFromPricing
 *
 * @package Test\Amadeus\Client\Struct\MiniRule
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class GetFromPricingTest extends BaseTestCase
{
    public function testCanMakeMessageAllPricings()
    {
        $opt = new MiniRuleGetFromPricingOptions();

        $message = new GetFromPricing($opt);

        $this->assertCount(1, $message->fareRecommendationId);
        $this->assertEquals(FareRecommendationId::REFERENCE_TYPE_FARE_RECOMMENDATION_NUMBER, $message->fareRecommendationId[0]->referenceType);
        $this->assertEquals(FareRecommendationId::PRICING_ID_ALL, $message->fareRecommendationId[0]->uniqueReference);
    }

    public function testCanMakeMessageSpecificPricings()
    {
        $opt = new MiniRuleGetFromPricingOptions([
            'pricings' => [2, 3]
        ]);

        $message = new GetFromPricing($opt);

        $this->assertCount(2, $message->fareRecommendationId);
        $this->assertEquals(FareRecommendationId::REFERENCE_TYPE_FARE_RECOMMENDATION_NUMBER, $message->fareRecommendationId[0]->referenceType);
        $this->assertEquals(2, $message->fareRecommendationId[0]->uniqueReference);
        $this->assertEquals(FareRecommendationId::REFERENCE_TYPE_FARE_RECOMMENDATION_NUMBER, $message->fareRecommendationId[1]->referenceType);
        $this->assertEquals(3, $message->fareRecommendationId[1]->uniqueReference);
    }
}
