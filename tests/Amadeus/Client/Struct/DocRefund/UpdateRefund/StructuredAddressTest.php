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

namespace Test\Amadeus\Client\Struct\DocRefund\UpdateRefund;

use Amadeus\Client\RequestOptions\DocRefund\AddressOpt;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\Address;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\StructuredAddress;
use Test\Amadeus\BaseTestCase;

/**
 * StructuredAddressTest
 *
 * @package Test\Amadeus\Client\Struct\DocRefund\DocRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class StructuredAddressTest extends BaseTestCase
{
    public function testCanMakeAddressWithPoBoxAndState()
    {
        $opt = new AddressOpt([
            'company' => 'GREAT COMPANY',
            'name' => 'MR SMITH',
            'addressLine1' => '12 LONG STREET',
            'addressLine2' => 'something',
            'postalCode' => 'BS7890',
            'city' => 'LOS ANGELES',
            'country' => 'US',
            'state' => 'CA',
            'poBox' => 2123
        ]);

        $address = new StructuredAddress($opt);

        $this->assertEquals(StructuredAddress::TYPE_BILLING_ADDRESS, $address->informationType);
        $this->assertCount(9, $address->address);
        $this->assertEquals(Address::OPTION_COMPANY, $address->address[0]->option);
        $this->assertEquals('GREAT COMPANY', $address->address[0]->optionText);
        $this->assertEquals(Address::OPTION_NAME, $address->address[1]->option);
        $this->assertEquals('MR SMITH', $address->address[1]->optionText);
        $this->assertEquals(Address::OPTION_ADDRESS_LINE_1, $address->address[2]->option);
        $this->assertEquals('12 LONG STREET', $address->address[2]->optionText);
        $this->assertEquals(Address::OPTION_ADDRESS_LINE_2, $address->address[3]->option);
        $this->assertEquals('something', $address->address[3]->optionText);
        $this->assertEquals(Address::OPTION_CITY, $address->address[4]->option);
        $this->assertEquals('LOS ANGELES', $address->address[4]->optionText);
        $this->assertEquals(Address::OPTION_COUNTRY, $address->address[5]->option);
        $this->assertEquals('US', $address->address[5]->optionText);
        $this->assertEquals(Address::OPTION_PO_BOX, $address->address[6]->option);
        $this->assertEquals(2123, $address->address[6]->optionText);
        $this->assertEquals(Address::OPTION_POSTAL_CODE, $address->address[7]->option);
        $this->assertEquals('BS7890', $address->address[7]->optionText);
        $this->assertEquals(Address::OPTION_STATE, $address->address[8]->option);
        $this->assertEquals('CA', $address->address[8]->optionText);
    }
}
