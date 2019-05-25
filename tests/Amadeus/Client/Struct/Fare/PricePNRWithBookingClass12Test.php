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
use Amadeus\Client\RequestOptions\Fare\PricePnr\Cabin;
use Amadeus\Client\RequestOptions\Fare\PricePnr\ExemptTax;
use Amadeus\Client\RequestOptions\Fare\PricePnr\FareBasis;
use Amadeus\Client\RequestOptions\Fare\PricePnr\ObFee;
use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;
use Amadeus\Client\RequestOptions\Fare\PricePnr\Tax;
use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
use Amadeus\Client\Struct\Fare\PricePnr12\AttributeDetails;
use Amadeus\Client\Struct\Fare\PricePnr12\CityDetail;
use Amadeus\Client\Struct\Fare\PricePnr12\CompanyDetails;
use Amadeus\Client\Struct\Fare\PricePnr12\DateOverride;
use Amadeus\Client\Struct\Fare\PricePnr12\PenDisInformation;
use Amadeus\Client\Struct\Fare\PricePnr12\RefDetails;
use Amadeus\Client\Struct\Fare\PricePnr12\TaxDetails;
use Amadeus\Client\Struct\Fare\PricePnr12\TaxIdentification;
use Amadeus\Client\Struct\Fare\PricePnr13\CriteriaDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\TaxData;
use Amadeus\Client\Struct\Fare\PricePNRWithBookingClass12;
use Test\Amadeus\BaseTestCase;

/**
 * @package Amadeus\Client\Struct\Fare
 * @author dieter <dermikagh@gmail.com>
 */
class PricePNRWithBookingClass12Test extends BaseTestCase
{
    public function testCanDoPricePnrCallWithLegacyFareBasisParams()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'overrideOptions' => [FarePricePnrWithBookingClassOptions::OVERRIDE_FAREBASIS],
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

        $message = new PricePNRWithBookingClass12($opt);

        $this->assertEquals(FarePricePnrWithBookingClassOptions::OVERRIDE_FAREBASIS, $message->overrideInformation->attributeDetails[0]->attributeType);
        $this->assertEquals('QNC', $message->pricingFareBase[0]->fareBasisOptions->fareBasisDetails->primaryCode);
        $this->assertEquals('469W2', $message->pricingFareBase[0]->fareBasisOptions->fareBasisDetails->fareBasisCode);
        $this->assertEquals(2, $message->pricingFareBase[0]->fareBasisSegReference[0]->refDetails->refNumber);
        $this->assertEquals(RefDetails::QUAL_SEGMENT_REFERENCE, $message->pricingFareBase[0]->fareBasisSegReference[0]->refDetails->refQualifier);
        $this->assertEquals('BA', $message->validatingCarrier->carrierInformation->carrierCode);
        $this->assertEquals('EUR', $message->currencyOverride->firstRateDetail->currencyCode);
    }

    public function testCanDoPricePnrCallWithNewFareBasisParams()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'overrideOptions' => [FarePricePnrWithBookingClassOptions::OVERRIDE_FAREBASIS],
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

        $message = new PricePNRWithBookingClass12($opt);

        $this->assertEquals(FarePricePnrWithBookingClassOptions::OVERRIDE_FAREBASIS, $message->overrideInformation->attributeDetails[0]->attributeType);
        $this->assertEquals('QNC', $message->pricingFareBase[0]->fareBasisOptions->fareBasisDetails->primaryCode);
        $this->assertEquals('469W2', $message->pricingFareBase[0]->fareBasisOptions->fareBasisDetails->fareBasisCode);
        $this->assertEquals(2, $message->pricingFareBase[0]->fareBasisSegReference[0]->refDetails->refNumber);
        $this->assertEquals(RefDetails::QUAL_SEGMENT_REFERENCE, $message->pricingFareBase[0]->fareBasisSegReference[0]->refDetails->refQualifier);
        $this->assertEquals('BA', $message->validatingCarrier->carrierInformation->carrierCode);
        $this->assertEquals('EUR', $message->currencyOverride->firstRateDetail->currencyCode);
    }

    public function testCanDoPricePnrCallWithNoOptions()
    {
        $opt = new FarePricePnrWithBookingClassOptions();

        $message = new PricePNRWithBookingClass12($opt);

        $this->assertCount(1, $message->overrideInformation->attributeDetails);
        $this->assertEquals(FarePricePnrWithBookingClassOptions::OVERRIDE_NO_OPTION, $message->overrideInformation->attributeDetails[0]->attributeType);
    }

    public function testCanThrowExceptionWhenDoPricePnrCallWithObFees()
    {
        $this->setExpectedException(
            '\Amadeus\Client\Struct\OptionNotSupportedException',
            'OB Fees option not supported in version 12 or lower'
        );

        $opt = new FarePricePnrWithBookingClassOptions([
            'obFees' => [
                new ObFee([
                    'rate' => 'dummy'
                ])
            ]
        ]);

        new PricePNRWithBookingClass12($opt);
    }

    public function testCanThrowExceptionWhenDoPricePnrCallWithPricingLogic()
    {
        $this->setExpectedException(
            '\Amadeus\Client\Struct\OptionNotSupportedException',
            'Pricing Logic option not supported in version 12 or lower'
        );

        $opt = new FarePricePnrWithBookingClassOptions([
            'pricingLogic' => FarePricePnrWithBookingClassOptions::PRICING_LOGIC_IATA
        ]);

        new PricePNRWithBookingClass12($opt);
    }

    public function testCanThrowExceptionWhenDoPricePnrCallWithOverrideOptionsWithCriteria()
    {
        $this->setExpectedException(
            '\Amadeus\Client\Struct\OptionNotSupportedException',
            'Override Options With Criteria are not supported in version 12 or lower'
        );

        $opt = new FarePricePnrWithBookingClassOptions([
            'overrideOptionsWithCriteria' => [
                [
                    'key' => 'SBF',
                    'optionDetail' => '1'
                ]
            ]
        ]);

        new PricePNRWithBookingClass12($opt);
    }

    public function testCanDoPricePnrCallWithCorpNegoFare()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'corporateNegoFare' => '012345'
        ]);

        $message = new PricePNRWithBookingClass12($opt);

        $this->assertCount(1, $message->overrideInformation->attributeDetails);
        $this->assertEquals(FarePricePnrWithBookingClassOptions::OVERRIDE_FARETYPE_CORPNR, $message->overrideInformation->attributeDetails[0]->attributeType);
        $this->assertEquals('012345', $message->overrideInformation->attributeDetails[0]->attributeDescription);
    }


    public function testCanDoPricePnrCallWithCorpUniFares()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'corporateUniFares' => ['012345', 'AMADEUS']
        ]);

        $message = new PricePNRWithBookingClass12($opt);

        $this->assertCount(2, $message->overrideInformation->attributeDetails);
        $this->assertEquals(FarePricePnrWithBookingClassOptions::OVERRIDE_FARETYPE_CORPUNI, $message->overrideInformation->attributeDetails[0]->attributeType);
        $this->assertEquals('012345', $message->overrideInformation->attributeDetails[0]->attributeDescription);
        $this->assertEquals(FarePricePnrWithBookingClassOptions::OVERRIDE_FARETYPE_CORPUNI, $message->overrideInformation->attributeDetails[1]->attributeType);
        $this->assertEquals('AMADEUS', $message->overrideInformation->attributeDetails[1]->attributeDescription);
    }

    public function testCanDoPricePnrCallWithPaxDiscountCodes()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'paxDiscountCodes' => ['FIF', 'CH'],
            'paxDiscountCodeRefs' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 1
                ])
            ]
        ]);

        $message = new PricePNRWithBookingClass12($opt);

        $this->assertCount(1, $message->overrideInformation->attributeDetails);
        $this->assertEquals(FarePricePnrWithBookingClassOptions::OVERRIDE_NO_OPTION, $message->overrideInformation->attributeDetails[0]->attributeType);
        $this->assertNull($message->overrideInformation->attributeDetails[0]->attributeDescription);

        $this->assertCount(1, $message->discountInformation);

        $this->assertEquals(PenDisInformation::QUAL_DISCOUNT, $message->discountInformation[0]->penDisInformation->infoQualifier);
        $this->assertCount(2, $message->discountInformation[0]->penDisInformation->penDisData);
        $this->assertEquals('FIF', $message->discountInformation[0]->penDisInformation->penDisData[0]->discountCode);
        $this->assertNull($message->discountInformation[0]->penDisInformation->penDisData[0]->penaltyAmount);
        $this->assertNull($message->discountInformation[0]->penDisInformation->penDisData[0]->penaltyCurrency);
        $this->assertNull($message->discountInformation[0]->penDisInformation->penDisData[0]->penaltyQualifier);
        $this->assertNull($message->discountInformation[0]->penDisInformation->penDisData[0]->penaltyType);
        $this->assertEquals('CH', $message->discountInformation[0]->penDisInformation->penDisData[1]->discountCode);
        $this->assertNull($message->discountInformation[0]->penDisInformation->penDisData[1]->penaltyAmount);
        $this->assertNull($message->discountInformation[0]->penDisInformation->penDisData[1]->penaltyCurrency);
        $this->assertNull($message->discountInformation[0]->penDisInformation->penDisData[1]->penaltyQualifier);
        $this->assertNull($message->discountInformation[0]->penDisInformation->penDisData[1]->penaltyType);

        $this->assertCount(1, $message->discountInformation[0]->referenceQualifier->refDetails);
        $this->assertEquals(1, $message->discountInformation[0]->referenceQualifier->refDetails[0]->refNumber);
        $this->assertEquals(RefDetails::QUAL_PASSENGER, $message->discountInformation[0]->referenceQualifier->refDetails[0]->refQualifier);
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

        $message = new PricePNRWithBookingClass12($opt);

        $this->assertCount(1, $message->overrideInformation->attributeDetails);
        $this->assertEquals(FarePricePnrWithBookingClassOptions::OVERRIDE_NO_OPTION, $message->overrideInformation->attributeDetails[0]->attributeType);
        $this->assertNull($message->overrideInformation->attributeDetails[0]->attributeDescription);


        $this->assertCount(2, $message->cityOverride->cityDetail);

        $this->assertEquals('LON', $message->cityOverride->cityDetail[0]->cityCode);
        $this->assertEquals(CityDetail::QUAL_POINT_OF_SALE, $message->cityOverride->cityDetail[0]->cityQualifier);

        $this->assertEquals('MAN', $message->cityOverride->cityDetail[1]->cityCode);
        $this->assertEquals(CityDetail::QUAL_POINT_OF_TICKETING, $message->cityOverride->cityDetail[1]->cityQualifier);

        $this->assertNull($message->currencyOverride);
        $this->assertNull($message->dateOverride);
        $this->assertEmpty($message->discountInformation);
        $this->assertNull($message->paxSegReference);
        $this->assertEmpty($message->pricingFareBase);
        $this->assertEmpty($message->taxDetails);
    }

    /**
     * Point of Ticketing override
     */
    public function testCanDoPricePnrCallWithPotOverride()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'pointOfTicketingOverride' => 'MAN'
        ]);

        $message = new PricePNRWithBookingClass12($opt);

        $this->assertCount(1, $message->overrideInformation->attributeDetails);
        $this->assertEquals(FarePricePnrWithBookingClassOptions::OVERRIDE_NO_OPTION, $message->overrideInformation->attributeDetails[0]->attributeType);
        $this->assertNull($message->overrideInformation->attributeDetails[0]->attributeDescription);


        $this->assertCount(1, $message->cityOverride->cityDetail);

        $this->assertEquals('MAN', $message->cityOverride->cityDetail[0]->cityCode);
        $this->assertEquals(CityDetail::QUAL_POINT_OF_TICKETING, $message->cityOverride->cityDetail[0]->cityQualifier);

        $this->assertNull($message->currencyOverride);
        $this->assertNull($message->dateOverride);
        $this->assertEmpty($message->discountInformation);
        $this->assertNull($message->paxSegReference);
        $this->assertEmpty($message->pricingFareBase);
        $this->assertEmpty($message->taxDetails);
    }

    public function testCanDoPricePnrCallWithTicketType()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'ticketType' => FarePricePnrWithBookingClassOptions::TICKET_TYPE_ELECTRONIC
        ]);

        $message = new PricePNRWithBookingClass12($opt);

        $this->assertCount(1, $message->overrideInformation->attributeDetails);
        $this->assertEquals(AttributeDetails::OVERRIDE_ELECTRONIC_TICKET, $message->overrideInformation->attributeDetails[0]->attributeType);
        $this->assertNull($message->overrideInformation->attributeDetails[0]->attributeDescription);

    }

    public function testCanDoPricePnrCallWithAddTaxes()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'taxes' => [
                new Tax([
                    'taxNature' => 'ADT',
                    'countryCode' => 'ZV',
                    'amount' => 12
                ])
            ]
        ]);

        $message = new PricePNRWithBookingClass12($opt);

        $this->assertCount(1, $message->overrideInformation->attributeDetails);
        $this->assertEquals(AttributeDetails::OVERRIDE_NO_OPTION, $message->overrideInformation->attributeDetails[0]->attributeType);
        $this->assertNull($message->overrideInformation->attributeDetails[0]->attributeDescription);

        $this->assertCount(1, $message->taxDetails);
        $this->assertEquals(TaxIdentification::IDENT_ADD_TAX, $message->taxDetails[0]->taxIdentification->taxIdentifier);
        $this->assertEquals(TaxDetails::QUAL_TAX, $message->taxDetails[0]->taxQualifier);
        $this->assertEquals('ZV', $message->taxDetails[0]->taxType->isoCountry);
        $this->assertEquals('ADT', $message->taxDetails[0]->taxNature);
        $this->assertEquals(12, $message->taxDetails[0]->taxData->taxRate);
        $this->assertEquals(TaxData::QUALIFIER_AMOUNT, $message->taxDetails[0]->taxData->taxValueQualifier);
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

        $message = new PricePNRWithBookingClass12($opt);

        $this->assertCount(1, $message->overrideInformation->attributeDetails);
        $this->assertEquals(AttributeDetails::OVERRIDE_NO_OPTION, $message->overrideInformation->attributeDetails[0]->attributeType);
        $this->assertNull($message->overrideInformation->attributeDetails[0]->attributeDescription);

        $this->assertCount(1, $message->taxDetails);
        $this->assertEquals(TaxIdentification::IDENT_EXEMPT_TAX, $message->taxDetails[0]->taxIdentification->taxIdentifier);
        $this->assertEquals('ZV', $message->taxDetails[0]->taxType->isoCountry);
        $this->assertEquals('GO', $message->taxDetails[0]->taxNature);
        $this->assertNull($message->taxDetails[0]->taxData);
    }

    /**
     * Price only segments 3 and 4 for passenger 1.
     */
    public function testCanDoPricePnrCallWithPaxSegRefs()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'references' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_SEGMENT,
                    'reference' => 3
                ]),
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_SEGMENT,
                    'reference' => 4
                ]),
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 1
                ]),
            ]
        ]);

        $message = new PricePNRWithBookingClass12($opt);

        $this->assertCount(1, $message->overrideInformation->attributeDetails);
        $this->assertEquals(AttributeDetails::OVERRIDE_NO_OPTION, $message->overrideInformation->attributeDetails[0]->attributeType);
        $this->assertNull($message->overrideInformation->attributeDetails[0]->attributeDescription);

        $this->assertEmpty($message->taxDetails);

        $this->assertCount(3, $message->paxSegReference->refDetails);
        $this->assertEquals(3, $message->paxSegReference->refDetails[0]->refNumber);
        $this->assertEquals(RefDetails::QUAL_SEGMENT_REFERENCE, $message->paxSegReference->refDetails[0]->refQualifier);
        $this->assertEquals(4, $message->paxSegReference->refDetails[1]->refNumber);
        $this->assertEquals(RefDetails::QUAL_SEGMENT_REFERENCE, $message->paxSegReference->refDetails[1]->refQualifier);
        $this->assertEquals(1, $message->paxSegReference->refDetails[2]->refNumber);
        $this->assertEquals(RefDetails::QUAL_PASSENGER, $message->paxSegReference->refDetails[2]->refQualifier);
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
                "2004-05-12T00:00:00+0000",
                new \DateTimeZone('UTC')
            )
        ]);

        $message = new PricePNRWithBookingClass12($opt);

        $this->assertCount(1, $message->overrideInformation->attributeDetails);
        $this->assertEquals(AttributeDetails::OVERRIDE_NO_OPTION, $message->overrideInformation->attributeDetails[0]->attributeType);
        $this->assertNull($message->overrideInformation->attributeDetails[0]->attributeDescription);

        $this->assertEmpty($message->taxDetails);
        $this->assertNull($message->paxSegReference);

        $this->assertEquals(DateOverride::OPT_DATE_OVERRIDE, $message->dateOverride->businessSemantic);
        $this->assertEquals('12', $message->dateOverride->dateTime->day);
        $this->assertEquals('05', $message->dateOverride->dateTime->month);
        $this->assertEquals('04', $message->dateOverride->dateTime->year);
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

        $message = new PricePNRWithBookingClass12($opt);


        $this->assertEmpty($message->taxDetails);
        $this->assertNull($message->paxSegReference);
        $this->assertNull($message->dateOverride);

        $this->assertCount(2, $message->overrideInformation->attributeDetails);
        $this->assertEquals(FarePricePnrWithBookingClassOptions::OVERRIDE_FARETYPE_CORPUNI, $message->overrideInformation->attributeDetails[0]->attributeType);
        $this->assertEquals('012345', $message->overrideInformation->attributeDetails[0]->attributeDescription);
        $this->assertEquals(FarePricePnrWithBookingClassOptions::OVERRIDE_FARETYPE_CORPUNI, $message->overrideInformation->attributeDetails[1]->attributeType);
        $this->assertEquals('456789', $message->overrideInformation->attributeDetails[1]->attributeDescription);

        $this->assertEquals('6X', $message->carrierAgreements->companyDetails[0]->company);
        $this->assertEquals(CompanyDetails::QUAL_AWARD_PUBLISHING, $message->carrierAgreements->companyDetails[0]->qualifier);

        $this->assertEquals('GOLD', $message->frequentFlyerInformation->flequentFlyerdetails->tierLevel);
    }
}
