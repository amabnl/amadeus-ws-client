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

use Amadeus\Client\RequestOptions\TicketInitRefundOptions;
use Amadeus\Client\Struct\Ticket\InitRefund;
use Test\Amadeus\BaseTestCase;

/**
 * InitRefundTest
 *
 * @package Test\Amadeus\Client\Struct\Ticket
 * @author Mike Hernas <m@hern.as>
 */
class InitRefundTest extends BaseTestCase
{
    /**
     * 5.2 Operation: Initiate ATC Refund
     */
    public function testCanMakeMessageAtcRefund()
    {
        $opt = new TicketInitRefundOptions([
          'ticketNumbers' => ['12313123123', '55555555'],
          'actionDetails' => [
            TicketInitRefundOptions::ACTION_ATC_REFUND
          ]
        ]);

        $msg = new InitRefund($opt);
        $this->assertEquals('2.000', $msg->Version);

        $this->assertCount(2, $msg->Contracts->Contract);
        $this->assertEquals('12313123123', $msg->Contracts->Contract[0]->Number);
        $this->assertEquals('55555555', $msg->Contracts->Contract[1]->Number);
        
        $this->assertCount(1, $msg->ActionDetails->ActionDetail);
        $this->assertEquals('ATC', $msg->ActionDetails->ActionDetail[0]->Indicator);
    }
}
