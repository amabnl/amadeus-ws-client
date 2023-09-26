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

namespace Test\Amadeus\Client\Struct\Travel;

use Amadeus\Client\RequestOptions\TravelOrderPayOptions;
use Amadeus\Client\Struct\Travel\OrderPay;
use Test\Amadeus\BaseTestCase;

/**
 * OrderPayTest
 *
 * @package Test\Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class OrderPayTest extends BaseTestCase
{
    public function testCanMakeOrderPay()
    {
        $opt = new TravelOrderPayOptions([
            'orderId' => 'AA12345',
            'ownerCode' => 'AA',
            'amount' => 249.45,
            'currencyCode' => 'USD',
            'type' => TravelOrderPayOptions::PAYMENT_TYPE_CASH,
        ]);

        $message = new OrderPay($opt);

        $this->assertEquals('unused', $message->Party->Sender->TravelAgency->AgencyID);
        $this->assertEquals('unused', $message->Party->Sender->TravelAgency->PseudoCityID);

        $this->assertTrue($message->Request->PaymentInfo->PaymentMethod->Cash->CashInd);
        $this->assertEquals(249.45, $message->Request->PaymentInfo->Amount->_);
        $this->assertEquals('USD', $message->Request->PaymentInfo->Amount->CurCode);
        $this->assertEquals('CA', $message->Request->PaymentInfo->TypeCode);
        $this->assertEquals('AA12345', $message->Request->Order->OrderID);
        $this->assertEquals('AA', $message->Request->Order->OwnerCode);
    }
}
