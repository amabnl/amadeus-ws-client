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

namespace Test\Amadeus\Client\Struct\Pay;

use Amadeus\Client\RequestOptions\Pay\AmountRange;
use Amadeus\Client\RequestOptions\Pay\Period;
use Amadeus\Client\RequestOptions\Pay\Reservation;
use Amadeus\Client\RequestOptions\PayListVirtualCardsOptions;
use Amadeus\Client\Struct\Pay\ListVirtualCards;
use Test\Amadeus\BaseTestCase;

/**
 * ListVirtualCardsTest
 *
 * @package Amadeus\Client\Struct\Pay
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class ListVirtualCardsTest extends BaseTestCase
{
    public function testCanMakeListVirtualCards(): void
    {
        $options = new PayListVirtualCardsOptions([
            'SubType' => PayListVirtualCardsOptions::SUBTYPE_PREPAID,
            'CurrencyCode' => 'EUR',
            'Period' => new Period([
                'start' => new \DateTime('2024-04-01'),
                'end' => new \DateTime('2024-04-18'),
            ]),
            'VendorCode' => 'VI',
            'CardStatus' => PayListVirtualCardsOptions::CARD_STATUS_ACTIVE,
            'AmountRange' => new AmountRange([
                'min' => 100,
                'max' => 200,
            ]),
            'Reservation' => new Reservation([
                'id' => 'ABC123',
                'externalId' => 'EXTERNALREF',
            ]),
        ]);

        $struct = new ListVirtualCards($options);

        self::assertEquals('Prepaid', $struct->SubType);
        self::assertEquals('EUR', $struct->CurrencyCode);
        self::assertEquals(
            '<ns1:Period Start="2024-04-01" End="2024-04-18" EventType="Creation" ></ns1:Period>',
            $struct->Period->enc_value,
        );
        self::assertEquals('VI', $struct->VendorCode);
        self::assertEquals('Active', $struct->CardStatus);
        self::assertEquals(100, $struct->AmountRange->Min);
        self::assertEquals(200, $struct->AmountRange->Max);
        self::assertEquals('ABC123', $struct->Reservation->ID);
        self::assertEquals('EXTERNALREF', $struct->Reservation->ExternalID);
    }
}
