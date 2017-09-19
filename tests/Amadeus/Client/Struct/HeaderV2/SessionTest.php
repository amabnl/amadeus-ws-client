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

namespace Test\Amadeus\Client\Struct\HeaderV2;

use Amadeus\Client\Struct\HeaderV2\Session;
use Test\Amadeus\BaseTestCase;

/**
 * SessionTest
 *
 * @package Test\Amadeus\Client\Struct\HeaderV2
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SessionTest extends BaseTestCase
{
    public function testCanMakeSessionHeaderStructure()
    {
        $header = new Session('34J2KML4J', 3, 'FHMZROEZJHKJDHSKJLDQH');

        $this->assertInstanceOf('\Amadeus\Client\Struct\HeaderV2\Session', $header);
        $this->assertEquals('34J2KML4J', $header->SessionId);
        $this->assertEquals(3, $header->SequenceNumber);
        $this->assertEquals('FHMZROEZJHKJDHSKJLDQH', $header->SecurityToken);
    }
}
