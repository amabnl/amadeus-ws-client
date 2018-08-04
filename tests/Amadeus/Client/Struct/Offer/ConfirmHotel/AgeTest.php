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

namespace Test\Amadeus\Client\Struct\Offer\ConfirmHotel;

use Amadeus\Client\Struct\Offer\ConfirmHotel\Age;
use Amadeus\Client\Struct\Offer\ConfirmHotel\QuantityDetails;
use Test\Amadeus\BaseTestCase;

/**
 * AgeTest
 *
 * @package Test\Amadeus\Client\Struct\Offer\ConfirmHotel
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AgeTest extends BaseTestCase
{
    public function testCanCreate()
    {
        $age = new Age('25');

        $this->assertEquals('25', $age->quantityDetails->value);
        $this->assertEquals(QuantityDetails::QUAL_AGE, $age->quantityDetails->qualifier);
    }
}
