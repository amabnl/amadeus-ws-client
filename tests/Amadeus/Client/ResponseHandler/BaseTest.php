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
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('8111', $result->messages[0]->code);
        $this->assertEquals("SIMULTANEOUS CHANGES TO PNR - USE WRA/RT TO PRINT OR IGNORE", $result->messages[0]->text);
        $this->assertEquals('general', $result->messages[0]->level);
    }

    public function testCanFindTopLevelErrorMessageInPnrReply()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('faultyPnrCreateTopError.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_AddMultiElements');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('102', $result->messages[0]->code);
        $this->assertEquals("CHECK DATE", $result->messages[0]->text);
        $this->assertEquals('general', $result->messages[0]->level);
    }

    public function testCanFindSegmentLevelErrorMessageInPnrReply()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('faultyPnrCreateSegmentError.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_AddMultiElements');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('102', $result->messages[0]->code);
        $this->assertEquals("CHECK DATE", $result->messages[0]->text);
        $this->assertEquals('segment', $result->messages[0]->level);
    }

    public function testCanFindElementLevelErrorMessageInPnrReply()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('faultyPnrCreateElementError.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_AddMultiElements');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('4498', $result->messages[0]->code);
        $this->assertEquals("COMBINATION OF ELEMENTS NOT ALLOWED", $result->messages[0]->text);
        $this->assertEquals('element', $result->messages[0]->level);
    }

    public function testCanFindErrorInPnrCancel()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('pnrCancelDemoError.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_Cancel');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanHandleOkPnrRetrieve()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyPnrRetrieveReply.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_Retrieve');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanSetWarningStatusForEmptyQueue()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('emptyqueueresponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Queue_List');

        $this->assertEquals(Result::STATUS_WARN, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals(926, $result->messages[0]->code);
        $this->assertEquals("Queue category empty", $result->messages[0]->text);
    }

    public function testWillSetGenericWarningForUnknownError()
    {
        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('emptyqueueresponsedummy666error.txt');

        $respHandler = new ResponseHandler\Base();

        $result = $respHandler->analyzeResponse($sendResult, 'Queue_List');

        $this->assertEquals(Result::STATUS_WARN, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals(666, $result->messages[0]->code);
        $this->assertEquals("QUEUE ERROR '666' (Error message unavailable)", $result->messages[0]->text);
    }

    public function testWillReturnUnknownStatusWhenHandlingResponseFromUnknownMessage()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFareDisplayFaresForCityPairReply.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_DisplayFaresForCityPair');

        $this->assertEquals(Result::STATUS_UNKNOWN, $result->status);
    }

    public function testCanFindAirFlightInfoError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyAirFlightInoResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Air_FlightInfo');

        $this->assertEquals(Result::STATUS_INFO, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('AUE', $result->messages[0]->code);
        $this->assertEquals("FLIGHT CANCELLED", $result->messages[0]->text);
    }

    public function testCanParseSecurityAuthenticateReplyOk()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummySecurityAuthenticateReply.txt');
        $sendResult->messageVersion = '6.1';

        $result = $respHandler->analyzeResponse($sendResult, 'Security_Authenticate');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanParseSecurityAuthenticateReplyErr()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummySecurityAuthenticateReplyError.txt');
        $sendResult->messageVersion = '6.1';

        $result = $respHandler->analyzeResponse($sendResult, 'Security_Authenticate');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('You are not authorized to login in this area.', $result->messages[0]->text);
        $this->assertEquals('', $result->messages[0]->level);
        $this->assertEquals('16199', $result->messages[0]->code);
    }

    public function testCanParseSecuritySignOutReplyErr()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummySecuritySignoutReply.txt');
        $sendResult->messageVersion = '6.1';

        $result = $respHandler->analyzeResponse($sendResult, 'Security_SignOut');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('ERROR', $result->messages[0]->text);
        $this->assertEquals('', $result->messages[0]->level);
        $this->assertEquals('1', $result->messages[0]->code);
    }

    public function testCanHandleSoapFault()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummySoapFault.txt');
        $sendResult->messageVersion = '14.2';
        $sendResult->exception = new \SoapFault("Sender", "1929|Application|INVALID RECORD LOCATOR");

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_Retrieve');

        $this->assertEquals(Result::STATUS_FATAL, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('1929', $result->messages[0]->code);
        $this->assertEquals("INVALID RECORD LOCATOR", $result->messages[0]->text);
        $this->assertEquals("Application", $result->messages[0]->level);
    }

    public function testCanHandleSoapFaultSession()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummySoapFaultSession.txt');
        $sendResult->messageVersion = '14.2';
        $sendResult->exception = new \SoapFault("Sender", "11|Session|");

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_Retrieve');

        $this->assertEquals(Result::STATUS_FATAL, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('11', $result->messages[0]->code);
        $this->assertEquals("Session", $result->messages[0]->level);
        $this->assertEquals('', $result->messages[0]->text);
    }

    public function testCanFindFarePricePnrWithBookingClassError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFarePricePnrWithBookingClassReplyError.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_PricePnrWithBookingClass');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('00477', $result->messages[0]->code);
        $this->assertEquals("INVALID FORMAT", $result->messages[0]->text);
    }
}
