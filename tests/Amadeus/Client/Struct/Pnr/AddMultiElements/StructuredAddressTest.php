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

namespace Test\Amadeus\Client\Struct\Pnr\AddMultiElements;

use Amadeus\Client\RequestOptions\Pnr\Element\Address as RequestAddress;
use Amadeus\Client\Struct\Pnr\AddMultiElements\OptionalData;
use Amadeus\Client\Struct\Pnr\AddMultiElements\StructuredAddress;
use Amadeus\Client\Struct\Pnr\AddMultiElements\Address;
use Test\Amadeus\BaseTestCase;

/**
 * StructuredAddressTest
 *
 * @package Test\Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class StructuredAddressTest extends BaseTestCase
{
    public function testCanConstructStructuredBillingAddress()
    {
        $element = new StructuredAddress(
            new RequestAddress([
                'type' => RequestAddress::TYPE_BILLING_STRUCTURED,
                'name' => 'Mister Amadeus',
                'addressLine1' => 'Medialaan 30',
                'addressLine2' => 'no actual line 2',
                'company' => 'Amadeus Benelux NV',
                'city' => 'Vilvoorde',
                'state' => 'Vlaams-Brabant',
                'country' => 'Belgium',
                'zipCode' => '1800'
            ])
        );

        $this->assertEquals(StructuredAddress::TYPE_BILLING_ADDRESS , $element->informationType);
        $this->assertEquals(Address::OPT_ADDRESS_LINE_1, $element->address->optionA1);
        $this->assertEquals('Medialaan 30', $element->address->optionTextA1);
        $this->assertEquals(7, count($element->optionalData));
        $this->assertEquals(OptionalData::OPT_ADDRESS_LINE_2, $element->optionalData[0]->option);
        $this->assertEquals('no actual line 2', $element->optionalData[0]->optionText);
        $this->assertEquals(OptionalData::OPT_CITY, $element->optionalData[1]->option);
        $this->assertEquals('Vilvoorde', $element->optionalData[1]->optionText);
        $this->assertEquals(OptionalData::OPT_COUNTRY, $element->optionalData[2]->option);
        $this->assertEquals('Belgium', $element->optionalData[2]->optionText);
        $this->assertEquals(OptionalData::OPT_NAME, $element->optionalData[3]->option);
        $this->assertEquals('Mister Amadeus', $element->optionalData[3]->optionText);
        $this->assertEquals(OptionalData::OPT_STATE, $element->optionalData[4]->option);
        $this->assertEquals('Vlaams-Brabant', $element->optionalData[4]->optionText);
        $this->assertEquals(OptionalData::OPT_ZIP_CODE, $element->optionalData[5]->option);
        $this->assertEquals('1800', $element->optionalData[5]->optionText);
        $this->assertEquals(OptionalData::OPT_COMPANY, $element->optionalData[6]->option);
        $this->assertEquals('Amadeus Benelux NV', $element->optionalData[6]->optionText);
    }
}
