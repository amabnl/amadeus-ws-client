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

namespace Test\Amadeus\Client\Struct\DocRefund;

use Amadeus\Client\RequestOptions\DocRefundInitRefundOptions;
use Amadeus\Client\Struct\DocRefund\InitRefund;
use Amadeus\Client\Struct\DocRefund\ItemNumberDetails;
use Amadeus\Client\Struct\DocRefund\StatusDetails;
use Test\Amadeus\BaseTestCase;

/**
 * InitRefundTest
 *
 * @package Test\Amadeus\Client\Struct\DocRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class InitRefundTest extends BaseTestCase
{
    /**
     * 5.1 Operation: ATC refund on a ticket paid with certificates
     */
    public function testCanMakeMessageAtcRefund()
    {
        $opt = new DocRefundInitRefundOptions([
            'ticketNumber' => '5272404450587',
            'actionCodes' => [
                DocRefundInitRefundOptions::ACTION_ATC_REFUND
            ]
        ]);

        $msg = new InitRefund($opt);

        $this->assertEquals('5272404450587', $msg->ticketNumberGroup->documentNumberDetails->documentDetails->number);
        $this->assertNull($msg->ticketNumberGroup->documentNumberDetails->status);
        $this->assertEmpty($msg->ticketNumberGroup->paperCouponOfDocNumber);

        $this->assertEquals(StatusDetails::INDICATOR_ATC_REFUND, $msg->actionDetails->statusDetails->indicator);
        $this->assertEmpty($msg->actionDetails->otherDetails);

        $this->assertNull($msg->itemNumberGroup);
        $this->assertNull($msg->currencyOverride);
        $this->assertNull($msg->stockProviderDetails);
        $this->assertNull($msg->targetOfficeDetails);
        $this->assertNull($msg->transactionContext);
    }
    
    /**
     * 5.1 Operation: ATC refund on a ticket paid with certificates
     */
    public function testCanMakeMessageAtcRefundWithStockProvider()
    {
        $opt = new DocRefundInitRefundOptions([
            'ticketNumber' => '5272404450587',
            'actionCodes' => [
                DocRefundInitRefundOptions::ACTION_ATC_REFUND
            ],
            'stockProvider' => 'AZ9',
        ]);
        
        $msg = new InitRefund($opt);
        
        $this->assertEquals('5272404450587', $msg->ticketNumberGroup->documentNumberDetails->documentDetails->number);
        $this->assertNull($msg->ticketNumberGroup->documentNumberDetails->status);
        $this->assertEmpty($msg->ticketNumberGroup->paperCouponOfDocNumber);
        
        $this->assertEquals(StatusDetails::INDICATOR_ATC_REFUND, $msg->actionDetails->statusDetails->indicator);
        $this->assertEmpty($msg->actionDetails->otherDetails);
        
        $this->assertNull($msg->itemNumberGroup);
        $this->assertNull($msg->currencyOverride);
        
        $this->assertEquals('AZ9', $msg->stockProviderDetails->stockProvider);
        $this->assertEmpty($msg->stockProviderDetails->stockTypeCode);
        
        $this->assertNull($msg->targetOfficeDetails);
        $this->assertNull($msg->transactionContext);
    }
    
    /**
     * 5.1 Operation: ATC refund on a ticket paid with certificates
     */
    public function testCanMakeMessageAtcRefundWithStockTypeCode()
    {
        $opt = new DocRefundInitRefundOptions([
            'ticketNumber' => '5272404450587',
            'actionCodes' => [
                DocRefundInitRefundOptions::ACTION_ATC_REFUND
            ],
            'stockTypeCode' => 'A',
        ]);
        
        $msg = new InitRefund($opt);
        
        $this->assertEquals('5272404450587', $msg->ticketNumberGroup->documentNumberDetails->documentDetails->number);
        $this->assertNull($msg->ticketNumberGroup->documentNumberDetails->status);
        $this->assertEmpty($msg->ticketNumberGroup->paperCouponOfDocNumber);
        
        $this->assertEquals(StatusDetails::INDICATOR_ATC_REFUND, $msg->actionDetails->statusDetails->indicator);
        $this->assertEmpty($msg->actionDetails->otherDetails);
        
        $this->assertNull($msg->itemNumberGroup);
        $this->assertNull($msg->currencyOverride);
    
        $this->assertEquals('A', $msg->stockProviderDetails->stockTypeCode);
        $this->assertEmpty($msg->stockProviderDetails->stockProvider);
    
        $this->assertNull($msg->targetOfficeDetails);
        $this->assertNull($msg->transactionContext);
    }

    /**
     * 5.1 Operation: ATC refund on a ticket paid with certificates
     *
     * This time only one string as action code instead of an array
     */
    public function testCanMakeMessageAtcRefundSingleActionCode()
    {
        $opt = new DocRefundInitRefundOptions([
            'ticketNumber' => '5272404450587',
            'actionCodes' => DocRefundInitRefundOptions::ACTION_ATC_REFUND
        ]);

        $msg = new InitRefund($opt);

        $this->assertEquals('5272404450587', $msg->ticketNumberGroup->documentNumberDetails->documentDetails->number);
        $this->assertNull($msg->ticketNumberGroup->documentNumberDetails->status);
        $this->assertEmpty($msg->ticketNumberGroup->paperCouponOfDocNumber);

        $this->assertEquals(StatusDetails::INDICATOR_ATC_REFUND, $msg->actionDetails->statusDetails->indicator);
        $this->assertEmpty($msg->actionDetails->otherDetails);

        $this->assertNull($msg->itemNumberGroup);
        $this->assertNull($msg->currencyOverride);
        $this->assertNull($msg->stockProviderDetails);
        $this->assertNull($msg->targetOfficeDetails);
        $this->assertNull($msg->transactionContext);
    }

    /**
     * 5.3 Operation: ATC refund with hold-for-future-use option
     */
    public function testCanMakeMessageAtcRefundWithHoldForFutureUse()
    {
        $opt = new DocRefundInitRefundOptions([
            'ticketNumber' => '5272404450587',
            'actionCodes' => [
                DocRefundInitRefundOptions::ACTION_ATC_REFUND,
                DocRefundInitRefundOptions::ACTION_HOLD_FOR_FUTURE_USE
            ]
        ]);

        $msg = new InitRefund($opt);

        $this->assertEquals('5272404450587', $msg->ticketNumberGroup->documentNumberDetails->documentDetails->number);
        $this->assertNull($msg->ticketNumberGroup->documentNumberDetails->status);
        $this->assertEmpty($msg->ticketNumberGroup->paperCouponOfDocNumber);

        $this->assertEquals(StatusDetails::INDICATOR_ATC_REFUND, $msg->actionDetails->statusDetails->indicator);
        $this->assertCount(1, $msg->actionDetails->otherDetails);
        $this->assertEquals(StatusDetails::INDICATOR_HOLD_FOR_FUTURE_USE, $msg->actionDetails->otherDetails[0]->indicator);

        $this->assertNull($msg->itemNumberGroup);
        $this->assertNull($msg->currencyOverride);
        $this->assertNull($msg->stockProviderDetails);
        $this->assertNull($msg->targetOfficeDetails);
        $this->assertNull($msg->transactionContext);
    }

    /**
     * 5.10 Operation: Redisplay an already processed refund
     */
    public function testCanMakeMessageRedisplayProcessedRefund()
    {
        $opt = new DocRefundInitRefundOptions([
            'itemNumber' => 2
        ]);

        $msg = new InitRefund($opt);

        $this->assertEquals(2, $msg->itemNumberGroup->sequenceNumber->itemNumberDetails->number);
        $this->assertNull($msg->itemNumberGroup->sequenceNumber->itemNumberDetails->type);
        $this->assertNull($msg->itemNumberGroup->paperCouponOfItemNumber);

        $this->assertNull($msg->ticketNumberGroup);
        $this->assertNull($msg->actionDetails);
        $this->assertNull($msg->currencyOverride);
        $this->assertNull($msg->stockProviderDetails);
        $this->assertNull($msg->targetOfficeDetails);
        $this->assertNull($msg->transactionContext);
    }

    /**
     * 5.11 Operation: Refund with item number and coupon number
     *
     * This operation initiates an ATC refund with coupon selection.
     */
    public function testCanMakeMessageRefundWithItemAndCouponNumber()
    {
        $opt = new DocRefundInitRefundOptions([
            'itemNumber' => '022431',
            'itemNumberType' => DocRefundInitRefundOptions::TYPE_FROM_NUMBER,
            'couponNumber' => 1
        ]);

        $msg = new InitRefund($opt);

        $this->assertEquals('022431', $msg->itemNumberGroup->sequenceNumber->itemNumberDetails->number);
        $this->assertEquals(ItemNumberDetails::TYPE_FROM_NUMBER, $msg->itemNumberGroup->sequenceNumber->itemNumberDetails->type);
        $this->assertEquals(1, $msg->itemNumberGroup->paperCouponOfItemNumber->couponDetails->cpnNumber);

        $this->assertNull($msg->ticketNumberGroup);
        $this->assertNull($msg->actionDetails);
        $this->assertNull($msg->currencyOverride);
        $this->assertNull($msg->stockProviderDetails);
        $this->assertNull($msg->targetOfficeDetails);
        $this->assertNull($msg->transactionContext);
    }
}
