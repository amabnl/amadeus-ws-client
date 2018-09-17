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

use Amadeus\Client\RequestOptions\TicketProcessETicketOptions;
use Amadeus\Client\Struct\Ticket\ProcessETicket;
use Test\Amadeus\BaseTestCase;

/**
 * ProcessETicketTest
 *
 * @package Test\Amadeus\Client\Struct\Ticket
 * @author Mike hernas <m@hern.as>
 */
class ProcessETicketTest extends BaseTestCase
{
    /**
     * 5.6 Operation: Eticket Single Display
     *
     * The example shows display of a single electronic ticket
     */
    public function testCanDisplayETicket()
    {
        $opt = new TicketProcessETicketOptions([
            'action' => TicketProcessETicketOptions::ACTION_ETICKET_DISPLAY,
            'ticketNumber' => '17282441010039'
        ]);

        $msg = new ProcessETicket($opt);

        $this->assertEquals(131, $msg->msgActionDetails->messageFunctionDetails->messageFunction);
        $this->assertEmpty($msg->msgActionDetails->messageFunctionDetails->additionalMessageFunction);

        $this->assertCount(1, $msg->ticketInfoGroup);
        $this->assertEquals('17282441010039', $msg->ticketInfoGroup[0]->ticket->documentDetails->number);
    }
}
