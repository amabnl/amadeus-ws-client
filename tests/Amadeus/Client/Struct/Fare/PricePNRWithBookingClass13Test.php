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
use Amadeus\Client\RequestOptions\Fare\PricePnr\FareFamily;
use Amadeus\Client\RequestOptions\Fare\PricePnr\FormOfPayment;
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
use Amadeus\Client\RequestOptions\Fare\PricePnr\ZapOff;
use Amadeus\Client\Struct\Fare\PricePNRWithBookingClass13;
use Test\Amadeus\BaseTestCase;

/**
 * @package Amadeus\Client\Struct\Fare
 * @author dieter <dermikagh@gmail.com>
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

        $msg = new PricePNRWithBookingClass13($opt);

        $validatingCarrierPo = new PricingOptionGroup(PricingOptionKey::OPTION_VALIDATING_CARRIER);
        $validatingCarrierPo->carrierInformation = new CarrierInformation('BA');

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOptionGroup, $validatingCarrierPo));

        $currencyOverridePo = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_CURRENCY_OVERRIDE);
        $currencyOverridePo->currency = new Currency('EUR');

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOptionGroup, $currencyOverridePo));

        $fareBasisOverridePo = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_BASIS_SIMPLE_OVERRIDE);
        $fareBasisOverridePo->optionDetail = new OptionDetail();
        $fareBasisOverridePo->optionDetail->criteriaDetails[] = new CriteriaDetails('QNC469W2');
        $fareBasisOverridePo->paxSegTstReference = new PaxSegTstReference(null, [2 => FareBasis::SEGREFTYPE_SEGMENT]);

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOptionGroup, $fareBasisOverridePo));

        $negofarePo = new PricingOptionGroup(PricingOptionKey::OPTION_NEGOTIATED_FARES);

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOptionGroup, $negofarePo));
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

        $msg = new PricePNRWithBookingClass13($opt);

        $validatingCarrierPo = new PricingOptionGroup(PricingOptionKey::OPTION_VALIDATING_CARRIER);
        $validatingCarrierPo->carrierInformation = new CarrierInformation('BA');

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOptionGroup, $validatingCarrierPo));

        $currencyOverridePo = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_CURRENCY_OVERRIDE);
        $currencyOverridePo->currency = new Currency('EUR');

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOptionGroup, $currencyOverridePo));

        $fareBasisOverridePo = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_BASIS_SIMPLE_OVERRIDE);
        $fareBasisOverridePo->optionDetail = new OptionDetail();
        $fareBasisOverridePo->optionDetail->criteriaDetails[] = new CriteriaDetails('QNC469W2');
        $fareBasisOverridePo->paxSegTstReference = new PaxSegTstReference([new PaxSegRef(['type'=> PaxSegRef::TYPE_SEGMENT, 'reference' => 2])]);

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOptionGroup, $fareBasisOverridePo));

        $negofarePo = new PricingOptionGroup(PricingOptionKey::OPTION_NEGOTIATED_FARES);

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOptionGroup, $negofarePo));
    }

    public function testCanDoPricePnrCallWithOverrideOptionWithCriteriaParams()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'validatingCarrier' => 'BA',
            'currencyOverride' => 'EUR',
            'overrideOptionsWithCriteria' => [
                [
                    'key' => 'SBF',
                    'optionDetail' => '1'
                ]
            ]
        ]);

        $msg = new PricePNRWithBookingClass13($opt);

        $validatingCarrierPo = new PricingOptionGroup(PricingOptionKey::OPTION_VALIDATING_CARRIER);
        $validatingCarrierPo->carrierInformation = new CarrierInformation('BA');

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOptionGroup, $validatingCarrierPo));

        $currencyOverridePo = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_CURRENCY_OVERRIDE);
        $currencyOverridePo->currency = new Currency('EUR');

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOptionGroup, $currencyOverridePo));

        $negofarePo = new PricingOptionGroup('SBF','1');

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOptionGroup, $negofarePo));
    }

    public function testCanDoPricePnrCallWithNoOptions()
    {
        $opt = new FarePricePnrWithBookingClassOptions();

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $msg->pricingOptionGroup);
        $this->assertEquals('NOP', $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertNull($msg->pricingOptionGroup[0]->currency);
        $this->assertNull($msg->pricingOptionGroup[0]->carrierInformation);
        $this->assertNull($msg->pricingOptionGroup[0]->dateInformation);
        $this->assertNull($msg->pricingOptionGroup[0]->formOfPaymentInformation);
        $this->assertNull($msg->pricingOptionGroup[0]->frequentFlyerInformation);
        $this->assertNull($msg->pricingOptionGroup[0]->locationInformation);
        $this->assertNull($msg->pricingOptionGroup[0]->monetaryInformation);
        $this->assertNull($msg->pricingOptionGroup[0]->optionDetail);
        $this->assertNull($msg->pricingOptionGroup[0]->paxSegTstReference);
        $this->assertNull($msg->pricingOptionGroup[0]->penDisInformation);
        $this->assertEmpty($msg->pricingOptionGroup[0]->taxInformation);
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

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(4, $msg->pricingOptionGroup);

        $validatingCarrierPo = new PricingOptionGroup(PricingOptionKey::OPTION_VALIDATING_CARRIER);
        $validatingCarrierPo->carrierInformation = new CarrierInformation('BA');

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOptionGroup, $validatingCarrierPo));

        $currencyOverridePo = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_CURRENCY_OVERRIDE);
        $currencyOverridePo->currency = new Currency('EUR');

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOptionGroup, $currencyOverridePo));

        $fareBasisOverridePo = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_BASIS_SIMPLE_OVERRIDE);
        $fareBasisOverridePo->optionDetail = new OptionDetail();
        $fareBasisOverridePo->optionDetail->criteriaDetails[] = new CriteriaDetails('QNC469W2');
        $fareBasisOverridePo->paxSegTstReference = new PaxSegTstReference(
            [new PaxSegRef(['type'=> PaxSegRef::TYPE_SEGMENT, 'reference' => 2])]
        );

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOptionGroup, $fareBasisOverridePo));

        $negofarePo = new PricingOptionGroup(PricingOptionKey::OPTION_NEGOTIATED_FARES);

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOptionGroup, $negofarePo));
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

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $msg->pricingOptionGroup);

        $this->assertEquals(PricingOptionKey::OPTION_OB_FEES, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(PenDisInformation::QUAL_OB_FEES, $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyQualifier);
        $this->assertCount(1, $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails);
        $this->assertEquals(DiscountPenaltyDetails::FUNCTION_INCLUDE_FEE, $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->function);
        $this->assertEquals(10, $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->amount);
        $this->assertEquals('USD', $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->currency);
        $this->assertEquals(DiscountPenaltyDetails::AMOUNTTYPE_FIXED_WHOLE_AMOUNT, $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->amountType);
        $this->assertEquals('FC1', $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->rate);
    }

    public function testCanDoPricePnrCallWithPricingLogic()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'pricingLogic' => FarePricePnrWithBookingClassOptions::PRICING_LOGIC_IATA
        ]);

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $msg->pricingOptionGroup);

        $this->assertEquals(PricingOptionKey::OPTION_PRICING_LOGIC, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(1, $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails);
        $this->assertEquals('IATA', $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails[0]->attributeType);
    }

    public function testCanDoPricePnrCallWithCorpNegoFare()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'corporateNegoFare' => '012345'
        ]);

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $msg->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_CORPORATE_NEGOTIATED_FARES, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(1, $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails);
        $this->assertEquals('012345', $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails[0]->attributeType);
    }

    public function testCanDoPricePnrCallWithCorpUniFares()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'corporateUniFares' => ['012345', 'AMADEUS']
        ]);

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $msg->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_CORPORATE_UNIFARES, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(2, $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails);
        $this->assertEquals('012345', $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails[0]->attributeType);
        $this->assertEquals('AMADEUS', $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails[1]->attributeType);
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

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $msg->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_PASSENGER_DISCOUNT_PTC, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(PenDisInformation::QUAL_DISCOUNT, $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyQualifier);
        $this->assertCount(3, $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails);
        $this->assertEquals('YTH', $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->rate);
        $this->assertNull($msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->amount);
        $this->assertNull($msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->amountType);
        $this->assertNull($msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->currency);
        $this->assertNull($msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->function);

        $this->assertEquals('AD20', $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[1]->rate);
        $this->assertNull($msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[1]->amount);
        $this->assertNull($msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[1]->amountType);
        $this->assertNull($msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[1]->currency);
        $this->assertNull($msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[1]->function);

        $this->assertEquals('MIL', $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[2]->rate);
        $this->assertNull($msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[2]->amount);
        $this->assertNull($msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[2]->amountType);
        $this->assertNull($msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[2]->currency);
        $this->assertNull($msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[2]->function);

        $this->assertCount(2, $msg->pricingOptionGroup[0]->paxSegTstReference->referenceDetails);
        $this->assertEquals(1, $msg->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_REFERENCE, $msg->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(4, $msg->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[1]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $msg->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[1]->type);
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

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(2, $msg->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_POINT_OF_SALE_OVERRIDE, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(LocationInformation::TYPE_POINT_OF_SALE, $msg->pricingOptionGroup[0]->locationInformation->locationType);
        $this->assertEquals('LON', $msg->pricingOptionGroup[0]->locationInformation->firstLocationDetails->code);
        $this->assertEquals(PricingOptionKey::OPTION_POINT_OF_TICKETING_OVERRIDE, $msg->pricingOptionGroup[1]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(LocationInformation::TYPE_POINT_OF_TICKETING, $msg->pricingOptionGroup[1]->locationInformation->locationType);
        $this->assertEquals('MAN', $msg->pricingOptionGroup[1]->locationInformation->firstLocationDetails->code);
    }

    public function testCanDoPricePnrCallWithTicketType()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'ticketType' => FarePricePnrWithBookingClassOptions::TICKET_TYPE_ELECTRONIC
        ]);

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $msg->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_TICKET_TYPE, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(1, $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails);
        $this->assertEquals(FarePricePnrWithBookingClassOptions::TICKET_TYPE_ELECTRONIC, $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails[0]->attributeType);
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

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $msg->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_ADD_TAX, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(2, $msg->pricingOptionGroup[0]->taxInformation);
        $this->assertEquals(TaxInformation::QUALIFIER_TAX, $msg->pricingOptionGroup[0]->taxInformation[0]->taxQualifier);
        $this->assertEquals('GO', $msg->pricingOptionGroup[0]->taxInformation[0]->taxNature);
        $this->assertEquals(50, $msg->pricingOptionGroup[0]->taxInformation[0]->taxData->taxRate);
        $this->assertEquals(TaxData::QUALIFIER_AMOUNT, $msg->pricingOptionGroup[0]->taxInformation[0]->taxData->taxValueQualifier);
        $this->assertEquals('ZV', $msg->pricingOptionGroup[0]->taxInformation[0]->taxType->isoCountry);

        $this->assertEquals(TaxInformation::QUALIFIER_TAX, $msg->pricingOptionGroup[0]->taxInformation[1]->taxQualifier);
        $this->assertNull($msg->pricingOptionGroup[0]->taxInformation[1]->taxNature);
        $this->assertEquals(10, $msg->pricingOptionGroup[0]->taxInformation[1]->taxData->taxRate);
        $this->assertEquals(TaxData::QUALIFIER_PERCENTAGE, $msg->pricingOptionGroup[0]->taxInformation[1]->taxData->taxValueQualifier);
        $this->assertEquals('FR', $msg->pricingOptionGroup[0]->taxInformation[1]->taxType->isoCountry);
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

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $msg->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_EXEMPT_FROM_TAX, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(1, $msg->pricingOptionGroup[0]->taxInformation);
        $this->assertEquals(TaxInformation::QUALIFIER_TAX, $msg->pricingOptionGroup[0]->taxInformation[0]->taxQualifier);
        $this->assertEquals('GO', $msg->pricingOptionGroup[0]->taxInformation[0]->taxNature);
        $this->assertNull($msg->pricingOptionGroup[0]->taxInformation[0]->taxData);
        $this->assertEquals('ZV', $msg->pricingOptionGroup[0]->taxInformation[0]->taxType->isoCountry);
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

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $msg->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_PAX_SEGMENT_TST_SELECTION, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(4, $msg->pricingOptionGroup[0]->paxSegTstReference->referenceDetails);

        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_INFANT, $msg->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(1, $msg->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_ADULT, $msg->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[1]->type);
        $this->assertEquals(2, $msg->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[1]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_REFERENCE, $msg->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[2]->type);
        $this->assertEquals(3, $msg->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[2]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $msg->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[3]->type);
        $this->assertEquals(1, $msg->pricingOptionGroup[0]->paxSegTstReference->referenceDetails[3]->value);

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

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $msg->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_PAST_DATE_PRICING, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(DateInformation::OPT_DATE_OVERRIDE, $msg->pricingOptionGroup[0]->dateInformation->businessSemantic);
        $this->assertEquals("2012", $msg->pricingOptionGroup[0]->dateInformation->dateTime->year);
        $this->assertEquals("06", $msg->pricingOptionGroup[0]->dateInformation->dateTime->month);
        $this->assertEquals("27", $msg->pricingOptionGroup[0]->dateInformation->dateTime->day);
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

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(2, $msg->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_CORPORATE_UNIFARES, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(2, $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails);
        $this->assertEquals('012345', $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails[0]->attributeType);
        $this->assertEquals('456789', $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails[1]->attributeType);

        $this->assertEquals(PricingOptionKey::OPTION_AWARD_PRICING, $msg->pricingOptionGroup[1]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals('6X', $msg->pricingOptionGroup[1]->carrierInformation->companyIdentification->otherCompany);
        $this->assertCount(1, $msg->pricingOptionGroup[1]->frequentFlyerInformation->frequentTravellerDetails);
        $this->assertEquals('GOLD', $msg->pricingOptionGroup[1]->frequentFlyerInformation->frequentTravellerDetails[0]->tierLevel);
        $this->assertNull($msg->pricingOptionGroup[1]->frequentFlyerInformation->frequentTravellerDetails[0]->carrier);
        $this->assertNull($msg->pricingOptionGroup[1]->frequentFlyerInformation->frequentTravellerDetails[0]->number);
        $this->assertNull($msg->pricingOptionGroup[1]->frequentFlyerInformation->frequentTravellerDetails[0]->priorityCode);
    }

    public function testCanDoPricePnrCallWithFormOfPaymentOverride()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
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

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $msg->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_FORM_OF_PAYMENT, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(\Amadeus\Client\Struct\Fare\PricePnr13\FormOfPayment::TYPE_CREDIT_CARD, $msg->pricingOptionGroup[0]->formOfPaymentInformation->formOfPayment->type);
        $this->assertEquals(10, $msg->pricingOptionGroup[0]->formOfPaymentInformation->formOfPayment->amount);
        $this->assertEquals('400000', $msg->pricingOptionGroup[0]->formOfPaymentInformation->formOfPayment->creditCardNumber);
        $this->assertNull($msg->pricingOptionGroup[0]->formOfPaymentInformation->formOfPayment->sourceOfApproval);
        $this->assertNull($msg->pricingOptionGroup[0]->formOfPaymentInformation->formOfPayment->authorisedAmount);
        $this->assertNull($msg->pricingOptionGroup[0]->formOfPaymentInformation->formOfPayment->indicator);
        $this->assertNull($msg->pricingOptionGroup[0]->formOfPaymentInformation->formOfPayment->addressVerification);
        $this->assertNull($msg->pricingOptionGroup[0]->formOfPaymentInformation->formOfPayment->approvalCode);
        $this->assertNull($msg->pricingOptionGroup[0]->formOfPaymentInformation->formOfPayment->customerAccount);
        $this->assertNull($msg->pricingOptionGroup[0]->formOfPaymentInformation->formOfPayment->expiryDate);
        $this->assertNull($msg->pricingOptionGroup[0]->formOfPaymentInformation->formOfPayment->extendedPayment);
        $this->assertNull($msg->pricingOptionGroup[0]->formOfPaymentInformation->formOfPayment->fopFreeText);
        $this->assertNull($msg->pricingOptionGroup[0]->formOfPaymentInformation->formOfPayment->membershipStatus);
        $this->assertNull($msg->pricingOptionGroup[0]->formOfPaymentInformation->formOfPayment->pinCode);
        $this->assertNull($msg->pricingOptionGroup[0]->formOfPaymentInformation->formOfPayment->pinCodeType);
        $this->assertNull($msg->pricingOptionGroup[0]->formOfPaymentInformation->formOfPayment->transactionInfo);
        $this->assertNull($msg->pricingOptionGroup[0]->formOfPaymentInformation->formOfPayment->vendorCode);
        $this->assertCount(1, $msg->pricingOptionGroup[0]->formOfPaymentInformation->otherFormOfPayment);
        $this->assertEquals(\Amadeus\Client\Struct\Fare\PricePnr13\FormOfPayment::TYPE_CASH, $msg->pricingOptionGroup[0]->formOfPaymentInformation->otherFormOfPayment[0]->type);
        $this->assertNull($msg->pricingOptionGroup[0]->formOfPaymentInformation->otherFormOfPayment[0]->amount);
        $this->assertNull($msg->pricingOptionGroup[0]->formOfPaymentInformation->otherFormOfPayment[0]->creditCardNumber);
    }

    /**
     * Test FarePricePnrWithBookingClassOptions with *fareFamily* specified,
     * the value *FLEX* should be transferred to the attributeDescription value.
     *
     * @throws \Amadeus\Client\RequestCreator\MessageVersionUnsupportedException
     */
    public function testPricePnrCallWithFareFamily()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'fareFamily' => 'FLEX'
        ]);

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $msg->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_FARE_FAMILY, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(1, $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails);
        $this->assertEquals('FF', $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails[0]->attributeType);
        $this->assertEquals('FLEX', $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails[0]->attributeDescription);
    }

    /**
     * Test FarePricePnrWithBookingClassOptions with *fareFamily* specified per segments,
     * the value *FLEX* should be transferred to the attributeDescription value for segments 1 and 2,
     * the value *ECO* should be transferred to the attributeDescription value for segments 3 and 4.
     *
     * @throws \Amadeus\Client\RequestCreator\MessageVersionUnsupportedException
     */
    public function testPricePnrCallWithMultipleFareFamily()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'fareFamily' => [
                new FareFamily([
                    'fareFamily' => 'FLEX',
                    'paxSegRefs' => [
                        new PaxSegRef([
                            'type' => PaxSegRef::TYPE_SEGMENT,
                            'reference' => 1
                        ]),
                        new PaxSegRef([
                            'type' => PaxSegRef::TYPE_SEGMENT,
                            'reference' => 2
                        ])
                    ]
                ]),
                new FareFamily([
                    'fareFamily' => 'ECOFLEX',
                    'paxSegRefs' => [
                        new PaxSegRef([
                            'type' => PaxSegRef::TYPE_SEGMENT,
                            'reference' => 3
                        ]),
                        new PaxSegRef([
                            'type' => PaxSegRef::TYPE_SEGMENT,
                            'reference' => 4
                        ])
                    ]
                ])
                ]
        ]);

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(2, $msg->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_FARE_FAMILY, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(1, $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails);
        $this->assertEquals('FF', $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails[0]->attributeType);
        $this->assertEquals('FLEX', $msg->pricingOptionGroup[0]->optionDetail->criteriaDetails[0]->attributeDescription);
        $this->assertCount(1, $msg->pricingOptionGroup[1]->optionDetail->criteriaDetails);
        $this->assertEquals('FF', $msg->pricingOptionGroup[1]->optionDetail->criteriaDetails[0]->attributeType);
        $this->assertEquals('ECOFLEX', $msg->pricingOptionGroup[1]->optionDetail->criteriaDetails[0]->attributeDescription);
    }

    public function testCanDoPricePnrCallWithZapOff()
    {
        $opt =  new FarePricePnrWithBookingClassOptions([
            'zapOff' => [
                new ZapOff([
                    'applyTo' => ZapOff::FUNCTION_TOTAL_FARE,
                    'rate' => 'CH50',
                    'amount' => 120,
                    'paxSegRefs' => [
                        new PaxSegRef([
                            'type' => PaxSegRef::TYPE_SEGMENT,
                            'reference' => 1
                        ]),
                        new PaxSegRef([
                            'type' => PaxSegRef::TYPE_SEGMENT,
                            'reference' => 2
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $msg->pricingOptionGroup);

        $this->assertEquals(PricingOptionKey::OPTION_ZAP_OFF, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(PenDisInformation::QUAL_ZAPOFF_DISCOUNT, $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyQualifier);
        $this->assertCount(1, $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails);
        $this->assertEquals(DiscountPenaltyDetails::FUNCTION_TOTAL_FARE, $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->function);
        $this->assertEquals(120, $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->amount);
        $this->assertEquals(DiscountPenaltyDetails::AMOUNTTYPE_FIXED_WHOLE_AMOUNT, $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->amountType);
        $this->assertEquals('CH50', $msg->pricingOptionGroup[0]->penDisInformation->discountPenaltyDetails[0]->rate);
    }
}
