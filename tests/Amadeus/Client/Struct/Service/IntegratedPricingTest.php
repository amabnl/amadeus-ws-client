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

namespace Test\Amadeus\Client\Struct\Service;

use Amadeus\Client\RequestOptions\Service\PaxSegRef;
use Amadeus\Client\RequestOptions\ServiceIntegratedPricingOptions;
use Amadeus\Client\Struct\Fare\PricePnr13\DateInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\FirstCurrencyDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\LocationInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\ReferenceDetails;
use Amadeus\Client\Struct\Service\IntegratedPricing;
use Test\Amadeus\BaseTestCase;

/**
 * IntegratedPricingTest
 *
 * @package Test\Amadeus\Client\Struct\Service
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class IntegratedPricingTest extends BaseTestCase
{
    public function testCanMakeMessageNoOptions()
    {
        $opt = new ServiceIntegratedPricingOptions();

        $message = new IntegratedPricing($opt);

        $this->assertCount(1, $message->pricingOption);
        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_NO_OPTION, $message->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertNull($message->pricingOption[0]->optionDetail);
        $this->assertNull($message->pricingOption[0]->carrierInformation);
        $this->assertNull($message->pricingOption[0]->currency);
        $this->assertNull($message->pricingOption[0]->dateInformation);
        $this->assertNull($message->pricingOption[0]->formOfPaymentInformation);
        $this->assertNull($message->pricingOption[0]->frequentFlyerInformation);
        $this->assertNull($message->pricingOption[0]->locationInformation);
        $this->assertNull($message->pricingOption[0]->paxSegTstReference);
        $this->assertNull($message->pricingOption[0]->ticketInformation);
    }

    public function testCanMakeMessageWithAccountCode()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'accountCode' => 'AAA123456',
            'accountCodeRefs' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 1
                ])
            ]
        ]);

        $message = new IntegratedPricing($opt);

        $this->assertCount(1, $message->pricingOption);

        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_ACCOUNT_CODE, $message->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(1, $message->pricingOption[0]->optionDetail->criteriaDetails);
        $this->assertEquals('AAA123456', $message->pricingOption[0]->optionDetail->criteriaDetails[0]->attributeType);
        $this->assertNull($message->pricingOption[0]->optionDetail->criteriaDetails[0]->attributeDescription);

        $this->assertCount(1, $message->pricingOption[0]->paxSegTstReference->referenceDetails);
        $this->assertEquals(1, $message->pricingOption[0]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_REFERENCE, $message->pricingOption[0]->paxSegTstReference->referenceDetails[0]->type);

        $this->assertNull($message->pricingOption[0]->carrierInformation);
        $this->assertNull($message->pricingOption[0]->currency);
        $this->assertNull($message->pricingOption[0]->dateInformation);
        $this->assertNull($message->pricingOption[0]->formOfPaymentInformation);
        $this->assertNull($message->pricingOption[0]->frequentFlyerInformation);
        $this->assertNull($message->pricingOption[0]->locationInformation);
        $this->assertNull($message->pricingOption[0]->ticketInformation);
    }

    /**
     * Price a single Service, for a single flight and a single passenger.
     */
    public function testCanMakeMessageWithPaxFlightAndServiceSelection()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'references' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 1
                ]),
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_SEGMENT,
                    'reference' => 2
                ]),
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_SERVICE,
                    'reference' => 16
                ])
            ]
        ]);

        $message = new IntegratedPricing($opt);

        $this->assertCount(1, $message->pricingOption);

        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_PAX_SEG_ELEMENT_SELECTION, $message->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertNull($message->pricingOption[0]->optionDetail);

        $this->assertCount(3, $message->pricingOption[0]->paxSegTstReference->referenceDetails);
        $this->assertEquals(1, $message->pricingOption[0]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_REFERENCE, $message->pricingOption[0]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(2, $message->pricingOption[0]->paxSegTstReference->referenceDetails[1]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $message->pricingOption[0]->paxSegTstReference->referenceDetails[1]->type);
        $this->assertEquals(16, $message->pricingOption[0]->paxSegTstReference->referenceDetails[2]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_ELEMENT, $message->pricingOption[0]->paxSegTstReference->referenceDetails[2]->type);

        $this->assertNull($message->pricingOption[0]->optionDetail);
        $this->assertNull($message->pricingOption[0]->carrierInformation);
        $this->assertNull($message->pricingOption[0]->currency);
        $this->assertNull($message->pricingOption[0]->dateInformation);
        $this->assertNull($message->pricingOption[0]->formOfPaymentInformation);
        $this->assertNull($message->pricingOption[0]->frequentFlyerInformation);
        $this->assertNull($message->pricingOption[0]->locationInformation);
        $this->assertNull($message->pricingOption[0]->ticketInformation);
    }

    public function testCanMakeMessageWithOverrideOptionServicePricingDescription()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'overrideOptions' => [
                ServiceIntegratedPricingOptions::OVERRIDE_SHOW_PRICING_DESCRIPTION
            ]
        ]);

        $message = new IntegratedPricing($opt);

        $this->assertCount(1, $message->pricingOption);

        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_SHOW_PRICING_DESCRIPTION, $message->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertNull($message->pricingOption[0]->optionDetail);
        $this->assertNull($message->pricingOption[0]->carrierInformation);
        $this->assertNull($message->pricingOption[0]->currency);
        $this->assertNull($message->pricingOption[0]->dateInformation);
        $this->assertNull($message->pricingOption[0]->formOfPaymentInformation);
        $this->assertNull($message->pricingOption[0]->frequentFlyerInformation);
        $this->assertNull($message->pricingOption[0]->locationInformation);
        $this->assertNull($message->pricingOption[0]->paxSegTstReference);
        $this->assertNull($message->pricingOption[0]->ticketInformation);
    }

    public function testCanMakeMessageWithOverridePointOfSale()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'pointOfSaleOverride' => 'MUC'
        ]);

        $message = new IntegratedPricing($opt);

        $this->assertCount(1, $message->pricingOption);

        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_POINT_OF_SALE, $message->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertEquals(LocationInformation::TYPE_POINT_OF_SALE, $message->pricingOption[0]->locationInformation->locationType);
        $this->assertEquals('MUC', $message->pricingOption[0]->locationInformation->firstLocationDetails->code);
        $this->assertNull($message->pricingOption[0]->locationInformation->secondLocationDetails);

        $this->assertNull($message->pricingOption[0]->optionDetail);
        $this->assertNull($message->pricingOption[0]->carrierInformation);
        $this->assertNull($message->pricingOption[0]->currency);
        $this->assertNull($message->pricingOption[0]->dateInformation);
        $this->assertNull($message->pricingOption[0]->formOfPaymentInformation);
        $this->assertNull($message->pricingOption[0]->frequentFlyerInformation);
        $this->assertNull($message->pricingOption[0]->paxSegTstReference);
        $this->assertNull($message->pricingOption[0]->ticketInformation);
    }

    public function testCanMakeMessageWithPricingDateOverride()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'overrideDate' => \DateTime::createFromFormat(
                \DateTime::ISO8601,
                "2012-06-27T00:00:00+0000",
                new \DateTimeZone('UTC')
            )
        ]);

        $message = new IntegratedPricing($opt);

        $this->assertCount(1, $message->pricingOption);
        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_PRICING_DATE, $message->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(DateInformation::OPT_DATE_OVERRIDE, $message->pricingOption[0]->dateInformation->businessSemantic);
        $this->assertEquals("2012", $message->pricingOption[0]->dateInformation->dateTime->year);
        $this->assertEquals("06", $message->pricingOption[0]->dateInformation->dateTime->month);
        $this->assertEquals("27", $message->pricingOption[0]->dateInformation->dateTime->day);
    }

    public function testCanMakeMessageWithCurrencyOverride()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'currencyOverride' => 'EUR'
        ]);

        $message = new IntegratedPricing($opt);

        $this->assertCount(1, $message->pricingOption);
        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_CURRENCY, $message->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertEquals(FirstCurrencyDetails::QUAL_CURRENCY_OVERRIDE, $message->pricingOption[0]->currency->firstCurrencyDetails->currencyQualifier);
        $this->assertEquals('EUR', $message->pricingOption[0]->currency->firstCurrencyDetails->currencyIsoCode);
    }

    public function testCanMakeMessageWithValidatingCarrierOverride()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'validatingCarrier' => 'BA'
        ]);

        $message = new IntegratedPricing($opt);

        $this->assertCount(1, $message->pricingOption);
        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_VALIDATING_CARRIER, $message->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertEquals('BA', $message->pricingOption[0]->carrierInformation->companyIdentification->otherCompany);
    }

    public function testCanMakeMessageWithAwardPricing()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'awardPricing' => ServiceIntegratedPricingOptions::AWARDPRICING_MILES
        ]);

        $message = new IntegratedPricing($opt);

        $this->assertCount(1, $message->pricingOption);
        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_AWARD, $message->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertEquals('MIL', $message->pricingOption[0]->optionDetail->criteriaDetails[0]->attributeType);
    }

    public function testCanMakeMessageWithCorporationNumber()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'corporationNumber' => '123435'
        ]);

        $message = new IntegratedPricing($opt);

        $this->assertCount(1, $message->pricingOption);
        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_CORPORATION_NUMBER, $message->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertEquals('123435', $message->pricingOption[0]->optionDetail->criteriaDetails[0]->attributeType);
    }

    public function testCanMakeMessageWithTicketDesignator()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'ticketDesignator' => '3M4J2KM432'
        ]);

        $message = new IntegratedPricing($opt);

        $this->assertCount(1, $message->pricingOption);
        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_TICKET_DESIGNATOR, $message->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertEquals('3M4J2KM432', $message->pricingOption[0]->optionDetail->criteriaDetails[0]->attributeType);
    }
}

