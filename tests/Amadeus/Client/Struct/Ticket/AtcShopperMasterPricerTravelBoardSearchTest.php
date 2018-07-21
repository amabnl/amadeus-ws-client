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

namespace Test\Amadeus\Client\Struct\Ticket;

use Amadeus\Client\RequestOptions\Fare\MPDate;
use Amadeus\Client\RequestOptions\Fare\MPItinerary;
use Amadeus\Client\RequestOptions\Fare\MPLocation;
use Amadeus\Client\RequestOptions\Fare\MPPassenger;
use Amadeus\Client\RequestOptions\Ticket\ReqSegOptions;
use Amadeus\Client\RequestOptions\TicketAtcShopperMpTbSearchOptions;
use Amadeus\Client\Struct\Fare\MasterPricer\PricingTicketing;
use Amadeus\Client\Struct\Fare\MasterPricer\UnitNumberDetail;
use Amadeus\Client\Struct\Ticket\AtcShopperMasterPricerTravelBoardSearch;
use Amadeus\Client\Struct\Ticket\CheckEligibility\ActionIdentification;
use Test\Amadeus\BaseTestCase;

/**
 * AtcShopperMasterPricerTravelBoardSearchTest
 *
 * @package Test\Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AtcShopperMasterPricerTravelBoardSearchTest extends BaseTestCase
{
    /**
     * 5.1 Operation: 0 - Search With Mandatory Elements
     *
     */
    public function testCanMakeMessageWithMandatoryParameters()
    {
        $opt = new TicketAtcShopperMpTbSearchOptions([
            'nrOfRequestedPassengers' => 2,
            'nrOfRequestedResults' => 2,
            'passengers' => [
                new MPPassenger([
                    'type' => MPPassenger::TYPE_ADULT,
                    'count' => 1
                ]),
                new MPPassenger([
                    'type' => MPPassenger::TYPE_CHILD,
                    'count' => 1
                ])
            ],
            'flightOptions' => [
                TicketAtcShopperMpTbSearchOptions::FLIGHTOPT_PUBLISHED,
                TicketAtcShopperMpTbSearchOptions::FLIGHTOPT_UNIFARES
            ],
            'itinerary' => [
                new MPItinerary([
                    'segmentReference' => 1,
                    'departureLocation' => new MPLocation(['city' => 'MAD']),
                    'arrivalLocation' => new MPLocation(['city' => 'LHR']),
                    'date' => new MPDate([
                        'date' => new \DateTime('2013-08-12T00:00:00+0000', new \DateTimeZone('UTC'))
                    ])
                ]),
                new MPItinerary([
                    'segmentReference' => 2,
                    'departureLocation' => new MPLocation(['city' => 'LHR']),
                    'arrivalLocation' => new MPLocation(['city' => 'MAD']),
                    'date' => new MPDate([
                        'date' => new \DateTime('2013-12-12T00:00:00+0000', new \DateTimeZone('UTC'))
                    ])
                ])
            ],
            'ticketNumbers' => [
                '0572187777498',
                '0572187777499'
            ],
            'requestedSegments' => [
                new ReqSegOptions([
                    'requestCode' => ReqSegOptions::REQUEST_CODE_KEEP_FLIGHTS_AND_FARES,
                    'connectionLocations' => [
                        'MAD',
                        'LHR'
                    ]
                ]),
                new ReqSegOptions([
                    'requestCode' => ReqSegOptions::REQUEST_CODE_CHANGE_REQUESTED_SEGMENT,
                    'connectionLocations' => [
                        'LHR',
                        'MAD'
                    ]
                ])
            ]
        ]);

        $msg = new AtcShopperMasterPricerTravelBoardSearch($opt);

        $this->assertCount(2, $msg->numberOfUnit->unitNumberDetail);
        $this->assertEquals(2, $msg->numberOfUnit->unitNumberDetail[0]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_PASS, $msg->numberOfUnit->unitNumberDetail[0]->typeOfUnit);
        $this->assertEquals(2, $msg->numberOfUnit->unitNumberDetail[1]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_RESULTS, $msg->numberOfUnit->unitNumberDetail[1]->typeOfUnit);

        $this->assertNull($msg->combinationFareFamilies);
        $this->assertNull($msg->customerRef);
        $this->assertEmpty($msg->fareFamilies);
        $this->assertNull($msg->feeOption);
        $this->assertNull($msg->formOfPaymentByPassenger);
        $this->assertNull($msg->globalOptions);
        $this->assertNull($msg->officeIdDetails);
        $this->assertNull($msg->priceToBeat);
        $this->assertNull($msg->solutionFamily);
        $this->assertNull($msg->taxInfo);
        $this->assertEmpty($msg->valueSearch);
        $this->assertNull($msg->travelFlightInfo);

        $this->assertCount(2, $msg->paxReference);
        $this->assertEquals('ADT', $msg->paxReference[0]->ptc[0]);
        $this->assertCount(1, $msg->paxReference[0]->traveller);
        $this->assertEquals(1, $msg->paxReference[0]->traveller[0]->ref);

        $this->assertEquals('CH', $msg->paxReference[1]->ptc[0]);
        $this->assertCount(1, $msg->paxReference[1]->traveller);
        $this->assertEquals(2, $msg->paxReference[1]->traveller[0]->ref);

        $this->assertCount(2, $msg->itinerary);
        $this->assertEquals(1, $msg->itinerary[0]->requestedSegmentRef->segRef);
        $this->assertNull($msg->itinerary[0]->requestedSegmentRef->locationForcing);
        $this->assertEquals('MAD', $msg->itinerary[0]->departureLocalization->departurePoint->locationId);
        $this->assertEquals('LHR', $msg->itinerary[0]->arrivalLocalization ->arrivalPointDetails->locationId);
        $this->assertEquals('120813', $msg->itinerary[0]->timeDetails->firstDateTimeDetail->date);
        $this->assertNull($msg->itinerary[0]->timeDetails->firstDateTimeDetail->time);
        $this->assertNull($msg->itinerary[0]->timeDetails->firstDateTimeDetail->timeWindow);
        $this->assertNull($msg->itinerary[0]->timeDetails->firstDateTimeDetail->timeQualifier);
        $this->assertNull($msg->itinerary[0]->timeDetails->rangeOfDate);

        $this->assertEquals(2, $msg->itinerary[1]->requestedSegmentRef->segRef);
        $this->assertNull($msg->itinerary[1]->requestedSegmentRef->locationForcing);
        $this->assertEquals('LHR', $msg->itinerary[1]->departureLocalization->departurePoint->locationId);
        $this->assertEquals('MAD', $msg->itinerary[1]->arrivalLocalization ->arrivalPointDetails->locationId);
        $this->assertEquals('121213', $msg->itinerary[1]->timeDetails->firstDateTimeDetail->date);
        $this->assertNull($msg->itinerary[1]->timeDetails->firstDateTimeDetail->time);
        $this->assertNull($msg->itinerary[1]->timeDetails->firstDateTimeDetail->timeWindow);
        $this->assertNull($msg->itinerary[1]->timeDetails->firstDateTimeDetail->timeQualifier);
        $this->assertNull($msg->itinerary[1]->timeDetails->rangeOfDate);

        $this->assertCount(2, $msg->ticketChangeInfo->ticketNumberDetails->documentDetails);
        $this->assertEquals('0572187777498', $msg->ticketChangeInfo->ticketNumberDetails->documentDetails[0]->number);
        $this->assertEquals('0572187777499', $msg->ticketChangeInfo->ticketNumberDetails->documentDetails[1]->number);

        $this->assertCount(2, $msg->ticketChangeInfo->ticketRequestedSegments);
        $this->assertEquals(ActionIdentification::REQ_KEEP_FLIGHTS_AND_FARES, $msg->ticketChangeInfo->ticketRequestedSegments[0]->actionIdentification->actionRequestCode);
        $this->assertNull($msg->ticketChangeInfo->ticketRequestedSegments[0]->actionIdentification->productDetails);
        $this->assertCount(2, $msg->ticketChangeInfo->ticketRequestedSegments[0]->connectPointDetails->connectionDetails);
        $this->assertEquals('MAD', $msg->ticketChangeInfo->ticketRequestedSegments[0]->connectPointDetails->connectionDetails[0]->location);
        $this->assertEquals('LHR', $msg->ticketChangeInfo->ticketRequestedSegments[0]->connectPointDetails->connectionDetails[1]->location);

        $this->assertEquals(ActionIdentification::REQ_CHANGE_REQUESTED_SEGMENT, $msg->ticketChangeInfo->ticketRequestedSegments[1]->actionIdentification->actionRequestCode);
        $this->assertNull($msg->ticketChangeInfo->ticketRequestedSegments[1]->actionIdentification->productDetails);
        $this->assertCount(2, $msg->ticketChangeInfo->ticketRequestedSegments[1]->connectPointDetails->connectionDetails);
        $this->assertEquals('LHR', $msg->ticketChangeInfo->ticketRequestedSegments[1]->connectPointDetails->connectionDetails[0]->location);
        $this->assertEquals('MAD', $msg->ticketChangeInfo->ticketRequestedSegments[1]->connectPointDetails->connectionDetails[1]->location);

        $this->assertCount(2, $msg->fareOptions->pricingTickInfo->pricingTicketing->priceType);
        $this->assertEquals(
            [
                PricingTicketing::PRICETYPE_PUBLISHEDFARES,
                PricingTicketing::PRICETYPE_UNIFARES
            ],
            $msg->fareOptions->pricingTickInfo->pricingTicketing->priceType
        );
    }
}
