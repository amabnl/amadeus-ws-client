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

namespace Test\Amadeus\Client\Struct\Fare;

use Amadeus\Client\RequestOptions\FareCheckRulesOptions;
use Amadeus\Client\Struct\Fare\CheckRules;
use Amadeus\Client\Struct\Fare\MessageFunctionDetails;
use Test\Amadeus\BaseTestCase;

/**
 * CheckRulesTest
 *
 * @package Test\Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class CheckRulesTest extends BaseTestCase
{
    public function testCanMakeBasicFareCheckRulesMessage()
    {
        $opt = new FareCheckRulesOptions([
            'recommendations' => [1]
        ]);

        $message = new CheckRules($opt);

        $this->assertEquals(MessageFunctionDetails::FARE_DISPLAY_RULES, $message->msgType->messageFunctionDetails->messageFunction);

        $this->assertEquals(1, count($message->itemNumber->itemNumberDetails));
        $this->assertEquals(1, $message->itemNumber->itemNumberDetails[0]->number);
        $this->assertEmpty($message->flightQualification);
    }

    public function testCanMakeBasicFareCheckRulesMessageRequestCategories()
    {
        $opt = new FareCheckRulesOptions([
            'recommendations' => [1],
            'categoryList' => true
        ]);

        $message = new CheckRules($opt);

        $this->assertEquals(MessageFunctionDetails::FARE_DISPLAY_RULES, $message->msgType->messageFunctionDetails->messageFunction);

        $this->assertEquals(1, count($message->itemNumber->itemNumberDetails));
        $this->assertEquals(1, $message->itemNumber->itemNumberDetails[0]->number);

        $this->assertEquals(1, count($message->flightQualification));
        $this->assertEquals(CheckRules\DiscountDetails::FAREQUAL_RULE_CATEGORIES, $message->flightQualification[0]->discountDetails[0]->fareQualifier);
    }

    public function testCanMakeBasicFareCheckRulesMessageRequestAllCategories()
    {
        $opt = new FareCheckRulesOptions([
            'recommendations' => [1],
            'categories' => ['RU']
        ]);

        $message = new CheckRules($opt);

        $this->assertEquals(1, count($message->itemNumber->itemNumberDetails));
        $this->assertEquals(1, $message->itemNumber->itemNumberDetails[0]->number);

        $this->assertEquals(1, count($message->fareRule->tarifFareRule->ruleSectionId));
        $this->assertEquals('RU', $message->fareRule->tarifFareRule->ruleSectionId[0]);
    }
}
