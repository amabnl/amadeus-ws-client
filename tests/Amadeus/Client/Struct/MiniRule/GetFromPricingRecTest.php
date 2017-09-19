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

use Amadeus\Client\RequestOptions\MiniRule\Pricing;
use Amadeus\Client\RequestOptions\MiniRuleGetFromPricingRecOptions;
use Amadeus\Client\Struct\MiniRule\GetFromPricingRec;
use Amadeus\Client\Struct\MiniRule\RecordId;
use Test\Amadeus\BaseTestCase;

/**
 * GetFromPricingRecTest
 *
 * @package Test\Amadeus\Client\Struct\MiniRule
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class GetFromPricingRecTest extends BaseTestCase
{
    public function testCanMakeMiniRulesRequestForAllOffers()
    {
        $opt = new MiniRuleGetFromPricingRecOptions([
            'pricings' => [
                new Pricing([
                    'id' => Pricing::ALL_PRICINGS,
                    'type' => Pricing::TYPE_OFFER
                ])
            ]
        ]);

        $message = new GetFromPricingRec($opt);

        $this->assertCount(1, $message->recordId);
        $this->assertEquals(RecordId::REFERENCE_TYPE_OFFER, $message->recordId[0]->referenceType);
        $this->assertEquals(RecordId::PRICING_ID_ALL, $message->recordId[0]->uniqueReference);
    }

    public function testCanMakeMiniRulesRequestForSpecificOffer()
    {
        $opt = new MiniRuleGetFromPricingRecOptions([
            'pricings' => [
                new Pricing([
                    'id' => 2,
                    'type' => Pricing::TYPE_OFFER
                ])
            ]
        ]);

        $message = new GetFromPricingRec($opt);

        $this->assertCount(1, $message->recordId);
        $this->assertEquals(RecordId::REFERENCE_TYPE_OFFER, $message->recordId[0]->referenceType);
        $this->assertEquals(2, $message->recordId[0]->uniqueReference);
    }

    public function testCanMakeMiniRulesRequestForAllPqrs()
    {
        $opt = new MiniRuleGetFromPricingRecOptions([
            'pricings' => [
                new Pricing([
                    'id' => Pricing::ALL_PRICINGS,
                    'type' => Pricing::TYPE_PROD_QUOTATION
                ])
            ]
        ]);

        $message = new GetFromPricingRec($opt);

        $this->assertCount(1, $message->recordId);
        $this->assertEquals(RecordId::REFERENCE_TYPE_PROD_QUOTATION, $message->recordId[0]->referenceType);
        $this->assertEquals(RecordId::PRICING_ID_ALL, $message->recordId[0]->uniqueReference);
    }

    public function testCanMakeMiniRulesRequestForSpecificTst()
    {
        $opt = new MiniRuleGetFromPricingRecOptions([
            'pricings' => [
                new Pricing([
                    'id' => 1,
                    'type' => Pricing::TYPE_TST
                ])
            ]
        ]);

        $message = new GetFromPricingRec($opt);

        $this->assertCount(1, $message->recordId);
        $this->assertEquals(RecordId::REFERENCE_TYPE_TST, $message->recordId[0]->referenceType);
        $this->assertEquals(1, $message->recordId[0]->uniqueReference);
    }
}
