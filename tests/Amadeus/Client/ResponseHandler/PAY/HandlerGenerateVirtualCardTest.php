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
 * HandlerGenerateVirtualCardTest
 *
 * @package Test\Amadeus\Client\ResponseHandler\PAY
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class HandlerGenerateVirtualCardTest extends BaseTestCase
{
    public function testCanRecognizeErrors(): void
    {
        $handler = new HandlerGenerateVirtualCard();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('PayGenerateVirtualCardErrorRS.xml');

        $result = $handler->analyze($sendResult);

        self::assertEquals(Result::STATUS_ERROR, $result->status);
        self::assertEquals(
            [
                [
                    'code' => '33099',
                    'text' => 'NO VIRTUAL CREDIT CARD AVAILABLE FOR THIS VENDOR',
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
}
