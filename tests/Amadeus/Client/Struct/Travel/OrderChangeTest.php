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

use Amadeus\Client\RequestOptions\Travel\DataList;
use Amadeus\Client\RequestOptions\Travel\OrderChange\AcceptChange;
use Amadeus\Client\RequestOptions\Travel\OrderChange\UpdateOrderItem;
use Amadeus\Client\RequestOptions\Travel\Pax;
use Amadeus\Client\RequestOptions\Travel\PaxList;
use Amadeus\Client\RequestOptions\Travel\SelectedAlaCarteOfferItem;
use Amadeus\Client\RequestOptions\Travel\SelectedOffer;
use Amadeus\Client\RequestOptions\Travel\SelectedOfferItem;
use Amadeus\Client\RequestOptions\Travel\SelectedSeat;
use Amadeus\Client\RequestOptions\TravelOrderChangeOptions;
use Amadeus\Client\Struct\Travel\OrderChange;
use Test\Amadeus\BaseTestCase;

/**
 * OrderChangeTest
 *
 * @package Test\Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class OrderChangeTest extends BaseTestCase
{
    public function testCanMakeOrderChange(): void
    {
        $opt = new TravelOrderChangeOptions([
            'orderId' => 'AA12345',
            'ownerCode' => 'AA',
            'acceptChange' => new AcceptChange([
                'orderItemRefIds' => ['1'],
            ]),
            'updateOrderItem' => new UpdateOrderItem([
                'offer' => new SelectedOffer([
                    'offerRefID' => 'ShoppingOfferRef1',
                    'ownerCode' => 'AA',
                    'shoppingResponseRefID' => 'ShoppingRef1',
                    'selectedOfferItems' => [
                        new SelectedOfferItem([
                            'offerItemRefId' => 'ShoppingOfferItemRef1',
                            'paxRefId' => ['1'],
                            'selectedAlaCarteOfferItem' => new SelectedAlaCarteOfferItem(['quantity' => 1]),
                            'selectedSeat' => new SelectedSeat([
                                'column' => 'A',
                                'rowNumber' => 19,
                            ]),
                        ]),
                    ],
                ]),
            ]),
            'dataLists' => [
                new DataList([
                    'paxList' => new PaxList([
                        'pax' => [
                            new Pax([
                                'paxId' => '1',
                                'ptc' => 'ADT',
                                'dob' => \DateTime::createFromFormat('Y-m-d', '1990-01-01'),
                                'gender' => 'M',
                                'firstName' => 'John',
                                'lastName' => 'Doe',
                            ]),
                        ],
                    ]),
                ]),
            ],
        ]);

        $message = new OrderChange($opt);

        $this->assertEquals('AA12345', $message->Request->Order->OrderID);
        $this->assertEquals('AA', $message->Request->Order->OwnerCode);

//        $this->assertEquals(['1'], $message->Request->ChangeOrder->AcceptChange->OrderItemRefID);
    }
}
