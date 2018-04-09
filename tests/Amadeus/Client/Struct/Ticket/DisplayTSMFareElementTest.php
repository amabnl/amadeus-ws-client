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

use Amadeus\Client\RequestOptions\TicketDisplayTsmFareElOptions;
use Amadeus\Client\Struct\Ticket\DisplayTSMFareElement;
use Test\Amadeus\BaseTestCase;

/**
 * DisplayTSMFareElementTest
 *
 * @package Test\Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DisplayTSMFareElementTest extends BaseTestCase
{
    public function testCanMakeMessage()
    {
        $opt = new TicketDisplayTsmFareElOptions([
            'tattoo' => 18
        ]);

        $msg = new DisplayTSMFareElement($opt);

        $this->assertCount(1, $msg->fareElementTattoo);
        $this->assertEquals(18, $msg->fareElementTattoo[0]->reference->number);
        $this->assertEquals(DisplayTSMFareElement\Reference::QUAL_ALL_FARE_ELEMENTS, $msg->fareElementTattoo[0]->reference->qualifier);
    }

    public function testCanMakeMessageFp()
    {
        $opt = new TicketDisplayTsmFareElOptions([
            'tattoo' => 25,
            'type' => TicketDisplayTsmFareElOptions::TYPE_FORM_OF_PAYMENT
        ]);

        $msg = new DisplayTSMFareElement($opt);

        $this->assertCount(1, $msg->fareElementTattoo);
        $this->assertEquals(25, $msg->fareElementTattoo[0]->reference->number);
        $this->assertEquals(DisplayTSMFareElement\Reference::QUAL_FORM_OF_PAYMENT, $msg->fareElementTattoo[0]->reference->qualifier);
    }
}
