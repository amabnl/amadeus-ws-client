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

namespace Test\Amadeus\Client\ResponseHandler;

use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;
use Test\Amadeus\BaseTestCase;
use Amadeus\Client\ResponseHandler;

/**
 * BaseTest
 *
 * @package Test\Amadeus\Client\ResponseHandler
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class BaseTest extends BaseTestCase
{
    public function testCanFindSimultaneousChangesErrorMessageInPnrReply()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('pnrAddMultiElementsSimultaneousChanges.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_AddMultiElements');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->errors));
        $this->assertEquals('8111', $result->errors[0]->code);
        $this->assertEquals("SIMULTANEOUS CHANGES TO PNR - USE WRA/RT TO PRINT OR IGNORE", $result->errors[0]->text);
        $this->assertEquals('general', $result->errors[0]->level);
    }

    public function testCanFindTopLevelErrorMessageInPnrReply()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('faultyPnrCreateTopError.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_AddMultiElements');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->errors));
        $this->assertEquals('102', $result->errors[0]->code);
        $this->assertEquals("CHECK DATE", $result->errors[0]->text);
        $this->assertEquals('general', $result->errors[0]->level);
    }

    public function testCanFindSegmentLevelErrorMessageInPnrReply()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('faultyPnrCreateSegmentError.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_AddMultiElements');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->errors));
        $this->assertEquals('102', $result->errors[0]->code);
        $this->assertEquals("CHECK DATE", $result->errors[0]->text);
        $this->assertEquals('segment', $result->errors[0]->level);
    }

    public function testCanFindElementLevelErrorMessageInPnrReply()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('faultyPnrCreateElementError.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_AddMultiElements');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->errors));
        $this->assertEquals('4498', $result->errors[0]->code);
        $this->assertEquals("COMBINATION OF ELEMENTS NOT ALLOWED", $result->errors[0]->text);
        $this->assertEquals('element', $result->errors[0]->level);
    }

    public function testCanSetWarningStatusForEmptyQueue()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('emptyqueueresponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Queue_List');

        $this->assertEquals(Result::STATUS_WARN, $result->status);
        $this->assertEquals(1, count($result->warnings));
        $this->assertEquals(926, $result->warnings[0]->code);
        $this->assertEquals("Queue category empty", $result->warnings[0]->text);
    }

    public function testWillSetGenericWarningForUnknownError()
    {
        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('emptyqueueresponsedummy666error.txt');

        $respHandler = new ResponseHandler\Base();

        $result = $respHandler->analyzeResponse($sendResult, 'Queue_List');

        $this->assertEquals(Result::STATUS_WARN, $result->status);
        $this->assertEquals(1, count($result->warnings));
        $this->assertEquals(666, $result->warnings[0]->code);
        $this->assertEquals("QUEUE ERROR '666' (Error message unavailable)", $result->warnings[0]->text);
    }

    public function testWillReturnUnknownStatusWhenHandlingResponseFromUnknownMessage()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFareDisplayFaresForCityPairReply.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_DisplayFaresForCityPair');

        $this->assertEquals(Result::STATUS_UNKNOWN, $result->status);
    }
}
