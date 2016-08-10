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

use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FlightInfo;
use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FrequentFlyer;
use Amadeus\Client\RequestOptions\AirRetrieveSeatMapOptions;
use Amadeus\Client\Struct\Air\RetrieveSeatMap;
use Test\Amadeus\BaseTestCase;

/**
 * RetrieveSeatMapTest
 *
 * @package Test\Amadeus\Client\Struct\Air
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class RetrieveSeatMapTest extends BaseTestCase
{
    public function testCanMakeRequestWithMandatoryParams()
    {
        $par = new AirRetrieveSeatMapOptions([
            'flight' => new FlightInfo([
                'airline' => 'SN',
                'flightNumber' => '652',
                'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2016-05-18 00:00:00'),
                'departure' => 'BRU',
                'arrival' => 'LIS'
            ])
        ]);

        $message = new RetrieveSeatMap($par);

        $this->assertEquals('SN', $message->travelProductIdent->companyDetails->marketingCompany);

        $this->assertEquals('652', $message->travelProductIdent->flightIdentification->flightNumber);
        $this->assertNull($message->travelProductIdent->flightIdentification->bookingClass);

        $this->assertEquals('180516', $message->travelProductIdent->flightDate->departureDate);
        $this->assertNull($message->travelProductIdent->flightDate->departureTime);

        $this->assertEquals('BRU', $message->travelProductIdent->boardPointDetails->trueLocationId);

        $this->assertEquals('LIS', $message->travelProductIdent->offpointDetails->trueLocationId);
    }

    public function testCanMakeRequestWithMandatoryParamsAndTime()
    {
        $par = new AirRetrieveSeatMapOptions([
            'flight' => new FlightInfo([
                'airline' => 'SN',
                'flightNumber' => '652',
                'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2016-05-18 14:35:00'),
                'departure' => 'BRU',
                'arrival' => 'LIS'
            ])
        ]);

        $message = new RetrieveSeatMap($par);

        $this->assertEquals('SN', $message->travelProductIdent->companyDetails->marketingCompany);

        $this->assertEquals('652', $message->travelProductIdent->flightIdentification->flightNumber);
        $this->assertNull($message->travelProductIdent->flightIdentification->bookingClass);

        $this->assertEquals('180516', $message->travelProductIdent->flightDate->departureDate);
        $this->assertEquals('1435', $message->travelProductIdent->flightDate->departureTime);

        $this->assertEquals('BRU', $message->travelProductIdent->boardPointDetails->trueLocationId);

        $this->assertEquals('LIS', $message->travelProductIdent->offpointDetails->trueLocationId);
    }

    public function testCanMakeRequestWithMandatoryParamsAndBookingClass()
    {
        $par = new AirRetrieveSeatMapOptions([
            'flight' => new FlightInfo([
                'airline' => 'SN',
                'flightNumber' => '652',
                'bookingClass' => 'Y',
                'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2016-05-18 00:00:00'),
                'departure' => 'BRU',
                'arrival' => 'LIS'
            ])
        ]);

        $message = new RetrieveSeatMap($par);

        $this->assertEquals('SN', $message->travelProductIdent->companyDetails->marketingCompany);

        $this->assertEquals('652', $message->travelProductIdent->flightIdentification->flightNumber);
        $this->assertEquals('Y', $message->travelProductIdent->flightIdentification->bookingClass);

        $this->assertEquals('180516', $message->travelProductIdent->flightDate->departureDate);
        $this->assertNull($message->travelProductIdent->flightDate->departureTime);

        $this->assertEquals('BRU', $message->travelProductIdent->boardPointDetails->trueLocationId);

        $this->assertEquals('LIS', $message->travelProductIdent->offpointDetails->trueLocationId);
    }

    public function testCanMakeRequestWithMandatoryParamsAndFrequentFlyer()
    {
        $par = new AirRetrieveSeatMapOptions([
            'flight' => new FlightInfo([
                'airline' => 'SN',
                'flightNumber' => '652',
                'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2016-05-18 00:00:00'),
                'departure' => 'BRU',
                'arrival' => 'LIS'
            ]),
            'frequentFlyer' => new FrequentFlyer([
                'company' => 'LH',
                'cardNumber' => '4099913025539611',
                'tierLevel' => 1,
            ])
        ]);

        $message = new RetrieveSeatMap($par);

        $this->assertEquals('LH', $message->frequentTravelerInfo->frequentTravellerDetails->carrier);
        $this->assertEquals('4099913025539611', $message->frequentTravelerInfo->frequentTravellerDetails->number);
        $this->assertEquals(1, $message->frequentTravelerInfo->frequentTravellerDetails->tierLevel);
    }
}
