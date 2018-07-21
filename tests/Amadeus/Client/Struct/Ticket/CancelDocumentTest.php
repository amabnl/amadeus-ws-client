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

use Amadeus\Client\RequestOptions\Ticket\SequenceRange;
use Amadeus\Client\RequestOptions\TicketCancelDocumentOptions;
use Amadeus\Client\Struct\Ticket\CancelDocument;
use Test\Amadeus\BaseTestCase;

/**
 * CancelDocumentTest
 *
 * @package Test\Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CancelDocumentTest extends BaseTestCase
{
    /**
     * 5.1 Operation: Request cancellation of a transaction by ticket number
     *
     * The cancellation action has been requested by an authorized agent signed in office NCE6X0100,
     * and the ticket 1721587458965 is eligible for the cancellation.
     */
    public function testCancellationByTicketNumber()
    {
        $msg = new CancelDocument(
            new TicketCancelDocumentOptions([
                'eTicket' => '1721587458965',
                'airlineStockProvider' => '6X',
                'officeId' => 'NCE6X0100'
            ])
        );

        $this->assertNull($msg->voidOption);

        $this->assertEmpty($msg->sequenceNumberRanges);

        $this->assertEquals('1721587458965', $msg->documentNumberDetails->documentDetails->number);
        $this->assertNull($msg->documentNumberDetails->documentDetails->type);
        $this->assertNull($msg->documentNumberDetails->status);

        $this->assertEquals('6X', $msg->stockProviderDetails->officeSettingsDetails->stockProviderCode);
        $this->assertNull($msg->stockProviderDetails->officeSettingsDetails->marketIataCode);

        $this->assertEquals('NCE6X0100', $msg->targetOfficeDetails->originatorDetails->inHouseIdentification2);
    }

    /**
     * 5.2 Operation: Request cancellation of a transaction by ticket number associated to sales report process(TRDC/SR)
     *
     * The void action has been requested by an authorized agent signed in office NCE6X0100,
     * the ticket 1721587458965 is eligible for the void and option "sales report only" is used.
     */
    public function testCancellationWithVoid()
    {
        $msg = new CancelDocument(
            new TicketCancelDocumentOptions([
                'eTicket' => '1721587458965',
                'airlineStockProvider' => '6X',
                'officeId' => 'NCE6X0100',
                'void' => true,
            ])
        );

        $this->assertEquals(CancelDocument\StatusInformation::INDICATOR_SALES_REPORT_ONLY, $msg->voidOption->statusInformation->indicator);

        $this->assertEmpty($msg->sequenceNumberRanges);

        $this->assertEquals('1721587458965', $msg->documentNumberDetails->documentDetails->number);
        $this->assertNull($msg->documentNumberDetails->documentDetails->type);
        $this->assertNull($msg->documentNumberDetails->status);

        $this->assertEquals('6X', $msg->stockProviderDetails->officeSettingsDetails->stockProviderCode);
        $this->assertNull($msg->stockProviderDetails->officeSettingsDetails->marketIataCode);

        $this->assertEquals('NCE6X0100', $msg->targetOfficeDetails->originatorDetails->inHouseIdentification2);
    }

    /**
     * 5.3 Operation: Request cancellation of a transaction by ticket number for Travel Agent
     *
     * The void action has been requested by an authorized agent signed in office FRAL12177,
     * and the ticket 4600052609 is eligible for the void.
     */
    public function testCancelTransactionByTicketNumberForTravelAgent()
    {
        $msg = new CancelDocument(
            new TicketCancelDocumentOptions([
                'eTicket' => '4600052609',
                'marketStockProvider' => 'DE',
                'officeId' => 'FRAL12177',
            ])
        );

        $this->assertNull($msg->voidOption);
        $this->assertEmpty($msg->sequenceNumberRanges);

        $this->assertEquals('4600052609', $msg->documentNumberDetails->documentDetails->number);
        $this->assertNull($msg->documentNumberDetails->documentDetails->type);
        $this->assertNull($msg->documentNumberDetails->status);

        $this->assertEquals('DE', $msg->stockProviderDetails->officeSettingsDetails->marketIataCode);
        $this->assertNull($msg->stockProviderDetails->officeSettingsDetails->stockProviderCode);

        $this->assertEquals('FRAL12177', $msg->targetOfficeDetails->originatorDetails->inHouseIdentification2);
    }

    /**
     * 5.4 Operation: Request cancellation of a transaction from query report
     *
     * The void action has been requested by an authorized agent signed in office NCE6X0100,
     * and the ticket which sequence number in query sales report is 1408, is eligible for the void.
     */
    public function testCanCancelFromQueryReport()
    {
        $msg = new CancelDocument(
            new TicketCancelDocumentOptions([
                'sequenceRanges' => [
                    new SequenceRange([
                        'from' => '1408'
                    ])
                ],
                'airlineStockProvider' => '6X',
                'officeId' => 'NCE6X0100',
            ])
        );

        $this->assertNull($msg->voidOption);
        $this->assertCount(1, $msg->sequenceNumberRanges);
        $this->assertCount(1, $msg->sequenceNumberRanges[0]->itemNumberDetails);
        $this->assertEquals('1408', $msg->sequenceNumberRanges[0]->itemNumberDetails[0]->number);
        $this->assertEquals(CancelDocument\ItemNumberDetails::TYPE_FROM, $msg->sequenceNumberRanges[0]->itemNumberDetails[0]->type);

        $this->assertNull($msg->documentNumberDetails);

        $this->assertEquals('6X', $msg->stockProviderDetails->officeSettingsDetails->stockProviderCode);
        $this->assertNull($msg->stockProviderDetails->officeSettingsDetails->marketIataCode);

        $this->assertEquals('NCE6X0100', $msg->targetOfficeDetails->originatorDetails->inHouseIdentification2);
    }

    /**
     * 5.5 Operation: Request cancellation of several tickets, individual items and ranges of items from query report
     *
     * The void action has been requested by an authorized agent signed in office NCE6X0100,
     * and the tickets which sequence number in query sales report between 1408 and 1415 are eligible for the void.
     * The void has been requested for individual items which sequence numbers are 1408 and 1414;
     * and for ranges of transactions with sequence numbers from 1410 to 1412.
     *
     */
    public function testCanCancelSeveralTickets()
    {
        $msg = new CancelDocument(
            new TicketCancelDocumentOptions([
                'sequenceRanges' => [
                    new SequenceRange([
                        'from' => '1408'
                    ]),
                    new SequenceRange([
                        'from' => '1410',
                        'to' => '1412'
                    ]),
                    new SequenceRange([
                        'from' => '1414'
                    ])
                ],
                'airlineStockProvider' => '6X',
                'officeId' => 'NCE6X0100',
            ])
        );

        $this->assertNull($msg->voidOption);

        $this->assertCount(3, $msg->sequenceNumberRanges);
        $this->assertCount(1, $msg->sequenceNumberRanges[0]->itemNumberDetails);
        $this->assertEquals('1408', $msg->sequenceNumberRanges[0]->itemNumberDetails[0]->number);
        $this->assertEquals(CancelDocument\ItemNumberDetails::TYPE_FROM, $msg->sequenceNumberRanges[0]->itemNumberDetails[0]->type);

        $this->assertCount(2, $msg->sequenceNumberRanges[1]->itemNumberDetails);
        $this->assertEquals('1410', $msg->sequenceNumberRanges[1]->itemNumberDetails[0]->number);
        $this->assertEquals(CancelDocument\ItemNumberDetails::TYPE_FROM, $msg->sequenceNumberRanges[1]->itemNumberDetails[0]->type);
        $this->assertEquals('1412', $msg->sequenceNumberRanges[1]->itemNumberDetails[1]->number);
        $this->assertEquals(CancelDocument\ItemNumberDetails::TYPE_TO, $msg->sequenceNumberRanges[1]->itemNumberDetails[1]->type);

        $this->assertCount(1, $msg->sequenceNumberRanges[2]->itemNumberDetails);
        $this->assertEquals('1414', $msg->sequenceNumberRanges[2]->itemNumberDetails[0]->number);
        $this->assertEquals(CancelDocument\ItemNumberDetails::TYPE_FROM, $msg->sequenceNumberRanges[2]->itemNumberDetails[0]->type);

        $this->assertNull($msg->documentNumberDetails);

        $this->assertEquals('6X', $msg->stockProviderDetails->officeSettingsDetails->stockProviderCode);
        $this->assertNull($msg->stockProviderDetails->officeSettingsDetails->marketIataCode);

        $this->assertEquals('NCE6X0100', $msg->targetOfficeDetails->originatorDetails->inHouseIdentification2);
    }

    /**
     * 5.6 Operation: Request E-ticket Direct cancellation
     *
     * This operation allows the end user to initiate a void transaction using E-ticket direct feature.
     * E-ticket direct cancellation is initiated from TNRMG210C office on XX airline stock.
     */
    public function testRequestDirectEticketCancellation()
    {
        $msg = new CancelDocument(
            new TicketCancelDocumentOptions([
                'eTicket' => '2327176820',
                'airlineStockProvider' => 'XX',
                'officeId' => 'TNRMG210C'
            ])
        );

        $this->assertNull($msg->voidOption);

        $this->assertEmpty($msg->sequenceNumberRanges);

        $this->assertEquals('2327176820', $msg->documentNumberDetails->documentDetails->number);
        $this->assertNull($msg->documentNumberDetails->documentDetails->type);
        $this->assertNull($msg->documentNumberDetails->status);

        $this->assertEquals('XX', $msg->stockProviderDetails->officeSettingsDetails->stockProviderCode);
        $this->assertNull($msg->stockProviderDetails->officeSettingsDetails->marketIataCode);

        $this->assertEquals('TNRMG210C', $msg->targetOfficeDetails->originatorDetails->inHouseIdentification2);
    }
}
