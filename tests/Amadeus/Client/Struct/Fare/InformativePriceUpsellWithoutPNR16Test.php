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

use Amadeus\Client\RequestOptions\Fare\InformativePricing\Passenger;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\PricingOptions;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\Segment;
use Amadeus\Client\RequestOptions\FarePriceUpsellWithoutPNROptions;
use Amadeus\Client\Struct\Fare\InformativePriceUpsellWithoutPNR16;
use Amadeus\Client\Struct\Fare\PricePnr13\FirstCurrencyDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionKey;
use Test\Amadeus\BaseTestCase;

/**
 * InformativeBestPricingWithoutPNR13Test
 *
 * @package Test\Amadeus\Client\Struct\Fare
 * @author Leonardo Travel <dermikagh@gmail.com>
 */
class InformativePriceUpsellWithoutPNR16Test extends BaseTestCase
{
    /**
     * Here is a complete pricing example of a TRN-FRA trip for 2 passengers, with options below:
     *
     * - take into account published fares (RP)
     * - take into account Unifares (RU)
     * - use passenger 1 ADT and passenger 2 CH
     * - convert fare into EUR (FCO)
     */
    public function test51OperationFullExample()
    {
        $opt = new FarePriceUpsellWithoutPNROptions([
            'passengers' => [
                new Passenger([
                    'tattoos' => [1],
                    'type' => Passenger::TYPE_ADULT
                ]),
                new Passenger([
                    'tattoos' => [2],
                    'type' => Passenger::TYPE_CHILD
                ])
            ],
            'segments' => [
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2018-08-17 14:20:00', new \DateTimeZone('UTC')),
                    'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2018-08-17 15:35:00', new \DateTimeZone('UTC')),
                    'from' => 'TRN',
                    'to' => 'FRA',
                    'marketingCompany' => 'LH',
                    'operatingCompany' => 'EN',
                    'flightNumber' => '299',
                    'bookingClass' => 'W',
                    'segmentTattoo' => 1,
                    'groupNumber' => 1
                ]),
            ],
            'pricingOptions' => new PricingOptions([
                'overrideOptions' => [
                    PricingOptions::OVERRIDE_RETURN_LOWEST_AVAILABLE,
                    PricingOptions::OVERRIDE_FARETYPE_PUB,
                    PricingOptions::OVERRIDE_FARETYPE_UNI
                ],
                'currencyOverride' => 'EUR'
            ])
        ]);

        $msg = new InformativePriceUpsellWithoutPNR16($opt);


        $this->assertCount(2, $msg->passengersGroup);
        print_r($msg->passengersGroup[0]->discountPtc->valueQualifier);
        $this->assertEquals('ADT', $msg->passengersGroup[0]->discountPtc->valueQualifier);
        $this->assertCount(1, $msg->passengersGroup[0]->travellersID->travellerDetails);
        $this->assertEquals(1, $msg->passengersGroup[0]->travellersID->travellerDetails[0]->measurementValue);
        $this->assertCount(1, $msg->passengersGroup[0]->segmentRepetitionControl->segmentControlDetails);
        $this->assertEquals(1, $msg->passengersGroup[0]->segmentRepetitionControl->segmentControlDetails[0]->numberOfUnits);
        $this->assertEquals(1, $msg->passengersGroup[0]->segmentRepetitionControl->segmentControlDetails[0]->quantity);

        $this->assertEquals('CH', $msg->passengersGroup[1]->discountPtc->valueQualifier);
        $this->assertCount(1, $msg->passengersGroup[1]->travellersID->travellerDetails);
        $this->assertEquals(2, $msg->passengersGroup[1]->travellersID->travellerDetails[0]->measurementValue);
        $this->assertCount(1, $msg->passengersGroup[1]->segmentRepetitionControl->segmentControlDetails);
        $this->assertEquals(1, $msg->passengersGroup[1]->segmentRepetitionControl->segmentControlDetails[0]->numberOfUnits);
        $this->assertEquals(2, $msg->passengersGroup[1]->segmentRepetitionControl->segmentControlDetails[0]->quantity);

        $this->assertCount(1, $msg->segmentGroup);

        $this->assertEquals('170818', $msg->segmentGroup[0]->segmentInformation->flightDate->departureDate);
        $this->assertEquals('1420', $msg->segmentGroup[0]->segmentInformation->flightDate->departureTime);
        $this->assertEquals('170818', $msg->segmentGroup[0]->segmentInformation->flightDate->arrivalDate);
        $this->assertEquals('1535', $msg->segmentGroup[0]->segmentInformation->flightDate->arrivalTime);
        $this->assertEquals('TRN', $msg->segmentGroup[0]->segmentInformation->boardPointDetails->trueLocationId);
        $this->assertEquals('FRA', $msg->segmentGroup[0]->segmentInformation->offpointDetails->trueLocationId);
        $this->assertEquals('LH', $msg->segmentGroup[0]->segmentInformation->companyDetails->marketingCompany);
        $this->assertEquals('EN', $msg->segmentGroup[0]->segmentInformation->companyDetails->operatingCompany);
        $this->assertEquals('299', $msg->segmentGroup[0]->segmentInformation->flightIdentification->flightNumber);
        $this->assertEquals('W', $msg->segmentGroup[0]->segmentInformation->flightIdentification->bookingClass);
        $this->assertEquals(1, $msg->segmentGroup[0]->segmentInformation->itemNumber);
        $this->assertEquals(1, $msg->segmentGroup[0]->segmentInformation->flightTypeDetails->flightIndicator[0]);
        $this->assertNull($msg->segmentGroup[0]->additionnalSegmentDetails);
        $this->assertNull($msg->segmentGroup[0]->inventory);

        $this->assertCount(4, $msg->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_FARE_CURRENCY_OVERRIDE, $msg->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals('EUR', $msg->pricingOptionGroup[0]->currency->firstCurrencyDetails->currencyIsoCode);
    }
}

