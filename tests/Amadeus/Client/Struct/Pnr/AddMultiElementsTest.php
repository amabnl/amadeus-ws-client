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

namespace Test\Amadeus\Client\Struct\Pnr;

use Amadeus\Client\RequestOptions\Pnr\Element\Contact;
use Amadeus\Client\RequestOptions\Pnr\Element\ReceivedFrom;
use Amadeus\Client\RequestOptions\Pnr\Element\Ticketing;
use Amadeus\Client\RequestOptions\Pnr\Segment\Miscellaneous;
use Amadeus\Client\RequestOptions\Pnr\Traveller;
use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
use Amadeus\Client\RequestOptions\Queue;
use Amadeus\Client\Struct\Pnr\AddMultiElements;
use Amadeus\Client\Struct\Pnr\AddMultiElements\PnrActions;
use Test\Amadeus\BaseTestCase;

/**
 * AddMultiElementsTest
 *
 * @package Amadeus\Client\Struct\Pnr
 */
class AddMultiElementsTest extends BaseTestCase
{

    public function testCanCreateMessageToCreateBasicPnr()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Devlieghere'
        ]);
        $createPnrOptions->actionCode = PnrActions::ACTIONOPTION_END_TRANSACT_W_RETRIEVE;
        $createPnrOptions->tripSegments[] = new Miscellaneous([]);
        $createPnrOptions->elements[] = new Ticketing([
            'ticketMode' => Ticketing::TICKETMODE_TIMELIMIT,
            'date' => \DateTime::createFromFormat('c', "2016-01-27T15:28:00+00:00", new \DateTimeZone('UTC')),
            'ticketQueue' => new Queue(['queue' => 50, 'category' => 0])
        ]);
        $createPnrOptions->elements[] = new Contact([]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->markTestIncomplete('PnrAddMultiElements structure & request options must first be completed');
    }
}
