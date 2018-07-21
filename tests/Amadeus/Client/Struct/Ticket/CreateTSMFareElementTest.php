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

use Amadeus\Client\RequestOptions\TicketCreateTsmFareElOptions;
use Amadeus\Client\Struct\Ticket\CreateTSMFareElement;
use Amadeus\Client\Struct\Ticket\DisplayTSMFareElement;
use Test\Amadeus\BaseTestCase;

/**
 * CreateTSMFareElementTest
 *
 * @package Test\Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CreateTSMFareElementTest extends BaseTestCase
{
    /**
     * 5.1 Operation: Delete the form of payment from the TSM
     *
     * This examples shows how to delete the form of payment Check from the TSM of tattoo 18.
     *
     * In order to delete a fare element, #####  needs to be put in <fareElementInfo> tag.
     */
    public function testCanMakeMessageDeleteFopFromTsm()
    {
        $opt = new TicketCreateTsmFareElOptions([
            'type' => TicketCreateTsmFareElOptions::TYPE_FORM_OF_PAYMENT,
            'tattoo' => 18,
            'info' => '#####',
        ]);

        $msg = new CreateTSMFareElement($opt);

        $this->assertEquals(DisplayTSMFareElement\Reference::QUAL_FORM_OF_PAYMENT, $msg->fareElementTattoo->reference->qualifier);
        $this->assertEquals(18, $msg->fareElementTattoo->reference->number);
        $this->assertEquals('#####', $msg->fareElementInfo->text);
    }

    /**
     * 5.2 Operation: Set the form of payment Check to the TSM of tattoo 18
     *
     * This examples shows how to set the form of payment Check to the TSM of tattoo 18.
     */
    public function testCanMakeMessagePaymentCheck()
    {
        $opt = new TicketCreateTsmFareElOptions([
            'type' => TicketCreateTsmFareElOptions::TYPE_FORM_OF_PAYMENT,
            'tattoo' => 18,
            'info' => 'CHECK/EUR304.89',
        ]);

        $msg = new CreateTSMFareElement($opt);

        $this->assertEquals(DisplayTSMFareElement\Reference::QUAL_FORM_OF_PAYMENT, $msg->fareElementTattoo->reference->qualifier);
        $this->assertEquals(18, $msg->fareElementTattoo->reference->number);
        $this->assertEquals('CHECK/EUR304.89', $msg->fareElementInfo->text);
    }
}
