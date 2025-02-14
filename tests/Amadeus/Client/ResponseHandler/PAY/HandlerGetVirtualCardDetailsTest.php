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

namespace Amadeus\Client\ResponseHandler\PAY;

use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;
use Test\Amadeus\BaseTestCase;

/**
 * HandlerGetVirtualCardDetailsTest
 *
 * @package Test\Amadeus\Client\ResponseHandler\PAY
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class HandlerGetVirtualCardDetailsTest extends BaseTestCase
{
    public function testCanRecognizeErrors(): void
    {
        $handler = new HandlerGetVirtualCardDetails();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('PayGetVirtualCardDetailsErrorRS.xml');

        $result = $handler->analyze($sendResult);

        self::assertEquals(Result::STATUS_ERROR, $result->status);
        self::assertEquals(
            [
                [
                    'code' => '34734',
                    'text' => 'VIRTUAL CARD NOT FOUND',
                ],
            ],
            array_map(
                static fn (Result\NotOk $notOk): array => [
                    'code' => $notOk->code,
                    'text' => $notOk->text,
                ],
                $result->messages,
            ),
        );
    }

    public function testFillsCardDetails(): void
    {
        $handler = new HandlerGetVirtualCardDetails();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('PayGetVirtualCardDetailsRS.xml');
        $sendResult->responseObject = json_decode(
            json_encode(new \SimpleXMLElement($sendResult->responseXml), JSON_THROW_ON_ERROR),
            false,
            512,
            JSON_THROW_ON_ERROR,
        );

        $result = $handler->analyze($sendResult);

        self::assertEquals('52532XXXXXX0854', $result->response->Success->VirtualCard->Card->PrimaryAccountNumber);
        self::assertEquals('123', $result->response->Success->VirtualCard->Card->CVV);
        self::assertEquals('Salvador de Madariaga 1', trim($result->response->Success->VirtualCard->Card->AddressVerificationSystemValue->Line));
        self::assertEquals('Madrid', $result->response->Success->VirtualCard->Card->AddressVerificationSystemValue->CityName);
        self::assertEquals('28027', $result->response->Success->VirtualCard->Card->AddressVerificationSystemValue->PostalCode);
        self::assertEquals('SPAIN', $result->response->Success->VirtualCard->Card->AddressVerificationSystemValue->Country);
        self::assertEquals('1', $result->response->Success->VirtualCard->AllowedTransactions);
        self::assertEquals('2025-08-31', $result->response->Success->VirtualCard->ValidityPeriod);
        self::assertEquals(1100, $result->response->Success->VirtualCard->Amount);
        self::assertEquals(2, $result->response->Success->VirtualCard->DecimalPlaces);
        self::assertEquals('EUR', $result->response->Success->VirtualCard->CurrencyCode);
    }
}
