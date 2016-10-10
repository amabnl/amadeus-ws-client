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
use Amadeus\Client\RequestOptions\FarePricePnrWithLowerFaresOptions;
use Amadeus\Client\Struct\Fare\PricePnr13\FirstCurrencyDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\PenDisInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionKey;
use Amadeus\Client\Struct\Fare\PricePnr13\ReferenceDetails;
use Amadeus\Client\Struct\Fare\PricePNRWithLowerFares13;
use Test\Amadeus\BaseTestCase;

/**
 * PricePNRWithLowerFares13Test
 *
 * @package Test\Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class PricePNRWithLowerFares13Test extends BaseTestCase
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
        $options = new FarePricePnrWithLowerFaresOptions([
            'overrideOptions' => [
                FarePricePnrWithLowerFaresOptions::OVERRIDE_FARETYPE_PUB,
                FarePricePnrWithLowerFaresOptions::OVERRIDE_FARETYPE_UNI
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

        $message = new PricePNRWithLowerFares13($options);

        $this->assertCount(4, $message->pricingOptionGroup);

        $this->assertEquals(PricingOptionKey::OPTION_FARE_CURRENCY_OVERRIDE, $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals('USD', $message->pricingOptionGroup[0]->currency->firstCurrencyDetails->currencyIsoCode);
        $this->assertEquals(FirstCurrencyDetails::QUAL_CURRENCY_OVERRIDE, $message->pricingOptionGroup[0]->currency->firstCurrencyDetails->currencyQualifier);

        $this->assertEquals(PricingOptionKey::OPTION_PUBLISHED_FARES, $message->pricingOptionGroup[1]->pricingOptionKey->pricingOptionKey);

        $this->assertEquals(PricingOptionKey::OPTION_UNIFARES, $message->pricingOptionGroup[2]->pricingOptionKey->pricingOptionKey);

        $this->assertEquals(PricingOptionKey::OPTION_PASSENGER_DISCOUNT, $message->pricingOptionGroup[3]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(PenDisInformation::QUAL_DISCOUNT, $message->pricingOptionGroup[3]->penDisInformation->discountPenaltyQualifier);
        $this->assertEquals('CH', $message->pricingOptionGroup[3]->penDisInformation->discountPenaltyDetails[0]->rate);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_REFERENCE, $message->pricingOptionGroup[3]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(2, $message->pricingOptionGroup[3]->paxSegTstReference->referenceDetails[0]->value);
    }
}
