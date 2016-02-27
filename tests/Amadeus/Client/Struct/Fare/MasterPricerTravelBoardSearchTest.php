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
use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
use Amadeus\Client\Struct\Fare\MasterPricer\ArrivalLocalization;
use Amadeus\Client\Struct\Fare\MasterPricer\CabinId;
use Amadeus\Client\Struct\Fare\MasterPricer\DepartureLocalization;
use Amadeus\Client\Struct\Fare\MasterPricer\Itinerary;
use Amadeus\Client\Struct\Fare\MasterPricer\NumberOfUnit;
use Amadeus\Client\Struct\Fare\MasterPricer\TimeDetails;
use Amadeus\Client\Struct\Fare\MasterPricer\Traveller;
use Amadeus\Client\Struct\Fare\MasterPricer\UnitNumberDetail;
use Amadeus\Client\Struct\Fare\MasterPricerTravelBoardSearch;
use Amadeus\Client\Struct\PriceXplorer\LocationInfo;
use Test\Amadeus\BaseTestCase;

/**
 * MasterPricerTravelBoardSearch
 *
 * @package Test\Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class MasterPricerTravelBoardSearchTest extends BaseTestCase
{
    public function testCanMakeBaseMasterPricerMessage()
    {
        $opt = new FareMasterPricerTbSearch();
        $opt->nrOfRequestedResults = 200;
        $opt->nrOfRequestedPassengers = 1;
        $opt->passengers[] = new MPPassenger([
            'type' => MPPassenger::TYPE_ADULT,
            'count' => 1
        ]);
        $opt->itinerary[] = new MPItinerary([
            'departureLocation' => new MPLocation(['city' => 'BRU']),
            'arrivalLocation' => new MPLocation(['city' => 'LON']),
            'date' => new MPDate(['date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertInternalType('array', $message->itinerary);
        $this->assertEquals(1, count($message->itinerary));
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\MasterPricer\Itinerary', $message->itinerary[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\MasterPricer\TimeDetails', $message->itinerary[0]->timeDetails);
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\MasterPricer\FirstDateTimeDetail', $message->itinerary[0]->timeDetails->firstDateTimeDetail);
        $this->assertEquals('150117', $message->itinerary[0]->timeDetails->firstDateTimeDetail->date);
        $this->assertNull($message->itinerary[0]->timeDetails->firstDateTimeDetail->time);
        $this->assertEquals('BRU', $message->itinerary[0]->departureLocalization->departurePoint->locationId);
        $this->assertEquals('C', $message->itinerary[0]->departureLocalization->departurePoint->airportCityQualifier);
        $this->assertEquals('LON', $message->itinerary[0]->arrivalLocalization->arrivalPointDetails->locationId);
        $this->assertEquals('C', $message->itinerary[0]->arrivalLocalization->arrivalPointDetails->airportCityQualifier);

        //TODO assert passenger

    }

    public function testCanMakeMasterPricerMessageWithCabinClass()
    {
        $opt = new FareMasterPricerTbSearch();
        $opt->nrOfRequestedResults = 200;
        $opt->nrOfRequestedPassengers = 1;
        $opt->passengers[] = new MPPassenger([
            'type' => MPPassenger::TYPE_ADULT,
            'count' => 1
        ]);
        $opt->itinerary[] = new MPItinerary([
            'departureLocation' => new MPLocation(['city' => 'BRU']),
            'arrivalLocation' => new MPLocation(['city' => 'LON']),
            'date' => new MPDate(['date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
        ]);
        $opt->cabinClass = FareMasterPricerTbSearch::CABIN_ECONOMY_PREMIUM;


        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertEquals(FareMasterPricerTbSearch::CABIN_ECONOMY_PREMIUM, $message->travelFlightInfo->cabinId->cabin);
        $this->assertNull($message->travelFlightInfo->cabinId->cabinQualifier);
    }

    public function testCanMakeMasterPricerMessageWithMultiAdultAndInfant()
    {
        $opt = new FareMasterPricerTbSearch();
        $opt->nrOfRequestedResults = 200;
        $opt->nrOfRequestedPassengers = 3;
        $opt->passengers[] = new MPPassenger([
            'type' => MPPassenger::TYPE_ADULT,
            'count' => 2
        ]);
        $opt->passengers[] = new MPPassenger([
            'type' => MPPassenger::TYPE_INFANT,
            'count' => 1
        ]);
        $opt->itinerary[] = new MPItinerary([
            'departureLocation' => new MPLocation(['city' => 'BRU']),
            'arrivalLocation' => new MPLocation(['city' => 'LON']),
            'date' => new MPDate(['date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertEquals('ADT', $message->paxReference[0]->ptc[0]);
        $this->assertNull($message->paxReference[0]->traveller[0]->infantIndicator);
        $this->assertEquals(1, $message->paxReference[0]->traveller[0]->ref);
        $this->assertNull($message->paxReference[0]->traveller[1]->infantIndicator);
        $this->assertEquals(2, $message->paxReference[0]->traveller[1]->ref);

        $this->assertEquals('INF', $message->paxReference[1]->ptc[0]);
        $this->assertEquals(1, $message->paxReference[1]->traveller[0]->ref);
        $this->assertEquals(1, $message->paxReference[1]->traveller[0]->infantIndicator);
    }

    public function testCanMakeMasterPricerMessageWithCityLocationAndGeoCoordinatesAndRadius()
    {
        $opt = new FareMasterPricerTbSearch();
        $opt->nrOfRequestedResults = 200;
        $opt->nrOfRequestedPassengers = 1;
        $opt->passengers[] = new MPPassenger([
            'type' => MPPassenger::TYPE_ADULT,
            'count' => 1
        ]);
        $opt->itinerary[] = new MPItinerary([
            'departureLocation' => new MPLocation([
                'airport' => 'OST',
                'radiusDistance' => 100,
                'radiusUnit' => MPLocation::RADIUSUNIT_KILOMETERS,
            ]),
            'arrivalLocation' => new MPLocation([
                'latitude' => '50.9139922',
                'longitude' => '4.406143'
            ]),
            'date' => new MPDate(['date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertEquals(100, $message->itinerary[0]->departureLocalization->departurePoint->distance);
        $this->assertEquals('K', $message->itinerary[0]->departureLocalization->departurePoint->distanceUnit);
        $this->assertEquals('OST', $message->itinerary[0]->departureLocalization->departurePoint->locationId);
        $this->assertEquals('A', $message->itinerary[0]->departureLocalization->departurePoint->airportCityQualifier);

        $this->assertEquals('50.9139922', $message->itinerary[0]->arrivalLocalization->arrivalPointDetails->latitude);
        $this->assertEquals('4.406143', $message->itinerary[0]->arrivalLocalization->arrivalPointDetails->longitude);

    }

}
