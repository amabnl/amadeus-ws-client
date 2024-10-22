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

use Amadeus\Client\RequestOptions\TravelServiceListOptions;
use Amadeus\Client\Struct\Travel\ServiceList;
use Test\Amadeus\BaseTestCase;

/**
 * ServiceListTest
 *
 * @package Test\Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class ServiceListTest extends BaseTestCase
{
    public function testCanMakeServiceListStructBasedOnOrder(): void
    {
        $opt = new TravelServiceListOptions([
            'orderId' => 'AA12345',
            'ownerCode' => 'AA',
        ]);

        $message = new ServiceList($opt);

        $this->assertEquals('AA12345', $message->Request->CoreRequest->Order->OrderID);
        $this->assertEquals('AA', $message->Request->CoreRequest->Order->OwnerCode);
    }

    public function testCanMakeServiceListStructBasedOnOffer(): void
    {
        $opt = new TravelServiceListOptions([
            'ownerCode' => 'AA',
            'offerId' => '1A_TPID_CiESG1NQMUYtMTQxOAI=',
            'offerItemId' => '1A_TPID_CAESH-VNQMUYS0x',
            'shoppingResponseId' => 'SP1F-14193187327050054900',
            'serviceId' => 1,
        ]);

        $message = new ServiceList($opt);

        $this->assertEquals('SP1F-14193187327050054900', $message->Request->ShoppingResponse->ShoppingResponseID);
        $this->assertEquals('AA', $message->Request->CoreRequest->Offer->OwnerCode);
        $this->assertEquals('1A_TPID_CiESG1NQMUYtMTQxOAI=', $message->Request->CoreRequest->Offer->OfferID);
        $this->assertEquals('AA', $message->Request->CoreRequest->Offer->OfferItem->OwnerCode);
        $this->assertEquals('1A_TPID_CAESH-VNQMUYS0x', $message->Request->CoreRequest->Offer->OfferItem->OfferItemID);
        $this->assertEquals(1, $message->Request->CoreRequest->Offer->OfferItem->Service->ServiceID);
    }

    public function testInvalidArgumentExceptionThrownWhenWrongArgumentsCombinationPassed(): void
    {
        $opt = new TravelServiceListOptions([
            'offerItemId' => '1A_TPID_CAESH-VNQMUYS0x',
            'orderId' => 'AA12345',
            'shoppingResponseId' => 'SP1F-14193187327050054900',
        ]);

        $this->expectException(\InvalidArgumentException::class);

        new ServiceList($opt);
    }
}
