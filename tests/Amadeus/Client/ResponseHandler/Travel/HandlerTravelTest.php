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

namespace Test\Amadeus\Client\ResponseHandler\Travel;

use Amadeus\Client\ResponseHandler\Travel\HandlerTravel;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;
use Test\Amadeus\BaseTestCase;

/**
 * HandlerTravelTest
 *
 * @package Test\Amadeus\Client\ResponseHandler
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class HandlerTravelTest extends BaseTestCase
{
    public function testCanGetMultipleErrorsFromReply(): void
    {
        $handler = new HandlerTravel();

        $xml = $this->getTestFile('TravelOrderCreateErrorReply.xml');
        $sendResult = new SendResult();
        $sendResult->responseObject = json_decode(
            json_encode(new \SimpleXMLElement($xml), JSON_THROW_ON_ERROR),
            false,
            512,
            JSON_THROW_ON_ERROR,
        );
        $sendResult->responseXml = $xml;

        $result = $handler->analyze($sendResult);

        self::assertEquals(Result::STATUS_ERROR, $result->status);
        self::assertEquals(
            [
                [
                    'code' => '38629',
                    'text' => 'Unable to proceed - The flight is not available and waitlist is closed',
                ],
                [
                    'code' => '',
                    'text' => 'Unable to confirm Flight AA442 2024-12-08 JFKDFW L - Invalid Place of Destination Code',
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

    public function testCanGetSingleErrorFromReply(): void
    {
        $handler = new HandlerTravel();

        $xml = $this->getTestFile('TravelOfferPriceErrorReply.xml');
        $sendResult = new SendResult();
        $sendResult->responseObject = json_decode(
            json_encode(new \SimpleXMLElement($xml), JSON_THROW_ON_ERROR),
            false,
            512,
            JSON_THROW_ON_ERROR,
        );
        $sendResult->responseXml = $xml;

        $result = $handler->analyze($sendResult);

        self::assertEquals(Result::STATUS_ERROR, $result->status);
        self::assertEquals(
            [
                [
                    'code' => '38658',
                    'text' => 'Technical Error - Please contact your helpdesk',
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

    public function testCanGetSingleErrorInErrorsNodeFromReply(): void
    {
        $handler = new HandlerTravel();

        $xml = $this->getTestFile('TravelOfferPriceError2Reply.xml');
        $sendResult = new SendResult();
        $sendResult->responseObject = json_decode(
            json_encode(new \SimpleXMLElement($xml), JSON_THROW_ON_ERROR),
            false,
            512,
            JSON_THROW_ON_ERROR,
        );
        $sendResult->responseXml = $xml;

        $result = $handler->analyze($sendResult);

        self::assertEquals(Result::STATUS_ERROR, $result->status);
        self::assertEquals(
            [
                [
                    'code' => '38658',
                    'text' => 'Technical Error - Please contact your helpdesk',
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


    public function testCanGetMultpleErrorsInErrorNodeFromReply(): void
    {
        $handler = new HandlerTravel();

        $xml = $this->getTestFile('TravelOfferPriceError3Reply.xml');
        $sendResult = new SendResult();
        $sendResult->responseObject = json_decode(
            json_encode(new \SimpleXMLElement($xml), JSON_THROW_ON_ERROR),
            false,
            512,
            JSON_THROW_ON_ERROR,
        );
        $sendResult->responseXml = $xml;

        $result = $handler->analyze($sendResult);

        self::assertEquals(Result::STATUS_ERROR, $result->status);
        self::assertEquals(
            [
                [
                    'code' => '38658',
                    'text' => 'Technical Error - Please contact your helpdesk',
                ],
                [
                    'code' => '99999',
                    'text' => 'External Error',
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

    public function testCanGetErrorWithWarningFromReply(): void
    {
        $handler = new HandlerTravel();

        $xml = $this->getTestFile('TravelOrderRetrieveErrorWithWarningsReply.xml');
        $sendResult = new SendResult();
        $sendResult->responseObject = json_decode(
            json_encode(new \SimpleXMLElement($xml), JSON_THROW_ON_ERROR),
            false,
            512,
            JSON_THROW_ON_ERROR,
        );
        $sendResult->responseXml = $xml;

        $result = $handler->analyze($sendResult);

        self::assertEquals(Result::STATUS_ERROR, $result->status);
        self::assertEquals(
            [
                [
                    'code' => '',
                    'text' => 'No matching NDC order ID found',
                ],
                [
                    'code' => '39004',
                    'text' => 'Invalid OrderID',
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
