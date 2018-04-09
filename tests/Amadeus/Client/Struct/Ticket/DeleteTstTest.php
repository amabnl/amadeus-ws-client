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

use Amadeus\Client\RequestOptions\TicketDeleteTstOptions;
use Amadeus\Client\Struct\Ticket\AttributeDetails;
use Amadeus\Client\Struct\Ticket\DeleteTST;
use Amadeus\Client\Struct\Ticket\ItemReference;
use Amadeus\Client\Struct\Ticket\RefDetails;
use Test\Amadeus\BaseTestCase;

/**
 * DeleteTstTest
 *
 * @package Test\Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DeleteTstTest extends BaseTestCase
{
    public function testCanDeleteAll()
    {
        $opt = new TicketDeleteTstOptions([
            'deleteMode' => TicketDeleteTstOptions::DELETE_MODE_ALL
        ]);

        $msg = new DeleteTST($opt);

        $this->assertEquals(AttributeDetails::MODE_ALL, $msg->deleteMode->attributeDetails->attributeType);
        $this->assertNull($msg->pnrLocatorData);
        $this->assertEmpty($msg->psaList);
    }

    public function testCanDeleteTstByNumber()
    {
        $opt = new TicketDeleteTstOptions([
            'deleteMode' => TicketDeleteTstOptions::DELETE_MODE_SELECTIVE,
            'tstNumber' => 1
        ]);

        $msg = new DeleteTST($opt);

        $this->assertEquals(AttributeDetails::MODE_SELECTIVE, $msg->deleteMode->attributeDetails->attributeType);
        $this->assertNull($msg->pnrLocatorData);
        $this->assertCount(1, $msg->psaList);
        $this->assertEquals(1, $msg->psaList[0]->itemReference->uniqueReference);
        $this->assertEquals(ItemReference::REFTYPE_TST, $msg->psaList[0]->itemReference->referenceType);
        $this->assertNull($msg->psaList[0]->itemReference->iDDescription);
        $this->assertNull($msg->psaList[0]->paxReference);
    }

    public function testCanDeleteTstByTattoo()
    {
        $opt = new TicketDeleteTstOptions([
            'deleteMode' => TicketDeleteTstOptions::DELETE_MODE_SELECTIVE,
            'tstTattooNr' => 3106394763
        ]);

        $msg = new DeleteTST($opt);

        $this->assertEquals(AttributeDetails::MODE_SELECTIVE, $msg->deleteMode->attributeDetails->attributeType);
        $this->assertNull($msg->pnrLocatorData);
        $this->assertCount(1, $msg->psaList);
        $this->assertNull($msg->psaList[0]->itemReference->uniqueReference);
        $this->assertEquals(ItemReference::REFTYPE_TST, $msg->psaList[0]->itemReference->referenceType);
        $this->assertEquals(3106394763, $msg->psaList[0]->itemReference->iDDescription->iDSequenceNumber);
        $this->assertNull($msg->psaList[0]->paxReference);
    }

    public function testCanDeleteTstByPaxRef()
    {
        $opt = new TicketDeleteTstOptions([
            'deleteMode' => TicketDeleteTstOptions::DELETE_MODE_SELECTIVE,
            'passengerNumber' => 3
        ]);

        $msg = new DeleteTST($opt);

        $this->assertEquals(AttributeDetails::MODE_SELECTIVE, $msg->deleteMode->attributeDetails->attributeType);
        $this->assertNull($msg->pnrLocatorData);
        $this->assertCount(1, $msg->psaList);
        $this->assertNull($msg->psaList[0]->itemReference->uniqueReference);
        $this->assertEquals(ItemReference::REFTYPE_TST, $msg->psaList[0]->itemReference->referenceType);
        $this->assertNull($msg->psaList[0]->itemReference->iDDescription);
        $this->assertEquals(3, $msg->psaList[0]->paxReference->refDetails[0]->refNumber);
        $this->assertEquals(RefDetails::QUAL_PASSENGER, $msg->psaList[0]->paxReference->refDetails[0]->refQualifier);
    }

    public function testCanDeleteTstBySegmentRef()
    {
        $opt = new TicketDeleteTstOptions([
            'deleteMode' => TicketDeleteTstOptions::DELETE_MODE_SELECTIVE,
            'segmentNumber' => 2
        ]);

        $msg = new DeleteTST($opt);

        $this->assertEquals(AttributeDetails::MODE_SELECTIVE, $msg->deleteMode->attributeDetails->attributeType);
        $this->assertNull($msg->pnrLocatorData);
        $this->assertCount(1, $msg->psaList);
        $this->assertNull($msg->psaList[0]->itemReference->uniqueReference);
        $this->assertEquals(ItemReference::REFTYPE_TST, $msg->psaList[0]->itemReference->referenceType);
        $this->assertNull($msg->psaList[0]->itemReference->iDDescription);
        $this->assertEquals(2, $msg->psaList[0]->paxReference->refDetails[0]->refNumber);
        $this->assertEquals(RefDetails::QUAL_SEGMENT_REFERENCE, $msg->psaList[0]->paxReference->refDetails[0]->refQualifier);
    }
}
