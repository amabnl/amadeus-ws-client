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

namespace Test\Amadeus\Client\Struct\Pnr;

use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\CarrierOrFlight;
use Amadeus\Client\RequestOptions\PnrListPassengersByFlightOptions;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\FlightIdentification;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\DateIdentification;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\NegoRecordLocator;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\NumericRange;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\Origin;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\OutputSelection;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\SearchCriteria;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\OutputAggregation;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\PassengerNameOrType;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\QueueDetails;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\SecondarySearchCriteria;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\SsrOsiSk;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\FrequentFlyer;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\DateIdentification as DateIdentificationStruct;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\FlightIdentification as FlightIdentificationStruct;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\OutputSelectionOption as OutputSelectionOptionStruct;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\SecondarySearchCriteria as SecondarySearchCriteriaStruct;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\SearchCriteria as SearchCriteriaStruct;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\SsrOsiSkValue;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\QueueDetails as QueueDetailsStruct;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\TriggerMarker;
use Test\Amadeus\BaseTestCase;

/**
 * ListPassengersByFlightTest
 *
 * @package Test\Amadeus\Client\Struct\Pnr
 * @author Evan Chuang <ycchuang1999@gmail.com>
 */
class ListPassengersByFlightTest extends BaseTestCase
{
    public function testCanConstructMinimal()
    {
        $opt = new PnrListPassengersByFlightOptions([
            'flightIdentification' => new FlightIdentification([
                'marketingCarrier' => 'LH',
                'flightNumber' => '123'
            ]),
            'dateIdentification' => new DateIdentification([
                'dateTime' => new \DateTime('2025-12-25')
            ])
        ]);

        $struct = new ListPassengersByFlight($opt);

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\ListPassengersByFlight\FlightDateQuery', $struct->flightDateQuery);
        $this->assertEquals('LH', $struct->flightDateQuery->flightIdentification->carrierDetails->marketingCarrier);
        $this->assertEquals('123', $struct->flightDateQuery->flightIdentification->flightDetails->flightNumber);
        $this->assertEquals('2025', $struct->flightDateQuery->dateIdentification->dateTime->year);
        $this->assertEquals('12', $struct->flightDateQuery->dateIdentification->dateTime->month);
        $this->assertEquals('25', $struct->flightDateQuery->dateIdentification->dateTime->day);
        $this->assertEquals('00', $struct->flightDateQuery->dateIdentification->dateTime->hour);
        $this->assertEquals('00', $struct->flightDateQuery->dateIdentification->dateTime->minute);

        $this->assertNull($struct->outputSelectionOption);
        $this->assertEmpty($struct->searchCriteria);
        $this->assertEmpty($struct->outputAggregationOption);
        $this->assertNull($struct->queueDetails);
    }

    public function testCanConstructFull()
    {
        $opt = new PnrListPassengersByFlightOptions([
            'flightIdentification' => new FlightIdentification([
                'marketingCarrier' => 'LH',
                'flightNumber' => '123',
                'operationSuffix' => 'D',
                'boardPoint' => 'SIN',
                'offPoint' => 'BKK'
            ]),
            'dateIdentification' => new DateIdentification([
                'businessSemantic' => DateIdentification::BUSINESS_SEMANTIC_FIRST_LEG_DATE,
                'dateTime' => new \DateTime('2025-12-25')
            ]),
            'outputSelection' => new OutputSelection([
                'outputType' => OutputSelection::OUTPUT_TYPE_COUNTERS_ONLY,
                'elementType' => [
                    OutputSelection::ELEMENT_TYPE_AIR_SEGMENTS,
                    OutputSelection::ELEMENT_TYPE_ASSOCIATED_CROSS_REFERENCE_RECORD
                ]
            ]),
            'searchCriteria' => [
                new SearchCriteria([
                    'primarySearchCriterion' => SearchCriteria::PRIMARY_SEARCH_CRITERION_BY_A_LLIANCE_FREQUENT_FLYER_DATA,
                    'negativeMode' => SearchCriteria::NEGATIVE_MODE_NOP,
                    'associationMode' => SearchCriteria::ASSOCIATION_MODE_AND,
                    'secondarySearchCriteria' => [
                        new SecondarySearchCriteria([
                            'classOfService' => SecondarySearchCriteria::CLASS_OF_SERVICE_BUSINESS,
                            'carrierOrFlight' => new carrierOrFlight([
                                'marketingCarrier' => 'LH',
                                'flightNumber' => '123',
                                'departureDate' => new \DateTime('2025-12-25'),
                                'boardPoint' => 'SIN',
                                'offPoint' => 'BKK'
                            ]),
                            'ssrOsiSk' => new SsrOsiSk([
                                'ssrCode' => 'OSI',
                                'serviceType' => 'NBML',
                                'seatNumber' => '12A'
                            ]),
                            'cabinCode' => 'Y',
                            'statusCode' => SecondarySearchCriteria::STATUS_CODE_HAVE_WAITLISTED,
                            'passengerNameOrType' => new PassengerNameOrType([
                                'paxSurname' => 'John',
                                'paxType' => 'PAX',
                                'otherPaxType' => 'IN'
                            ]),
                            'origin' => new Origin([
                                'inHouseIdentification1' => 'OFFICEID',
                                'trueLocationId' => Origin::TRUE_LOCATION_ID_FRANKFURT,
                                'countryCode' => Origin::COUNTRY_CODE_GERMANY,
                                'systemCode' => Origin::SYSTEM_CODE_SABRE
                            ]),
                            'ticketArrangement' => SecondarySearchCriteria::TICKET_ARRANGEMENT_AIRPORT,
                            'numericRange' => new NumericRange([
                                'dateType' => NumericRange::DATE_TYPE_DURATION_IN_HOURS,
                                'min' => 1,
                                'max' => 2
                            ]),
                            'segmentName' => SecondarySearchCriteria::SEGMENT_NAME_AIR_SEQUENCE_NUMBER,
                            'frequentFlyer' => new FrequentFlyer([
                                'tierLevel' => '1',
                                'priorityCode' => FrequentFlyer::PRIORITY_CODE_LEVEL_1_TIER_1,
                                'tierDescription' => 'Tier 1'
                            ]),
                            'negoRecordLocator' => new NegoRecordLocator([
                                'negoRloc' => '123456',
                                'nego1Aid' => 1
                            ]),
                            'extendedPassengerName' => 'John Doe'
                        ])
                    ]
                ])
            ],
            'outputAggregation' => [
                new OutputAggregation([
                    'aggregationLevel' => 1,
                    'aggregationKey' => OutputAggregation::AGGREGATION_KEY_AIRLINE_PRIORITY_CODE,
                ])
            ],
            'queueDetails' => new QueueDetails([
                'number' => 1,
                'category' => 'category'
            ])
        ]);

        $struct = new ListPassengersByFlight($opt);
        $secondarySearchCriteria = $struct->searchCriteria[0]->secondarySearchCriteria[0];

        $this->assertInstanceOf(ListPassengersByFlight::class, $struct);

        $this->assertInstanceOf(FlightIdentificationStruct::class, $struct->flightDateQuery->flightIdentification);
        $this->assertEquals('LH', $struct->flightDateQuery->flightIdentification->carrierDetails->marketingCarrier);
        $this->assertEquals('123', $struct->flightDateQuery->flightIdentification->flightDetails->flightNumber);
        $this->assertEquals('D', $struct->flightDateQuery->flightIdentification->flightDetails->operationSuffix);
        $this->assertEquals('SIN', $struct->flightDateQuery->flightIdentification->boardPoint);
        $this->assertEquals('BKK', $struct->flightDateQuery->flightIdentification->offPoint);

        $this->assertInstanceOf(DateIdentificationStruct::class, $struct->flightDateQuery->dateIdentification);
        $this->assertEquals('2025', $struct->flightDateQuery->dateIdentification->dateTime->year);
        $this->assertEquals('12', $struct->flightDateQuery->dateIdentification->dateTime->month);
        $this->assertEquals('25', $struct->flightDateQuery->dateIdentification->dateTime->day);
        $this->assertEquals('00', $struct->flightDateQuery->dateIdentification->dateTime->hour);
        $this->assertEquals('00', $struct->flightDateQuery->dateIdentification->dateTime->minute);

        $this->assertInstanceOf(OutputSelectionOptionStruct::class, $struct->outputSelectionOption);
        $this->assertEquals(OutputSelection::OUTPUT_TYPE_COUNTERS_ONLY, $struct->outputSelectionOption->outputType->selectionDetails->option);
        $this->assertEquals(OutputSelection::ELEMENT_TYPE_AIR_SEGMENTS, $struct->outputSelectionOption->elementType[0]->segmentName);
        $this->assertEquals(OutputSelection::ELEMENT_TYPE_ASSOCIATED_CROSS_REFERENCE_RECORD, $struct->outputSelectionOption->elementType[1]->segmentName);

        $this->assertInstanceOf(SearchCriteriaStruct::class, $struct->searchCriteria[0]);
        $this->assertEquals(SearchCriteria::PRIMARY_SEARCH_CRITERION_BY_A_LLIANCE_FREQUENT_FLYER_DATA, $struct->searchCriteria[0]->primarySearchCriterion->selectionDetails->option);
        $this->assertEquals(SearchCriteria::NEGATIVE_MODE_NOP, $struct->searchCriteria[0]->negativeMode->booleanExpression->codeOperator);
        $this->assertEquals(SearchCriteria::ASSOCIATION_MODE_AND, $struct->searchCriteria[0]->associationMode->booleanExpression->codeOperator);

        $this->assertInstanceOf(SecondarySearchCriteriaStruct::class, $secondarySearchCriteria);
        $this->assertInstanceOf(TriggerMarker::class, $secondarySearchCriteria->triggerMarker);
        $this->assertEquals('C', $secondarySearchCriteria->classOfServiceValue->bookingClassDetails->designator);
        $this->assertEquals('LH', $secondarySearchCriteria->carrierOrFlightValue->carrierDetails->marketingCarrier);
        $this->assertEquals('123', $secondarySearchCriteria->carrierOrFlightValue->flightDetails->flightNumber);
        $this->assertNull($secondarySearchCriteria->carrierOrFlightValue->flightDetails->operationSuffix);
        $this->assertEquals('SIN', $secondarySearchCriteria->carrierOrFlightValue->boardPoint);
        $this->assertEquals('BKK', $secondarySearchCriteria->carrierOrFlightValue->offPoint);

        $this->assertInstanceOf(SsrOsiSkValue::class, $secondarySearchCriteria->ssrOsiSkValue);
        $this->assertEquals('OSI', $secondarySearchCriteria->ssrOsiSkValue->specialRequirementsInfo->ssrCode);
        $this->assertEquals('NBML', $secondarySearchCriteria->ssrOsiSkValue->specialRequirementsInfo->serviceType);
        $this->assertEquals('12A', $secondarySearchCriteria->ssrOsiSkValue->seatDetails->seatNumber);

        $this->assertEquals('Y', $secondarySearchCriteria->cabinValue->cabinCode);
        $this->assertEquals(SecondarySearchCriteria::STATUS_CODE_HAVE_WAITLISTED, $secondarySearchCriteria->statusCodeValue->statusCode);

        $this->assertInstanceOf(QueueDetailsStruct::class, $struct->queueDetails);
        $this->assertEquals(1, $struct->queueDetails->queue->queueDetails->number);
        $this->assertEquals('category', $struct->queueDetails->category->subQueueInfoDetails->itemNumber);
    }
}
