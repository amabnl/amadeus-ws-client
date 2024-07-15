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

use Amadeus\Client\RequestOptions\FarePriceUpsellWithoutPnrOptions;
use Amadeus\Client\Struct\Fare\PriceUpsellWithoutPNR;
use Test\Amadeus\BaseTestCase;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\Passenger;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\Segment;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionKey;

/**
 * PriceUpsellWithoutPNRTest
 *
 * @package Test\Amadeus\Client\Struct\Fare
 */
class PriceUpsellWithoutPNRTest extends BaseTestCase
{
    public function testCanMakeMessage()
    {
        $options = new FarePriceUpsellWithoutPnrOptions([
            'passengers' => [
                new Passenger([
                    'tattoos' => [1, 2],
                    'type' => Passenger::TYPE_ADULT
                ])
            ],
            'segments' => [
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2019-11-21 09:15:00', new \DateTimeZone('UTC')),
                    'from' => 'BRU',
                    'to' => 'LIS',
                    'marketingCompany' => 'TP',
                    'flightNumber' => '4652',
                    'bookingClass' => 'Y',
                    'segmentTattoo' => 1,
                    'groupNumber' => 1
                ]),
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2019-11-28 14:20:00', new \DateTimeZone('UTC')),
                    'from' => 'LIS',
                    'to' => 'BRU',
                    'marketingCompany' => 'TP',
                    'flightNumber' => '3581',
                    'bookingClass' => 'C',
                    'segmentTattoo' => 2,
                    'groupNumber' => 2
                ])
            ]
        ]);

        $message = new PriceUpsellWithoutPNR($options);

        $this->assertCount(1, $message->passengersGroup);
        $this->assertEquals(1, $message->passengersGroup[0]->segmentRepetitionControl->segmentControlDetails[0]->quantity);
        $this->assertEquals(2, $message->passengersGroup[0]->segmentRepetitionControl->segmentControlDetails[0]->numberOfUnits);
        $this->assertEquals(Passenger::TYPE_ADULT, $message->passengersGroup[0]->discountPtc->valueQualifier);
        $this->assertNull($message->passengersGroup[0]->discountPtc->fareDetails);
        $this->assertCount(2, $message->passengersGroup[0]->travellersID->travellerDetails);
        $this->assertEquals(1, $message->passengersGroup[0]->travellersID->travellerDetails[0]->measurementValue);
        $this->assertEquals(2, $message->passengersGroup[0]->travellersID->travellerDetails[1]->measurementValue);

        $this->assertCount(2, $message->segmentGroup);
        $this->assertEquals('211119', $message->segmentGroup[0]->segmentInformation->flightDate->departureDate);
        $this->assertEquals('0915', $message->segmentGroup[0]->segmentInformation->flightDate->departureTime);
        $this->assertNull($message->segmentGroup[0]->segmentInformation->flightDate->arrivalDate);
        $this->assertNull($message->segmentGroup[0]->segmentInformation->flightDate->arrivalTime);
        $this->assertEquals('BRU', $message->segmentGroup[0]->segmentInformation->boardPointDetails->trueLocationId);
        $this->assertEquals('LIS', $message->segmentGroup[0]->segmentInformation->offpointDetails->trueLocationId);
        $this->assertEquals('TP', $message->segmentGroup[0]->segmentInformation->companyDetails->marketingCompany);
        $this->assertNull($message->segmentGroup[0]->segmentInformation->companyDetails->operatingCompany);
        $this->assertEquals('4652', $message->segmentGroup[0]->segmentInformation->flightIdentification->flightNumber);
        $this->assertEquals('Y', $message->segmentGroup[0]->segmentInformation->flightIdentification->bookingClass);
        $this->assertEquals(1, $message->segmentGroup[0]->segmentInformation->itemNumber);
        $this->assertEquals(1, $message->segmentGroup[0]->segmentInformation->flightTypeDetails->flightIndicator[0]);
        $this->assertNull($message->segmentGroup[0]->additionnalSegmentDetails);
        $this->assertNull($message->segmentGroup[0]->inventory);

        $this->assertEquals('281119', $message->segmentGroup[1]->segmentInformation->flightDate->departureDate);
        $this->assertEquals('1420', $message->segmentGroup[1]->segmentInformation->flightDate->departureTime);
        $this->assertNull($message->segmentGroup[1]->segmentInformation->flightDate->arrivalDate);
        $this->assertNull($message->segmentGroup[1]->segmentInformation->flightDate->arrivalTime);
        $this->assertEquals('LIS', $message->segmentGroup[1]->segmentInformation->boardPointDetails->trueLocationId);
        $this->assertEquals('BRU', $message->segmentGroup[1]->segmentInformation->offpointDetails->trueLocationId);
        $this->assertEquals('TP', $message->segmentGroup[1]->segmentInformation->companyDetails->marketingCompany);
        $this->assertNull($message->segmentGroup[1]->segmentInformation->companyDetails->operatingCompany);
        $this->assertEquals('3581', $message->segmentGroup[1]->segmentInformation->flightIdentification->flightNumber);
        $this->assertEquals('C', $message->segmentGroup[1]->segmentInformation->flightIdentification->bookingClass);
        $this->assertEquals(2, $message->segmentGroup[1]->segmentInformation->itemNumber);
        $this->assertEquals(2, $message->segmentGroup[1]->segmentInformation->flightTypeDetails->flightIndicator[0]);
        $this->assertNull($message->segmentGroup[1]->additionnalSegmentDetails);
        $this->assertNull($message->segmentGroup[1]->inventory);

        $this->assertCount(1, $message->pricingOptionGroup);
        $this->assertEquals(
            PricingOptionKey::OPTION_NO_OPTION,
            $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey
        );
    }

    public function testCanMakeMessageFromNull()
    {
        $message = new PriceUpsellWithoutPNR(null);

        self::assertEmpty($message->passengersGroup);
        self::assertEmpty($message->pricingOptionGroup);
        self::assertEmpty($message->segmentGroup);
    }
}
