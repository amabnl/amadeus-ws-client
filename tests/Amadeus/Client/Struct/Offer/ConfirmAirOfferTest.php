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

namespace Amadeus\Client\Struct\Offer;
use Amadeus\Client\RequestOptions\Offer\PassengerDef;
use Amadeus\Client\RequestOptions\Offer\PassengerReAssocOptions;
use Amadeus\Client\RequestOptions\OfferConfirmAirOptions;
use Test\Amadeus\BaseTestCase;

/**
 * ConfirmAirOfferTest
 *
 * @package Amadeus\Client\Struct\Offer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ConfirmAirOfferTest extends BaseTestCase
{
    public function testCanMakeMessageWithPassengerReassociation()
    {
        $opt = new OfferConfirmAirOptions([
            'tattooNumber' => 2,
            'passengerReassociation' => [
                new PassengerReAssocOptions([
                    'pricingReference' => 5,
                    'paxReferences' => [
                        new PassengerDef([
                            'passengerType' => "PA", //ADULT
                            'passengerTattoo' => 1
                        ])
                    ]
                ])
            ]
        ]);

        $message = new ConfirmAir($opt);

        $this->assertEquals(2, $message->offerTatoo->reference->value);
        $this->assertEquals(Reference::TYPE_OFFER_TATTOO, $message->offerTatoo->reference->type);
        $this->assertEquals(OfferTattoo::SEGMENT_AIR, $message->offerTatoo->segmentName);
        $this->assertEquals('PA', $message->passengerReassociation[0]->paxReference->passengerReference[0]->type);
        $this->assertEquals(1, $message->passengerReassociation[0]->paxReference->passengerReference[0]->value);
        $this->assertEquals(PricingRecordId::REFTYPE_PRODQUOTREC, $message->passengerReassociation[0]->pricingRecordId->referenceType);
        $this->assertEquals(5, $message->passengerReassociation[0]->pricingRecordId->uniqueReference);
    }
}
