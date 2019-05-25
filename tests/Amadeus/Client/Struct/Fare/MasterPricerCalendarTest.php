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

use Amadeus\Client\RequestOptions\Fare\MPDate;
use Amadeus\Client\RequestOptions\Fare\MPItinerary;
use Amadeus\Client\RequestOptions\Fare\MPLocation;
use Amadeus\Client\RequestOptions\Fare\MPPassenger;
use Amadeus\Client\RequestOptions\Fare\MPTripDetails;
use Amadeus\Client\RequestOptions\FareMasterPricerCalendarOptions;
use Amadeus\Client\Struct\Fare\MasterPricer\RangeOfDate;
use Amadeus\Client\Struct\Fare\MasterPricer\TripDetails;
use Amadeus\Client\Struct\Fare\MasterPricer\UnitNumberDetail;
use Amadeus\Client\Struct\Fare\MasterPricerCalendar;
use Test\Amadeus\BaseTestCase;

/**
 * MasterPricerCalendarTest
 *
 * @package Test\Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MasterPricerCalendarTest extends BaseTestCase
{
    /**
     * Specifying only the mandatory elements allows for the search possibilities
     * of a specified range of dates, city pair
     * and commercial fare family or parameterized fare family.
     */
    public function testCanMakeBasicRequestWithMandatoryElements()
    {
        $opt = new FareMasterPricerCalendarOptions([
            'nrOfRequestedPassengers' => 1,
            'passengers' => [
                new MPPassenger([
                    'type' => MPPassenger::TYPE_ADULT,
                    'count' => 1
                ])
            ],
            'itinerary' => [
                new MPItinerary([
                    'departureLocation' => new MPLocation(['city' => 'NCE']),
                    'arrivalLocation' => new MPLocation(['city' => 'LHR']),
                    'date' => new MPDate([
                        'date' => new \DateTime('2010-04-03T00:00:00+0000', new \DateTimeZone('UTC')),
                        'rangeMode' => MPDate::RANGEMODE_MINUS_PLUS,
                        'range' => 3,
                    ])
                ])
            ]
        ]);

        $message = new MasterPricerCalendar($opt);

        $this->assertCount(1, $message->numberOfUnit->unitNumberDetail);
        $this->assertEquals(1, $message->numberOfUnit->unitNumberDetail[0]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_PASS, $message->numberOfUnit->unitNumberDetail[0]->typeOfUnit);

        $this->assertCount(1, $message->paxReference);
        $this->assertEquals(1, $message->paxReference[0]->traveller[0]->ref);
        $this->assertEquals('ADT', $message->paxReference[0]->ptc[0]);

        $this->assertCount(1, $message->itinerary);
        $this->assertEquals(1, $message->itinerary[0]->requestedSegmentRef->segRef);
        $this->assertEquals('NCE', $message->itinerary[0]->departureLocalization->departurePoint->locationId);
        $this->assertEquals('LHR', $message->itinerary[0]->arrivalLocalization->arrivalPointDetails->locationId);

        $this->assertEquals('030410', $message->itinerary[0]->timeDetails->firstDateTimeDetail->date);
        $this->assertEquals(3, $message->itinerary[0]->timeDetails->rangeOfDate->dayInterval);
        $this->assertEquals(RangeOfDate::RANGEMODE_MINUS_PLUS, $message->itinerary[0]->timeDetails->rangeOfDate->rangeQualifier);


        $this->assertEmpty($message->buckets);
        $this->assertNull($message->combinationFareFamilies);
        $this->assertNull($message->customerRef);
        $this->assertEmpty($message->fareFamilies);
        $this->assertNull($message->fareOptions);
        $this->assertNull($message->feeOption);
        $this->assertNull($message->customerRef);
        $this->assertNull($message->formOfPaymentByPassenger);
        $this->assertNull($message->globalOptions);
        $this->assertNull($message->officeIdDetails);
        $this->assertEmpty($message->passengerInfoGrp);
        $this->assertNull($message->priceToBeat);
        $this->assertNull($message->solutionFamily);
        $this->assertNull($message->taxInfo);
        $this->assertNull($message->ticketChangeInfo);
        $this->assertNull($message->travelFlightInfo);
        $this->assertEmpty($message->valueSearch);
    }

    /**
     * Test creating itinerary/timeDetails/tripDetails.
     *
     * Amadeus currently not uses this node, but may be used in future versions.
     */
    public function testCanMakeRequestWithTripDetails()
    {
        $opt = new FareMasterPricerCalendarOptions([
            'nrOfRequestedPassengers' => 1,
            'passengers' => [
                new MPPassenger([
                    'type' => MPPassenger::TYPE_ADULT,
                    'count' => 1
                ])
            ],
            'itinerary' => [
                new MPItinerary([
                    'departureLocation' => new MPLocation(['city' => 'KBP']),
                    'arrivalLocation' => new MPLocation(['city' => 'JFK']),
                    'date' => new MPDate([
                        'date' => new \DateTime('2018-11-16T00:00:00+0000', new \DateTimeZone('UTC')),
                        'rangeMode' => MPDate::RANGEMODE_MINUS_PLUS,
                        'range' => 3,
                        'tripDetails' => new MPTripDetails([
                            'flexibilityQualifier' => MPTripDetails::FLEXIBILITY_COMBINED,
                        ])
                    ])
                ])
            ]
        ]);

        $message = new MasterPricerCalendar($opt);

        $this->assertCount(1, $message->numberOfUnit->unitNumberDetail);
        $this->assertEquals(1, $message->numberOfUnit->unitNumberDetail[0]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_PASS, $message->numberOfUnit->unitNumberDetail[0]->typeOfUnit);

        $this->assertCount(1, $message->paxReference);
        $this->assertEquals(1, $message->paxReference[0]->traveller[0]->ref);
        $this->assertEquals('ADT', $message->paxReference[0]->ptc[0]);

        $this->assertCount(1, $message->itinerary);
        $this->assertEquals(1, $message->itinerary[0]->requestedSegmentRef->segRef);
        $this->assertEquals('KBP', $message->itinerary[0]->departureLocalization->departurePoint->locationId);
        $this->assertEquals('JFK', $message->itinerary[0]->arrivalLocalization->arrivalPointDetails->locationId);

        $this->assertEquals('161118', $message->itinerary[0]->timeDetails->firstDateTimeDetail->date);
        $this->assertEquals(3, $message->itinerary[0]->timeDetails->rangeOfDate->dayInterval);
        $this->assertEquals(RangeOfDate::RANGEMODE_MINUS_PLUS, $message->itinerary[0]->timeDetails->rangeOfDate->rangeQualifier);
        $this->assertEquals(TripDetails::FLEXIBILITY_COMBINED, $message->itinerary[0]->timeDetails->tripDetails->flexibilityQualifier);
    }
}
