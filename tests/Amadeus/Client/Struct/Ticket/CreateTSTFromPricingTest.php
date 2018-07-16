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

use Amadeus\Client\RequestOptions\Ticket\PassengerReference;
use Amadeus\Client\RequestOptions\Ticket\Pricing;
use Amadeus\Client\RequestOptions\TicketCreateTstFromPricingOptions;
use Amadeus\Client\Struct\Ticket\CreateTSTFromPricing;
use Amadeus\Client\Struct\Ticket\ItemReference;
use Amadeus\Client\Struct\Ticket\RefDetails;
use Test\Amadeus\BaseTestCase;

/**
 * CreateTSTFromPricingTest
 *
 * @package Test\Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CreateTSTFromPricingTest extends BaseTestCase
{
    public function testCanMakeTstFromPricingWithPassengerReference()
    {
        $msg = new CreateTSTFromPricing(
            new TicketCreateTstFromPricingOptions([
                'pricings' => [
                    new Pricing([
                        'tstNumber' => 3,
                        'passengerReferences' => [
                            new PassengerReference([
                                'id' => 1,
                                'type' => PassengerReference::TYPE_ADULT
                            ])
                        ]
                    ])
                ]
            ])
        );

        $this->assertCount(1, $msg->psaList);
        $this->assertEquals(3, $msg->psaList[0]->itemReference->uniqueReference);
        $this->assertEquals(ItemReference::REFTYPE_TST, $msg->psaList[0]->itemReference->referenceType);

        $this->assertCount(1, $msg->psaList[0]->paxReference->refDetails);
        $this->assertEquals(1, $msg->psaList[0]->paxReference->refDetails[0]->refNumber);
        $this->assertEquals(RefDetails::QUAL_ADULT, $msg->psaList[0]->paxReference->refDetails[0]->refQualifier);
    }

    public function testCanMakeMultiTstFromPricing()
    {
        $msg = new CreateTSTFromPricing(
            new TicketCreateTstFromPricingOptions([
                'pricings' => [
                    new Pricing([
                        'tstNumber' => 1
                    ]),
                    new Pricing([
                        'tstNumber' => 2
                    ]),
                    new Pricing([
                        'tstNumber' => 3
                    ]),

                ]
            ])
        );

        $this->assertEquals(3, count($msg->psaList));
        $this->assertEquals(1, $msg->psaList[0]->itemReference->uniqueReference);
        $this->assertEquals(ItemReference::REFTYPE_TST, $msg->psaList[0]->itemReference->referenceType);
        $this->assertNull($msg->psaList[0]->paxReference);
        $this->assertEquals(2, $msg->psaList[1]->itemReference->uniqueReference);
        $this->assertEquals(ItemReference::REFTYPE_TST, $msg->psaList[1]->itemReference->referenceType);
        $this->assertNull($msg->psaList[1]->paxReference);
        $this->assertEquals(3, $msg->psaList[2]->itemReference->uniqueReference);
        $this->assertEquals(ItemReference::REFTYPE_TST, $msg->psaList[2]->itemReference->referenceType);
        $this->assertNull($msg->psaList[2]->paxReference);
    }


    public function testCanMakeTstFromPricingWithPnrInfo()
    {
        // !!! This PNR record locator is used for tracing purpose, no internal retrieve. !!!

        $msg = new CreateTSTFromPricing(
            new TicketCreateTstFromPricingOptions([
                'informationalRecordLocator' => 'ABC123',
                'pricings' => [
                    new Pricing([
                        'tstNumber' => 3
                    ])
                ]
            ])
        );

        $this->assertEquals('ABC123', $msg->pnrLocatorData->reservationInformation->controlNumber);
    }
}
