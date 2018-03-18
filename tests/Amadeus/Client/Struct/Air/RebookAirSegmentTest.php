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

namespace Test\Amadeus\Client\Struct\Air;

use Amadeus\Client\RequestOptions\Air\RebookAirSegment\Itinerary;
use Amadeus\Client\RequestOptions\Air\RebookAirSegment\Segment;
use Amadeus\Client\RequestOptions\AirRebookAirSegmentOptions;
use Amadeus\Client\Struct\Air\RebookAirSegment;
use Test\Amadeus\BaseTestCase;

/**
 * RebookAirSegmentTest
 *
 * @package Test\Amadeus\Client\Struct\Air
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class RebookAirSegmentTest extends BaseTestCase
{
    /**
     * 5.2 Operation: Class Rebook after pricing PNR with lower fare
     */
    public function testCanMakeClassRebookAfterPricingLowerFare()
    {
        $par = new AirRebookAirSegmentOptions([
            'bestPricerOption' => 2,
            'itinerary' => [
                new Itinerary([
                    'from' => 'FRA',
                    'to' => 'BKK',
                    'segments' => [
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040308220000', new \DateTimeZone('UTC')),
                            'arrivalDate' =>  \DateTime::createFromFormat('YmdHis','20040309141000', new \DateTimeZone('UTC')),
                            'dateVariation' => 1,
                            'from' => 'FRA',
                            'to' => 'BKK',
                            'companyCode' => 'LH',
                            'flightNumber' => '744',
                            'bookingClass' => 'F',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_CANCEL_SEGMENT
                        ]),
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040308220000', new \DateTimeZone('UTC')),
                            'arrivalTime' =>  \DateTime::createFromFormat('His','141000', new \DateTimeZone('UTC')),
                            'from' => 'FRA',
                            'to' => 'BKK',
                            'companyCode' => 'LH',
                            'flightNumber' => '744',
                            'bookingClass' => 'C',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_SELL_SEGMENT
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new RebookAirSegment($par);

        $this->assertEquals(2, $msg->bestPricerRecommendation->selectionDetails->optionInformation);
        $this->assertEquals('FXZ', $msg->bestPricerRecommendation->selectionDetails->option);

        $this->assertCount(1, $msg->originDestinationDetails);
        $this->assertEquals('FRA', $msg->originDestinationDetails[0]->originDestination->origin);
        $this->assertEquals('BKK', $msg->originDestinationDetails[0]->originDestination->destination);

        $this->assertCount(2, $msg->originDestinationDetails[0]->itineraryInfo);

        $this->assertEquals('080304', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->departureDate);
        $this->assertEquals('2200', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->departureTime);
        $this->assertEquals('090304', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->arrivalDate);
        $this->assertEquals('1410', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->arrivalTime);
        $this->assertEquals(1, $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->dateVariation);

        $this->assertEquals('FRA', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->boardPointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->boardPointDetails->trueLocation);

        $this->assertEquals('BKK', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->offpointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->offpointDetails->trueLocation);

        $this->assertEquals('LH', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->companyDetails->marketingCompany);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->companyDetails->operatingCompany);

        $this->assertEquals('744', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightIdentification->flightNumber);
        $this->assertEquals('F', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightIdentification->bookingClass);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightIdentification->modifier);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightIdentification->operationalSuffix);

        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightTypeDetails);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->marriageDetails);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->specialSegment);

        $this->assertEquals(RebookAirSegment\RelatedFlightInfo::STATUS_CANCEL_SEGMENT, $msg->originDestinationDetails[0]->itineraryInfo[0]->relatedFlightInfo->statusCode);
        $this->assertEquals(1, $msg->originDestinationDetails[0]->itineraryInfo[0]->relatedFlightInfo->quantity);

        $this->assertEquals('080304', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->departureDate);
        $this->assertEquals('2200', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->departureTime);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->arrivalDate);
        $this->assertEquals('1410', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->arrivalTime);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->dateVariation);

        $this->assertEquals('FRA', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->boardPointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->boardPointDetails->trueLocation);

        $this->assertEquals('BKK', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->offpointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->offpointDetails->trueLocation);

        $this->assertEquals('LH', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->companyDetails->marketingCompany);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->companyDetails->operatingCompany);

        $this->assertEquals('744', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightIdentification->flightNumber);
        $this->assertEquals('C', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightIdentification->bookingClass);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightIdentification->modifier);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightIdentification->operationalSuffix);

        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightTypeDetails);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->marriageDetails);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->specialSegment);

        $this->assertEquals(RebookAirSegment\RelatedFlightInfo::STATUS_SELL_SEGMENT, $msg->originDestinationDetails[0]->itineraryInfo[1]->relatedFlightInfo->statusCode);
        $this->assertEquals(1, $msg->originDestinationDetails[0]->itineraryInfo[1]->relatedFlightInfo->quantity);
    }

    /**
     * 5.3 Operation: Force Rebook
     */
    public function testCanMakeForceRebook()
    {
        $par = new AirRebookAirSegmentOptions([
            'itinerary' => [
                new Itinerary([
                    'from' => 'FRA',
                    'to' => 'BKK',
                    'segments' => [
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040308220000', new \DateTimeZone('UTC')),
                            'arrivalDate' =>  \DateTime::createFromFormat('YmdHis','20040309141000', new \DateTimeZone('UTC')),
                            'dateVariation' => 1,
                            'from' => 'FRA',
                            'to' => 'BKK',
                            'companyCode' => 'LH',
                            'flightNumber' => '744',
                            'bookingClass' => 'F',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_CANCEL_SEGMENT
                        ]),
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040308220000', new \DateTimeZone('UTC')),
                            'arrivalTime' =>  \DateTime::createFromFormat('His','141000', new \DateTimeZone('UTC')),
                            'from' => 'FRA',
                            'to' => 'BKK',
                            'companyCode' => 'LH',
                            'flightNumber' => '744',
                            'bookingClass' => 'C',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_FORCE_BOOKING
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new RebookAirSegment($par);

        $this->assertNull($msg->bestPricerRecommendation);

        $this->assertCount(1, $msg->originDestinationDetails);
        $this->assertEquals('FRA', $msg->originDestinationDetails[0]->originDestination->origin);
        $this->assertEquals('BKK', $msg->originDestinationDetails[0]->originDestination->destination);

        $this->assertCount(2, $msg->originDestinationDetails[0]->itineraryInfo);

        $this->assertEquals('080304', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->departureDate);
        $this->assertEquals('2200', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->departureTime);
        $this->assertEquals('090304', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->arrivalDate);
        $this->assertEquals('1410', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->arrivalTime);
        $this->assertEquals(1, $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->dateVariation);

        $this->assertEquals('FRA', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->boardPointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->boardPointDetails->trueLocation);

        $this->assertEquals('BKK', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->offpointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->offpointDetails->trueLocation);

        $this->assertEquals('LH', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->companyDetails->marketingCompany);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->companyDetails->operatingCompany);

        $this->assertEquals('744', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightIdentification->flightNumber);
        $this->assertEquals('F', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightIdentification->bookingClass);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightIdentification->modifier);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightIdentification->operationalSuffix);

        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightTypeDetails);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->marriageDetails);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->specialSegment);

        $this->assertEquals(RebookAirSegment\RelatedFlightInfo::STATUS_CANCEL_SEGMENT, $msg->originDestinationDetails[0]->itineraryInfo[0]->relatedFlightInfo->statusCode);
        $this->assertEquals(1, $msg->originDestinationDetails[0]->itineraryInfo[0]->relatedFlightInfo->quantity);

        $this->assertEquals('080304', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->departureDate);
        $this->assertEquals('2200', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->departureTime);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->arrivalDate);
        $this->assertEquals('1410', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->arrivalTime);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->dateVariation);

        $this->assertEquals('FRA', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->boardPointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->boardPointDetails->trueLocation);

        $this->assertEquals('BKK', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->offpointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->offpointDetails->trueLocation);

        $this->assertEquals('LH', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->companyDetails->marketingCompany);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->companyDetails->operatingCompany);

        $this->assertEquals('744', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightIdentification->flightNumber);
        $this->assertEquals('C', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightIdentification->bookingClass);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightIdentification->modifier);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightIdentification->operationalSuffix);

        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightTypeDetails);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->marriageDetails);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->specialSegment);

        $this->assertEquals(RebookAirSegment\RelatedFlightInfo::STATUS_FORCE_BOOKING, $msg->originDestinationDetails[0]->itineraryInfo[1]->relatedFlightInfo->statusCode);
        $this->assertEquals(1, $msg->originDestinationDetails[0]->itineraryInfo[1]->relatedFlightInfo->quantity);
    }

    /**
     * 5.4 Operation: Rebook Flight Number
     */
    public function testCanMakeMessageRebookFlightNumber()
    {
        $par = new AirRebookAirSegmentOptions([
            'itinerary' => [
                new Itinerary([
                    'from' => 'FRA',
                    'to' => 'SIN',
                    'segments' => [
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040308221000', new \DateTimeZone('UTC')),
                            'arrivalDate' =>  \DateTime::createFromFormat('YmdHis','20040309165000', new \DateTimeZone('UTC')),
                            'from' => 'FRA',
                            'to' => 'SIN',
                            'companyCode' => 'LH',
                            'flightNumber' => '778',
                            'bookingClass' => 'F',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_CANCEL_SEGMENT
                        ]),
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040308120000', new \DateTimeZone('UTC')),
                            'arrivalDate' =>  \DateTime::createFromFormat('YmdHis','20040309065500', new \DateTimeZone('UTC')),
                            'from' => 'FRA',
                            'to' => 'SIN',
                            'companyCode' => 'LH',
                            'flightNumber' => '9762',
                            'bookingClass' => 'F',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_SELL_SEGMENT
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new RebookAirSegment($par);

        $this->assertNull($msg->bestPricerRecommendation);

        $this->assertCount(1, $msg->originDestinationDetails);
        $this->assertEquals('FRA', $msg->originDestinationDetails[0]->originDestination->origin);
        $this->assertEquals('SIN', $msg->originDestinationDetails[0]->originDestination->destination);

        $this->assertCount(2, $msg->originDestinationDetails[0]->itineraryInfo);

        $this->assertEquals('080304', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->departureDate);
        $this->assertEquals('2210', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->departureTime);
        $this->assertEquals('090304', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->arrivalDate);
        $this->assertEquals('1650', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->arrivalTime);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->dateVariation);

        $this->assertEquals('FRA', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->boardPointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->boardPointDetails->trueLocation);

        $this->assertEquals('SIN', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->offpointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->offpointDetails->trueLocation);

        $this->assertEquals('LH', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->companyDetails->marketingCompany);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->companyDetails->operatingCompany);

        $this->assertEquals('778', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightIdentification->flightNumber);
        $this->assertEquals('F', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightIdentification->bookingClass);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightIdentification->modifier);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightIdentification->operationalSuffix);

        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightTypeDetails);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->marriageDetails);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->specialSegment);

        $this->assertEquals(RebookAirSegment\RelatedFlightInfo::STATUS_CANCEL_SEGMENT, $msg->originDestinationDetails[0]->itineraryInfo[0]->relatedFlightInfo->statusCode);
        $this->assertEquals(1, $msg->originDestinationDetails[0]->itineraryInfo[0]->relatedFlightInfo->quantity);

        $this->assertEquals('080304', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->departureDate);
        $this->assertEquals('1200', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->departureTime);
        $this->assertEquals('090304', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->arrivalDate);
        $this->assertEquals('0655', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->arrivalTime);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->dateVariation);

        $this->assertEquals('FRA', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->boardPointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->boardPointDetails->trueLocation);

        $this->assertEquals('SIN', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->offpointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->offpointDetails->trueLocation);

        $this->assertEquals('LH', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->companyDetails->marketingCompany);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->companyDetails->operatingCompany);

        $this->assertEquals('9762', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightIdentification->flightNumber);
        $this->assertEquals('F', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightIdentification->bookingClass);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightIdentification->modifier);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightIdentification->operationalSuffix);

        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightTypeDetails);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->marriageDetails);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->specialSegment);

        $this->assertEquals(RebookAirSegment\RelatedFlightInfo::STATUS_SELL_SEGMENT, $msg->originDestinationDetails[0]->itineraryInfo[1]->relatedFlightInfo->statusCode);
        $this->assertEquals(1, $msg->originDestinationDetails[0]->itineraryInfo[1]->relatedFlightInfo->quantity);
    }

    /**
     * 5.6 Operation: Rebook Two Segment Classes
     */
    public function testCanRebookTwoSegmentClasses()
    {
        $par = new AirRebookAirSegmentOptions([
            'itinerary' => [
                new Itinerary([
                    'from' => 'FRA',
                    'to' => 'BKK',
                    'segments' => [
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040308220000', new \DateTimeZone('UTC')),
                            'arrivalDate' =>  \DateTime::createFromFormat('YmdHis','20040309141000', new \DateTimeZone('UTC')),
                            'dateVariation' => 1,
                            'from' => 'FRA',
                            'to' => 'BKK',
                            'companyCode' => 'LH',
                            'flightNumber' => '744',
                            'bookingClass' => 'F',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_CANCEL_SEGMENT
                        ]),
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040308220000', new \DateTimeZone('UTC')),
                            'arrivalDate' =>  \DateTime::createFromFormat('YmdHis','20040309141000', new \DateTimeZone('UTC')),
                            'dateVariation' => 1,
                            'from' => 'FRA',
                            'to' => 'BKK',
                            'companyCode' => 'LH',
                            'flightNumber' => '744',
                            'bookingClass' => 'C',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_SELL_SEGMENT
                        ])
                    ]
                ]),
                new Itinerary([
                    'from' => 'BKK',
                    'to' => 'SIN',
                    'segments' => [
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040309153000', new \DateTimeZone('UTC')),
                            'arrivalDate' =>  \DateTime::createFromFormat('YmdHis','20040309184500', new \DateTimeZone('UTC')),
                            'dateVariation' => 0,
                            'from' => 'BKK',
                            'to' => 'SIN',
                            'companyCode' => 'LX',
                            'flightNumber' => '182',
                            'bookingClass' => 'J',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_CANCEL_SEGMENT
                        ]),
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040309153000', new \DateTimeZone('UTC')),
                            'arrivalDate' =>  \DateTime::createFromFormat('YmdHis','20040309184500', new \DateTimeZone('UTC')),
                            'dateVariation' => 0,
                            'from' => 'BKK',
                            'to' => 'SIN',
                            'companyCode' => 'LX',
                            'flightNumber' => '182',
                            'bookingClass' => 'C',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_SELL_SEGMENT
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new RebookAirSegment($par);

        $this->assertNull($msg->bestPricerRecommendation);

        $this->assertCount(2, $msg->originDestinationDetails);

        $this->assertEquals('FRA', $msg->originDestinationDetails[0]->originDestination->origin);
        $this->assertEquals('BKK', $msg->originDestinationDetails[0]->originDestination->destination);

        $this->assertCount(2, $msg->originDestinationDetails[0]->itineraryInfo);

        $this->assertEquals('080304', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->departureDate);
        $this->assertEquals('2200', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->departureTime);
        $this->assertEquals('090304', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->arrivalDate);
        $this->assertEquals('1410', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->arrivalTime);
        $this->assertEquals(1, $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightDate->dateVariation);

        $this->assertEquals('FRA', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->boardPointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->boardPointDetails->trueLocation);

        $this->assertEquals('BKK', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->offpointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->offpointDetails->trueLocation);

        $this->assertEquals('LH', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->companyDetails->marketingCompany);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->companyDetails->operatingCompany);

        $this->assertEquals('744', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightIdentification->flightNumber);
        $this->assertEquals('F', $msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightIdentification->bookingClass);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightIdentification->modifier);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightIdentification->operationalSuffix);

        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->flightTypeDetails);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->marriageDetails);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->flightDetails->specialSegment);

        $this->assertEquals(RebookAirSegment\RelatedFlightInfo::STATUS_CANCEL_SEGMENT, $msg->originDestinationDetails[0]->itineraryInfo[0]->relatedFlightInfo->statusCode);
        $this->assertEquals(1, $msg->originDestinationDetails[0]->itineraryInfo[0]->relatedFlightInfo->quantity);

        $this->assertEquals('080304', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->departureDate);
        $this->assertEquals('2200', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->departureTime);
        $this->assertEquals('090304', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->arrivalDate);
        $this->assertEquals('1410', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->arrivalTime);
        $this->assertEquals(1, $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightDate->dateVariation);

        $this->assertEquals('FRA', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->boardPointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->boardPointDetails->trueLocation);

        $this->assertEquals('BKK', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->offpointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->offpointDetails->trueLocation);

        $this->assertEquals('LH', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->companyDetails->marketingCompany);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->companyDetails->operatingCompany);

        $this->assertEquals('744', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightIdentification->flightNumber);
        $this->assertEquals('C', $msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightIdentification->bookingClass);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightIdentification->modifier);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightIdentification->operationalSuffix);

        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->flightTypeDetails);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->marriageDetails);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[1]->flightDetails->specialSegment);

        $this->assertEquals(RebookAirSegment\RelatedFlightInfo::STATUS_SELL_SEGMENT, $msg->originDestinationDetails[0]->itineraryInfo[1]->relatedFlightInfo->statusCode);
        $this->assertEquals(1, $msg->originDestinationDetails[0]->itineraryInfo[1]->relatedFlightInfo->quantity);


        $this->assertEquals('BKK', $msg->originDestinationDetails[1]->originDestination->origin);
        $this->assertEquals('SIN', $msg->originDestinationDetails[1]->originDestination->destination);

        $this->assertCount(2, $msg->originDestinationDetails[1]->itineraryInfo);

        $this->assertEquals('090304', $msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->flightDate->departureDate);
        $this->assertEquals('1530', $msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->flightDate->departureTime);
        $this->assertEquals('090304', $msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->flightDate->arrivalDate);
        $this->assertEquals('1845', $msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->flightDate->arrivalTime);
        $this->assertEquals(0, $msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->flightDate->dateVariation);

        $this->assertEquals('BKK', $msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->boardPointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->boardPointDetails->trueLocation);

        $this->assertEquals('SIN', $msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->offpointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->offpointDetails->trueLocation);

        $this->assertEquals('LX', $msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->companyDetails->marketingCompany);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->companyDetails->operatingCompany);

        $this->assertEquals('182', $msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->flightIdentification->flightNumber);
        $this->assertEquals('J', $msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->flightIdentification->bookingClass);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->flightIdentification->modifier);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->flightIdentification->operationalSuffix);

        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->flightTypeDetails);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->marriageDetails);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->flightDetails->specialSegment);

        $this->assertEquals(RebookAirSegment\RelatedFlightInfo::STATUS_CANCEL_SEGMENT, $msg->originDestinationDetails[1]->itineraryInfo[0]->relatedFlightInfo->statusCode);
        $this->assertEquals(1, $msg->originDestinationDetails[1]->itineraryInfo[0]->relatedFlightInfo->quantity);

        $this->assertEquals('090304', $msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->flightDate->departureDate);
        $this->assertEquals('1530', $msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->flightDate->departureTime);
        $this->assertEquals('090304', $msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->flightDate->arrivalDate);
        $this->assertEquals('1845', $msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->flightDate->arrivalTime);
        $this->assertEquals(0, $msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->flightDate->dateVariation);

        $this->assertEquals('BKK', $msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->boardPointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->boardPointDetails->trueLocation);

        $this->assertEquals('SIN', $msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->offpointDetails->trueLocationId);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->offpointDetails->trueLocation);

        $this->assertEquals('LX', $msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->companyDetails->marketingCompany);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->companyDetails->operatingCompany);

        $this->assertEquals('182', $msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->flightIdentification->flightNumber);
        $this->assertEquals('C', $msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->flightIdentification->bookingClass);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->flightIdentification->modifier);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->flightIdentification->operationalSuffix);

        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->flightTypeDetails);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->marriageDetails);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[1]->flightDetails->specialSegment);

        $this->assertEquals(RebookAirSegment\RelatedFlightInfo::STATUS_SELL_SEGMENT, $msg->originDestinationDetails[1]->itineraryInfo[1]->relatedFlightInfo->statusCode);
        $this->assertEquals(1, $msg->originDestinationDetails[1]->itineraryInfo[1]->relatedFlightInfo->quantity);
    }
}
