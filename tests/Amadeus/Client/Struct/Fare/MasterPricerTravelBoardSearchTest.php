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
use Amadeus\Client\Struct\Fare\MasterPricer\DepartureLocalization;
use Amadeus\Client\Struct\Fare\MasterPricer\Itinerary;
use Amadeus\Client\Struct\Fare\MasterPricer\NumberOfUnit;
use Amadeus\Client\Struct\Fare\MasterPricer\TimeDetails;
use Amadeus\Client\Struct\Fare\MasterPricer\Traveller;
use Amadeus\Client\Struct\Fare\MasterPricer\UnitNumberDetail;
use Amadeus\Client\Struct\Fare\MasterPricerTravelBoardSearch;
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

}
