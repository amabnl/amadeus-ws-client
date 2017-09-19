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

namespace Test\Amadeus\Client\Struct\Fop;

use Amadeus\Client\RequestOptions\Fop\FraudScreeningAddress;
use Amadeus\Client\RequestOptions\Fop\FraudScreeningOptions;
use Amadeus\Client\Struct\Fop\AddressDetails;
use Amadeus\Client\Struct\Fop\FraudScreeningData;
use Test\Amadeus\BaseTestCase;

/**
 * FraudScreeningDataTest
 *
 * @package Test\Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FraudScreeningDataTest extends BaseTestCase
{
    public function testCanCreateWithBillingAddress()
    {
        $fraudscreen = new FraudScreeningData(
            new FraudScreeningOptions([
                'billingAddress' => new FraudScreeningAddress([
                    'addressLines' => [
                        'Medialaan 30',
                        'Dummy line'
                    ],
                    'city' => 'VILVOORDE',
                    'zipCode' => '1800',
                    'countryCode' => 'BE'
                ])
            ])
        );

        $this->assertEquals(AddressDetails::FORMAT_UNSTRUCTURED, $fraudscreen->billingAddress->addressDetails->format);
        $this->assertEquals('Medialaan 30', $fraudscreen->billingAddress->addressDetails->line1);
        $this->assertEquals('Dummy line', $fraudscreen->billingAddress->addressDetails->line2);
        $this->assertNull($fraudscreen->billingAddress->addressDetails->line3);
        $this->assertNull($fraudscreen->billingAddress->addressDetails->line4);
        $this->assertNull($fraudscreen->billingAddress->addressDetails->line5);
        $this->assertNull($fraudscreen->billingAddress->addressDetails->line6);
        $this->assertEquals('1800', $fraudscreen->billingAddress->zipCode);
        $this->assertEquals('VILVOORDE', $fraudscreen->billingAddress->city);
        $this->assertEquals('BE', $fraudscreen->billingAddress->countryCode);
    }
}
