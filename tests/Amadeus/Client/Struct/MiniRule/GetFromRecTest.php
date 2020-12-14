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

use Amadeus\Client\RequestOptions\MiniRule\Pricing\FilteringOption;
use Amadeus\Client\RequestOptions\MiniRule\Language;
use Amadeus\Client\RequestOptions\MiniRule\Pricing;
use Amadeus\Client\RequestOptions\MiniRuleGetFromRecOptions;
use Amadeus\Client\Struct\MiniRule\GetFromRec;
use Amadeus\Client\Struct\MiniRule\RecordId;
use Amadeus\Client\Struct\MiniRule\ReferenceDetails;
use Test\Amadeus\BaseTestCase;

/**
 * GetFromRecTest
 *
 * @package Test\Amadeus\Client\Struct\MiniRule
 * @author Aleksandr Kalugin <xkalugin@gmail.com>
 */
class GetFromRecTest extends BaseTestCase
{
    public function testCanMakeMiniRulesRequestForAllOffers()
    {
        $opt = new MiniRuleGetFromRecOptions([
            'pricings' => [
                new Pricing([
                    'id' => Pricing::ALL_PRICINGS,
                    'type' => Pricing::TYPE_OFFER
                ])
            ]
        ]);

        $message = new GetFromRec($opt);

        $this->assertCount(1, $message->groupRecords);
        $this->assertEquals(RecordId::REFERENCE_TYPE_OFFER, $message->groupRecords[0]->recordID->referenceType);
        $this->assertEquals(RecordId::PRICING_ID_ALL, $message->groupRecords[0]->recordID->uniqueReference);
    }

    public function testCanMakeMiniRulesRequestForSpecificOffer()
    {
        $opt = new MiniRuleGetFromRecOptions([
            'pricings' => [
                new Pricing([
                    'id' => 2,
                    'type' => Pricing::TYPE_OFFER
                ])
            ]
        ]);

        $message = new GetFromRec($opt);

        $this->assertCount(1, $message->groupRecords);
        $this->assertEquals(RecordId::REFERENCE_TYPE_OFFER, $message->groupRecords[0]->recordID->referenceType);
        $this->assertEquals(2, $message->groupRecords[0]->recordID->uniqueReference);
    }

    public function testCanMakeMiniRulesRequestForAllPqrs()
    {
        $opt = new MiniRuleGetFromRecOptions([
            'pricings' => [
                new Pricing([
                    'id' => Pricing::ALL_PRICINGS,
                    'type' => Pricing::TYPE_PROD_QUOTATION
                ])
            ]
        ]);

        $message = new GetFromRec($opt);

        $this->assertCount(1, $message->groupRecords);
        $this->assertEquals(RecordId::REFERENCE_TYPE_PROD_QUOTATION, $message->groupRecords[0]->recordID->referenceType);
        $this->assertEquals(RecordId::PRICING_ID_ALL, $message->groupRecords[0]->recordID->uniqueReference);
    }

    public function testCanMakeMiniRulesRequestForSpecificTst()
    {
        $opt = new MiniRuleGetFromRecOptions([
            'pricings' => [
                new Pricing([
                    'id' => 1,
                    'type' => Pricing::TYPE_TST
                ])
            ]
        ]);

        $message = new GetFromRec($opt);

        $this->assertCount(1, $message->groupRecords);
        $this->assertEquals(RecordId::REFERENCE_TYPE_TST, $message->groupRecords[0]->recordID->referenceType);
        $this->assertEquals(1, $message->groupRecords[0]->recordID->uniqueReference);
    }

    public function testCanMakeMiniRulesRequestForAllFRN()
    {
        $opt = new MiniRuleGetFromRecOptions([
            'pricings' => [
                new Pricing([
                    'id' => Pricing::ALL_PRICINGS,
                    'type' => Pricing::TYPE_FARE_RECOMMENDATION_NUMBER
                ])
            ]
        ]);

        $message = new GetFromRec($opt);

        $this->assertCount(1, $message->groupRecords);
        $this->assertEquals(RecordId::REFERENCE_TYPE_FARE_RECOMMENDATION_NUMBER, $message->groupRecords[0]->recordID->referenceType);
        $this->assertEquals(RecordId::PRICING_ID_ALL, $message->groupRecords[0]->recordID->uniqueReference);
    }

    public function testCanMakeMiniRulesRequestForAllFUN()
    {
        $opt = new MiniRuleGetFromRecOptions([
            'pricings' => [
                new Pricing([
                    'id' => Pricing::ALL_PRICINGS,
                    'type' => Pricing::TYPE_FARE_UPSELL_RECOMMENDATION_NUMBER
                ])
            ]
        ]);

        $message = new GetFromRec($opt);

        $this->assertCount(1, $message->groupRecords);
        $this->assertEquals(RecordId::REFERENCE_TYPE_FARE_UPSELL_RECOMMENDATION_NUMBER, $message->groupRecords[0]->recordID->referenceType);
        $this->assertEquals(RecordId::PRICING_ID_ALL, $message->groupRecords[0]->recordID->uniqueReference);
    }

    public function testCanMakeMiniRulesRequestForPNR()
    {
        $opt = new MiniRuleGetFromRecOptions([
            'pricings' => [
                new Pricing([
                    'id' => "RECLOCNUM123",
                    'type' => Pricing::TYPE_RECORD_LOCATOR
                ])
            ]
        ]);

        $message = new GetFromRec($opt);

        $this->assertCount(1, $message->groupRecords);
        $this->assertEquals(RecordId::REFERENCE_TYPE_RECORD_LOCATOR, $message->groupRecords[0]->recordID->referenceType);
        $this->assertEquals("RECLOCNUM123", $message->groupRecords[0]->recordID->uniqueReference);
    }

    public function testCanMakeMiniRulesRequestForPNRWithSpecificPassenger()
    {
        $opt = new MiniRuleGetFromRecOptions([
            'pricings' => [
                new Pricing([
                    'id' => "RECLOCNUM123",
                    'type' => Pricing::TYPE_RECORD_LOCATOR,
                    'filteringOptions' => [
                        new FilteringOption([
                            'type' => FilteringOption::TYPE_PAX,
                            'value' => 1
                        ])
                    ]
                ])
            ]
        ]);

        $message = new GetFromRec($opt);

        $this->assertCount(1, $message->groupRecords);
        $this->assertEquals(RecordId::REFERENCE_TYPE_RECORD_LOCATOR, $message->groupRecords[0]->recordID->referenceType);
        $this->assertEquals("RECLOCNUM123", $message->groupRecords[0]->recordID->uniqueReference);
        $this->assertCount(1, $message->groupRecords[0]->filteringSelection->referenceDetails);
        $this->assertEquals(ReferenceDetails::REFERENCE_TYPE_PAX, $message->groupRecords[0]->filteringSelection->referenceDetails[0]->type);
        $this->assertEquals(1, $message->groupRecords[0]->filteringSelection->referenceDetails[0]->value);
    }

    public function testCanMakeMiniRulesRequestForPNRWithSpecificSegment()
    {
        $opt = new MiniRuleGetFromRecOptions([
            'pricings' => [
                new Pricing([
                    'id' => "RECLOCNUM123",
                    'type' => Pricing::TYPE_RECORD_LOCATOR,
                    'filteringOptions' => [
                        new FilteringOption([
                            'type' => FilteringOption::TYPE_SEGMENT,
                            'value' => 2
                        ])
                    ]
                ])
            ]
        ]);

        $message = new GetFromRec($opt);

        $this->assertCount(1, $message->groupRecords);
        $this->assertEquals(RecordId::REFERENCE_TYPE_RECORD_LOCATOR, $message->groupRecords[0]->recordID->referenceType);
        $this->assertEquals("RECLOCNUM123", $message->groupRecords[0]->recordID->uniqueReference);
        $this->assertCount(1, $message->groupRecords[0]->filteringSelection->referenceDetails);
        $this->assertEquals(ReferenceDetails::REFERENCE_TYPE_SEGMENT, $message->groupRecords[0]->filteringSelection->referenceDetails[0]->type);
        $this->assertEquals(2, $message->groupRecords[0]->filteringSelection->referenceDetails[0]->value);
    }


    public function testCanMakeMiniRulesRequestForPNRWithSpecificLanguage()
    {
        $opt = new MiniRuleGetFromRecOptions([
            'language' => new Language([
                'qualifier' => Language::LQ_LANGUAGE_NORMALLY_USED,
                'code' => "UA"
            ]),
            'pricings' => [
                new Pricing([
                    'id' => "RECLOCNUM123",
                    'type' => Pricing::TYPE_RECORD_LOCATOR
                ])
            ]
        ]);

        $message = new GetFromRec($opt);

        $this->assertEquals(\Amadeus\Client\Struct\MiniRule\Language::LQ_LANGUAGE_NORMALLY_USED, $message->language->languageQualifier);
        $this->assertEquals("UA", $message->language->languageDetails->languageCode);
        $this->assertCount(1, $message->groupRecords);
        $this->assertEquals(RecordId::REFERENCE_TYPE_RECORD_LOCATOR, $message->groupRecords[0]->recordID->referenceType);
        $this->assertEquals("RECLOCNUM123", $message->groupRecords[0]->recordID->uniqueReference);
    }

}
