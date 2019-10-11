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

namespace Test\Amadeus\Client\Struct\DocIssuance;

use Amadeus\Client\RequestOptions\DocIssuance\CompoundOption;
use Amadeus\Client\RequestOptions\DocIssuanceIssueMiscDocOptions;
use Amadeus\Client\Struct\DocIssuance\AttributeDetails;
use Amadeus\Client\Struct\DocIssuance\IssueMiscellaneousDocuments;
use Amadeus\Client\Struct\DocIssuance\PassengerReference;
use Amadeus\Client\Struct\DocIssuance\PaxDetails;
use Amadeus\Client\Struct\DocIssuance\ReferenceDetails;
use Amadeus\Client\Struct\DocIssuance\StatusDetails;
use Amadeus\Client\Struct\DocIssuance\StockTicketNumberDetails;
use Test\Amadeus\BaseTestCase;

/**
 * IssueMiscellaneousDocumentsTest
 *
 * @package Test\Amadeus\Client\Struct\DocIssuance
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class IssueMiscellaneousDocumentsTest extends BaseTestCase
{
    /**
     * 5.1 Operation: Consolidator Method
     *
     * The consolidator functionality allows authorized travel agents to issue directly on an airline stock.
     *
     * The related cryptic option is: T-xx (i.e. TTM/T-AF).
     *
     * The related XML Codeset is: ETC.
     */
    public function testCanMakeMessageWithConsolidatorMethod()
    {
        $opt = new DocIssuanceIssueMiscDocOptions([
            'compoundOptions' => [
                new CompoundOption([
                    'type' => CompoundOption::TYPE_ET_CONSOLIDATOR,
                    'details' => '1A'
                ])
            ]
        ]);

        $message = new IssueMiscellaneousDocuments($opt);

        $this->assertEmpty($message->optionGroup);

        $this->assertCount(1, $message->otherCompoundOptions);
        $this->assertEquals(
            AttributeDetails::TYPE_ET_CONSOLIDATOR,
            $message->otherCompoundOptions[0]->attributeDetails->attributeType
        );
        $this->assertEquals(
            '1A',
            $message->otherCompoundOptions[0]->attributeDetails->attributeDescription
        );

        $this->assertNull($message->infantOrAdultAssociation);
        $this->assertEmpty($message->paxSelection);
        $this->assertEmpty($message->selection);
        $this->assertNull($message->stock);
    }

    /**
     * 5.16 Operation: Logical Print Address
     *
     * This option is used to identify the output printer. A printer identification may be one to six characters,
     * either alpha or numeric.
     *
     * The related cryptic option is: xxxxxx (TTMxxxxxxx)
     *
     * The related XML Codeset is: LPA.
     */
    public function testCanMakeMessageWithLogicalPrintAddress()
    {
        $opt = new DocIssuanceIssueMiscDocOptions([
            'compoundOptions' => [
                new CompoundOption([
                    'type' => CompoundOption::TYPE_LOGICAL_PRINTER_ADDRESS,
                    'details' => '771990'
                ])
            ]
        ]);

        $message = new IssueMiscellaneousDocuments($opt);

        $this->assertEmpty($message->optionGroup);

        $this->assertCount(1, $message->otherCompoundOptions);
        $this->assertEquals(
            AttributeDetails::TYPE_LOGICAL_PRINTER_ADDRESS,
            $message->otherCompoundOptions[0]->attributeDetails->attributeType
        );
        $this->assertEquals(
            '771990',
            $message->otherCompoundOptions[0]->attributeDetails->attributeDescription
        );

        $this->assertNull($message->infantOrAdultAssociation);
        $this->assertEmpty($message->paxSelection);
        $this->assertEmpty($message->selection);
        $this->assertNull($message->stock);
    }

    /**
     * 5.27 Operation: Print an Invoice
     *
     * The service allows requesting the printing of an invoice at issuance time.
     * This functionality is allowed to Central System users only.
     *
     * Two types of invoice exist: Basic and extended. The different between them is that the Basic one includes a
     * basic itinerary and the extended one includes an extended itinerary.
     *
     * This service allows the request of different invoice types:
     *
     *  - INV - Print Basic Invoice
     *
     * - IVJ - Print Basic Joint Invoice for all passengers
     *
     *  - INE - Print Extended Invoice
     *
     *  - INJ - Print Extended Joint Invoice for all passengers
     *
     * - IPE - PNR invoice. Used by BA, with a local credit form of payment (customer specific)
     *
     * - IPG - GAC invoice. Used by BA, with a global accounting form of payment (customer specific)
     *
     * To specify the type of invoice needed, the 3 letter codesets listed above (INV, IVJ, INE, INJ, IPE, IPG)
     * must be entered in the <optionGroup> section of the request:
     *
     * <optionGroup>
     *     <switches>
     *         <statusDetails>
     *             <indicator>INV</indicator>
     *         </statusDetails>
     *     </switches>
     * </optionGroup>
     */
    public function testCanMakeMessageWithPrintInvoice()
    {
        $opt = new DocIssuanceIssueMiscDocOptions([
            'options' => [
                DocIssuanceIssueMiscDocOptions::OPTION_EXTENDED_INVOICE_ALL_PAX
            ]
        ]);

        $message = new IssueMiscellaneousDocuments($opt);

        $this->assertCount(1, $message->optionGroup);
        $this->assertEquals(StatusDetails::OPTION_EXTENDED_INVOICE_ALL_PAX, $message->optionGroup[0]->switches->statusDetails->indicator);

        $this->assertEmpty($message->otherCompoundOptions);
        $this->assertNull($message->infantOrAdultAssociation);
        $this->assertEmpty($message->paxSelection);
        $this->assertEmpty($message->selection);
        $this->assertNull($message->stock);
    }

    /**
     * 5.11 Operation: INF Passenger Type Selection
     *
     * If only TSM related to infants (INF passenger type) are to be issued, this option must be used.
     *
     * The related cryptic option is: INF (i.e. TTM/INF).
     *
     * The related XML Codeset is: IN.
     */
    public function testCanMakeMessageWithPassengerTypeInf()
    {
        $opt = new DocIssuanceIssueMiscDocOptions([
            'passengerType' => DocIssuanceIssueMiscDocOptions::PAX_TYPE_INFANT
        ]);

        $message = new IssueMiscellaneousDocuments($opt);

        $this->assertEmpty($message->optionGroup);

        $this->assertEmpty($message->otherCompoundOptions);

        $this->assertEquals(PaxDetails::TYPE_INFANT, $message->infantOrAdultAssociation->paxDetails->type);
        $this->assertEmpty($message->paxSelection);
        $this->assertEmpty($message->selection);
        $this->assertNull($message->stock);
    }

    /**
     * 5.35 Operation: TSM selection
     *
     * This option allows to issue a specific TSM by referencing the  TSM number or the TSM Tattoo.
     *
     * To reference a TSM tatoo, the codeset TMT must be used.
     * <selection><referenceDetails><type>TMT</type></referenceDetails></selection>
     *
     * To reference a TSM number, the codeset M must be used.
     * <selection><referenceDetails><type>M</type></referenceDetails></selection>
     *
     * Multi-TSM selection is permitted. To do so, the entity must be repeated as many times as needed.
     *
     * The related cryptic entry is: M.
     *
     * The related XML Codeset is: M or TMT.
     */
    public function testCanMakeMessageWithTsmSelection()
    {
        $opt = new DocIssuanceIssueMiscDocOptions([
            'tsmNumbers' => [1]
        ]);

        $message = new IssueMiscellaneousDocuments($opt);

        $this->assertCount(1, $message->selection->referenceDetails);
        $this->assertEquals(ReferenceDetails::TYPE_TSM, $message->selection->referenceDetails[0]->type);
        $this->assertEquals(1, $message->selection->referenceDetails[0]->value);

        $this->assertEmpty($message->optionGroup);
        $this->assertEmpty($message->otherCompoundOptions);
        $this->assertNull($message->infantOrAdultAssociation);
        $this->assertEmpty($message->paxSelection);
        $this->assertNull($message->stock);

        //And now with TSM Tattoo:
        $opt = new DocIssuanceIssueMiscDocOptions([
            'tsmTattoos' => [3]
        ]);

        $message2 = new IssueMiscellaneousDocuments($opt);

        $this->assertCount(1, $message2->selection->referenceDetails);
        $this->assertEquals(ReferenceDetails::TYPE_TSM_TATTOO, $message2->selection->referenceDetails[0]->type);
        $this->assertEquals(3, $message2->selection->referenceDetails[0]->value);

        $this->assertEmpty($message2->optionGroup);
        $this->assertEmpty($message2->otherCompoundOptions);
        $this->assertNull($message2->infantOrAdultAssociation);
        $this->assertEmpty($message2->paxSelection);
        $this->assertNull($message2->stock);
    }

    /**
     * 5.15 Operation: Line Number Selection
     *
     * If some , but not all, miscellaneous documents are desired, this option has to be used.
     *
     * This option allows to select the line reference for MCO/XSB/SVC/SVF/EMD element to be issued by entering the line number.
     *
     * Multi-line selection is also allowed. To do so, it is enough to repeat the entity as many times as required.
     *
     * The related cryptic option is: Ln (TTM/L5)
     *
     * The related XML Codeset is: L.
     */
    public function testCanMakeMessageWithLineNumberSelection()
    {
        $opt = new DocIssuanceIssueMiscDocOptions([
            'lineNumbers' => [5, 6]
        ]);

        $message = new IssueMiscellaneousDocuments($opt);

        $this->assertCount(2, $message->selection->referenceDetails);
        $this->assertEquals(ReferenceDetails::TYPE_LINE_NUMBER, $message->selection->referenceDetails[0]->type);
        $this->assertEquals(5, $message->selection->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::TYPE_LINE_NUMBER, $message->selection->referenceDetails[1]->type);
        $this->assertEquals(6, $message->selection->referenceDetails[1]->value);

        $this->assertEmpty($message->optionGroup);
        $this->assertEmpty($message->otherCompoundOptions);
        $this->assertNull($message->infantOrAdultAssociation);
        $this->assertEmpty($message->paxSelection);
        $this->assertNull($message->stock);
    }

    /**
     * 5.22 Operation: Passenger Selection
     *
     * If some, but not all, passengers services are to be issued, this option can be used.
     *
     * This operation allows selecting the passenger(s) either:
     *
     * - by entering the passenger reference in the PNR: <type>PAX</type><value>1</value>
     * - or by entering the passenger tattoo: <type>PT</type><value>2</value>
     *
     * Multipax selection is also allowed when using this operation.
     * To do so, it is enough to repeat the <paxSelection> entity as many times as needed.
     *
     * The related cryptic option is: Pn (TTM/P1).
     *
     * The related XML Codeset is: PT or PAX.
     */
    public function testCanMakeMessageWithPassengerSelection()
    {
        $opt = new DocIssuanceIssueMiscDocOptions([
            'passengerNumbers' => [1, 2]
        ]);

        $message = new IssueMiscellaneousDocuments($opt);

        $this->assertCount(2, $message->paxSelection);
        $this->assertEquals(PassengerReference::TYPE_PAX_SELECTION, $message->paxSelection[0]->passengerReference->type);
        $this->assertEquals(1, $message->paxSelection[0]->passengerReference->value);
        $this->assertEquals(PassengerReference::TYPE_PAX_SELECTION, $message->paxSelection[1]->passengerReference->type);
        $this->assertEquals(2, $message->paxSelection[1]->passengerReference->value);

        $this->assertEmpty($message->selection);
        $this->assertEmpty($message->optionGroup);
        $this->assertEmpty($message->otherCompoundOptions);
        $this->assertNull($message->infantOrAdultAssociation);
        $this->assertNull($message->stock);

        //And now with TSM Tattoo:
        $opt = new DocIssuanceIssueMiscDocOptions([
            'passengerTattoos' => [3, 4]
        ]);

        $message2 = new IssueMiscellaneousDocuments($opt);

        $this->assertCount(2, $message2->paxSelection);
        $this->assertEquals(PassengerReference::TYPE_PAX_TATTOO, $message2->paxSelection[0]->passengerReference->type);
        $this->assertEquals(3, $message2->paxSelection[0]->passengerReference->value);
        $this->assertEquals(PassengerReference::TYPE_PAX_TATTOO, $message2->paxSelection[1]->passengerReference->type);
        $this->assertEquals(4, $message2->paxSelection[1]->passengerReference->value);

        $this->assertEmpty($message2->selection);
        $this->assertEmpty($message2->optionGroup);
        $this->assertEmpty($message2->otherCompoundOptions);
        $this->assertNull($message2->infantOrAdultAssociation);
        $this->assertNull($message2->stock);
    }

    /**
     * 5.32 Operation: Stock Reference
     *
     * A stock reference from the Stock Master File associated to an Office Identification Profile
     * can be specified in the Issuance command.
     * The Stock Reference is composed of up to two (2) alpha characters followed by up to three (3) numeric characters.
     *
     * The related cryptic option is: Xxxxxx (i.e. TTM/xh1)
     *
     * The related XML Codeset is: S.
     */
    public function testCanMakeMessageWithStockReference()
    {
        $opt = new DocIssuanceIssueMiscDocOptions([
            'stockReference' => 'A1'
        ]);

        $message = new IssueMiscellaneousDocuments($opt);

        $this->assertEquals('A1', $message->stock->stockTicketNumberDetails->controlNumber);
        $this->assertEquals(StockTicketNumberDetails::QUAL_STOCK_CONTROL_NR, $message->stock->stockTicketNumberDetails->qualifier);

        $this->assertEmpty($message->optionGroup);
        $this->assertEmpty($message->otherCompoundOptions);
        $this->assertNull($message->infantOrAdultAssociation);
        $this->assertEmpty($message->paxSelection);
        $this->assertEmpty($message->selection);
    }
}
