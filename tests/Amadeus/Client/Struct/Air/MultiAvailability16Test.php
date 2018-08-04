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

use Amadeus\Client\RequestOptions\Air\MultiAvailability\RequestOptions;
use Amadeus\Client\RequestOptions\AirMultiAvailabilityOptions;
use Amadeus\Client\Struct\Air\MultiAvailability16;
use Amadeus\Client\Struct\Air\MultiAvailability;
use Test\Amadeus\BaseTestCase;

/**
 * MultiAvailability16Test
 *
 * @package Test\Amadeus\Client\Struct\Air
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MultiAvailability16Test extends BaseTestCase
{
    public function testCanCreateMixedMessageV16()
    {
        $opt = new AirMultiAvailabilityOptions([
            'actionCode' => AirMultiAvailabilityOptions::ACTION_SCHEDULE,
            'requestOptions' => [
                new RequestOptions([
                    'departureDate' => \DateTime::createFromFormat('Ymd-His', '20170215-140000', new \DateTimeZone('UTC')),
                    'from' => 'NCE',
                    'to' => 'NYC',
                    'cabinCode' => RequestOptions::CABIN_ECONOMY_PREMIUM_MAIN,
                    'includedConnections' => ['PAR'],
                    'nrOfSeats' => 5,
                    'includedAirlines' => ['AF'],
                    'requestType' => RequestOptions::REQ_TYPE_BY_ARRIVAL_TIME
                ])
            ]
        ]);

        $msg = new MultiAvailability16($opt);

        $this->assertEquals(
            MultiAvailability\FunctionDetails::ACTION_SCHEDULE,
            $msg->messageActionDetails->functionDetails->actionCode
        );
        $this->assertCount(1, $msg->requestSection);

        $this->assertInstanceOf('Amadeus\Client\Struct\Air\MultiAvailability\RequestSection16', $msg->requestSection[0]);
        $this->assertEquals('150217', $msg->requestSection[0]->availabilityProductInfo->availabilityDetails[0]->departureDate);
        $this->assertEquals('1400', $msg->requestSection[0]->availabilityProductInfo->availabilityDetails[0]->departureTime);
        $this->assertNull($msg->requestSection[0]->availabilityProductInfo->availabilityDetails[0]->arrivalDate);
        $this->assertNull($msg->requestSection[0]->availabilityProductInfo->availabilityDetails[0]->arrivalTime);
        $this->assertEquals('NCE', $msg->requestSection[0]->availabilityProductInfo->departureLocationInfo->cityAirport);
        $this->assertEquals('NYC', $msg->requestSection[0]->availabilityProductInfo->arrivalLocationInfo->cityAirport);
        $this->assertEquals(
            MultiAvailability\CabinDesignation::CABIN_ECONOMY_PREMIUM_MAIN,
            $msg->requestSection[0]->cabinOption->cabinDesignation->cabinClassOfServiceList
        );
        $this->assertNull($msg->requestSection[0]->optionClass);
        $this->assertCount(1, $msg->requestSection[0]->airlineOrFlightOption);
        $this->assertNull($msg->requestSection[0]->airlineOrFlightOption[0]->excludeAirlineIndicator);
        $this->assertEquals('AF', $msg->requestSection[0]->airlineOrFlightOption[0]->flightIdentification[0]->airlineCode);
        $this->assertEquals(5, $msg->requestSection[0]->numberOfSeatsInfo->numberOfPassengers);
        $this->assertCount(1, $msg->requestSection[0]->connectionOption);
        $this->assertEquals('PAR', $msg->requestSection[0]->connectionOption[0]->firstConnection->location);
        $this->assertNull($msg->requestSection[0]->connectionOption[0]->firstConnection->indicatorList);
        $this->assertEmpty($msg->requestSection[0]->connectionOption[0]->secondConnection);

        $this->assertEquals(
            MultiAvailability\ProductTypeDetails::REQ_TYPE_BY_ARRIVAL_TIME,
            $msg->requestSection[0]->availabilityOptions->typeOfRequest
        );

        $this->assertNull($msg->frequentTraveller);
        $this->assertNull($msg->consumerReferenceInformation);
        $this->assertNull($msg->pointOfCommencement);
    }
}
