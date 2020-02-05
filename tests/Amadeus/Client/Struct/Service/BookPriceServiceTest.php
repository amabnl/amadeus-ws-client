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

namespace Test\Amadeus\Client\Struct\Service;

use Amadeus\Client\RequestOptions\ServiceBookPriceServiceOptions;
use Amadeus\Client\RequestOptions\Service\BookPriceService\Identifier;
use Amadeus\Client\RequestOptions\Service\BookPriceService\Service;
use Amadeus\Client\Struct\Service\BookPriceService;
use Test\Amadeus\BaseTestCase;

/**
 * BookPriceServiceTest
 *
 * @package Test\Amadeus\Client\Struct\Service
 * @author Mike Hernas <mike@ahoy.io>
 */
class BookPriceServiceTest extends BaseTestCase
{
    public function testCanMakeMessageRFIC()
    {
        $opt = new ServiceBookPriceServiceOptions([
            'services' => [new Service([
                'TID' => 'R1',
                'serviceProvider' => 'LH',
                'passengerTattoo' => '1',
                'segmentTattoo' => '2',
                'identifier' => new Identifier([
                    'bookingMethod' => '01',
                    'RFIC' => 'F',
                    'RFISC' => '040',
                ])
            ])]
        ]);

        $msg = new BookPriceService($opt);

        $this->assertEquals('01', $msg->Product[0]->Service->identifier->bookingMethod);
        $this->assertEquals('F', $msg->Product[0]->Service->identifier->RFIC);
        $this->assertEquals('040', $msg->Product[0]->Service->identifier->RFISC);
        $this->assertEmpty($msg->Product[0]->Service->identifier->Code);
        $this->assertEquals('LH', $msg->Product[0]->Service->serviceProvider->code);
        $this->assertEquals('R1', $msg->Product[0]->Service->TID);
        $this->assertEquals('1', $msg->Product[0]->Service->customerRefIDs);
        $this->assertEquals('2', $msg->Product[0]->Service->segmentRefIDs);
        $this->assertEquals(0, $msg->Version);
    }
    public function testCanMakeMessageCode()
    {
        $opt = new ServiceBookPriceServiceOptions([
            'services' => [new Service([
                'TID' => 'R1',
                'serviceProvider' => 'LH',
                'identifier' => new Identifier([
                    'bookingMethod' => '02',
                    'code' => 'OXY',
                ])
            ])]
        ]);

        $msg = new BookPriceService($opt);

        $this->assertEquals('02', $msg->Product[0]->Service->identifier->bookingMethod);
        $this->assertEmpty($msg->Product[0]->Service->identifier->RFIC);
        $this->assertEmpty($msg->Product[0]->Service->identifier->RFISC);
        $this->assertEquals('OXY', $msg->Product[0]->Service->identifier->Code);
        $this->assertEquals('LH', $msg->Product[0]->Service->serviceProvider->code);
        $this->assertEquals('R1', $msg->Product[0]->Service->TID);
    }
}
