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

use Amadeus\Client\RequestOptions\Fare\InformativeBestPricingWithoutPnr\Cabin;
use Amadeus\Client\RequestOptions\Fare\InformativeBestPricingWithoutPnr\CabinOptions;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\Passenger;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\PricingOptions;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\Segment;
use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;
use Amadeus\Client\RequestOptions\FareInformativeBestPricingWithoutPnrOptions;
use Amadeus\Client\Struct\Fare\InformativeBestPricingWithoutPNR13;
use Amadeus\Client\Struct\Fare\PricePnr13\FirstCurrencyDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\PenDisInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionKey;
use Amadeus\Client\Struct\Fare\PricePnr13\ReferenceDetails;
use Test\Amadeus\BaseTestCase;

/**
 * InformativeBestPricingWithoutPNR13Test
 *
 * @package Test\Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class InformativeBestPricingWithoutPNR13Test extends BaseTestCase
{
    /**
     * Here is a complete pricing example of a CDG-LHR-CDG trip for 2 passengers, with options below:
     *
     * - take into account published fares (RP)
     * - take into account Unifares (RU)
     * - use PTC "CH" for passenger 2 (PAX)
     * - convert fare into USD (FCO)
     */
    public function test51OperationFullExample()
    {
        $opt = new FareInformativeBestPricingWithoutPnrOptions([
            'passengers' => [
                new Passenger([
                    'tattoos' => [1, 2],
                    'type' => Passenger::TYPE_ADULT
                ])
            ],
            'segments' => [
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2013-12-01 07:30:00', new \DateTimeZone('UTC')),
                    'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2013-12-01 07:50:00', new \DateTimeZone('UTC')),
                    'from' => 'CDG',
                    'to' => 'LHR',
                    'marketingCompany' => '6X',
                    'operatingCompany' => '6X',
                    'flightNumber' => '1680',
                    'bookingClass' => 'T',
                    'segmentTattoo' => 1,
                    'groupNumber' => 1
                ]),
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2013-12-10 06:40:00', new \DateTimeZone('UTC')),
                    'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2013-12-10 09:00:00', new \DateTimeZone('UTC')),
                    'from' => 'LHR',
                    'to' => 'CDG',
                    'marketingCompany' => '6X',
                    'operatingCompany' => '6X',
                    'flightNumber' => '1381',
                    'bookingClass' => 'V',
                    'segmentTattoo' => 2,
                    'groupNumber' => 1
                ])
            ],
            'pricingOptions' => new PricingOptions([
                'overrideOptions' => [
                    PricingOptions::OVERRIDE_FARETYPE_PUB,
                    PricingOptions::OVERRIDE_FARETYPE_UNI
                ],
                'currencyOverride' => 'USD',
                'paxDiscountCodes' => ['CH'],
                'paxDiscountCodeRefs' => [
                    new PaxSegRef([
                        'type' => PaxSegRef::TYPE_PASSENGER,
                        'reference' => 2
                    ])
                ]
            ]),
            'cabin' => new CabinOptions(
                [
                    new Cabin(Cabin::TYPE_FIRST_CABIN, Cabin::CLASS_PREMIUM_ECONOMY)
                ]
            )
        ]);

        $msg = new InformativeBestPricingWithoutPNR13($opt);


        $this->assertCount(1, $msg->passengersGroup);
        $this->assertEquals('ADT', $msg->passengersGroup[0]->discountPtc->valueQualifier);
        $this->assertCount(2, $msg->passengersGroup[0]->travellersID->travellerDetails);
        $this->assertEquals(1, $msg->passengersGroup[0]->travellersID->travellerDetails[0]->measurementValue);
        $this->assertEquals(2, $msg->passengersGroup[0]->travellersID->travellerDetails[1]->measurementValue);
        $this->assertCount(1, $msg->passengersGroup[0]->segmentRepetitionControl->segmentControlDetails);
        $this->assertEquals(2, $msg->passengersGroup[0]->segmentRepetitionControl->segmentControlDetails[0]->numberOfUnits);
        $this->assertEquals(1, $msg->passengersGroup[0]->segmentRepetitionControl->segmentControlDetails[0]->quantity);

        $this->assertCount(2, $msg->segmentGroup);

        $this->assertEquals('011213', $msg->segmentGroup[0]->segmentInformation->flightDate->departureDate);
        $this->assertEquals('0730', $msg->segmentGroup[0]->segmentInformation->flightDate->departureTime);
        $this->assertEquals('011213', $msg->segmentGroup[0]->segmentInformation->flightDate->arrivalDate);
        $this->assertEquals('0750', $msg->segmentGroup[0]->segmentInformation->flightDate->arrivalTime);
        $this->assertEquals('CDG', $msg->segmentGroup[0]->segmentInformation->boardPointDetails->trueLocationId);
        $this->assertEquals('LHR', $msg->segmentGroup[0]->segmentInformation->offpointDetails->trueLocationId);
        $this->assertEquals('6X', $msg->segmentGroup[0]->segmentInformation->companyDetails->marketingCompany);
        $this->assertEquals('6X', $msg->segmentGroup[0]->segmentInformation->companyDetails->operatingCompany);
        $this->assertEquals('1680', $msg->segmentGroup[0]->segmentInformation->flightIdentification->flightNumber);
        $this->assertEquals('T', $msg->segmentGroup[0]->segmentInformation->flightIdentification->bookingClass);
        $this->assertEquals(1, $msg->segmentGroup[0]->segmentInformation->itemNumber);
        $this->assertEquals(1, $msg->segmentGroup[0]->segmentInformation->flightTypeDetails->flightIndicator[0]);
        $this->assertNull($msg->segmentGroup[0]->additionnalSegmentDetails);
        $this->assertNull($msg->segmentGroup[0]->inventory);

        $this->assertEquals('101213', $msg->segmentGroup[1]->segmentInformation->flightDate->departureDate);
        $this->assertEquals('0640', $msg->segmentGroup[1]->segmentInformation->flightDate->departureTime);
        $this->assertEquals('101213', $msg->segmentGroup[1]->segmentInformation->flightDate->arrivalDate);
        $this->assertEquals('0900', $msg->segmentGroup[1]->segmentInformation->flightDate->arrivalTime);
        $this->assertEquals('LHR', $msg->segmentGroup[1]->segmentInformation->boardPointDetails->trueLocationId);
        $this->assertEquals('CDG', $msg->segmentGroup[1]->segmentInformation->offpointDetails->trueLocationId);
        $this->assertEquals('6X', $msg->segmentGroup[1]->segmentInformation->companyDetails->marketingCompany);
        $this->assertEquals('6X', $msg->segmentGroup[1]->segmentInformation->companyDetails->operatingCompany);
        $this->assertEquals('1381', $msg->segmentGroup[1]->segmentInformation->flightIdentification->flightNumber);
        $this->assertEquals('V', $msg->segmentGroup[1]->segmentInformation->flightIdentification->bookingClass);
        $this->assertEquals(2, $msg->segmentGroup[1]->segmentInformation->itemNumber);
        $this->assertEquals(1, $msg->segmentGroup[1]->segmentInformation->flightTypeDetails->flightIndicator[0]);
        $this->assertNull($msg->segmentGroup[1]->additionnalSegmentDetails);
        $this->assertNull($msg->segmentGroup[1]->inventory);

        $this->assertCount(5, $msg->pricingOptionGroup);

        $this->assertEquals(PricingOptionKey::OPTION_FARE_CURRENCY_OVERRIDE, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals('USD', $msg->pricingOptionGroup[0]->currency->firstCurrencyDetails->currencyIsoCode);
        $this->assertEquals(FirstCurrencyDetails::QUAL_CURRENCY_OVERRIDE, $msg->pricingOptionGroup[0]->currency->firstCurrencyDetails->currencyQualifier);

        $this->assertEquals(PricingOptionKey::OPTION_PASSENGER_DISCOUNT, $msg->pricingOptionGroup[1]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(PenDisInformation::QUAL_DISCOUNT, $msg->pricingOptionGroup[1]->penDisInformation->discountPenaltyQualifier);
        $this->assertEquals('CH', $msg->pricingOptionGroup[1]->penDisInformation->discountPenaltyDetails[0]->rate);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_REFERENCE, $msg->pricingOptionGroup[1]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(2, $msg->pricingOptionGroup[1]->paxSegTstReference->referenceDetails[0]->value);

        $this->assertEquals(PricingOptionKey::OPTION_PUBLISHED_FARES, $msg->pricingOptionGroup[2]->pricingOptionKey->pricingOptionKey);

        $this->assertEquals(PricingOptionKey::OPTION_UNIFARES, $msg->pricingOptionGroup[3]->pricingOptionKey->pricingOptionKey);

        $this->assertEquals(PricingOptionKey::OPTION_CABIN, $msg->pricingOptionGroup[4]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(Cabin::TYPE_FIRST_CABIN, $msg->pricingOptionGroup[4]->optionDetail->criteriaDetails[0]->attributeType);
        $this->assertEquals(Cabin::CLASS_PREMIUM_ECONOMY, $msg->pricingOptionGroup[4]->optionDetail->criteriaDetails[0]->attributeDescription);
    }
}

