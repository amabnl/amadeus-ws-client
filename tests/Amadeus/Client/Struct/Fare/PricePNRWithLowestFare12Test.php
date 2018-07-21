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

namespace Test\Amadeus\Client\Struct\Fare;

use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;
use Amadeus\Client\RequestOptions\FarePricePnrWithLowestFareOptions;
use Amadeus\Client\Struct\Fare\PricePnr12\AttributeDetails;
use Amadeus\Client\Struct\Fare\PricePnr12\PenDisInformation;
use Amadeus\Client\Struct\Fare\PricePnr12\RefDetails;
use Amadeus\Client\Struct\Fare\PricePNRWithLowestFare12;
use Test\Amadeus\BaseTestCase;

/**
 * PricePNRWithLowestFare12Test
 *
 * @package Test\Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PricePNRWithLowestFare12Test extends BaseTestCase
{
    /**
     * 5.1 Operation: Pricing options
     *
     * Here is a complete example of pricing, with options listed below:
     *
     * - take published fares into account (RP)
     * - take Unifares into account (RU)
     * - use PTC "CH" for passenger 2 (PAX)
     * - convert fare into USD (FCO)
     */
    public function test51OperationPricingOptions()
    {
        $options = new FarePricePnrWithLowestFareOptions([
            'overrideOptions' => [
                FarePricePnrWithLowestFareOptions::OVERRIDE_FARETYPE_PUB,
                FarePricePnrWithLowestFareOptions::OVERRIDE_FARETYPE_UNI
            ],
            'currencyOverride' => 'USD',
            'paxDiscountCodes' => ['CH'],
            'paxDiscountCodeRefs' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 2
                ])
            ]
        ]);

        $message = new PricePNRWithLowestFare12($options);

        $this->assertCount(2, $message->overrideInformation->attributeDetails);
        $this->assertEquals(AttributeDetails::OVERRIDE_FARETYPE_PUB, $message->overrideInformation->attributeDetails[0]->attributeType);
        $this->assertNull($message->overrideInformation->attributeDetails[0]->attributeDescription);
        $this->assertEquals(AttributeDetails::OVERRIDE_FARETYPE_UNI, $message->overrideInformation->attributeDetails[1]->attributeType);
        $this->assertNull($message->overrideInformation->attributeDetails[1]->attributeDescription);
        $this->assertEquals('USD', $message->currencyOverride->firstRateDetail->currencyCode);

        $this->assertCount(1, $message->discountInformation);
        $this->assertCount(1, $message->discountInformation[0]->referenceQualifier->refDetails);
        $this->assertEquals(2, $message->discountInformation[0]->referenceQualifier->refDetails[0]->refNumber);
        $this->assertEquals(RefDetails::QUAL_PASSENGER, $message->discountInformation[0]->referenceQualifier->refDetails[0]->refQualifier);
        $this->assertEquals(PenDisInformation::QUAL_DISCOUNT, $message->discountInformation[0]->penDisInformation->infoQualifier);
        $this->assertCount(1, $message->discountInformation[0]->penDisInformation->penDisData);
        $this->assertEquals('CH', $message->discountInformation[0]->penDisInformation->penDisData[0]->discountCode);
        $this->assertNull($message->discountInformation[0]->penDisInformation->penDisData[0]->penaltyAmount);
        $this->assertNull($message->discountInformation[0]->penDisInformation->penDisData[0]->penaltyCurrency);
        $this->assertNull($message->discountInformation[0]->penDisInformation->penDisData[0]->penaltyQualifier);
        $this->assertNull($message->discountInformation[0]->penDisInformation->penDisData[0]->penaltyType);

        $this->assertNull($message->dateOverride);
        $this->assertNull($message->validatingCarrier);
        $this->assertNull($message->cityOverride);
        $this->assertEmpty($message->taxDetails);
        $this->assertEmpty($message->pricingFareBase);
        $this->assertNull($message->flightInformation);
        $this->assertNull($message->openSegmentsInformation);
        $this->assertNull($message->bookingClassSelection);
        $this->assertNull($message->fopInformation);
        $this->assertNull($message->carrierAgreements);
        $this->assertNull($message->frequentFlyerInformation);
        $this->assertNull($message->instantPricingOption);
    }
}
