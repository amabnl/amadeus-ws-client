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
use Amadeus\Client\RequestOptions\DocIssuanceIssueTicketOptions;
use Amadeus\Client\Struct\DocIssuance\AttributeDetails;
use Amadeus\Client\Struct\DocIssuance\InternalIdDetails;
use Amadeus\Client\Struct\DocIssuance\IssueTicket;
use Amadeus\Client\Struct\DocIssuance\OverrideDate;
use Amadeus\Client\Struct\DocIssuance\PassengerReference;
use Amadeus\Client\Struct\DocIssuance\ReferenceDetails;
use Test\Amadeus\BaseTestCase;

/**
 * IssueTicketTest
 *
 * @package Test\Amadeus\Client\Struct\DocIssuance
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class IssueTicketTest extends BaseTestCase
{
    public function testCanMakeIssueTicketRequestETicket()
    {
        $opt = new DocIssuanceIssueTicketOptions([
            'options' => [DocIssuanceIssueTicketOptions::OPTION_ETICKET]
        ]);

        $message = new IssueTicket($opt);

        $this->assertCount(1, $message->optionGroup);
        $this->assertEquals(
            DocIssuanceIssueTicketOptions::OPTION_ETICKET,
            $message->optionGroup[0]->switches->statusDetails->indicator
        );
    }

    public function testCanMakeIssueTicketRequestWithPaxAndSegReferences()
    {
        $opt = new DocIssuanceIssueTicketOptions([
            'segmentTattoos' => [1],
            'passengerTattoos' => [2,3]
        ]);

        $message = new IssueTicket($opt);

        $this->assertCount(0, $message->optionGroup);
        $this->assertCount(1, $message->selection->referenceDetails);
        $this->assertEquals(1, $message->selection->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::TYPE_SEGMENT_TATTOO, $message->selection->referenceDetails[0]->type);
        $this->assertCount(2, $message->paxSelection);
        $this->assertEquals(2, $message->paxSelection[0]->passengerReference->value);
        $this->assertEquals(PassengerReference::TYPE_PAX_TATTOO, $message->paxSelection[1]->passengerReference->type);
        $this->assertEquals(3, $message->paxSelection[1]->passengerReference->value);
        $this->assertEquals(PassengerReference::TYPE_PAX_TATTOO, $message->paxSelection[1]->passengerReference->type);
    }

    public function testCanMakeIssueTicketRequestWithTstRef()
    {
        $opt = new DocIssuanceIssueTicketOptions([
            'tsts' => [1]
        ]);

        $message = new IssueTicket($opt);

        $this->assertCount(0, $message->optionGroup);
        $this->assertCount(1, $message->selection->referenceDetails);
        $this->assertEquals(1, $message->selection->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::TYPE_TST, $message->selection->referenceDetails[0]->type);
    }

    public function testCanMakeIssueTicketRequestWithAlternateDateVal()
    {
        $opt = new DocIssuanceIssueTicketOptions([
            'alternateDateValidation' => \DateTime::createFromFormat('dmY', '16102016', new \DateTimeZone('UTC'))
        ]);

        $message = new IssueTicket($opt);

        $this->assertCount(0, $message->optionGroup);
        $this->assertEquals(OverrideDate::OPT_ALTERNATE_DATE_VALIDATION, $message->overrideDate->businessSemantic);
        $this->assertEquals('16', $message->overrideDate->dateTime->day);
        $this->assertEquals('10', $message->overrideDate->dateTime->month);
        $this->assertEquals('2016', $message->overrideDate->dateTime->year);
    }

    public function testCanMakeIssueTicketRequestWithOverridePastDateTst()
    {
        $opt = new DocIssuanceIssueTicketOptions([
            'overridePastDateTst' => true
        ]);

        $message = new IssueTicket($opt);

        $this->assertCount(0, $message->optionGroup);
        $this->assertEquals(OverrideDate::OPT_OVERRIDE_PAST_DATE_TST, $message->overrideDate->businessSemantic);
        $this->assertNull($message->overrideDate->dateTime);
    }

    public function testCanMakeIssueTicketRequestWithOverrideAgentCode()
    {
        $opt = new DocIssuanceIssueTicketOptions([
            'agentCode' => 'ABCD1'
        ]);

        $message = new IssueTicket($opt);

        $this->assertCount(0, $message->optionGroup);
        $this->assertEquals('ABCD1', $message->agentInfo->internalIdDetails->inhouseId);
        $this->assertEquals(InternalIdDetails::TYPE_AGENT_CODE, $message->agentInfo->internalIdDetails->type);
    }

    public function testCanMakeIssueTicketRequestWithOverridePassengerType()
    {
        $opt = new DocIssuanceIssueTicketOptions([
            'passengerType' => DocIssuanceIssueTicketOptions::PAX_TYPE_ADULT
        ]);

        $message = new IssueTicket($opt);

        $this->assertCount(0, $message->optionGroup);
        $this->assertEquals(DocIssuanceIssueTicketOptions::PAX_TYPE_ADULT, $message->infantOrAdultAssociation->paxDetails->type);
    }

    public function testCanMakeIssueTicketRequestWithConsolidatorMethod()
    {
        $opt = new DocIssuanceIssueTicketOptions([
            'options' => [
                DocIssuanceIssueTicketOptions::OPTION_ETICKET
            ],
            'compoundOptions' => [
                new CompoundOption([
                    'type' => CompoundOption::TYPE_ET_CONSOLIDATOR,
                    'details' => '1A'
                ])
            ]
        ]);

        $message = new IssueTicket($opt);

        $this->assertCount(1, $message->optionGroup);
        $this->assertEquals(
            DocIssuanceIssueTicketOptions::OPTION_ETICKET,
            $message->optionGroup[0]->switches->statusDetails->indicator
        );

        $this->assertCount(1, $message->otherCompoundOptions);
        $this->assertEquals(
            AttributeDetails::TYPE_ET_CONSOLIDATOR,
            $message->otherCompoundOptions[0]->attributeDetails->attributeType
        );
        $this->assertEquals(
            '1A',
            $message->otherCompoundOptions[0]->attributeDetails->attributeDescription
        );

        $this->assertNull($message->overrideDate);
        $this->assertNull($message->infantOrAdultAssociation);
        $this->assertNull($message->agentInfo);
        $this->assertEmpty($message->paxSelection);
        $this->assertEmpty($message->selection);
        $this->assertNull($message->stock);
    }

    public function testCanMakeRevalidationWithFOPLineNumberSegmentChaneAndCouponNumber()
    {
        $opt = new DocIssuanceIssueTicketOptions([
            'options' => [
                DocIssuanceIssueTicketOptions::OPTION_ETICKET_REVALIDATION
            ],
            'segmentTattoos' => [1],
            'lineNumbers' => [14],
            'couponNumbers' => [3]
        ]);

        $message = new IssueTicket($opt);

        $this->assertCount(1, $message->optionGroup);
        $this->assertEquals(
            DocIssuanceIssueTicketOptions::OPTION_ETICKET_REVALIDATION,
            $message->optionGroup[0]->switches->statusDetails->indicator
        );

        $this->assertCount(3, $message->selection->referenceDetails);

        $this->assertEquals(1, $message->selection->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::TYPE_SEGMENT_TATTOO, $message->selection->referenceDetails[0]->type);

        $this->assertEquals(14, $message->selection->referenceDetails[1]->value);
        $this->assertEquals(ReferenceDetails::TYPE_LINE_NUMBER, $message->selection->referenceDetails[1]->type);

        $this->assertEquals(3, $message->selection->referenceDetails[2]->value);
        $this->assertEquals(ReferenceDetails::TYPE_COUPON_NUMBER, $message->selection->referenceDetails[2]->type);
    }
}
