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

namespace Test\Amadeus\Client\Struct\Ticket;

use Amadeus\Client\RequestOptions\TicketUpdateRefundOptions;
use Amadeus\Client\Struct\Ticket\UpdateRefund;
use Test\Amadeus\BaseTestCase;

/**
 * UpdateRefundTest
 *
 * @package Test\Amadeus\Client\Struct\Ticket
 * @author Vladimir Kikot <shoxyoyo@gmail.com>
 */
class UpdateRefundTest extends BaseTestCase
{
    public function testCanMakeMessageUpdateRefund()
    {
        $opt = new TicketUpdateRefundOptions([
            'contractBundleId' => 1,
            'waiverCode' => 'TESTWAIVER11',
        ]);

        $msg = new UpdateRefund($opt);
        $this->assertEquals('3.000', $msg->Version);

        $this->assertEquals('TESTWAIVER11', $msg->ContractBundle->WaiverCode->Code);
        $this->assertEquals(1, $msg->ContractBundle->ID);
    }
}
