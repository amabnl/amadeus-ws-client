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

use Amadeus\Client\RequestOptions\DocIssuance\Option;
use Amadeus\Client\RequestOptions\DocIssuanceIssueCombinedOptions;
use Amadeus\Client\Struct\DocIssuance\IssueCombined;
use Amadeus\Client\Struct\DocIssuance\StatusDetails;
use Test\Amadeus\BaseTestCase;

/**
 * IssueCombinedTest
 *
 * @package Test\Amadeus\Client\Struct\DocIssuance
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class IssueCombinedTest extends BaseTestCase
{
    public function testCanMakeMessageWithDocReceipt()
    {
        $msg = new IssueCombined(
            new DocIssuanceIssueCombinedOptions([
                'options' => [
                    new Option([
                        'indicator' => Option::INDICATOR_DOCUMENT_RECEIPT,
                        'subCompoundType' => 'EMPRA'
                    ])
                ]
            ])
        );

        $this->assertCount(1, $msg->optionGroup);
        $this->assertEquals(StatusDetails::OPTION_DOCUMENT_RECEIPT, $msg->optionGroup[0]->switches->statusDetails->indicator);
        $this->assertCount(1, $msg->optionGroup[0]->subCompoundOptions);
        $this->assertEquals('EMPRA', $msg->optionGroup[0]->subCompoundOptions[0]->criteriaDetails->attributeType);
        $this->assertNull($msg->optionGroup[0]->subCompoundOptions[0]->criteriaDetails->attributeDescription);
        $this->assertNull($msg->optionGroup[0]->overrideAlternativeDate);

        $this->assertNull($msg->agentInfo);
        $this->assertNull($msg->infantOrAdultAssociation);
        $this->assertEmpty($msg->otherCompoundOptions);
        $this->assertNull($msg->overrideDate);
        $this->assertEmpty($msg->paxSelection);
        $this->assertEmpty($msg->selection);
        $this->assertNull($msg->stock);
    }
    public function testIssueCombinedTstAndTsm()
    {
        $msg = new IssueCombined(
            new DocIssuanceIssueCombinedOptions([
                'tsts' => [1, 2],
                'tsmTattoos' => [5, 8]
            ])
        );

        $this->assertEquals(1, $msg->selection->referenceDetails[0]->value);
        $this->assertEquals('TS', $msg->selection->referenceDetails[0]->type);
        $this->assertEquals(2, $msg->selection->referenceDetails[1]->value);
        $this->assertEquals('TS', $msg->selection->referenceDetails[1]->type);
        $this->assertEquals(5, $msg->selection->referenceDetails[2]->value);
        $this->assertEquals('TMT', $msg->selection->referenceDetails[2]->type);
        $this->assertEquals(8, $msg->selection->referenceDetails[3]->value);
        $this->assertEquals('TMT', $msg->selection->referenceDetails[3]->type);
    }
}
