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

namespace Test\Amadeus\Client\Session\MsgClassmap;

use Amadeus\Client\Session\MsgClassmap\Hotel\LoaderMultiSingleAvailability;
use Amadeus\Client\Session\MsgClassmap\Loader;
use Test\Amadeus\BaseTestCase;

/**
 * LoaderTest
 *
 * @package Test\Amadeus\Client\Session\MsgClassmap
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class LoaderTest extends BaseTestCase
{
    public function testCanLoadHotelMultiSingleClassmap()
    {
        $msgAndVer = [
            'PNR_Retrieve' => '14.2',
            'Hotel_MultiSingleAvailability' => '10.0'
        ];

        $classmap = Loader::loadMessagesSpecificClasses($msgAndVer);

        $expected = LoaderMultiSingleAvailability::loadClassMapForMessage('10.0');

        $this->assertEquals($expected, $classmap);
    }
}
