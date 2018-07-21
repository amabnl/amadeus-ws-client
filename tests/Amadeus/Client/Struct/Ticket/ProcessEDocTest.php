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

use Amadeus\Client\RequestOptions\Ticket\FrequentFlyer;
use Amadeus\Client\RequestOptions\TicketProcessEDocOptions;
use Amadeus\Client\Struct\Ticket\ProcessEDoc;
use Test\Amadeus\BaseTestCase;

/**
 * ProcessEDocTest
 *
 * @package Test\Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ProcessEDocTest extends BaseTestCase
{
    /**
     * 5.4 Operation: EMD display by document number
     *
     * The example shows an EMD display by EMD document number
     */
    public function testCanMakeEmdDisplayByDocNumber()
    {
        $opt = new TicketProcessEDocOptions([
            'action' => TicketProcessEDocOptions::ACTION_EMD_DISPLAY,
            'ticketNumber' => '17282441010039'
        ]);

        $msg = new ProcessEDoc($opt);

        $this->assertEquals(791, $msg->msgActionDetails->messageFunctionDetails->messageFunction);
        $this->assertEmpty($msg->msgActionDetails->messageFunctionDetails->additionalMessageFunction);

        $this->assertCount(1, $msg->infoGroup);
        $this->assertEquals('17282441010039', $msg->infoGroup[0]->docInfo->documentDetails->number);

        $this->assertEmpty($msg->textInfo);
        $this->assertNull($msg->customerReference);
        $this->assertNull($msg->frequentTravellerInfo);
        $this->assertEmpty($msg->docGroup);
        $this->assertNull($msg->pricingInfo);
    }

    /**
     * 5.13 Operation: Enhanced ETKT list display
     *
     * For ETS hosted airline offices, an option can be appended to the ETKT list display request
     * to add some extra information to the reply including all the coupons of the ETKT
     * with their status, flight details, form of payment, record locator of the reservation system.
     */
    public function testCanMakeEnhancedEticketListDisplay()
    {
        $opt = new TicketProcessEDocOptions([
            'action' => TicketProcessEDocOptions::ACTION_ETICKET_DISPLAY,
            'additionalActions' => [
                TicketProcessEDocOptions::ADD_ACTION_ENHANCED_LIST_DISPLAY
            ],
            'frequentTravellers' => [
                new FrequentFlyer([
                    'number' => '21354657',
                    'carrier' => '6X'
                ])
            ]
        ]);

        $msg = new ProcessEDoc($opt);

        $this->assertEquals(131, $msg->msgActionDetails->messageFunctionDetails->messageFunction);
        $this->assertCount(1, $msg->msgActionDetails->messageFunctionDetails->additionalMessageFunction);
        $this->assertEquals('EXT', $msg->msgActionDetails->messageFunctionDetails->additionalMessageFunction[0]);

        $this->assertCount(1, $msg->frequentTravellerInfo->frequentTravellerDetails);
        $this->assertEquals('21354657', $msg->frequentTravellerInfo->frequentTravellerDetails[0]->number);
        $this->assertEquals('6X', $msg->frequentTravellerInfo->frequentTravellerDetails[0]->carrier);

        $this->assertEmpty($msg->infoGroup);
        $this->assertEmpty($msg->textInfo);
        $this->assertNull($msg->customerReference);
        $this->assertEmpty($msg->docGroup);
        $this->assertNull($msg->pricingInfo);
    }
}
