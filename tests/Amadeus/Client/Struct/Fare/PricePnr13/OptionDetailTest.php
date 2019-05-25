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

namespace Amadeus\Client\Struct\Fare\PricePnr13;

use Test\Amadeus\BaseTestCase;

/**
 * @package Test\Amadeus\Client\Struct\Fare\PricePnr13
 * @author  tsari <tibor.sari@invia.de>
 */
class OptionDetailTest extends BaseTestCase
{

    public function testCanConstructWithString()
    {
        $optionDetail = new OptionDetail('string-value');
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\PricePnr13\CriteriaDetails', $optionDetail->criteriaDetails[0]);
    }

    public function testCanConstructWithArrayOfStrings()
    {
        $optionDetail = new OptionDetail(['string-value1', 'string-value2']);
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\PricePnr13\CriteriaDetails', $optionDetail->criteriaDetails[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\PricePnr13\CriteriaDetails', $optionDetail->criteriaDetails[1]);
    }

    public function testCanConstructWithObject()
    {
        $optionDetail = new OptionDetail(new CriteriaDetails('string-value'));
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\PricePnr13\CriteriaDetails', $optionDetail->criteriaDetails[0]);
    }

    public function testCanConstructWithArrayOfObjects()
    {
        $optionDetail = new OptionDetail([new CriteriaDetails('string-value1'), new CriteriaDetails('string-value2')]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\PricePnr13\CriteriaDetails', $optionDetail->criteriaDetails[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\PricePnr13\CriteriaDetails', $optionDetail->criteriaDetails[1]);
    }
}