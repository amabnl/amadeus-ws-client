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

use Amadeus\Client\RequestOptions\PnrTransferOwnershipOptions;
use Amadeus\Client\Struct\Pnr\TransferOwnership;
use Test\Amadeus\BaseTestCase;

/**
 * TransferOwnershipTest
 *
 * @package Test\Amadeus\Client\Struct\Pnr
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TransferOwnershipTest extends BaseTestCase
{
    public function testCanMakeBasicMessage()
    {
        $opt = new PnrTransferOwnershipOptions([
            'recordLocator' => 'ABC123',
            'newOffice' => 'BRU1AXXXX'
        ]);

        $message = new TransferOwnership($opt);

        $this->assertEquals('ABC123', $message->recordLocator->reservation->controlNumber);
        $this->assertEquals('BRU1AXXXX', $message->officeIdentification->officeIdentificator->originatorDetails->inHouseIdentification1);
        $this->assertNull($message->officeIdentification->officeIdentificator->originatorDetails->inHouseIdentification2);
        $this->assertEmpty($message->officeIdentification->specificChanges);

        $this->assertNull($message->oaIdentificator);
        $this->assertNull($message->propagatioAction);
    }

    public function testCanMakeBasicMessageWithTransferOptions()
    {
        $opt = new PnrTransferOwnershipOptions([
            'recordLocator' => 'ABC123',
            'newOffice' => 'BRU1AXXXX',
            'changeTicketingOffice' => true
        ]);

        $message = new TransferOwnership($opt);

        $this->assertEquals('ABC123', $message->recordLocator->reservation->controlNumber);
        $this->assertEquals('BRU1AXXXX', $message->officeIdentification->officeIdentificator->originatorDetails->inHouseIdentification1);
        $this->assertNull($message->officeIdentification->officeIdentificator->originatorDetails->inHouseIdentification2);
        $this->assertCount(1, $message->officeIdentification->specificChanges);
        $this->assertEquals(TransferOwnership\SpecificChanges::ACTION_TICKETING_OFFICE, $message->officeIdentification->specificChanges[0]->actionRequestCode);

        $this->assertNull($message->oaIdentificator);
        $this->assertNull($message->propagatioAction);
    }

    public function testCanMakeBasicMessageWithChangeUserEntity()
    {
        $opt = new PnrTransferOwnershipOptions([
            'recordLocator' => 'ABC987',
            'newUserSecurityEntity' => 'AgencyLON',
        ]);

        $message = new TransferOwnership($opt);

        $this->assertEquals('ABC987', $message->recordLocator->reservation->controlNumber);
        $this->assertNull($message->officeIdentification->officeIdentificator->originatorDetails->inHouseIdentification1);
        $this->assertEquals('AgencyLON', $message->officeIdentification->officeIdentificator->originatorDetails->inHouseIdentification2);
        $this->assertEmpty($message->officeIdentification->specificChanges);

        $this->assertNull($message->oaIdentificator);
        $this->assertNull($message->propagatioAction);
    }

    public function testCanMakeBasicMessageWithTransferOwnershipToThirdParty()
    {
        $opt = new PnrTransferOwnershipOptions([
            'recordLocator' => 'ABC987',
            'newThirdParty' => 'HDQRM',
        ]);

        $message = new TransferOwnership($opt);

        $this->assertEquals('ABC987', $message->recordLocator->reservation->controlNumber);
        $this->assertNull($message->officeIdentification);
        $this->assertEquals('HDQRM', $message->oaIdentificator->referenceDetails->value);
        $this->assertNull($message->propagatioAction);
    }

    /**
     * This example shows the query which transfers ownership of a retrieved PNR,
     * changing also the ticketing office, the queueing office and the office specified in the option queue element,
     * without spreading through the AXR.
     */
    public function testCanMakeMessageTransferOwnershipToOffice()
    {
        $opt = new PnrTransferOwnershipOptions([
            'recordLocator' => 'ABC654',
            'newOffice' => 'NCE6X0980',
            'inhibitPropagation' => true,
            'changeTicketingOffice' => true,
            'changeQueueingOffice' => true,
            'changeOptionQueueElement' => true,
        ]);

        $message = new TransferOwnership($opt);

        $this->assertEquals('ABC654', $message->recordLocator->reservation->controlNumber);
        $this->assertEquals('NCE6X0980', $message->officeIdentification->officeIdentificator->originatorDetails->inHouseIdentification1);
        $this->assertCount(3, $message->officeIdentification->specificChanges);
        $this->assertEquals(TransferOwnership\SpecificChanges::ACTION_TICKETING_OFFICE, $message->officeIdentification->specificChanges[0]->actionRequestCode);
        $this->assertEquals(TransferOwnership\SpecificChanges::ACTION_QUEUEING_OFFICE, $message->officeIdentification->specificChanges[1]->actionRequestCode);
        $this->assertEquals(TransferOwnership\SpecificChanges::ACTION_OPT_QUEUE_ELEMENT, $message->officeIdentification->specificChanges[2]->actionRequestCode);

        $this->assertNull($message->oaIdentificator);
        $this->assertEquals(TransferOwnership\PropagationAction::PROPAGATION_INHIBIT, $message->propagatioAction->actionRequestCode);
    }
}
