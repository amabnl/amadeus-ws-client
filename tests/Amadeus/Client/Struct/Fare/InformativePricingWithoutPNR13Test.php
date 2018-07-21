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

use Amadeus\Client\RequestOptions\Fare\PricePnr\FareBasis;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\Passenger;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\PricingOptions;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\Segment;
use Amadeus\Client\RequestOptions\FareInformativePricingWithoutPnrOptions;
use Amadeus\Client\Struct\Fare\InformativePricing13\FareDetails;
use Amadeus\Client\Struct\Fare\InformativePricingWithoutPNR13;
use Amadeus\Client\Struct\Fare\PricePnr13\CarrierInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\CriteriaDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\Currency;
use Amadeus\Client\Struct\Fare\PricePnr13\OptionDetail;
use Amadeus\Client\Struct\Fare\PricePnr13\PaxSegTstReference;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionGroup;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionKey;
use Test\Amadeus\BaseTestCase;

/**
 * InformativePricingWithoutPNR13Test
 *
 * @package Test\Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class InformativePricingWithoutPNR13Test extends BaseTestCase
{
    public function testCanCreateMessageWithMandatoryParams()
    {
        $options = new FareInformativePricingWithoutPnrOptions([
            'passengers' => [
                new Passenger([
                    'tattoos' => [1, 2],
                    'type' => Passenger::TYPE_ADULT
                ])
            ],
            'segments' => [
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2016-11-21 09:15:00', new \DateTimeZone('UTC')),
                    'from' => 'BRU',
                    'to' => 'LIS',
                    'marketingCompany' => 'TP',
                    'flightNumber' => '4652',
                    'bookingClass' => 'Y',
                    'segmentTattoo' => 1,
                    'groupNumber' => 1
                ]),
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2016-11-28 14:20:00', new \DateTimeZone('UTC')),
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

        $message = new InformativePricingWithoutPNR13($options);

        $this->assertNull($message->originatorGroup);

        $this->assertCount(1, $message->passengersGroup);
        $this->assertEquals(1, $message->passengersGroup[0]->segmentRepetitionControl->segmentControlDetails[0]->quantity);
        $this->assertEquals(2, $message->passengersGroup[0]->segmentRepetitionControl->segmentControlDetails[0]->numberOfUnits);
        $this->assertEquals(Passenger::TYPE_ADULT, $message->passengersGroup[0]->discountPtc->valueQualifier);
        $this->assertNull($message->passengersGroup[0]->discountPtc->fareDetails);
        $this->assertCount(2, $message->passengersGroup[0]->travellersID->travellerDetails);
        $this->assertEquals(1, $message->passengersGroup[0]->travellersID->travellerDetails[0]->measurementValue);
        $this->assertEquals(2, $message->passengersGroup[0]->travellersID->travellerDetails[1]->measurementValue);

        $this->assertCount(2, $message->segmentGroup);
        $this->assertEquals('211116', $message->segmentGroup[0]->segmentInformation->flightDate->departureDate);
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

        $this->assertEquals('281116', $message->segmentGroup[1]->segmentInformation->flightDate->departureDate);
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
        $this->assertEquals(PricingOptionKey::OPTION_NO_OPTION, $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
    }

    public function testCanCreateMessageWithFullSegmentParamsAndInfant()
    {
        $options = new FareInformativePricingWithoutPnrOptions([
            'passengers' => [
                new Passenger([
                    'tattoos' => [1, 2],
                    'type' => Passenger::TYPE_ADULT
                ]),
                new Passenger([
                    'tattoos' => [1],
                    'type' => Passenger::TYPE_INFANT
                ])
            ],
            'segments' => [
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2009-03-18 09:15:00', new \DateTimeZone('UTC')),
                    'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2009-03-18 12:30:00', new \DateTimeZone('UTC')),
                    'from' => 'SYD',
                    'to' => 'BKK',
                    'marketingCompany' => '6X',
                    'operatingCompany' => '6X',
                    'flightNumber' => '340',
                    'bookingClass' => 'Y',
                    'airplaneCode' => '757',
                    'segmentTattoo' => 1,
                    'groupNumber' => 1,
                    'inventory' => [
                        'J' => '9',
                        'C' => '9',
                        'D' => '8',
                        'R' => '9',
                        'I' => '8',
                        'Y' => '5',
                        'B' => '9',
                        'H' => '9'
                    ]
                ]),
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2009-03-18 15:05:00', new \DateTimeZone('UTC')),
                    'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2009-03-18 21:50:00', new \DateTimeZone('UTC')),
                    'from' => 'BKK',
                    'to' => 'LHR',
                    'marketingCompany' => '6X',
                    'operatingCompany' => '6X',
                    'flightNumber' => '341',
                    'bookingClass' => 'Y',
                    'airplaneCode' => '757',
                    'segmentTattoo' => 2,
                    'groupNumber' => 1,
                    'inventory' => [
                        'J' => '9',
                        'C' => '9',
                        'D' => '9',
                        'R' => '9',
                        'I' => '9',
                        'Y' => '9',
                        'B' => '9',
                        'H' => '9'
                    ]
                ]),
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2009-03-28 09:25:00', new \DateTimeZone('UTC')),
                    'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2009-03-28 18:10:00', new \DateTimeZone('UTC')),
                    'from' => 'LHR',
                    'to' => 'BKK',
                    'marketingCompany' => '6X',
                    'operatingCompany' => '6X',
                    'flightNumber' => '342',
                    'bookingClass' => 'Y',
                    'airplaneCode' => '757',
                    'nrOfStops' => 1,
                    'segmentTattoo' => 3,
                    'groupNumber' => 2,
                    'inventory' => [
                        'J' => '9',
                        'C' => '9',
                        'D' => '9',
                        'R' => '9',
                        'I' => '9',
                        'Y' => '9',
                        'B' => '9',
                        'H' => '9'
                    ]
                ]),
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2009-03-28 19:50:00', new \DateTimeZone('UTC')),
                    'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2009-03-29 08:45:00', new \DateTimeZone('UTC')),
                    'from' => 'BKK',
                    'to' => 'SYD',
                    'marketingCompany' => '6X',
                    'operatingCompany' => '6X',
                    'flightNumber' => '342',
                    'bookingClass' => 'Y',
                    'airplaneCode' => '757',
                    'segmentTattoo' => 4,
                    'groupNumber' => 2,
                    'inventory' => [
                        'J' => '9',
                        'C' => '9',
                        'D' => '9',
                        'R' => '9',
                        'I' => '9',
                        'Y' => '9',
                        'B' => '9',
                        'H' => '9',
                        'K' => '9'
                    ]
                ])
            ]
        ]);

        $message = new InformativePricingWithoutPNR13($options);

        $this->assertNull($message->originatorGroup);

        $this->assertCount(2, $message->passengersGroup);
        $this->assertEquals(1, $message->passengersGroup[0]->segmentRepetitionControl->segmentControlDetails[0]->quantity);
        $this->assertEquals(2, $message->passengersGroup[0]->segmentRepetitionControl->segmentControlDetails[0]->numberOfUnits);
        $this->assertEquals(Passenger::TYPE_ADULT, $message->passengersGroup[0]->discountPtc->valueQualifier);
        $this->assertNull($message->passengersGroup[0]->discountPtc->fareDetails);
        $this->assertCount(2, $message->passengersGroup[0]->travellersID->travellerDetails);
        $this->assertEquals(1, $message->passengersGroup[0]->travellersID->travellerDetails[0]->measurementValue);
        $this->assertEquals(2, $message->passengersGroup[0]->travellersID->travellerDetails[1]->measurementValue);

        $this->assertEquals(2, $message->passengersGroup[1]->segmentRepetitionControl->segmentControlDetails[0]->quantity);
        $this->assertEquals(1, $message->passengersGroup[1]->segmentRepetitionControl->segmentControlDetails[0]->numberOfUnits);
        $this->assertEquals(Passenger::TYPE_INFANT, $message->passengersGroup[1]->discountPtc->valueQualifier);
        $this->assertEquals(FareDetails::QUAL_INFANT_WITHOUT_SEAT, $message->passengersGroup[1]->discountPtc->fareDetails->qualifier);
        $this->assertCount(1, $message->passengersGroup[1]->travellersID->travellerDetails);
        $this->assertEquals(1, $message->passengersGroup[1]->travellersID->travellerDetails[0]->measurementValue);

        $this->assertCount(4, $message->segmentGroup);
        $this->assertEquals('180309', $message->segmentGroup[0]->segmentInformation->flightDate->departureDate);
        $this->assertEquals('0915', $message->segmentGroup[0]->segmentInformation->flightDate->departureTime);
        $this->assertEquals('180309', $message->segmentGroup[0]->segmentInformation->flightDate->arrivalDate);
        $this->assertEquals('1230', $message->segmentGroup[0]->segmentInformation->flightDate->arrivalTime);
        $this->assertEquals('SYD', $message->segmentGroup[0]->segmentInformation->boardPointDetails->trueLocationId);
        $this->assertEquals('BKK', $message->segmentGroup[0]->segmentInformation->offpointDetails->trueLocationId);
        $this->assertEquals('6X', $message->segmentGroup[0]->segmentInformation->companyDetails->marketingCompany);
        $this->assertEquals('6X', $message->segmentGroup[0]->segmentInformation->companyDetails->operatingCompany);
        $this->assertEquals('340', $message->segmentGroup[0]->segmentInformation->flightIdentification->flightNumber);
        $this->assertEquals('Y', $message->segmentGroup[0]->segmentInformation->flightIdentification->bookingClass);
        $this->assertEquals(1, $message->segmentGroup[0]->segmentInformation->itemNumber);
        $this->assertEquals(1, $message->segmentGroup[0]->segmentInformation->flightTypeDetails->flightIndicator[0]);
        $this->assertEquals('757', $message->segmentGroup[0]->additionnalSegmentDetails->legDetails->equipment);
        $this->assertNull($message->segmentGroup[0]->additionnalSegmentDetails->legDetails->numberOfStops);
        $this->assertCount(8, $message->segmentGroup[0]->inventory->bookingClassDetails);
        $this->assertEquals('J', $message->segmentGroup[0]->inventory->bookingClassDetails[0]->designator);
        $this->assertEquals(9, $message->segmentGroup[0]->inventory->bookingClassDetails[0]->availabilityStatus);
        $this->assertEquals('C', $message->segmentGroup[0]->inventory->bookingClassDetails[1]->designator);
        $this->assertEquals(9, $message->segmentGroup[0]->inventory->bookingClassDetails[1]->availabilityStatus);
        $this->assertEquals('D', $message->segmentGroup[0]->inventory->bookingClassDetails[2]->designator);
        $this->assertEquals(8, $message->segmentGroup[0]->inventory->bookingClassDetails[2]->availabilityStatus);
        $this->assertEquals('R', $message->segmentGroup[0]->inventory->bookingClassDetails[3]->designator);
        $this->assertEquals(9, $message->segmentGroup[0]->inventory->bookingClassDetails[3]->availabilityStatus);
        $this->assertEquals('I', $message->segmentGroup[0]->inventory->bookingClassDetails[4]->designator);
        $this->assertEquals(8, $message->segmentGroup[0]->inventory->bookingClassDetails[4]->availabilityStatus);
        $this->assertEquals('Y', $message->segmentGroup[0]->inventory->bookingClassDetails[5]->designator);
        $this->assertEquals(5, $message->segmentGroup[0]->inventory->bookingClassDetails[5]->availabilityStatus);
        $this->assertEquals('B', $message->segmentGroup[0]->inventory->bookingClassDetails[6]->designator);
        $this->assertEquals(9, $message->segmentGroup[0]->inventory->bookingClassDetails[6]->availabilityStatus);
        $this->assertEquals('H', $message->segmentGroup[0]->inventory->bookingClassDetails[7]->designator);
        $this->assertEquals(9, $message->segmentGroup[0]->inventory->bookingClassDetails[7]->availabilityStatus);

        $this->assertEquals('180309', $message->segmentGroup[1]->segmentInformation->flightDate->departureDate);
        $this->assertEquals('1505', $message->segmentGroup[1]->segmentInformation->flightDate->departureTime);
        $this->assertEquals('180309', $message->segmentGroup[1]->segmentInformation->flightDate->arrivalDate);
        $this->assertEquals('2150', $message->segmentGroup[1]->segmentInformation->flightDate->arrivalTime);
        $this->assertEquals('BKK', $message->segmentGroup[1]->segmentInformation->boardPointDetails->trueLocationId);
        $this->assertEquals('LHR', $message->segmentGroup[1]->segmentInformation->offpointDetails->trueLocationId);
        $this->assertEquals('6X', $message->segmentGroup[1]->segmentInformation->companyDetails->marketingCompany);
        $this->assertEquals('6X', $message->segmentGroup[1]->segmentInformation->companyDetails->operatingCompany);
        $this->assertEquals('341', $message->segmentGroup[1]->segmentInformation->flightIdentification->flightNumber);
        $this->assertEquals('Y', $message->segmentGroup[1]->segmentInformation->flightIdentification->bookingClass);
        $this->assertEquals(2, $message->segmentGroup[1]->segmentInformation->itemNumber);
        $this->assertEquals(1, $message->segmentGroup[1]->segmentInformation->flightTypeDetails->flightIndicator[0]);
        $this->assertEquals('757', $message->segmentGroup[1]->additionnalSegmentDetails->legDetails->equipment);
        $this->assertNull($message->segmentGroup[1]->additionnalSegmentDetails->legDetails->numberOfStops);
        $this->assertCount(8, $message->segmentGroup[1]->inventory->bookingClassDetails);

        $this->assertEquals('280309', $message->segmentGroup[2]->segmentInformation->flightDate->departureDate);
        $this->assertEquals('0925', $message->segmentGroup[2]->segmentInformation->flightDate->departureTime);
        $this->assertEquals('280309', $message->segmentGroup[2]->segmentInformation->flightDate->arrivalDate);
        $this->assertEquals('1810', $message->segmentGroup[2]->segmentInformation->flightDate->arrivalTime);
        $this->assertEquals('LHR', $message->segmentGroup[2]->segmentInformation->boardPointDetails->trueLocationId);
        $this->assertEquals('BKK', $message->segmentGroup[2]->segmentInformation->offpointDetails->trueLocationId);
        $this->assertEquals('6X', $message->segmentGroup[2]->segmentInformation->companyDetails->marketingCompany);
        $this->assertEquals('6X', $message->segmentGroup[2]->segmentInformation->companyDetails->operatingCompany);
        $this->assertEquals('342', $message->segmentGroup[2]->segmentInformation->flightIdentification->flightNumber);
        $this->assertEquals('Y', $message->segmentGroup[2]->segmentInformation->flightIdentification->bookingClass);
        $this->assertEquals(3, $message->segmentGroup[2]->segmentInformation->itemNumber);
        $this->assertEquals(2, $message->segmentGroup[2]->segmentInformation->flightTypeDetails->flightIndicator[0]);
        $this->assertEquals('757', $message->segmentGroup[2]->additionnalSegmentDetails->legDetails->equipment);
        $this->assertEquals(1, $message->segmentGroup[2]->additionnalSegmentDetails->legDetails->numberOfStops);
        $this->assertCount(8, $message->segmentGroup[2]->inventory->bookingClassDetails);

        $this->assertEquals('280309', $message->segmentGroup[3]->segmentInformation->flightDate->departureDate);
        $this->assertEquals('1950', $message->segmentGroup[3]->segmentInformation->flightDate->departureTime);
        $this->assertEquals('290309', $message->segmentGroup[3]->segmentInformation->flightDate->arrivalDate);
        $this->assertEquals('0845', $message->segmentGroup[3]->segmentInformation->flightDate->arrivalTime);
        $this->assertEquals('BKK', $message->segmentGroup[3]->segmentInformation->boardPointDetails->trueLocationId);
        $this->assertEquals('SYD', $message->segmentGroup[3]->segmentInformation->offpointDetails->trueLocationId);
        $this->assertEquals('6X', $message->segmentGroup[3]->segmentInformation->companyDetails->marketingCompany);
        $this->assertEquals('6X', $message->segmentGroup[3]->segmentInformation->companyDetails->operatingCompany);
        $this->assertEquals('342', $message->segmentGroup[3]->segmentInformation->flightIdentification->flightNumber);
        $this->assertEquals('Y', $message->segmentGroup[3]->segmentInformation->flightIdentification->bookingClass);
        $this->assertEquals(4, $message->segmentGroup[3]->segmentInformation->itemNumber);
        $this->assertEquals(2, $message->segmentGroup[3]->segmentInformation->flightTypeDetails->flightIndicator[0]);
        $this->assertEquals('757', $message->segmentGroup[3]->additionnalSegmentDetails->legDetails->equipment);
        $this->assertNull($message->segmentGroup[3]->additionnalSegmentDetails->legDetails->numberOfStops);
        $this->assertCount(9, $message->segmentGroup[3]->inventory->bookingClassDetails);
        $this->assertEquals('K', $message->segmentGroup[3]->inventory->bookingClassDetails[8]->designator);
        $this->assertEquals(9, $message->segmentGroup[3]->inventory->bookingClassDetails[8]->availabilityStatus);

        $this->assertCount(1, $message->pricingOptionGroup);
        $this->assertEquals(PricingOptionKey::OPTION_NO_OPTION, $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
    }

    public function testPricingOptionsLegacyFormat()
    {
        $options = new FareInformativePricingWithoutPnrOptions([
            'passengers' => [
                new Passenger([
                    'tattoos' => [1],
                    'type' => Passenger::TYPE_ADULT
                ])
            ],
            'segments' => [
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2016-12-06 05:04:00', new \DateTimeZone('UTC')),
                    'from' => 'BRU',
                    'to' => 'LON',
                    'marketingCompany' => 'BA',
                    'flightNumber' => '716',
                    'bookingClass' => 'C',
                    'segmentTattoo' => 1,
                    'groupNumber' => 1
                ])
            ],
            'pricingOptions' => new PricingOptions([
                'overrideOptions' => [PricingOptions::OVERRIDE_FARETYPE_NEG, PricingOptions::OVERRIDE_FAREBASIS],
                'validatingCarrier' => 'BA',
                'currencyOverride' => 'EUR',
                'pricingsFareBasis' => [
                    new FareBasis([
                        'fareBasisPrimaryCode' => 'QNC',
                        'fareBasisCode' => '469W2',
                        'segmentReference' => [1 => FareBasis::SEGREFTYPE_SEGMENT]
                    ])
                ]
            ])
        ]);

        $message = new InformativePricingWithoutPNR13($options);

        $this->assertNull($message->originatorGroup);

        $this->assertCount(1, $message->passengersGroup);
        $this->assertEquals(1, $message->passengersGroup[0]->segmentRepetitionControl->segmentControlDetails[0]->quantity);
        $this->assertEquals(1, $message->passengersGroup[0]->segmentRepetitionControl->segmentControlDetails[0]->numberOfUnits);
        $this->assertEquals(Passenger::TYPE_ADULT, $message->passengersGroup[0]->discountPtc->valueQualifier);
        $this->assertNull($message->passengersGroup[0]->discountPtc->fareDetails);
        $this->assertCount(1, $message->passengersGroup[0]->travellersID->travellerDetails);
        $this->assertEquals(1, $message->passengersGroup[0]->travellersID->travellerDetails[0]->measurementValue);

        $this->assertCount(1, $message->segmentGroup);
        $this->assertEquals('061216', $message->segmentGroup[0]->segmentInformation->flightDate->departureDate);
        $this->assertEquals('0504', $message->segmentGroup[0]->segmentInformation->flightDate->departureTime);
        $this->assertNull($message->segmentGroup[0]->segmentInformation->flightDate->arrivalDate);
        $this->assertNull($message->segmentGroup[0]->segmentInformation->flightDate->arrivalTime);
        $this->assertEquals('BRU', $message->segmentGroup[0]->segmentInformation->boardPointDetails->trueLocationId);
        $this->assertEquals('LON', $message->segmentGroup[0]->segmentInformation->offpointDetails->trueLocationId);
        $this->assertEquals('BA', $message->segmentGroup[0]->segmentInformation->companyDetails->marketingCompany);
        $this->assertNull($message->segmentGroup[0]->segmentInformation->companyDetails->operatingCompany);
        $this->assertEquals('716', $message->segmentGroup[0]->segmentInformation->flightIdentification->flightNumber);
        $this->assertEquals('C', $message->segmentGroup[0]->segmentInformation->flightIdentification->bookingClass);
        $this->assertEquals(1, $message->segmentGroup[0]->segmentInformation->itemNumber);
        $this->assertEquals(1, $message->segmentGroup[0]->segmentInformation->flightTypeDetails->flightIndicator[0]);

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
            [1 => FareBasis::SEGREFTYPE_SEGMENT]
        );

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $fareBasisOverridePo));

        $negofarePo = new PricingOptionGroup(PricingOptionKey::OPTION_NEGOTIATED_FARES);

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $negofarePo));
    }

}
