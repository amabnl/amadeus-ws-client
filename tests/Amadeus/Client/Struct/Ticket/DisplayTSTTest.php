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

use Amadeus\Client\RequestOptions\TicketDisplayTstOptions;
use Amadeus\Client\Struct\Ticket\AttributeDetails;
use Amadeus\Client\Struct\Ticket\DisplayTST;
use Amadeus\Client\Struct\Ticket\RefDetails;
use Amadeus\Client\Struct\Ticket\TstReference;
use Test\Amadeus\BaseTestCase;

/**
 * DisplayTSTTest
 *
 * @package Test\Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DisplayTSTTest extends BaseTestCase
{
    public function testCanMakeMessage()
    {
        $msg = new DisplayTST(new TicketDisplayTstOptions([
            'displayMode' => TicketDisplayTstOptions::MODE_ALL
        ]));

        $this->assertEquals(AttributeDetails::MODE_ALL, $msg->displayMode->attributeDetails->attributeType);
        $this->assertNull($msg->pnrLocatorData);
        $this->assertEmpty($msg->psaInformation);
        $this->assertNull($msg->scrollingInformation);
        $this->assertEmpty($msg->tstReference);
    }

    public function testCanMakeMessageWithTstRef()
    {
        $msg = new DisplayTST(new TicketDisplayTstOptions([
            'displayMode' => TicketDisplayTstOptions::MODE_SELECTIVE,
            'tstNumbers' => [1,2]
        ]));

        $this->assertEquals(AttributeDetails::MODE_SELECTIVE, $msg->displayMode->attributeDetails->attributeType);
        $this->assertCount(2, $msg->tstReference);
        $this->assertEquals(1, $msg->tstReference[0]->uniqueReference);
        $this->assertEquals(TstReference::REFTYPE_TST, $msg->tstReference[0]->referenceType);
        $this->assertEquals(2, $msg->tstReference[1]->uniqueReference);
        $this->assertEquals(TstReference::REFTYPE_TST, $msg->tstReference[1]->referenceType);

        $this->assertNull($msg->pnrLocatorData);
        $this->assertEmpty($msg->psaInformation);
        $this->assertNull($msg->scrollingInformation);
    }

    public function testCanMakeMessageWithPassengerRef()
    {
        $msg = new DisplayTST(new TicketDisplayTstOptions([
            'displayMode' => TicketDisplayTstOptions::MODE_SELECTIVE,
            'passengers' => [2,3]
        ]));

        $this->assertEquals(AttributeDetails::MODE_SELECTIVE, $msg->displayMode->attributeDetails->attributeType);

        $this->assertCount(2, $msg->psaInformation->refDetails);
        $this->assertEquals(2, $msg->psaInformation->refDetails[0]->refNumber);
        $this->assertEquals(RefDetails::QUAL_PASSENGER, $msg->psaInformation->refDetails[0]->refQualifier);
        $this->assertEquals(3, $msg->psaInformation->refDetails[1]->refNumber);
        $this->assertEquals(RefDetails::QUAL_PASSENGER, $msg->psaInformation->refDetails[1]->refQualifier);

        $this->assertEmpty($msg->tstReference);
        $this->assertNull($msg->pnrLocatorData);
        $this->assertNull($msg->scrollingInformation);
    }

    public function testCanMakeMessageWithSegmentRef()
    {
        $msg = new DisplayTST(new TicketDisplayTstOptions([
            'displayMode' => TicketDisplayTstOptions::MODE_SELECTIVE,
            'segments' => [1,2,3]
        ]));

        $this->assertEquals(AttributeDetails::MODE_SELECTIVE, $msg->displayMode->attributeDetails->attributeType);

        $this->assertCount(3, $msg->psaInformation->refDetails);
        $this->assertEquals(1, $msg->psaInformation->refDetails[0]->refNumber);
        $this->assertEquals(RefDetails::QUAL_SEGMENT_REFERENCE, $msg->psaInformation->refDetails[0]->refQualifier);
        $this->assertEquals(2, $msg->psaInformation->refDetails[1]->refNumber);
        $this->assertEquals(RefDetails::QUAL_SEGMENT_REFERENCE, $msg->psaInformation->refDetails[1]->refQualifier);
        $this->assertEquals(3, $msg->psaInformation->refDetails[2]->refNumber);
        $this->assertEquals(RefDetails::QUAL_SEGMENT_REFERENCE, $msg->psaInformation->refDetails[2]->refQualifier);

        $this->assertEmpty($msg->tstReference);
        $this->assertNull($msg->pnrLocatorData);
        $this->assertNull($msg->scrollingInformation);
    }

    public function testCanMakeMessageWithScrolling()
    {
        $msg = new DisplayTST(new TicketDisplayTstOptions([
            'displayMode' => TicketDisplayTstOptions::MODE_SELECTIVE,
            'scrollingStart' => 25,
            'scrollingCount' => 2
        ]));

        $this->assertEquals(AttributeDetails::MODE_SELECTIVE, $msg->displayMode->attributeDetails->attributeType);
        $this->assertEquals(2, $msg->scrollingInformation->nextListInformation->remainingInformation);
        $this->assertEquals(25, $msg->scrollingInformation->nextListInformation->remainingReference);

        $this->assertEmpty($msg->psaInformation);
        $this->assertEmpty($msg->tstReference);
        $this->assertNull($msg->pnrLocatorData);
    }
}
