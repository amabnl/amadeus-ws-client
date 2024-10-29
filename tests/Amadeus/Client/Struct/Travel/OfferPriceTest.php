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

use Amadeus\Client\RequestOptions\Travel as RequestOptions;
use Amadeus\Client\RequestOptions\TravelOfferPriceOptions;
use Amadeus\Client\Struct\Travel\OfferPrice;
use Test\Amadeus\BaseTestCase;

/**
 * OrderCreateTest
 *
 * @package Test\Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class OfferPriceTest extends BaseTestCase
{
    public function testCanMakeOfferPrice()
    {
        $opt = new TravelOfferPriceOptions([
            'dataLists' => [
                new RequestOptions\DataList([
                    'paxList' => new RequestOptions\PaxList([
                        'pax' => [
                            new RequestOptions\Pax([
                                'paxId' => 'T1',
                                'ptc' => 'ADT',
                            ])
                        ],
                    ]),
                ]),
            ],
            'pricedOffer' => new RequestOptions\PricedOffer([
                'selectedOffer' => new RequestOptions\SelectedOffer([
                    'offerRefID' => 'OfferRef1',
                    'ownerCode' => 'AA',
                    'shoppingResponseRefID' => 'ShopRef1',
                    'selectedOfferItems' => [
                        new RequestOptions\SelectedOfferItem([
                            'offerItemRefId' => 'ItemRef1',
                            'paxRefId' => [
                                'T1',
                            ],
                        ]),
                    ],
                ]),
            ]),
        ]);

        $message = new OfferPrice($opt);

        $this->assertEquals('unused', $message->Party->Sender->TravelAgency->AgencyID);
        $this->assertEquals('unused', $message->Party->Sender->TravelAgency->PseudoCityID);

        $this->assertCount(1, $message->Request->DataLists->PaxList->Pax);
        $this->assertEquals('T1', $message->Request->DataLists->PaxList->Pax[0]->PaxID);
        $this->assertEquals('ADT', $message->Request->DataLists->PaxList->Pax[0]->PTC);

        $this->assertEquals('ShopRef1', $message->Request->PricedOffer->SelectedOffer->ShoppingResponseRefID);
        $this->assertEquals('AA', $message->Request->PricedOffer->SelectedOffer->OwnerCode);
        $this->assertCount(1, $message->Request->PricedOffer->SelectedOffer->SelectedOfferItem);
        $this->assertEquals('OfferRef1', $message->Request->PricedOffer->SelectedOffer->OfferRefID);
        $this->assertEquals('ItemRef1', $message->Request->PricedOffer->SelectedOffer->SelectedOfferItem[0]->OfferItemRefID);
        $this->assertEquals(['T1'], $message->Request->PricedOffer->SelectedOffer->SelectedOfferItem[0]->PaxRefID);
    }
}
