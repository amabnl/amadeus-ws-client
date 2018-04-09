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

namespace Amadeus\Client\Struct\Air;

use Amadeus\Client\RequestOptions\AirFlightInfoOptions;
use Test\Amadeus\BaseTestCase;

/**
 * FlightInfoTest
 *
 * @package Amadeus\Client\Struct\Air
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FlightInfoTest extends BaseTestCase
{
    public function testCanMakeBasicFlightInfoMessage()
    {
        $options = new AirFlightInfoOptions([
            'airlineCode' => 'SN',
            'flightNumber' => '652',
            'departureDate' => \DateTime::createFromFormat('Y-m-d', '2016-05-18', new \DateTimeZone('UTC')),
            'departureLocation' => 'BRU',
            'arrivalLocation' => 'LIS'
        ]);

        $msg = new FlightInfo($options);

        $this->assertEquals('BRU', $msg->generalFlightInfo->boardPointDetails->trueLocationId);
        $this->assertEquals('LIS', $msg->generalFlightInfo->offPointDetails->trueLocationId);
        $this->assertEquals('180516', $msg->generalFlightInfo->flightDate->departureDate);
        $this->assertNull($msg->generalFlightInfo->flightDate->departureTime);
        $this->assertNull($msg->generalFlightInfo->flightDate->arrivalDate);
        $this->assertNull($msg->generalFlightInfo->flightDate->arrivalTime);
        $this->assertNull($msg->generalFlightInfo->flightDate->dateVariation);
        $this->assertEquals('SN', $msg->generalFlightInfo->companyDetails->marketingCompany);
        $this->assertNull($msg->generalFlightInfo->companyDetails->operatingCompany);
        $this->assertEquals('652', $msg->generalFlightInfo->flightIdentification->flightNumber);
        $this->assertNull( $msg->generalFlightInfo->flightIdentification->bookingClass);
        $this->assertNull($msg->generalFlightInfo->flightIdentification->operationalSuffix);
        $this->assertNull($msg->generalFlightInfo->flightTypeDetails);
        $this->assertEmpty($msg->generalFlightInfo->marriageDetails);
    }

    public function testCanMakeFlightInfoWithOperationalSuffixMessage()
    {
        $options = new AirFlightInfoOptions([
            'airlineCode' => 'SN',
            'flightNumber' => '652',
            'flightNumberSuffix' => 'A',
            'departureDate' => \DateTime::createFromFormat('Y-m-d', '2016-05-18', new \DateTimeZone('UTC')),
            'departureLocation' => 'BRU',
            'arrivalLocation' => 'LIS'
        ]);

        $msg = new FlightInfo($options);

        $this->assertEquals('BRU', $msg->generalFlightInfo->boardPointDetails->trueLocationId);
        $this->assertEquals('LIS', $msg->generalFlightInfo->offPointDetails->trueLocationId);
        $this->assertEquals('180516', $msg->generalFlightInfo->flightDate->departureDate);
        $this->assertNull($msg->generalFlightInfo->flightDate->departureTime);
        $this->assertNull($msg->generalFlightInfo->flightDate->arrivalDate);
        $this->assertNull($msg->generalFlightInfo->flightDate->arrivalTime);
        $this->assertNull($msg->generalFlightInfo->flightDate->dateVariation);
        $this->assertEquals('SN', $msg->generalFlightInfo->companyDetails->marketingCompany);
        $this->assertNull($msg->generalFlightInfo->companyDetails->operatingCompany);
        $this->assertEquals('652', $msg->generalFlightInfo->flightIdentification->flightNumber);
        $this->assertEquals('A', $msg->generalFlightInfo->flightIdentification->operationalSuffix);
        $this->assertNull( $msg->generalFlightInfo->flightIdentification->bookingClass);
        $this->assertNull($msg->generalFlightInfo->flightTypeDetails);
        $this->assertEmpty($msg->generalFlightInfo->marriageDetails);
    }
}
