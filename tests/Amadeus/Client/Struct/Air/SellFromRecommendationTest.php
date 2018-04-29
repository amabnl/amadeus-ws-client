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

use Amadeus\Client\RequestOptions\Air\SellFromRecommendation\Itinerary;
use Amadeus\Client\RequestOptions\Air\SellFromRecommendation\Segment;
use Amadeus\Client\RequestOptions\AirSellFromRecommendationOptions;
use Amadeus\Client\Struct\Air\FlightTypeDetails;
use Amadeus\Client\Struct\Air\MessageFunctionDetails;
use Amadeus\Client\Struct\Air\RelatedproductInformation;
use Amadeus\Client\Struct\Air\SellFromRecommendation;
use Test\Amadeus\BaseTestCase;

/**
 * SellFromRecommendationTest
 *
 * @package Test\Amadeus\Client\Struct\Air
 * @author dieter <dermikagh@gmail.com>
 */
class SellFromRecommendationTest extends BaseTestCase
{
    public function testCanMakeBaseSellFromRecommendationMessage()
    {
        $opt = new AirSellFromRecommendationOptions([
            'itinerary' => [
                new Itinerary([
                    'from' => 'BRU',
                    'to' => 'LON',
                    'segments' => [
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('Ymd', '20170120', new \DateTimeZone('UTC')),
                            'from' => 'BRU',
                            'to' => 'LGW',
                            'companyCode' => 'SN',
                            'flightNumber' => '123',
                            'bookingClass' => 'Y',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_SELL_SEGMENT
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new SellFromRecommendation($opt);

        $this->assertEquals(1, count($msg->itineraryDetails));
        $this->assertEquals(MessageFunctionDetails::MSGFUNC_LOWEST_FARE, $msg->messageActionDetails->messageFunctionDetails->messageFunction);
        $this->assertEquals(MessageFunctionDetails::MSGFUNC_CANCEL_IF_UNSUCCESSFUL, $msg->messageActionDetails->messageFunctionDetails->additionalMessageFunction);
        $this->assertInstanceOf('Amadeus\Client\Struct\Air\ItineraryDetails', $msg->itineraryDetails[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Air\Message', $msg->itineraryDetails[0]->message);
        $this->assertEquals(MessageFunctionDetails::MSGFUNC_LOWEST_FARE, $msg->itineraryDetails[0]->message->messageFunctionDetails->messageFunction);
        $this->assertEquals('BRU', $msg->itineraryDetails[0]->originDestinationDetails->origin);
        $this->assertEquals('LON', $msg->itineraryDetails[0]->originDestinationDetails->destination);
        $this->assertEquals(1, count($msg->itineraryDetails[0]->segmentInformation));
        $this->assertInstanceOf('Amadeus\Client\Struct\Air\SegmentInformation', $msg->itineraryDetails[0]->segmentInformation[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Air\TravelProductInformation', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation);
        $this->assertEquals('200117', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation->flightDate->departureDate);
        $this->assertEquals('BRU', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation->boardPointDetails->trueLocationId);
        $this->assertEquals('LGW', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation->offpointDetails->trueLocationId);
        $this->assertEquals('123', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation->flightIdentification->flightNumber);
        $this->assertEquals('Y', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation->flightIdentification->bookingClass);
        $this->assertEquals('SN', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation->companyDetails->marketingCompany);
        $this->assertInstanceOf('Amadeus\Client\Struct\Air\RelatedproductInformation', $msg->itineraryDetails[0]->segmentInformation[0]->relatedproductInformation);
        $this->assertEquals(RelatedproductInformation::STATUS_SELL_SEGMENT, $msg->itineraryDetails[0]->segmentInformation[0]->relatedproductInformation->statusCode);
        $this->assertEquals(1, $msg->itineraryDetails[0]->segmentInformation[0]->relatedproductInformation->quantity);
    }

    public function testCanMakeBaseSellFromRecommendationWithOptionalsMessage()
    {
        $opt = new AirSellFromRecommendationOptions([
            'itinerary' => [
                new Itinerary([
                    'from' => 'SFO',
                    'to' => 'NYC',
                    'segments' => [
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('Ymd Hi','20180315 1540', new \DateTimeZone('UTC')),
                            'arrivalDate' => \DateTime::createFromFormat('Ymd Hi','20180316 0010', new \DateTimeZone('UTC')),
                            'from' => 'SFO',
                            'to' => 'JFK',
                            'companyCode' => 'AA',
                            'flightNumber' => '20',
                            'bookingClass' => 'S',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_SELL_SEGMENT
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new SellFromRecommendation($opt);

        $this->assertEquals(1, count($msg->itineraryDetails));
        $this->assertEquals(MessageFunctionDetails::MSGFUNC_LOWEST_FARE, $msg->messageActionDetails->messageFunctionDetails->messageFunction);
        $this->assertEquals(MessageFunctionDetails::MSGFUNC_CANCEL_IF_UNSUCCESSFUL, $msg->messageActionDetails->messageFunctionDetails->additionalMessageFunction);
        $this->assertInstanceOf('Amadeus\Client\Struct\Air\ItineraryDetails', $msg->itineraryDetails[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Air\Message', $msg->itineraryDetails[0]->message);
        $this->assertEquals(MessageFunctionDetails::MSGFUNC_LOWEST_FARE, $msg->itineraryDetails[0]->message->messageFunctionDetails->messageFunction);
        $this->assertEquals('SFO', $msg->itineraryDetails[0]->originDestinationDetails->origin);
        $this->assertEquals('NYC', $msg->itineraryDetails[0]->originDestinationDetails->destination);
        $this->assertEquals(1, count($msg->itineraryDetails[0]->segmentInformation));
        $this->assertInstanceOf('Amadeus\Client\Struct\Air\SegmentInformation', $msg->itineraryDetails[0]->segmentInformation[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Air\TravelProductInformation', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation);
        $this->assertEquals('150318', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation->flightDate->departureDate);
        $this->assertEquals('1540', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation->flightDate->departureTime);
        $this->assertEquals('160318', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation->flightDate->arrivalDate);
        $this->assertEquals('0010', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation->flightDate->arrivalTime);
        $this->assertEquals('SFO', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation->boardPointDetails->trueLocationId);
        $this->assertEquals('JFK', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation->offpointDetails->trueLocationId);
        $this->assertEquals('20', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation->flightIdentification->flightNumber);
        $this->assertEquals('S', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation->flightIdentification->bookingClass);
        $this->assertEquals('AA', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation->companyDetails->marketingCompany);
        $this->assertInstanceOf('Amadeus\Client\Struct\Air\RelatedproductInformation', $msg->itineraryDetails[0]->segmentInformation[0]->relatedproductInformation);
        $this->assertEquals(RelatedproductInformation::STATUS_SELL_SEGMENT, $msg->itineraryDetails[0]->segmentInformation[0]->relatedproductInformation->statusCode);
        $this->assertEquals(1, $msg->itineraryDetails[0]->segmentInformation[0]->relatedproductInformation->quantity);
    }

    public function testCanMakeSellFromRecommendationWithLocalAvailability()
    {
        $opt = new AirSellFromRecommendationOptions([
            'itinerary' => [
                new Itinerary([
                    'from' => 'BOS',
                    'to' => 'HNL',
                    'segments' => [
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('Ymd Hi','20180513 0800', new \DateTimeZone('UTC')),
                            'arrivalDate' => \DateTime::createFromFormat('Ymd Hi','20180513 1105', new \DateTimeZone('UTC')),
                            'from' => 'BOS',
                            'to' => 'SFO',
                            'companyCode' => 'UA',
                            'flightNumber' => '207',
                            'bookingClass' => 'W',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_SELL_SEGMENT,
                            'flightTypeDetails' => FlightTypeDetails::INDICATOR_LOCAL_AVAILABILITY,
                        ]),
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('Ymd Hi','20180513 1635', new \DateTimeZone('UTC')),
                            'arrivalDate' => \DateTime::createFromFormat('Ymd Hi','20180513 1853', new \DateTimeZone('UTC')),
                            'from' => 'SFO',
                            'to' => 'HNL',
                            'companyCode' => 'UA',
                            'flightNumber' => '1509',
                            'bookingClass' => 'W',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_SELL_SEGMENT,
                            'flightTypeDetails' => FlightTypeDetails::INDICATOR_LOCAL_AVAILABILITY,
                        ]),
                    ],
                ]),
            ],
        ]);

        $msg = new SellFromRecommendation($opt);

        $this->assertEquals(1, count($msg->itineraryDetails));
        $this->assertEquals(MessageFunctionDetails::MSGFUNC_LOWEST_FARE, $msg->messageActionDetails->messageFunctionDetails->messageFunction);
        $this->assertEquals(MessageFunctionDetails::MSGFUNC_CANCEL_IF_UNSUCCESSFUL, $msg->messageActionDetails->messageFunctionDetails->additionalMessageFunction);
        $this->assertInstanceOf('Amadeus\Client\Struct\Air\ItineraryDetails', $msg->itineraryDetails[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Air\Message', $msg->itineraryDetails[0]->message);
        $this->assertEquals(MessageFunctionDetails::MSGFUNC_LOWEST_FARE, $msg->itineraryDetails[0]->message->messageFunctionDetails->messageFunction);
        $this->assertEquals('BOS', $msg->itineraryDetails[0]->originDestinationDetails->origin);
        $this->assertEquals('HNL', $msg->itineraryDetails[0]->originDestinationDetails->destination);
        $this->assertEquals(2, count($msg->itineraryDetails[0]->segmentInformation));
        $this->assertInstanceOf('Amadeus\Client\Struct\Air\SegmentInformation', $msg->itineraryDetails[0]->segmentInformation[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Air\TravelProductInformation', $msg->itineraryDetails[0]->segmentInformation[0]->travelProductInformation);
        // asserting first segment
        $segmentOne = $msg->itineraryDetails[0]->segmentInformation[0];
        $this->assertEquals('130518', $segmentOne->travelProductInformation->flightDate->departureDate);
        $this->assertEquals('0800', $segmentOne->travelProductInformation->flightDate->departureTime);
        $this->assertEquals('130518', $segmentOne->travelProductInformation->flightDate->arrivalDate);
        $this->assertEquals('1105', $segmentOne->travelProductInformation->flightDate->arrivalTime);
        $this->assertEquals('BOS', $segmentOne->travelProductInformation->boardPointDetails->trueLocationId);
        $this->assertEquals('SFO', $segmentOne->travelProductInformation->offpointDetails->trueLocationId);
        $this->assertEquals('207', $segmentOne->travelProductInformation->flightIdentification->flightNumber);
        $this->assertEquals('W', $segmentOne->travelProductInformation->flightIdentification->bookingClass);
        $this->assertEquals('UA', $segmentOne->travelProductInformation->companyDetails->marketingCompany);
        $this->assertEquals('LA', $segmentOne->travelProductInformation->flightTypeDetails->flightIndicator[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Air\RelatedproductInformation', $segmentOne->relatedproductInformation);
        $this->assertEquals(RelatedproductInformation::STATUS_SELL_SEGMENT, $segmentOne->relatedproductInformation->statusCode);
        $this->assertEquals(1, $segmentOne->relatedproductInformation->quantity);
        // asserting second segment
        $segmentTwo = $msg->itineraryDetails[0]->segmentInformation[1];
        $this->assertEquals('130518', $segmentTwo->travelProductInformation->flightDate->departureDate);
        $this->assertEquals('1635', $segmentTwo->travelProductInformation->flightDate->departureTime);
        $this->assertEquals('130518', $segmentTwo->travelProductInformation->flightDate->arrivalDate);
        $this->assertEquals('1853', $segmentTwo->travelProductInformation->flightDate->arrivalTime);
        $this->assertEquals('SFO', $segmentTwo->travelProductInformation->boardPointDetails->trueLocationId);
        $this->assertEquals('HNL', $segmentTwo->travelProductInformation->offpointDetails->trueLocationId);
        $this->assertEquals('1509', $segmentTwo->travelProductInformation->flightIdentification->flightNumber);
        $this->assertEquals('W', $segmentTwo->travelProductInformation->flightIdentification->bookingClass);
        $this->assertEquals('UA', $segmentTwo->travelProductInformation->companyDetails->marketingCompany);
        $this->assertEquals('LA', $segmentTwo->travelProductInformation->flightTypeDetails->flightIndicator[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Air\RelatedproductInformation', $segmentTwo->relatedproductInformation);
        $this->assertEquals(RelatedproductInformation::STATUS_SELL_SEGMENT, $segmentTwo->relatedproductInformation->statusCode);
        $this->assertEquals(1, $segmentTwo->relatedproductInformation->quantity);
    }
}
