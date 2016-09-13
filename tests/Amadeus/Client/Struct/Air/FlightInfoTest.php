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
use Test\Amadeus\Client\RequestCreator\BaseTest;

/**
 * FlightInfoTest
 *
 * @package Amadeus\Client\Struct\Air
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class FlightInfoTest extends BaseTest
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

        $message = new FlightInfo($options);

        $this->assertEquals('BRU', $message->generalFlightInfo->boardPointDetails->trueLocationId);
        $this->assertEquals('LIS', $message->generalFlightInfo->offPointDetails->trueLocationId);
        $this->assertEquals('180516', $message->generalFlightInfo->flightDate->departureDate);
        $this->assertNull($message->generalFlightInfo->flightDate->departureTime);
        $this->assertNull($message->generalFlightInfo->flightDate->arrivalDate);
        $this->assertNull($message->generalFlightInfo->flightDate->arrivalTime);
        $this->assertNull($message->generalFlightInfo->flightDate->dateVariation);
        $this->assertEquals('SN', $message->generalFlightInfo->companyDetails->marketingCompany);
        $this->assertNull($message->generalFlightInfo->companyDetails->operatingCompany);
        $this->assertEquals('652', $message->generalFlightInfo->flightIdentification->flightNumber);
        $this->assertNull( $message->generalFlightInfo->flightIdentification->bookingClass);
        $this->assertNull($message->generalFlightInfo->flightIdentification->operationalSuffix);
        $this->assertNull($message->generalFlightInfo->flightTypeDetails);
        $this->assertEmpty($message->generalFlightInfo->marriageDetails);
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

        $message = new FlightInfo($options);

        $this->assertEquals('BRU', $message->generalFlightInfo->boardPointDetails->trueLocationId);
        $this->assertEquals('LIS', $message->generalFlightInfo->offPointDetails->trueLocationId);
        $this->assertEquals('180516', $message->generalFlightInfo->flightDate->departureDate);
        $this->assertNull($message->generalFlightInfo->flightDate->departureTime);
        $this->assertNull($message->generalFlightInfo->flightDate->arrivalDate);
        $this->assertNull($message->generalFlightInfo->flightDate->arrivalTime);
        $this->assertNull($message->generalFlightInfo->flightDate->dateVariation);
        $this->assertEquals('SN', $message->generalFlightInfo->companyDetails->marketingCompany);
        $this->assertNull($message->generalFlightInfo->companyDetails->operatingCompany);
        $this->assertEquals('652', $message->generalFlightInfo->flightIdentification->flightNumber);
        $this->assertEquals('A', $message->generalFlightInfo->flightIdentification->operationalSuffix);
        $this->assertNull( $message->generalFlightInfo->flightIdentification->bookingClass);
        $this->assertNull($message->generalFlightInfo->flightTypeDetails);
        $this->assertEmpty($message->generalFlightInfo->marriageDetails);
    }
}
