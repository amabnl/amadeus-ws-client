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

use Amadeus\Client\RequestOptions\Fare\PricePnr\AwardPricing;
use Amadeus\Client\RequestOptions\Fare\PricePnr\ExemptTax;
use Amadeus\Client\RequestOptions\Fare\PricePnr\FareBasis;
use Amadeus\Client\RequestOptions\Fare\PricePnr\ObFee;
use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;
use Amadeus\Client\RequestOptions\Fare\PricePnr\Tax;
use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
use Amadeus\Client\Struct\Fare\PricePnr13\CarrierInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\CriteriaDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\Currency;
use Amadeus\Client\Struct\Fare\PricePnr13\DateInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\DiscountPenaltyDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\LocationInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\OptionDetail;
use Amadeus\Client\Struct\Fare\PricePnr13\PaxSegTstReference;
use Amadeus\Client\Struct\Fare\PricePnr13\PenDisInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionGroup;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionKey;
use Amadeus\Client\Struct\Fare\PricePnr13\ReferenceDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\TaxData;
use Amadeus\Client\Struct\Fare\PricePnr13\TaxInformation;
use Amadeus\Client\Struct\Fare\PricePNRWithBookingClass13;
use Test\Amadeus\BaseTestCase;

/**
 * @package Amadeus\Client\Struct\Fare
 * @author dieter <dieter.devlieghere@benelux.amadeus.com>
 */
class PricePNRWithBookingClass13Test extends BaseTestCase
{
    public function testCanDoPricePnrCallWithLegacyFareBasisParams()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'overrideOptions' => [FarePricePnrWithBookingClassOptions::OVERRIDE_FARETYPE_NEG],
            'validatingCarrier' => 'BA',
            'currencyOverride' => 'EUR',
            'pricingsFareBasis' => [
                new FareBasis([
                    'fareBasisPrimaryCode' => 'QNC',
                    'fareBasisCode' => '469W2',
                    'segmentReference' => [2 => FareBasis::SEGREFTYPE_SEGMENT]
                ])
            ]
        ]);

        $message = new PricePNRWithBookingClass13($opt);

        $validatingCarrierPo = new PricingOptionGroup(PricingOptionKey::OPTION_VALIDATING_CARRIER);
        $validatingCarrierPo->carrierInformation = new CarrierInformation('BA');

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $validatingCarrierPo));

        $currencyOverridePo = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_CURRENCY_OVERRIDE);
        $currencyOverridePo->currency = new Currency('EUR');

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $currencyOverridePo));

        $fareBasisOverridePo = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_BASIS_SIMPLE_OVERRIDE);
        $fareBasisOverridePo->optionDetail = new OptionDetail();
        $fareBasisOverridePo->optionDetail->criteriaDetails[] = new CriteriaDetails('QNC469W2');
        $fareBasisOverridePo->paxSegTstReference = new PaxSegTstReference([2 => FareBasis::SEGREFTYPE_SEGMENT]);

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $fareBasisOverridePo));

        $negofarePo = new PricingOptionGroup(PricingOptionKey::OPTION_NEGOTIATED_FARES);

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $negofarePo));
    }

    public function testCanDoPricePnrCallWithNewFareBasisParams()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'overrideOptions' => [FarePricePnrWithBookingClassOptions::OVERRIDE_FARETYPE_NEG],
            'validatingCarrier' => 'BA',
            'currencyOverride' => 'EUR',
            'pricingsFareBasis' => [
                new FareBasis([
                    'fareBasisCode' => 'QNC469W2',
                    'references' => [
                        new PaxSegRef([
                            'reference' => 2,
                            'type' => PaxSegRef::TYPE_SEGMENT
                        ])
                    ]
                ])
            ]
        ]);

        $message = new PricePNRWithBookingClass13($opt);

        $validatingCarrierPo = new PricingOptionGroup(PricingOptionKey::OPTION_VALIDATING_CARRIER);
        $validatingCarrierPo->carrierInformation = new CarrierInformation('BA');

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $validatingCarrierPo));

        $currencyOverridePo = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_CURRENCY_OVERRIDE);
        $currencyOverridePo->currency = new Currency('EUR');

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $currencyOverridePo));

        $fareBasisOverridePo = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_BASIS_SIMPLE_OVERRIDE);
        $fareBasisOverridePo->optionDetail = new OptionDetail();
        $fareBasisOverridePo->optionDetail->criteriaDetails[] = new CriteriaDetails('QNC469W2');
        $fareBasisOverridePo->paxSegTstReference = new PaxSegTstReference(null, [new PaxSegRef(['type'=> PaxSegRef::TYPE_SEGMENT, 'reference' => 2])]);

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $fareBasisOverridePo));

        $negofarePo = new PricingOptionGroup(PricingOptionKey::OPTION_NEGOTIATED_FARES);

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $negofarePo));
    }

    public function testCanDoPricePnrCallWithNoOptions()
    {
        $opt = new FarePricePnrWithBookingClassOptions();

        $message = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $message->pricingOptionGroup);
        $this->assertEquals('NOP', $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertNull($message->pricingOptionGroup[0]->currency);
        $this->assertNull($message->pricingOptionGroup[0]->carrierInformation);
        $this->assertNull($message->pricingOptionGroup[0]->dateInformation);
        $this->assertNull($message->pricingOptionGroup[0]->formOfPaymentInformation);
        $this->assertNull($message->pricingOptionGroup[0]->frequentFlyerInformation);
        $this->assertNull($message->pricingOptionGroup[0]->locationInformation);
        $this->assertNull($message->pricingOptionGroup[0]->monetaryInformation);
        $this->assertNull($message->pricingOptionGroup[0]->optionDetail);
        $this->assertNull($message->pricingOptionGroup[0]->paxSegTstReference);
        $this->assertNull($message->pricingOptionGroup[0]->penDisInformation);
        $this->assertEmpty($message->pricingOptionGroup[0]->taxInformation);
    }

    /**
     * Testcase where we have the Fare Basis override in the override options and also pricingsFareBasis provided
     * we will test that we only have 1 pricingOptionGroup for the fare basis override.
     */
    public function testCanDoPricePnrCallWithFareOverrideDuplicate()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'overrideOptions' => [FarePricePnrWithBookingClassOptions::OVERRIDE_FARETYPE_NEG, FarePricePnrWithBookingClassOptions::OVERRIDE_FAREBASIS],
            'validatingCarrier' => 'BA',
            'currencyOverride' => 'EUR',
            'pricingsFareBasis' => [
                new FareBasis([
                    'fareBasisCode' => 'QNC469W2',
                    'references' => [
                        new PaxSegRef([
                            'reference' => 2,
                            'type' => PaxSegRef::TYPE_SEGMENT
                        ])
                    ]
                ])
            ]
        ]);

        $message = new PricePNRWithBookingClass13($opt);

        $this->assertCount(4, $message->pricingOptionGroup);

        $validatingCarrierPo = new PricingOptionGroup(PricingOptionKey::OPTION_VALIDATING_CARRIER);
        $validatingCarrierPo->carrierInformation = new CarrierInformation('BA');

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $validatingCarrierPo));

        $currencyOverridePo = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_CURRENCY_OVERRIDE);
        $currencyOverridePo->currency = new Currency('EUR');

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $currencyOverridePo));

        $fareBasisOverridePo = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_BASIS_SIMPLE_OVERRIDE);
        $fareBasisOverridePo->optionDetail = new OptionDetail();
        $fareBasisOverridePo->optionDetail->criteriaDetails[] = new CriteriaDetails('QNC469W2');
        $fareBasisOverridePo->paxSegTstReference = new PaxSegTstReference(
            null,
            [new PaxSegRef(['type'=> PaxSegRef::TYPE_SEGMENT, 'reference' => 2])]
        );

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $fareBasisOverridePo));

        $negofarePo = new PricingOptionGroup(PricingOptionKey::OPTION_NEGOTIATED_FARES);

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $negofarePo));
    }

    public function testCanDoPricePnrCallWithObFees()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'obFees' => [
                new ObFee([
                    'include' => true,
                    'rate' => 'FC1',
                    'amount' => 10,
                    'currency' => "USD"
                ])
            ],
            'obFeeRefs' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 1
                ])
            ]
        ]);

        $message = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $message->pricingOptionGroup);

        $this->assertEquals(PricingOptionKey::OPTION_OB_FEES, $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(PenDisInformation::QUAL_OB_FEES, $message->pricingOptionGroup[0]->penDisInformation->discountPenaltyQualifier);
        $this->assertCount(1, $message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails);
        $this->assertEquals(DiscountPenaltyDetails::FUNCTION_INCLUDE_FEE, $message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->function);
        $this->assertEquals(10, $message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->amount);
        $this->assertEquals('USD', $message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->currency);
        $this->assertEquals(DiscountPenaltyDetails::AMOUNTTYPE_FIXED_WHOLE_AMOUNT, $message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->amountType);
        $this->assertEquals('FC1', $message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->rate);
    }

    public function testCanDoPricePnrCallWithPricingLogic()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'pricingLogic' => FarePricePnrWithBookingClassOptions::PRICING_LOGIC_IATA
        ]);

        $message = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $message->pricingOptionGroup);

        $this->assertEquals(PricingOptionKey::OPTION_PRICING_LOGIC, $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(1, $message->pricingOptionGroup[0]->optionDetail->criteriaDetails);
        $this->assertEquals('IATA', $message->pricingOptionGroup[0]->optionDetail->criteriaDetails[0]->attributeType);
    }

    public function testCanDoPricePnrCallWithCorpNegoFare()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'corporateNegoFare' => '012345'
        ]);

        $message = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $message->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_CORPORATE_NEGOTIATED_FARES, $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(1, $message->pricingOptionGroup[0]->optionDetail->criteriaDetails);
        $this->assertEquals('012345', $message->pricingOptionGroup[0]->optionDetail->criteriaDetails[0]->attributeType);
    }


    public function testCanDoPricePnrCallWithCorpUniFares()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'corporateUniFares' => ['012345', 'AMADEUS']
        ]);

        $message = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $message->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_CORPORATE_UNIFARES, $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(2, $message->pricingOptionGroup[0]->optionDetail->criteriaDetails);
        $this->assertEquals('012345', $message->pricingOptionGroup[0]->optionDetail->criteriaDetails[0]->attributeType);
        $this->assertEquals('AMADEUS', $message->pricingOptionGroup[0]->optionDetail->criteriaDetails[1]->attributeType);

    }

    public function testCanDoPricePnrCallWithPaxDiscountCodes()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'paxDiscountCodes' => ['YTH', 'AD20', 'MIL'],
            'paxDiscountCodeRefs' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 1
                ]),
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_SEGMENT,
                    'reference' => 4
                ])
            ]
        ]);

        $message = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $message->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_PASSENGER_DISCOUNT_PTC, $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(PenDisInformation::QUAL_DISCOUNT, $message->pricingOptionGroup[0]->penDisInformation->discountPenaltyQualifier);
        $this->assertCount(3, $message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails);
        $this->assertEquals('YTH', $message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->rate);
        $this->assertNull($message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->amount);
        $this->assertNull($message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->amountType);
        $this->assertNull($message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->currency);
        $this->assertNull($message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->function);

        $this->assertEquals('AD20', $message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[1]->rate);
        $this->assertNull($message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[1]->amount);
        $this->assertNull($message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[1]->amountType);
        $this->assertNull($message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[1]->currency);
        $this->assertNull($message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[1]->function);

        $this->assertEquals('MIL', $message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[2]->rate);
        $this->assertNull($message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[2]->amount);
        $this->assertNull($message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[2]->amountType);
        $this->assertNull($message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[2]->currency);
        $this->assertNull($message->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[2]->function);

        $this->assertCount(2, $message->pricingOptionGroup[0]->paxSegTstReference->referenceDetails);
        $this->assertEquals(1, $message->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_REFERENCE, $message->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(4, $message->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[1]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $message->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[1]->type);
    }

    /**
     * Point of Sale & Point of Ticketing override
     */
    public function testCanDoPricePnrCallWithPosPotOverride()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'pointOfSaleOverride' => 'LON',
            'pointOfTicketingOverride' => 'MAN'
        ]);

        $message = new PricePNRWithBookingClass13($opt);

        $this->assertCount(2, $message->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_POINT_OF_SALE_OVERRIDE, $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(LocationInformation::TYPE_POINT_OF_SALE, $message->pricingOptionGroup[0]->locationInformation->locationType);
        $this->assertEquals('LON', $message->pricingOptionGroup[0]->locationInformation->firstLocationDetails->code);
        $this->assertEquals(PricingOptionKey::OPTION_POINT_OF_TICKETING_OVERRIDE, $message->pricingOptionGroup[1]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(LocationInformation::TYPE_POINT_OF_TICKETING, $message->pricingOptionGroup[1]->locationInformation->locationType);
        $this->assertEquals('MAN', $message->pricingOptionGroup[1]->locationInformation->firstLocationDetails->code);
    }

    public function testCanDoPricePnrCallWithTicketType()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'ticketType' => FarePricePnrWithBookingClassOptions::TICKET_TYPE_ELECTRONIC
        ]);

        $message = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $message->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_TICKET_TYPE, $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(1, $message->pricingOptionGroup[0]->optionDetail->criteriaDetails);
        $this->assertEquals(FarePricePnrWithBookingClassOptions::TICKET_TYPE_ELECTRONIC, $message->pricingOptionGroup[0]->optionDetail->criteriaDetails[0]->attributeType);
    }

    public function testCanDoPricePnrCallWithAddTaxes()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'taxes' => [
                new Tax([
                    'taxNature' => 'GO',
                    'countryCode' => 'ZV',
                    'amount' => 50
                ]),
                new Tax([
                    'taxNature' => null,
                    'countryCode' => 'FR',
                    'percentage' => 10
                ])
            ]
        ]);

        $message = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $message->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_ADD_TAX, $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(2, $message->pricingOptionGroup[0]->taxInformation);
        $this->assertEquals(TaxInformation::QUALIFIER_TAX, $message->pricingOptionGroup[0]->taxInformation[0]->taxQualifier);
        $this->assertEquals('GO', $message->pricingOptionGroup[0]->taxInformation[0]->taxNature);
        $this->assertEquals(50, $message->pricingOptionGroup[0]->taxInformation[0]->taxData->taxRate);
        $this->assertEquals(TaxData::QUALIFIER_AMOUNT, $message->pricingOptionGroup[0]->taxInformation[0]->taxData->taxValueQualifier);
        $this->assertEquals('ZV', $message->pricingOptionGroup[0]->taxInformation[0]->taxType->isoCountry);

        $this->assertEquals(TaxInformation::QUALIFIER_TAX, $message->pricingOptionGroup[0]->taxInformation[1]->taxQualifier);
        $this->assertNull($message->pricingOptionGroup[0]->taxInformation[1]->taxNature);
        $this->assertEquals(10, $message->pricingOptionGroup[0]->taxInformation[1]->taxData->taxRate);
        $this->assertEquals(TaxData::QUALIFIER_PERCENTAGE, $message->pricingOptionGroup[0]->taxInformation[1]->taxData->taxValueQualifier);
        $this->assertEquals('FR', $message->pricingOptionGroup[0]->taxInformation[1]->taxType->isoCountry);
    }

    public function testCanDoPricePnrCallWithExemptTaxes()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'exemptTaxes' => [
                new ExemptTax([
                    'taxNature' => 'GO',
                    'countryCode' => 'ZV',
                ])
            ]
        ]);

        $message = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $message->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_EXEMPT_FROM_TAX, $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(1, $message->pricingOptionGroup[0]->taxInformation);
        $this->assertEquals(TaxInformation::QUALIFIER_TAX, $message->pricingOptionGroup[0]->taxInformation[0]->taxQualifier);
        $this->assertEquals('GO', $message->pricingOptionGroup[0]->taxInformation[0]->taxNature);
        $this->assertNull($message->pricingOptionGroup[0]->taxInformation[0]->taxData);
        $this->assertEquals('ZV', $message->pricingOptionGroup[0]->taxInformation[0]->taxType->isoCountry);
    }

    /**
     * Price only segments 3 and 4 for passenger 1.
     */
    public function testCanDoPricePnrCallWithPaxSegRefs()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'references' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER_INFANT,
                    'reference' => 1
                ]),
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER_ADULT,
                    'reference' => 2
                ]),
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 3
                ]),
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_SEGMENT,
                    'reference' => 1
                ]),
            ]
        ]);

        $message = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $message->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_PAX_SEGMENT_TST_SELECTION, $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(4, $message->pricingOptionGroup[0]->paxSegTstReference->referenceDetails);

        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_INFANT, $message->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(1, $message->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_ADULT, $message->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[1]->type);
        $this->assertEquals(2, $message->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[1]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_REFERENCE, $message->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[2]->type);
        $this->assertEquals(3, $message->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[2]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $message->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[3]->type);
        $this->assertEquals(1, $message->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[3]->value);

    }

    /**
     * 5.20 Operation: Past Date
     *
     * This example shows a pricing on a past date, 12 May 2004.
     *
     * The cryptic entry corresponding to the example: FXX/R,12MAY04
     */
    public function testCanDoPricePnrCallWithPastDatePricing()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'pastDatePricing' => \DateTime::createFromFormat(
                \DateTime::ISO8601,
                "2012-06-27T00:00:00+0000",
                new \DateTimeZone('UTC')
            )
        ]);

        $message = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $message->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_PAST_DATE_PRICING, $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(DateInformation::OPT_DATE_OVERRIDE, $message->pricingOptionGroup[0]->dateInformation->businessSemantic);
        $this->assertEquals("2012", $message->pricingOptionGroup[0]->dateInformation->dateTime->year);
        $this->assertEquals("06", $message->pricingOptionGroup[0]->dateInformation->dateTime->month);
        $this->assertEquals("27", $message->pricingOptionGroup[0]->dateInformation->dateTime->day);
    }

    public function testCanDoPricePnrCallWithAwardPricing()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'corporateUniFares' => ['012345', '456789'],
            'awardPricing' => new AwardPricing([
                'carrier' => '6X',
                'tierLevel' => 'GOLD'
            ])
        ]);

        $message = new PricePNRWithBookingClass13($opt);

        $this->assertCount(2, $message->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_CORPORATE_UNIFARES, $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(2, $message->pricingOptionGroup[0]->optionDetail->criteriaDetails);
        $this->assertEquals('012345', $message->pricingOptionGroup[0]->optionDetail->criteriaDetails[0]->attributeType);
        $this->assertEquals('456789', $message->pricingOptionGroup[0]->optionDetail->criteriaDetails[1]->attributeType);

        $this->assertEquals(PricingOptionKey::OPTION_AWARD_PRICING, $message->pricingOptionGroup[1]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals('6X', $message->pricingOptionGroup[1]->carrierInformation->companyIdentification->otherCompany);
        $this->assertCount(1, $message->pricingOptionGroup[1]->frequentFlyerInformation->frequentTravellerDetails);
        $this->assertEquals('GOLD', $message->pricingOptionGroup[1]->frequentFlyerInformation->frequentTravellerDetails[0]->tierLevel);
        $this->assertNull($message->pricingOptionGroup[1]->frequentFlyerInformation->frequentTravellerDetails[0]->carrier);
        $this->assertNull($message->pricingOptionGroup[1]->frequentFlyerInformation->frequentTravellerDetails[0]->number);
        $this->assertNull($message->pricingOptionGroup[1]->frequentFlyerInformation->frequentTravellerDetails[0]->priorityCode);
    }
}
