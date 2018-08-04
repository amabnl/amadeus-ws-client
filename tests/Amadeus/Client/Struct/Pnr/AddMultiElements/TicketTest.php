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

namespace Test\Amadeus\Client\Struct\Pnr\AddMultiElements;

use Amadeus\Client\RequestOptions\Pnr\Element\Ticketing;
use Amadeus\Client\RequestOptions\Queue;
use Amadeus\Client\Struct\Pnr\AddMultiElements\Ticket;
use Test\Amadeus\BaseTestCase;

/**
 * TicketTest
 *
 * @package Test\Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TicketTest extends BaseTestCase
{
    public function testCanMakeTicketingElementWithDateTime()
    {
        $element = new Ticket(
            Ticketing::TICKETMODE_TIMELIMIT,
            \DateTime::createFromFormat(\DateTime::ISO8601, "2016-01-27T12:00:00+0000", new \DateTimeZone('UTC')),
            new Queue(['queue' => 50, 'category' => 0])
        );

        $this->assertEquals(Ticket::TICK_IND_TL, $element->indicator);
        $this->assertEquals('270116', $element->date);
        $this->assertEquals('1200', $element->time);
        $this->assertEquals(50, $element->queueNumber);
        $this->assertEquals(0, $element->queueCategory);
    }

    public function testCanMakeTicketingElementWithQueueOffice()
    {
        $element = new Ticket(
            Ticketing::TICKETMODE_TIMELIMIT,
            \DateTime::createFromFormat(\DateTime::ISO8601, "2016-01-27T00:00:00+0000", new \DateTimeZone('UTC')),
            new Queue(['queue' => 50, 'category' => 0, 'officeId' => 'BRUXX0900'])
        );

        $this->assertEquals(Ticket::TICK_IND_TL, $element->indicator);
        $this->assertEquals('270116', $element->date);
        $this->assertNull($element->time);
        $this->assertEquals(50, $element->queueNumber);
        $this->assertEquals(0, $element->queueCategory);
        $this->assertEquals('BRUXX0900', $element->officeId);
    }
}

