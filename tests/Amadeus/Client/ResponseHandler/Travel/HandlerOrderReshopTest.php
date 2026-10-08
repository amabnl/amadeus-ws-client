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

use Amadeus\Client\ResponseHandler\Travel\HandlerOrderReshop;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;
use Test\Amadeus\BaseTestCase;

/**
 * HandlerOrderReshopTest
 *
 * @package Test\Amadeus\Client\ResponseHandler\Travel
 */
class HandlerOrderReshopTest extends BaseTestCase
{
    public function testCanHandleSuccessResponseAndUnwrapResponseNode(): void
    {
        $handler = new HandlerOrderReshop();

        $result = $handler->analyze($this->makeSendResultFromXml('TravelOrderReshopReply.xml'));

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEmpty($result->messages);
        // The <Response> wrapper is unwrapped, so the offer is directly reachable.
        $this->assertEquals('OFFER-1', $result->response->ReshopOffers->Offer->OfferID);
    }

    public function testCanDetectFlatNdcError(): void
    {
        $handler = new HandlerOrderReshop();

        $result = $handler->analyze($this->makeSendResultFromXml('TravelOrderReshopFlatErrorReply.xml'));

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(
            [['code' => '368', 'text' => 'NO CANCELLATION SOLUTION FOUND']],
            $this->flattenMessages($result)
        );
    }

    public function testCanDetectSingleRootErrorNode(): void
    {
        $handler = new HandlerOrderReshop();

        $responseObject = (object) [
            'Error' => (object) ['Code' => '39004', 'DescText' => 'Invalid OrderID'],
        ];

        $result = $handler->analyze($this->makeSendResultFromObject($responseObject));

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(
            [['code' => '39004', 'text' => 'Invalid OrderID']],
            $this->flattenMessages($result)
        );
    }

    public function testCanDetectMultipleRootErrorNodes(): void
    {
        $handler = new HandlerOrderReshop();

        $responseObject = (object) [
            'Error' => [
                (object) ['Code' => '1', 'DescText' => 'A'],
                (object) ['Code' => '2', 'DescText' => 'B'],
            ],
        ];

        $result = $handler->analyze($this->makeSendResultFromObject($responseObject));

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(
            [
                ['code' => '1', 'text' => 'A'],
                ['code' => '2', 'text' => 'B'],
            ],
            $this->flattenMessages($result)
        );
    }

    public function testCanDetectWrappedErrorsNestedUnderResponseAndUnwrap(): void
    {
        $handler = new HandlerOrderReshop();

        $response = (object) [
            'Errors' => (object) [
                'Error' => [
                    (object) ['Code' => '38658', 'DescText' => 'Technical Error'],
                    (object) ['Code' => '99999', 'DescText' => 'External Error'],
                ],
            ],
            'Order' => (object) ['OrderID' => 'ORD1'],
        ];
        $responseObject = (object) ['Response' => $response];

        $result = $handler->analyze($this->makeSendResultFromObject($responseObject));

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(
            [
                ['code' => '38658', 'text' => 'Technical Error'],
                ['code' => '99999', 'text' => 'External Error'],
            ],
            $this->flattenMessages($result)
        );
        // Response node unwrapped.
        $this->assertEquals('ORD1', $result->response->Order->OrderID);
    }

    public function testReturnsEmptyStringForNonScalarErrorProperties(): void
    {
        $handler = new HandlerOrderReshop();

        $responseObject = (object) [
            'Error' => (object) [
                'Code' => (object) ['Nested' => 'x'],
                'DescText' => 'Weird error',
            ],
        ];

        $result = $handler->analyze($this->makeSendResultFromObject($responseObject));

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(
            [['code' => '', 'text' => 'Weird error']],
            $this->flattenMessages($result)
        );
    }

    public function testIgnoresNonObjectErrorAndErrorsEntries(): void
    {
        $handler = new HandlerOrderReshop();

        $responseObject = (object) [
            'Error' => 'plaintext',
            'Errors' => 'plaintext',
        ];

        $result = $handler->analyze($this->makeSendResultFromObject($responseObject));

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEmpty($result->messages);
    }

    public function testIgnoresNonObjectWrappedErrorEntry(): void
    {
        $handler = new HandlerOrderReshop();

        $responseObject = (object) [
            'Errors' => (object) ['Error' => 'plaintext'],
        ];

        $result = $handler->analyze($this->makeSendResultFromObject($responseObject));

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEmpty($result->messages);
    }

    public function testHandlesNonObjectResponseObject(): void
    {
        $handler = new HandlerOrderReshop();

        $result = $handler->analyze($this->makeSendResultFromObject(null));

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEmpty($result->messages);
    }

    public function testDoesNotUnwrapWhenResponseIsNotAnObject(): void
    {
        $handler = new HandlerOrderReshop();

        $responseObject = (object) ['Response' => 'sometext'];

        $result = $handler->analyze($this->makeSendResultFromObject($responseObject));

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEmpty($result->messages);
    }

    /**
     * Build a SendResult from an XML fixture (decoded exactly like SoapClient).
     *
     * @param string $fileName
     * @return SendResult
     */
    private function makeSendResultFromXml($fileName)
    {
        $xml = $this->getTestFile($fileName);

        $sendResult = new SendResult();
        $sendResult->responseXml = $xml;
        $sendResult->responseObject = json_decode(
            json_encode(new \SimpleXMLElement($xml), JSON_THROW_ON_ERROR),
            false,
            512,
            JSON_THROW_ON_ERROR
        );

        return $sendResult;
    }

    /**
     * Build a SendResult from an already-decoded object graph.
     *
     * @param \stdClass|null $responseObject
     * @return SendResult
     */
    private function makeSendResultFromObject($responseObject)
    {
        $sendResult = new SendResult();
        $sendResult->responseXml = '<root/>';
        $sendResult->responseObject = $responseObject;

        return $sendResult;
    }

    /**
     * @param Result $result
     * @return array
     */
    private function flattenMessages(Result $result)
    {
        return array_map(
            static function (Result\NotOk $notOk) {
                return ['code' => $notOk->code, 'text' => $notOk->text];
            },
            $result->messages
        );
    }
}
