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

namespace Test\Amadeus\Client\Struct\Offer;

use Amadeus\Client\RequestOptions\Offer\AirRecommendation;
use Amadeus\Client\RequestOptions\Offer\PassengerDef;
use Amadeus\Client\RequestOptions\Offer\ProductReference;
use Amadeus\Client\RequestOptions\OfferCreateOptions;
use Amadeus\Client\Struct\Offer\Create;
use Amadeus\Client\Struct\Offer\PassengerReference;
use Test\Amadeus\BaseTestCase;

/**
 * CreateTest
 *
 * @package Test\Amadeus\Client\Struct\Offer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CreateTest extends BaseTestCase
{
    public function testCanCreateAir()
    {
        $opt = new OfferCreateOptions([
            'airRecommendations' => [
                new AirRecommendation([
                    'type' => AirRecommendation::TYPE_FARE_RECOMMENDATION_NR,
                    'id' => 2,
                    'paxReferences' => [
                        new PassengerDef([
                            'passengerTattoo' => 1
                        ])
                    ]
                ])
            ]
        ]);

        $message = new Create($opt);

        $this->assertCount(1, $message->airPricingRecommendation);
        $this->assertEquals(
            Create\FareRecommendationReference::TYPE_FARE_RECOMMENDATION_NR,
            $message->airPricingRecommendation[0]->fareRecommendationReference->referenceType
        );
        $this->assertEquals(2, $message->airPricingRecommendation[0]->fareRecommendationReference->uniqueReference);

        $this->assertCount(1, $message->airPricingRecommendation[0]->paxReference->passengerReference);
        $this->assertEquals(PassengerReference::TYPE_PAXREF, $message->airPricingRecommendation[0]->paxReference->passengerReference[0]->type);
        $this->assertEquals(1, $message->airPricingRecommendation[0]->paxReference->passengerReference[0]->value);

        $this->assertNull($message->totalPrice);
        $this->assertNull($message->productReference);
    }

    public function testCanCreateHotel()
    {
        $opt = new OfferCreateOptions([
            'productReferences' => [
                new ProductReference([
                    'reference' => '000000C',
                    'referenceType' => ProductReference::PRODREF_BOOKING_CODE,
                ]),
                new ProductReference([
                    'reference' => 'RDLON308',
                    'referenceType' => ProductReference::PRODREF_HOTEL_PROPERTY_CODE,
                ]),
            ]
        ]);

        $message = new Create($opt);

        $this->assertEmpty($message->airPricingRecommendation);
        $this->assertNull($message->totalPrice);

        $this->assertCount(2, $message->productReference->referenceDetails);
        $this->assertEquals('000000C', $message->productReference->referenceDetails[0]->value);
        $this->assertEquals(Create\ReferenceDetails::PRODREF_BOOKING_CODE, $message->productReference->referenceDetails[0]->type);
        $this->assertEquals('RDLON308', $message->productReference->referenceDetails[1]->value);
        $this->assertEquals(Create\ReferenceDetails::PRODREF_HOTEL_PROPERTY_CODE, $message->productReference->referenceDetails[1]->type);
    }

    public function testCanCreateWithMarkup()
    {
        $opt = new OfferCreateOptions([
            'airRecommendations' => [
                new AirRecommendation([
                    'type' => AirRecommendation::TYPE_FARE_RECOMMENDATION_NR,
                    'id' => 2,
                    'paxReferences' => [
                        new PassengerDef([
                            'passengerTattoo' => 1,
                            'passengerType' => 'PA'
                        ])
                    ]
                ])
            ],
            'markupAmount' => 20,
            'markupCurrency' => 'EUR'
        ]);

        $message = new Create($opt);

        $this->assertCount(1, $message->airPricingRecommendation);
        $this->assertEquals(
            Create\FareRecommendationReference::TYPE_FARE_RECOMMENDATION_NR,
            $message->airPricingRecommendation[0]->fareRecommendationReference->referenceType
        );
        $this->assertEquals(2, $message->airPricingRecommendation[0]->fareRecommendationReference->uniqueReference);

        $this->assertCount(1, $message->airPricingRecommendation[0]->paxReference->passengerReference);
        $this->assertEquals(PassengerReference::TYPE_ADULT, $message->airPricingRecommendation[0]->paxReference->passengerReference[0]->type);
        $this->assertEquals(1, $message->airPricingRecommendation[0]->paxReference->passengerReference[0]->value);

        $this->assertEquals(20, $message->totalPrice->monetaryDetails->amount);
        $this->assertEquals('EUR', $message->totalPrice->monetaryDetails->currency);
        $this->assertEquals(Create\MonetaryDetails::QUAL_MARKUP_AMOUNT, $message->totalPrice->monetaryDetails->typeQualifier);
        $this->assertNull($message->totalPrice->otherMonetaryDetails);

        $this->assertNull($message->productReference);
    }
}
