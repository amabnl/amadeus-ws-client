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

namespace Amadeus\Client\Struct\Travel;

use Amadeus\Client\RequestOptions\TravelSeatAvailabilityOptions;
use Test\Amadeus\BaseTestCase;

/**
 * SeatAvailabilityTest
 *
 * @package Test\Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class SeatAvailabilityTest extends BaseTestCase
{
    public function testCanMakeSeatAvailabilityStructForOrder(): void
    {
        $opt = new TravelSeatAvailabilityOptions([
            'orderId' => 'AA12345',
            'ownerCode' => 'AA',
        ]);

        $message = new SeatAvailability($opt);

        $this->assertEquals('AA12345', $message->Request->CoreRequest->Order->OrderID);
        $this->assertEquals('AA', $message->Request->CoreRequest->Order->OwnerCode);
    }

    public function testCanMakeSeatAvailabilityStructForOffer(): void
    {
        $opt = new TravelSeatAvailabilityOptions([
            'offerItemId' => '1A_TPID_CAESH-VNQMUYS0x',
            'ownerCode' => 'AA',
            'shoppingResponseId' => 'SP1F-14193187327050054900',
        ]);

        $message = new SeatAvailability($opt);

        $this->assertEquals('AA', $message->Request->CoreRequest->Offer->OwnerCode);
        $this->assertEquals('1A_TPID_CAESH-VNQMUYS0x', $message->Request->CoreRequest->Offer->OfferItemID);
        $this->assertEquals('SP1F-14193187327050054900', $message->Request->ShoppingResponse->ShoppingResponseID);
    }

    public function testInvalidArgumentExceptionThrownWhenWrongArgumentsCombinationPassed(): void
    {
        $opt = new TravelSeatAvailabilityOptions([
            'offerItemId' => '1A_TPID_CAESH-VNQMUYS0x',
            'orderId' => 'AA12345',
            'shoppingResponseId' => 'SP1F-14193187327050054900',
        ]);

        $this->expectException(\InvalidArgumentException::class);

        new SeatAvailability($opt);
    }
}
