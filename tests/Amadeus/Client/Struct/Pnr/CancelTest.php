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

use Amadeus\Client\Struct\Pnr\AddMultiElements\PnrActions;
use Test\Amadeus\BaseTestCase;
use Amadeus\Client\Struct\Pnr\Cancel;
use Amadeus\Client\RequestOptions\PnrCancelOptions;

/**
 * CancelTest
 *
 * @package Test\Amadeus\Client\Struct\Pnr
 */
class CancelTest extends BaseTestCase
{
    public function testCanMakeCancelMessageForOffer()
    {
        $message = new Cancel(
            new PnrCancelOptions([
                'actionCode' => PnrCancelOptions::ACTION_END_TRANSACT,
                'offers' => [2]
            ])
        );
        $this->assertCount(1, $message->pnrActions->optionCode);
        $this->assertEquals(PnrCancelOptions::ACTION_END_TRANSACT, $message->pnrActions->optionCode[0]);
        $this->assertNull($message->reservationInfo);
        $this->assertEquals(1, count($message->cancelElements));
        $this->assertEquals(Cancel\Elements::ENTRY_ELEMENT, $message->cancelElements[0]->entryType);
        $this->assertEquals(1, count($message->cancelElements[0]->element));
        $this->assertEquals(Cancel\Element::IDENT_OFFER_TATTOO, $message->cancelElements[0]->element[0]->identifier);
        $this->assertEquals(2, $message->cancelElements[0]->element[0]->number);
        $this->assertNull($message->cancelElements[0]->element[0]->subElement);
    }

    public function testCanMakeCancelByTattooMessageWithPnrRetrieve()
    {
        $message = new Cancel(
            new PnrCancelOptions([
                'recordLocator' => 'ABC123',
                'actionCode' => PnrCancelOptions::ACTION_NO_PROCESSING,
                'elementsByTattoo' => [14, 16, 20]
            ])
        );

        $this->assertCount(1, $message->pnrActions->optionCode);
        $this->assertEquals(PnrActions::ACTIONOPTION_NO_SPECIAL_PROCESSING, $message->pnrActions->optionCode[0]);
        $this->assertEquals('ABC123', $message->reservationInfo->reservation->controlNumber);
        $this->assertEquals(1, count($message->cancelElements));
        $this->assertEquals(Cancel\Elements::ENTRY_ELEMENT, $message->cancelElements[0]->entryType);
        $this->assertEquals(3, count($message->cancelElements[0]->element));
        $this->assertEquals(Cancel\Element::IDENT_OTHER_TATTOO, $message->cancelElements[0]->element[0]->identifier);
        $this->assertEquals(14, $message->cancelElements[0]->element[0]->number);
        $this->assertEquals(Cancel\Element::IDENT_OTHER_TATTOO, $message->cancelElements[0]->element[1]->identifier);
        $this->assertEquals(16, $message->cancelElements[0]->element[1]->number);
        $this->assertEquals(Cancel\Element::IDENT_OTHER_TATTOO, $message->cancelElements[0]->element[2]->identifier);
        $this->assertEquals(20, $message->cancelElements[0]->element[2]->number);
    }

    public function testCanMakeCancelItineraryMessage()
    {
        $message = new Cancel(
            new PnrCancelOptions([
                'actionCode' => PnrCancelOptions::ACTION_END_TRANSACT_RETRIEVE,
                'cancelItinerary' => true
            ])
        );

        $this->assertCount(1, $message->pnrActions->optionCode);
        $this->assertEquals(PnrActions::ACTIONOPTION_END_TRANSACT_W_RETRIEVE, $message->pnrActions->optionCode[0]);
        $this->assertEquals(1, count($message->cancelElements));
        $this->assertEquals(Cancel\Elements::ENTRY_ITINERARY, $message->cancelElements[0]->entryType);
        $this->assertEquals(0, count($message->cancelElements[0]->element));
    }

    public function testCanMakeCancelPassengerMessage()
    {
        $message = new Cancel(
            new PnrCancelOptions([
                'actionCode' => PnrCancelOptions::ACTION_NO_PROCESSING,
                'passengers' => [3]
            ])
        );
        $this->assertCount(1, $message->pnrActions->optionCode);
        $this->assertEquals(PnrActions::ACTIONOPTION_NO_SPECIAL_PROCESSING, $message->pnrActions->optionCode[0]);
        $this->assertNull($message->reservationInfo);
        $this->assertEquals(1, count($message->cancelElements));
        $this->assertEquals(Cancel\Elements::ENTRY_ELEMENT, $message->cancelElements[0]->entryType);
        $this->assertEquals(1, count($message->cancelElements[0]->element));
        $this->assertEquals(Cancel\Element::IDENT_PASSENGER_TATTOO, $message->cancelElements[0]->element[0]->identifier);
        $this->assertEquals(3, $message->cancelElements[0]->element[0]->number);
    }

    public function testCanMakeCancelGroupPassengerMessage()
    {
        $message = new Cancel(
            new PnrCancelOptions([
                'actionCode' => PnrCancelOptions::ACTION_NO_PROCESSING,
                'groupPassengers' => [5,6]
            ])
        );
        $this->assertCount(1, $message->pnrActions->optionCode);
        $this->assertEquals(PnrActions::ACTIONOPTION_NO_SPECIAL_PROCESSING, $message->pnrActions->optionCode[0]);
        $this->assertNull($message->reservationInfo);
        $this->assertEquals(1, count($message->cancelElements));
        $this->assertEquals(Cancel\Elements::ENTRY_NAME_INTEGRATION, $message->cancelElements[0]->entryType);
        $this->assertEquals(2, count($message->cancelElements[0]->element));
        $this->assertEquals(Cancel\Element::IDENT_PASSENGER_TATTOO, $message->cancelElements[0]->element[0]->identifier);
        $this->assertEquals(5, $message->cancelElements[0]->element[0]->number);
        $this->assertEquals(Cancel\Element::IDENT_PASSENGER_TATTOO, $message->cancelElements[0]->element[1]->identifier);
        $this->assertEquals(6, $message->cancelElements[0]->element[1]->number);
    }

    public function testCanMakeCancelSegmentsMessage()
    {
        $message = new Cancel(
            new PnrCancelOptions([
                'actionCode' => PnrCancelOptions::ACTION_NO_PROCESSING,
                'segments' => [3,4]
            ])
        );

        $this->assertCount(1, $message->pnrActions->optionCode);
        $this->assertEquals(0, $message->pnrActions->optionCode[0]);

        $this->assertEquals(1, count($message->cancelElements));
        $this->assertEquals(Cancel\Elements::ENTRY_ELEMENT, $message->cancelElements[0]->entryType);
        $this->assertEquals(2, count($message->cancelElements[0]->element));
        $this->assertEquals(Cancel\Element::IDENT_SEGMENT_TATTOO, $message->cancelElements[0]->element[0]->identifier);
        $this->assertEquals(3, $message->cancelElements[0]->element[0]->number);
        $this->assertEquals(Cancel\Element::IDENT_SEGMENT_TATTOO, $message->cancelElements[0]->element[1]->identifier);
        $this->assertEquals(4, $message->cancelElements[0]->element[1]->number);
    }
}
