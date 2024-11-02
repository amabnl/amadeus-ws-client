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

namespace Test\Amadeus\Client\ResponseHandler\Ticket;

use Amadeus\Client\ResponseHandler\Ticket\HandlerList;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;
use Test\Amadeus\BaseTestCase;

/**
 * HandlerListTest
 *
 * @package Test\Amadeus\Client\ResponseHandler
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class HandlerListTest extends BaseTestCase
{
    public function testCanGetErrorFromList(): void
    {
        $handler = new HandlerList();

        $xml = $this->getTestFile('TicketProcessETicketKnownErrorReply.xml');
        $sendResult = new SendResult();
        $sendResult->responseObject = json_decode(
            json_encode(new \SimpleXMLElement($xml), JSON_THROW_ON_ERROR),
            false,
            512,
            JSON_THROW_ON_ERROR,
        );
        $sendResult->responseXml = $xml;

        $result = $handler->analyze($sendResult);

        self::assertEquals(Result::STATUS_WARN, $result->status);
        self::assertEquals(
            [
                [
                    'code' => '401',
                    'text' => 'Ticket number not found',
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

    public function testNoErrorInListFallback(): void
    {
        $handler = new HandlerList();

        $xml = $this->getTestFile('TicketProcessETicketUnknownErrorReply.xml');
        $sendResult = new SendResult();
        $sendResult->responseObject = json_decode(
            json_encode(new \SimpleXMLElement($xml), JSON_THROW_ON_ERROR),
            false,
            512,
            JSON_THROW_ON_ERROR,
        );
        $sendResult->responseXml = $xml;

        $result = $handler->analyze($sendResult);

        self::assertEquals(Result::STATUS_WARN, $result->status);
        self::assertEquals(
            [
                [
                    'code' => '99999999',
                    'text' => "PROCESS E-TICKET ERROR '99999999' (Error message unavailable)",
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
