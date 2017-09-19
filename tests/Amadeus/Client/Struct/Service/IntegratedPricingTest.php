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

use Amadeus\Client\RequestOptions\Service\FormOfPayment;
use Amadeus\Client\RequestOptions\Service\FrequentFlyer;
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
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class IntegratedPricingTest extends BaseTestCase
{
    public function testCanMakeMessageNoOptions()
    {
        $opt = new ServiceIntegratedPricingOptions();

        $msg = new IntegratedPricing($opt);

        $this->assertCount(1, $msg->pricingOption);
        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_NO_OPTION, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertNull($msg->pricingOption[0]->optionDetail);
        $this->assertNull($msg->pricingOption[0]->carrierInformation);
        $this->assertNull($msg->pricingOption[0]->currency);
        $this->assertNull($msg->pricingOption[0]->dateInformation);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation);
        $this->assertNull($msg->pricingOption[0]->frequentFlyerInformation);
        $this->assertNull($msg->pricingOption[0]->locationInformation);
        $this->assertNull($msg->pricingOption[0]->paxSegTstReference);
        $this->assertNull($msg->pricingOption[0]->ticketInformation);
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

        $msg = new IntegratedPricing($opt);

        $this->assertCount(1, $msg->pricingOption);

        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_ACCOUNT_CODE, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(1, $msg->pricingOption[0]->optionDetail->criteriaDetails);
        $this->assertEquals('AAA123456', $msg->pricingOption[0]->optionDetail->criteriaDetails[0]->attributeType);
        $this->assertNull($msg->pricingOption[0]->optionDetail->criteriaDetails[0]->attributeDescription);

        $this->assertCount(1, $msg->pricingOption[0]->paxSegTstReference->referenceDetails);
        $this->assertEquals(1, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->type);

        $this->assertNull($msg->pricingOption[0]->carrierInformation);
        $this->assertNull($msg->pricingOption[0]->currency);
        $this->assertNull($msg->pricingOption[0]->dateInformation);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation);
        $this->assertNull($msg->pricingOption[0]->frequentFlyerInformation);
        $this->assertNull($msg->pricingOption[0]->locationInformation);
        $this->assertNull($msg->pricingOption[0]->ticketInformation);
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

        $msg = new IntegratedPricing($opt);

        $this->assertCount(1, $msg->pricingOption);

        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_PAX_SEG_ELEMENT_SELECTION, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertNull($msg->pricingOption[0]->optionDetail);

        $this->assertCount(3, $msg->pricingOption[0]->paxSegTstReference->referenceDetails);
        $this->assertEquals(1, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(2, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[1]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[1]->type);
        $this->assertEquals(16, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[2]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_ELEMENT, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[2]->type);

        $this->assertNull($msg->pricingOption[0]->optionDetail);
        $this->assertNull($msg->pricingOption[0]->carrierInformation);
        $this->assertNull($msg->pricingOption[0]->currency);
        $this->assertNull($msg->pricingOption[0]->dateInformation);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation);
        $this->assertNull($msg->pricingOption[0]->frequentFlyerInformation);
        $this->assertNull($msg->pricingOption[0]->locationInformation);
        $this->assertNull($msg->pricingOption[0]->ticketInformation);
    }

    public function testCanMakeMessageWithOverrideOptionServicePricingDescription()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'overrideOptions' => [
                ServiceIntegratedPricingOptions::OVERRIDE_SHOW_PRICING_DESCRIPTION
            ]
        ]);

        $msg = new IntegratedPricing($opt);

        $this->assertCount(1, $msg->pricingOption);

        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_SHOW_PRICING_DESCRIPTION, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertNull($msg->pricingOption[0]->optionDetail);
        $this->assertNull($msg->pricingOption[0]->carrierInformation);
        $this->assertNull($msg->pricingOption[0]->currency);
        $this->assertNull($msg->pricingOption[0]->dateInformation);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation);
        $this->assertNull($msg->pricingOption[0]->frequentFlyerInformation);
        $this->assertNull($msg->pricingOption[0]->locationInformation);
        $this->assertNull($msg->pricingOption[0]->paxSegTstReference);
        $this->assertNull($msg->pricingOption[0]->ticketInformation);
    }

    public function testCanMakeMessageWithOverridePointOfSale()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'pointOfSaleOverride' => 'MUC'
        ]);

        $msg = new IntegratedPricing($opt);

        $this->assertCount(1, $msg->pricingOption);

        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_POINT_OF_SALE, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertEquals(LocationInformation::TYPE_POINT_OF_SALE, $msg->pricingOption[0]->locationInformation->locationType);
        $this->assertEquals('MUC', $msg->pricingOption[0]->locationInformation->firstLocationDetails->code);
        $this->assertNull($msg->pricingOption[0]->locationInformation->secondLocationDetails);

        $this->assertNull($msg->pricingOption[0]->optionDetail);
        $this->assertNull($msg->pricingOption[0]->carrierInformation);
        $this->assertNull($msg->pricingOption[0]->currency);
        $this->assertNull($msg->pricingOption[0]->dateInformation);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation);
        $this->assertNull($msg->pricingOption[0]->frequentFlyerInformation);
        $this->assertNull($msg->pricingOption[0]->paxSegTstReference);
        $this->assertNull($msg->pricingOption[0]->ticketInformation);
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

        $msg = new IntegratedPricing($opt);

        $this->assertCount(1, $msg->pricingOption);
        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_PRICING_DATE, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(DateInformation::OPT_DATE_OVERRIDE, $msg->pricingOption[0]->dateInformation->businessSemantic);
        $this->assertEquals("2012", $msg->pricingOption[0]->dateInformation->dateTime->year);
        $this->assertEquals("06", $msg->pricingOption[0]->dateInformation->dateTime->month);
        $this->assertEquals("27", $msg->pricingOption[0]->dateInformation->dateTime->day);
    }

    public function testCanMakeMessageWithCurrencyOverride()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'currencyOverride' => 'EUR'
        ]);

        $msg = new IntegratedPricing($opt);

        $this->assertCount(1, $msg->pricingOption);
        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_CURRENCY, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertEquals(FirstCurrencyDetails::QUAL_CURRENCY_OVERRIDE, $msg->pricingOption[0]->currency->firstCurrencyDetails->currencyQualifier);
        $this->assertEquals('EUR', $msg->pricingOption[0]->currency->firstCurrencyDetails->currencyIsoCode);
    }

    public function testCanMakeMessageWithValidatingCarrierOverride()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'validatingCarrier' => 'BA'
        ]);

        $msg = new IntegratedPricing($opt);

        $this->assertCount(1, $msg->pricingOption);
        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_VALIDATING_CARRIER, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertEquals('BA', $msg->pricingOption[0]->carrierInformation->companyIdentification->otherCompany);
    }

    public function testCanMakeMessageWithAwardPricing()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'awardPricing' => ServiceIntegratedPricingOptions::AWARDPRICING_MILES
        ]);

        $msg = new IntegratedPricing($opt);

        $this->assertCount(1, $msg->pricingOption);
        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_AWARD, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertEquals('MIL', $msg->pricingOption[0]->optionDetail->criteriaDetails[0]->attributeType);
    }

    public function testCanMakeMessageWithCorporationNumber()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'corporationNumber' => '123435'
        ]);

        $msg = new IntegratedPricing($opt);

        $this->assertCount(1, $msg->pricingOption);
        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_CORPORATION_NUMBER, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertEquals('123435', $msg->pricingOption[0]->optionDetail->criteriaDetails[0]->attributeType);
    }

    public function testCanMakeMessageWithTicketDesignator()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'ticketDesignator' => '3M4J2KM432'
        ]);

        $msg = new IntegratedPricing($opt);

        $this->assertCount(1, $msg->pricingOption);
        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_TICKET_DESIGNATOR, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertEquals('3M4J2KM432', $msg->pricingOption[0]->optionDetail->criteriaDetails[0]->attributeType);
    }

    public function testCanMakeMessageWithFormOfPaymentOverride()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'formOfPayment' => [
                new FormOfPayment([
                    'type' => FormOfPayment::TYPE_CREDIT_CARD,
                    'amount' => 10,
                    'creditCardNumber' => '400000'
                ]),
                new FormOfPayment([
                    'type' => FormOfPayment::TYPE_CASH
                ]),
            ]
        ]);

        $msg = new IntegratedPricing($opt);

        $this->assertCount(1, $msg->pricingOption);
        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_FORM_OF_PAYMENT, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(\Amadeus\Client\Struct\Fare\PricePnr13\FormOfPayment::TYPE_CREDIT_CARD, $msg->pricingOption[0]->formOfPaymentInformation->formOfPayment->type);
        $this->assertEquals(10, $msg->pricingOption[0]->formOfPaymentInformation->formOfPayment->amount);
        $this->assertEquals('400000', $msg->pricingOption[0]->formOfPaymentInformation->formOfPayment->creditCardNumber);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation->formOfPayment->sourceOfApproval);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation->formOfPayment->authorisedAmount);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation->formOfPayment->indicator);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation->formOfPayment->addressVerification);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation->formOfPayment->approvalCode);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation->formOfPayment->customerAccount);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation->formOfPayment->expiryDate);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation->formOfPayment->extendedPayment);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation->formOfPayment->fopFreeText);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation->formOfPayment->membershipStatus);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation->formOfPayment->pinCode);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation->formOfPayment->pinCodeType);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation->formOfPayment->transactionInfo);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation->formOfPayment->vendorCode);
        $this->assertCount(1, $msg->pricingOption[0]->formOfPaymentInformation->otherFormOfPayment);
        $this->assertEquals(\Amadeus\Client\Struct\Fare\PricePnr13\FormOfPayment::TYPE_CASH, $msg->pricingOption[0]->formOfPaymentInformation->otherFormOfPayment[0]->type);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation->otherFormOfPayment[0]->amount);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation->otherFormOfPayment[0]->creditCardNumber);
    }

    public function testCanMakeMessageWithFrequentFlyerOverride()
    {
        $opt = new ServiceIntegratedPricingOptions([
            'frequentFlyers' => [
                new FrequentFlyer([
                    'company' => '6X',
                    'number' => '1234567891011',
                    'tierLevel' => 'SILVER',
                    'priorityCode' => '1'
                ])
            ]
        ]);

        $msg = new IntegratedPricing($opt);
        $this->assertCount(1, $msg->pricingOption);
        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_FREQUENT_FLYER_INFORMATION, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(1, $msg->pricingOption[0]->frequentFlyerInformation->frequentTravellerDetails);
        $this->assertEquals('6X', $msg->pricingOption[0]->frequentFlyerInformation->frequentTravellerDetails[0]->carrier);
        $this->assertEquals('1234567891011', $msg->pricingOption[0]->frequentFlyerInformation->frequentTravellerDetails[0]->number);
        $this->assertEquals('SILVER', $msg->pricingOption[0]->frequentFlyerInformation->frequentTravellerDetails[0]->tierLevel);
        $this->assertEquals('1', $msg->pricingOption[0]->frequentFlyerInformation->frequentTravellerDetails[0]->priorityCode);

    }
}

