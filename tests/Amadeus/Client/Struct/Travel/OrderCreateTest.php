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
use Amadeus\Client\RequestOptions\TravelOrderCreateOptions;
use Amadeus\Client\Struct\Travel\OrderCreate;
use Test\Amadeus\BaseTestCase;

/**
 * OrderCreateTest
 *
 * @package Test\Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class OrderCreateTest extends BaseTestCase
{
    public function testCanMakeOrderCreate()
    {
        $opt = new TravelOrderCreateOptions([
            'dataLists' => [
                new RequestOptions\DataList([
                    'paxList' => new RequestOptions\PaxList([
                        'pax' => [
                          new RequestOptions\Pax([
                              'paxId' => 'T1',
                              'ptc' => 'ADT',
                              'dob' => new \DateTime('1990-01-01'),
                              'genderCode' => 'M',
                              'firstName' => 'John',
                              'lastName' => 'Doe',
                              'phoneNumber' => '5552225555',
                              'email' => 'example@test.com',
                              'passengerContactRefused' => true,
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

        $message = new OrderCreate($opt);

        $this->assertEquals('unused', $message->Party->Sender->TravelAgency->AgencyID);
        $this->assertEquals('unused', $message->Party->Sender->TravelAgency->PseudoCityID);

        $this->assertCount(1, $message->Request->DataLists->PaxList->Pax);
        $this->assertEquals('T1', $message->Request->DataLists->PaxList->Pax[0]->PaxID);
        $this->assertEquals('ADT', $message->Request->DataLists->PaxList->Pax[0]->PTC);
        $this->assertEquals('1990-01-01', $message->Request->DataLists->PaxList->Pax[0]->Birthdate);
        $this->assertEquals('M', $message->Request->DataLists->PaxList->Pax[0]->GenderCode);
        $this->assertEquals('M', $message->Request->DataLists->PaxList->Pax[0]->Individual->GenderCode);
        $this->assertEquals('John', $message->Request->DataLists->PaxList->Pax[0]->Individual->GivenName);
        $this->assertEquals('Doe', $message->Request->DataLists->PaxList->Pax[0]->Individual->Surname);
        $this->assertEquals('5552225555', $message->Request->DataLists->PaxList->Pax[0]->ContactInfo->Phone->PhoneNumber);
        $this->assertEquals('example@test.com', $message->Request->DataLists->PaxList->Pax[0]->ContactInfo->EmailAddress->EmailAddressText);
        $this->assertEquals('HOME', $message->Request->DataLists->PaxList->Pax[0]->ContactInfo->EmailAddress->LabelText);
        $this->assertTrue($message->Request->DataLists->PaxList->Pax[0]->ContactInfo->ContactRefusedInd);

        $this->assertEquals('ShopRef1', $message->Request->CreateOrder->SelectedOffer->ShoppingResponseRefID);
        $this->assertEquals('AA', $message->Request->CreateOrder->SelectedOffer->OwnerCode);
        $this->assertCount(1, $message->Request->CreateOrder->SelectedOffer->SelectedOfferItem);
        $this->assertEquals('OfferRef1', $message->Request->CreateOrder->SelectedOffer->OfferID);
        $this->assertEquals('ItemRef1', $message->Request->CreateOrder->SelectedOffer->SelectedOfferItem[0]->OfferItemID);
        $this->assertEquals(['T1'], $message->Request->CreateOrder->SelectedOffer->SelectedOfferItem[0]->PaxRefID);
    }
}
