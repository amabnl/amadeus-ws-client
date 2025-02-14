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

namespace Amadeus\Client\Struct\Pay;

use Amadeus\Client\RequestOptions\PayGenerateVirtualCardOptions;
use Amadeus\Client\Struct\InvalidArgumentException;
use Test\Amadeus\BaseTestCase;

/**
 * GenerateVirtualCardDetailsTest
 *
 * @package Amadeus\Client\Struct\Pay
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class GenerateVirtualCardTest extends BaseTestCase
{
    public function testCanMakePayGenerateVirtualCardOptions(): void
    {
        $options = new PayGenerateVirtualCardOptions([
            'CardName' => 'Card_Friendly_Name_UNIQ',
            'VendorCode' => PayGenerateVirtualCardOptions::VENDOR_VISA,
            'ReturnCVV' => true,
            'SubType' => PayGenerateVirtualCardOptions::SUBTYPE_DEBIT,
            'Amount' => 1,
            'DecimalPlaces' => 0,
            'CurrencyCode' => 'EUR',
            'maxAllowedTransactions' => 1,
            'endValidityPeriod' => '2025-03-01',
        ]);

        $struct = new GenerateVirtualCard($options);

        self::assertEquals('Card_Friendly_Name_UNIQ', $struct->VirtualCard->CardName);
        self::assertEquals('DEBIT', $struct->VirtualCard->SubType);
        self::assertEquals('EUR', $struct->VirtualCard->CurrencyCode);
        self::assertEquals('VI', $struct->VirtualCard->VendorCode);
        self::assertTrue($struct->VirtualCard->ReturnCVV);
        self::assertEquals(1, $struct->VirtualCard->Amount);
        self::assertEquals(0, $struct->VirtualCard->DecimalPlaces);
        self::assertEquals(1, $struct->VirtualCard->Limitations->AllowedTransactions->Maximum);
        self::assertEquals('2025-03-01', $struct->VirtualCard->Limitations->ValidityPeriod->EndDate);
    }

    public function testVirtualCardMaxLength35(): void
    {
        $options = new PayGenerateVirtualCardOptions([
            'CardName' => str_repeat('a', 36),
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Max card name length is 35 characters');

        new GenerateVirtualCard($options);
    }

    public function testVirtualCardAmountIsRequired(): void
    {
        $options = new PayGenerateVirtualCardOptions([
            'CardName' => 'Card_Friendly_Name_UNIQ',
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Amount is required');

        new GenerateVirtualCard($options);
    }

    public function testVirtualCardCurrencyCodeIsRequired(): void
    {
        $options = new PayGenerateVirtualCardOptions([
            'CardName' => 'Card_Friendly_Name_UNIQ',
            'Amount' => 1,
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Currency code is required');

        new GenerateVirtualCard($options);
    }
}
