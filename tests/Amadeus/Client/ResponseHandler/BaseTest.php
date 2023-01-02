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

use Amadeus\Client\ResponseHandler;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;
use Test\Amadeus\BaseTestCase;

/**
 * BaseTest
 *
 * @package Test\Amadeus\Client\ResponseHandler
 * @author Dieter Devlieghere <dermikagh@gmail.com>
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

    public function testCanFindPassengerNameErrorMessageInPnrReply()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyPnrAddMultElementsNameError.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_AddMultiElements');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('20', $result->messages[0]->code);
        $this->assertEquals("RESTRICTED", $result->messages[0]->text);
        $this->assertEquals('passenger', $result->messages[0]->level);
    }

    public function testCanHandleIssue50ErrorMessageInPnrReply()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('pnrAddMultiElements14_1_need_received_from.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_AddMultiElements');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertCount(1, $result->messages);
        $this->assertEquals('8111', $result->messages[0]->code);
        $this->assertEquals("ERROR AT END OF TRANSACTION TIME - ERROR AT EOT TIME - NEED RECEIVED FROM", $result->messages[0]->text);
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

    public function testCanHandlePnrRetrieveAndDisplayErr()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyPnrRetrieveAndDisplayErrResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_RetrieveAndDisplay');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('1', $result->messages[0]->code);
        $this->assertEquals("NO MATCH FOR RECORD LOCATOR", $result->messages[0]->text);
        $this->assertEquals('', $result->messages[0]->level);
    }

    public function testCanHandlePnrRetrieveAndDisplayErr2()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyPnrRetrieveAndDisplayErr2Response.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_RetrieveAndDisplay');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('27563', $result->messages[0]->code);
        $this->assertEquals("NO OFFER", $result->messages[0]->text);
        $this->assertEquals('', $result->messages[0]->level);
    }

    public function testCanHandlePnrDisplayHistoryError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyPnrDisplayHistoryErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_DisplayHistory');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('20', $result->messages[0]->code);
        $this->assertEquals("RESTRICTED", $result->messages[0]->text);
        $this->assertEquals('', $result->messages[0]->level);
    }

    public function testCanHandlePnrDisplayHistoryOk()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyPnrDisplayHistoryOkResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_DisplayHistory');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanHandlePnrTransferOwnershipError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyPnrTransferOwnershipErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_TransferOwnership');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('1931', $result->messages[0]->code);
        $this->assertEquals("NO MATCH FOR RECORD LOCATOR", $result->messages[0]->text);
        $this->assertEquals('', $result->messages[0]->level);
    }

    public function testCanHandlePnrTransferOwnershipOfficeError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyPnrTransferOwnershipOfficeErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_TransferOwnership');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('1533', $result->messages[0]->code);
        $this->assertEquals("INVALID OFFICE IDENTIFICATION CODE", $result->messages[0]->text);
        $this->assertEquals('', $result->messages[0]->level);
    }

    public function testCanHandlePnrNameChangePassengerError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyPnrNameChangePassengerErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_NameChange');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('42', $result->messages[0]->code);
        $this->assertEquals("INVALID/DUPLICATE NAME EXISTS", $result->messages[0]->text);
        $this->assertEquals('passenger', $result->messages[0]->level);
    }

    public function testCanHandlePnrNameChangeError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyPnrNameChangeErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_NameChange');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('1194', $result->messages[0]->code);
        $this->assertEquals("INVALID NUMBER IN PARTY", $result->messages[0]->text);
        $this->assertEquals('', $result->messages[0]->level);
    }

    public function testCanHandleQueueRemoveItemOk()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyQueueRemoveItemOkResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Queue_RemoveItem');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanHandleQueuePlacePNROk()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyQueuePlacePNROkResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Queue_PlacePNR');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanHandleQueueMoveItemOk()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyQueueMoveItemOkResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Queue_MoveItem');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanHandleQueueMoveItemErr()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyQueueMoveItemErrResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Queue_MoveItem');

        $this->assertEquals(Result::STATUS_WARN, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals("79D", $result->messages[0]->code);
        $this->assertEquals("Queue identifier has not been assigned for specified office identification", $result->messages[0]->text);
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

    public function testCanFindAirSellFromRecommendationError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyAirSellFromRecommendationErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Air_SellFromRecommendation');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('390', $result->messages[0]->code);
        $this->assertEquals("UNABLE TO REFORMAT", $result->messages[0]->text);
    }

    public function testCanFindAirSellFromRecommendationErrorPartialSuccesCancelled()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyAirSellFromRecommendationPartialSuccessCancelled.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Air_SellFromRecommendation');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('288', $result->messages[0]->code);
        $this->assertEquals("UNABLE TO SATISFY, NEED CONFIRMED FLIGHT STATUS", $result->messages[0]->text);
    }

    public function testCanHandleAirRebookAirSegmentError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyAirRebookAirSegmentResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Air_RebookAirSegment');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('ZZZ', $result->messages[0]->code);
        $this->assertEquals("UNABLE TO REPLICATE - INFORMATIONAL SEGMENT", $result->messages[0]->text);
    }

    public function testCanFindAirFlightInfoError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyAirFlightInfoResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Air_FlightInfo');

        $this->assertEquals(Result::STATUS_INFO, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('AUE', $result->messages[0]->code);
        $this->assertEquals("FLIGHT CANCELLED", $result->messages[0]->text);
    }

    public function testCanFindAirRetrieveSeatMapErrorWithCode()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyAirRetrieveSeatMapError2Response.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Air_RetrieveSeatMap');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('94', $result->messages[0]->code);
        $this->assertEquals('application', $result->messages[0]->level);
        $this->assertEquals("Flight does not operate between requested cities", $result->messages[0]->text);
    }

    public function testCanFindAirRetrieveSeatMapError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyAirRetrieveSeatMapErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Air_RetrieveSeatMap');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('2', $result->messages[0]->code);
        $this->assertEquals('application', $result->messages[0]->level);
        $this->assertEquals("Seat not available on the requested class/zone", $result->messages[0]->text);
    }

    public function testCanFindAirRetrieveSeatMapErrorWithDescription()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyAirRetrieveSeatMapErrorWdResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Air_RetrieveSeatMap');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('2', $result->messages[0]->code);
        $this->assertEquals('application', $result->messages[0]->level);
        $this->assertEquals("test description 100% is the goal", $result->messages[0]->text);
    }

    public function testCanParseAirRetrieveSeatMapOk()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyAirRetrieveSeatMapOkResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Air_RetrieveSeatMap');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanFindAirRetrieveSeatMapWarning()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyAirRetrieveSeatMapWarningResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Air_RetrieveSeatMap');

        $this->assertEquals(Result::STATUS_WARN, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('62', $result->messages[0]->code);
        $this->assertEquals('application', $result->messages[0]->level);
        $this->assertEquals("Smoking zone unavailable", $result->messages[0]->text);
    }

    public function testCanFindAirRetrieveSeatMapWarningWithDescription()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyAirRetrieveSeatMapWarningWdResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Air_RetrieveSeatMap');

        $this->assertEquals(Result::STATUS_WARN, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('62', $result->messages[0]->code);
        $this->assertEquals('application', $result->messages[0]->level);
        $this->assertEquals("test description 100% is the goal", $result->messages[0]->text);
    }

    public function testCanFindAirMultiAvailabilityError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyAirMultiAvailabilityErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Air_MultiAvailability');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('A08', $result->messages[0]->code);
        $this->assertEquals("CHECK DATE", $result->messages[0]->text);
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
        $sendResult->messageVersion = '4.1';

        $result = $respHandler->analyzeResponse($sendResult, 'Security_SignOut');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('ERROR', $result->messages[0]->text);
        $this->assertEquals('', $result->messages[0]->level);
        $this->assertEquals('1', $result->messages[0]->code);
    }

    public function testCanParseSecuritySignOutReplyOk()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummySecuritySignoutReplyOk.txt');
        $sendResult->messageVersion = '4.1';

        $result = $respHandler->analyzeResponse($sendResult, 'Security_SignOut');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanHandleOfferConfirmCarOfferOk()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyOfferConfirmCarOfferOkResponse.txt');
        $sendResult->messageVersion = '13.1';

        $result = $respHandler->analyzeResponse($sendResult, 'Offer_ConfirmCarOffer');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('OFFER CONFIRMED SUCCESSFULLY', $result->messages[0]->text);
        $this->assertEquals('', $result->messages[0]->level);
        $this->assertEquals(0, $result->messages[0]->code);
    }

    public function testCanHandleOfferConfirmCarOfferErr()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyOfferConfirmCarOfferErrResponse.txt');
        $sendResult->messageVersion = '13.1';

        $result = $respHandler->analyzeResponse($sendResult, 'Offer_ConfirmCarOffer');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('OFFER NO LONGER AVAILABLE', $result->messages[0]->text);
        $this->assertEquals('', $result->messages[0]->level);
        $this->assertEquals(27635, $result->messages[0]->code);
    }

    public function testCanHandleOfferConfirmHotelOfferOk()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyOfferConfirmHotelOfferOkResponse.txt');
        $sendResult->messageVersion = '10.2';

        $result = $respHandler->analyzeResponse($sendResult, 'Offer_ConfirmHotelOffer');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanHandleOfferConfirmHotelOfferErr()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyOfferConfirmHotelOfferErrResponse.txt');
        $sendResult->messageVersion = '10.2';

        $result = $respHandler->analyzeResponse($sendResult, 'Offer_ConfirmHotelOffer');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('CREDIT CARD DEPOSIT REQUIRED', $result->messages[0]->text);
        $this->assertEquals('', $result->messages[0]->level);
        $this->assertEquals('03659', $result->messages[0]->code);
    }

    public function testCanHandleOfferConfirmAirOfferOk()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyOfferConfirmAirOfferOkResponse.txt');
        $sendResult->messageVersion = '10.1';

        $result = $respHandler->analyzeResponse($sendResult, 'Offer_ConfirmAirOffer');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('OFFER CONFIRMED SUCCESSFULLY', $result->messages[0]->text);
        $this->assertEquals('', $result->messages[0]->level);
        $this->assertEquals(0, $result->messages[0]->code);
    }

    public function testCanHandleOfferConfirmAirOfferErr()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyOfferConfirmAirOfferErrResponse.txt');
        $sendResult->messageVersion = '10.1';

        $result = $respHandler->analyzeResponse($sendResult, 'Offer_ConfirmAirOffer');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('INACTIVE OFFER CAN NOT BE VERIFIED NOR CONFIRMED', $result->messages[0]->text);
        $this->assertEquals('', $result->messages[0]->level);
        $this->assertEquals('27631', $result->messages[0]->code);
    }

    public function testCanHandleOfferVerifyOfferOk()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyOfferVerifyOfferOkResponse.txt');
        $sendResult->messageVersion = '10.1';

        $result = $respHandler->analyzeResponse($sendResult, 'Offer_VerifyOffer');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('OFFER VERIFIED SUCCESSFULLY', $result->messages[0]->text);
        $this->assertEquals('', $result->messages[0]->level);
        $this->assertEquals(0, $result->messages[0]->code);
    }

    public function testCanHandleOfferVerifyOfferErr()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyOfferVerifyOfferErrResponse.txt');
        $sendResult->messageVersion = '10.1';

        $result = $respHandler->analyzeResponse($sendResult, 'Offer_VerifyOffer');

        $this->assertEquals(Result::STATUS_WARN, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('PRICING CONDITIONS HAVE CHANGED', $result->messages[0]->text);
        $this->assertEquals('', $result->messages[0]->level);
        $this->assertEquals('27706', $result->messages[0]->code);
    }

    public function testCanHandleOfferCreateOfferErr()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyOfferCreateOfferErrorResponse.txt');
        $sendResult->messageVersion = '13.2';

        $result = $respHandler->analyzeResponse($sendResult, 'Offer_CreateOffer');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('Offer creation not possible on ticketed segment', $result->messages[0]->text);
        $this->assertEquals('', $result->messages[0]->level);
        $this->assertEquals('27568', $result->messages[0]->code);
    }

    public function testCanHandleOfferCreateOfferOk()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyOfferCreateOfferOkResponse.txt');
        $sendResult->messageVersion = '13.2';

        $result = $respHandler->analyzeResponse($sendResult, 'Offer_CreateOffer');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
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

    public function testCanHandleFareMasterPricerTravelBoarSearchError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFareMasterPricerTravelBoardSearchErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_MasterPricerTravelBoardSearch');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('920', $result->messages[0]->code);
        $this->assertEquals("Past date/time not allowed", $result->messages[0]->text);
    }

    public function testCanHandleFareMasterPricerExpertSearchError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFareMasterPricerExpertSearchErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_MasterPricerTravelBoardSearch');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('920', $result->messages[0]->code);
        $this->assertEquals("Past date/time not allowed", $result->messages[0]->text);
    }

    public function testCanHandleFareMasterPricerCalendarError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFareMasterPricerCalendarErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_MasterPricerCalendar');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('931', $result->messages[0]->code);
        $this->assertEquals("NO ITINERARY FOUND FOR REQUESTED SEGMENT 1", $result->messages[0]->text);
    }

    public function testCanFindFarePricePnrWithBookingClassError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFarePricePnrWithBookingClassReplyError.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_PricePNRWithBookingClass');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('00477', $result->messages[0]->code);
        $this->assertEquals("INVALID FORMAT", $result->messages[0]->text);
    }

    public function testCanFindFarePricePnrWithBookingClass12Error()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFarePricePnrWithBookingClass12ErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_PricePNRWithBookingClass');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('00477', $result->messages[0]->code);
        $this->assertEquals("INVALID FORMAT", $result->messages[0]->text);
    }

    public function testCanFindFarePricePnrWithBookingClass7Error()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFarePricePnrWithBookingClass7ErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_PricePNRWithBookingClass');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('00911', $result->messages[0]->code);
        $this->assertEquals("NO TICKETABLE VALIDATING CARRIER", $result->messages[0]->text);
    }

    public function testCanFindFarePricePnrWithLowerFares14Error()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFarePricePnrWithLowerFares14ErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_PricePNRWithLowerFares');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('00477', $result->messages[0]->code);
        $this->assertEquals("INVALID FORMAT", $result->messages[0]->text);
    }

    public function testCanFindFarePricePnrWithLowestFare14Error()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFarePricePnrWithLowestFare14ErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_PricePNRWithLowestFare');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('00477', $result->messages[0]->code);
        $this->assertEquals("INVALID FORMAT", $result->messages[0]->text);
    }

    public function testCanHandleFareConvertCurrencyError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFareConvertCurrencyErrResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_ConvertCurrency');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('0123', $result->messages[0]->code);
        $this->assertEquals("VERIFY CURRENCY", $result->messages[0]->text);
    }

    public function testCanHandleFareCheckRulesError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFareCheckRulesReplyErr.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_CheckRules');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('0', $result->messages[0]->code);
        $this->assertEquals("ENTRY REQUIRES PREVIOUS PRICING/FARE DISPLAY REQUEST", $result->messages[0]->text);
    }

    public function testCanHandleFareGetFareRulesError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFareGetFareRulesErrorReply.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_GetFareRules');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('0', $result->messages[0]->code);
        $this->assertEquals("UNKNOWN CITY/AIRPORT", $result->messages[0]->text);
    }

    public function testCanHandleFareGetFareRulesWarning()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFareGetFareRulesWarningReply.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_GetFareRules');

        $this->assertEquals(Result::STATUS_WARN, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEmpty($result->messages[0]->code);
        $this->assertEquals("MORE THAN ONE FARE MATCHING AGAINST INPUT", $result->messages[0]->text);
    }

    public function testCanHandleFareInformativePricingWithoutPNRError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFareInformativePricingWithoutPNRErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_InformativePricingWithoutPNR');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('00477', $result->messages[0]->code);
        $this->assertEquals("INVALID FORMAT", $result->messages[0]->text);
    }

    public function testCanHandleFarePriceUpsellWithoutPNRError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFarePriceUpsellWithoutPNRErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_PriceUpsellWithoutPNR');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertCount(1, $result->messages);
        $this->assertEquals('5436', $result->messages[0]->code);
        $this->assertEquals('UNABLE TO FARE - NO INVENTORY FOR FLIGHT REQUESTED -TK 57', $result->messages[0]->text);
    }

    public function testCanHandleFareGetFamilyDescriptionError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFareGetFareFamilyDescriptionErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_GetFareFamilyDescription');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertCount(1, $result->messages);
        $this->assertEquals('33116', $result->messages[0]->code);
        $this->assertEquals('CHECK FARE COMPONENT NUMBER', $result->messages[0]->text);
    }

    public function testCanHandleFareInformativeBestPricingWithoutPNRError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFareInformativeBestPricingWithoutPNRErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_InformativeBestPricingWithoutPNR');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('00477', $result->messages[0]->code);
        $this->assertEquals("INVALID FORMAT", $result->messages[0]->text);
    }

    public function testCanHandleDocIssuanceIssueTicketOkResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyDocIssuanceIssueTicketReplyOk.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'DocIssuance_IssueTicket');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('OK', $result->messages[0]->code);
        $this->assertEquals("OK ETICKET", $result->messages[0]->text);
    }

    public function testCanHandleDocIssuanceIssueTicketErrResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyDocIssuanceIssueTicketReplyError.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'DocIssuance_IssueTicket');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('120', $result->messages[0]->code);
        $this->assertEquals("UNABLE TO PROCESS", $result->messages[0]->text);
    }

    public function testCanHandleDocIssuanceIssueMiscDocErrorResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyDocIssuanceIssueMiscDocReplyError.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'DocIssuance_IssueMiscellaneousDocuments');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('6043', $result->messages[0]->code);
        $this->assertEquals("UNABLE TO PROCESS - EDIFACT ERROR", $result->messages[0]->text);
    }

    public function testCanHandleDocIssuanceIssueCombinedErrorResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyDocIssuanceIssueCombinedErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'DocIssuance_IssueCombined');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('6043', $result->messages[0]->code);
        $this->assertEquals("UNABLE TO PROCESS - EDIFACT EROR", $result->messages[0]->text);
    }

    public function testCanHandleDocRefundInitRefundErrorResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyDocRefundInitRefundErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'DocRefund_InitRefund');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('25677', $result->messages[0]->code);
        $this->assertEquals("ATC REFUND NOT AUTHORIZED", $result->messages[0]->text);
    }

    public function testCanHandleDocRefundUpdateRefundErrorResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyDocRefundUpdateRefundErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'DocRefund_UpdateRefund');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('2213', $result->messages[0]->code);
        $this->assertEquals("INVALID FORM OF PAYMENT", $result->messages[0]->text);
    }

    public function testCanHandleTicketCreateTSTFromPricingErrResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketCreateTSTFromPricingReplyError.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_CreateTSTFromPricing');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('CM01959', $result->messages[0]->code);
        $this->assertEquals("NEED PNR", $result->messages[0]->text);
    }

    public function testCanHandleDocRefundProcessRefundErrorResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyDocRefundProcessRefundErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'DocRefund_ProcessRefund');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('11062', $result->messages[0]->code);
        $this->assertEquals("NEED FARE USED FOR PARTIAL REFUND", $result->messages[0]->text);
    }

    public function testCanHandleTicketCreateTSMFromPricingErrResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketCreateTSMFromPricingReplyErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_CreateTSMFromPricing');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('CM01908', $result->messages[0]->code);
        $this->assertEquals("CHECK PASSENGER NUMBER", $result->messages[0]->text);
    }

    public function testCanHandleTicketCreateTasf()
    {
        $responseHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketCreateTASFResponse.txt');

        $result = $responseHandler->analyzeResponse($sendResult, 'Ticket_CreateTASF');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanHandleTicketCreateTasfErrorResponse()
    {
        $responseHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketCreateTasfErrorResponse.txt');

        $result = $responseHandler->analyzeResponse($sendResult, 'Ticket_CreateTASF');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('00477', $result->messages[0]->code);
        $this->assertEquals("INVALID FORMAT - ERROR DURING TASF CREATION", $result->messages[0]->text);
    }

    public function testCanHandleTicketReissueConfirmedPricingErrResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketReissueConfirmedPricingErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_ReissueConfirmedPricing');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('CM00477', $result->messages[0]->code);
        $this->assertEquals("INVALID FORMAT", $result->messages[0]->text);
    }

    public function testCanHandleTicketDeleteTSTErrResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketDeleteTSTReplyError.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_DeleteTST');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('CM01959', $result->messages[0]->code);
        $this->assertEquals("NEED PNR", $result->messages[0]->text);
    }

    public function testCanHandleTicketDeleteTSMPErrorResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketDeleteTsmpErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_DeleteTSMP');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('911', $result->messages[0]->code);
        $this->assertEmpty($result->messages[0]->text);
    }

    public function testCanHandleTicketDisplayTSTErrResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketDisplayTstReplyErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_DisplayTST');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('2075', $result->messages[0]->code);
        $this->assertEquals("INVALID TST NUMBER", $result->messages[0]->text);
    }

    public function testCanHandleTicketDisplayTSPErrResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketDisplayTsmpReplyErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_DisplayTSMP');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('09922', $result->messages[0]->code);
        $this->assertEquals("CHECK TSM NUMBER", $result->messages[0]->text);
    }

    public function testCanHandleTicketDisplayTSMFareElementErrResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketDisplayTsmFareElementReplyErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_DisplayTSMFareElement');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('02085', $result->messages[0]->code);
        $this->assertEquals("CHECK FARE ELEMENTS", $result->messages[0]->text);
    }

    public function testCanHandleTicketRetrieveListOfTSMResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketRetrieveListOfTSMReplyResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_RetrieveListOfTSM');

        $this->assertEquals(Result::STATUS_OK, $result->status);
    }

    public function testCanHandleTicketCheckEligibilityErrResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketCheckEligibilityErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_CheckEligibility');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('123', $result->messages[0]->code);
        $this->assertEquals("E-ticket(s) not eligible to ATC - Fare Calculation not valid for ATC transaction - Document number not eligible for Amadeus Ticket Changer", $result->messages[0]->text);
        $this->assertEquals("application", $result->messages[0]->level);
    }

    public function testCanHandleTicketAtcShopperMasterPricerTravelBoardSearchErrResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketActShopperMasterPricerTravelBoardSearchErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_ATCShopperMasterPricerTravelBoardSearch');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('866', $result->messages[0]->code);
        $this->assertEquals("NO FARE FOUND FOR REQUESTED ITINERARY", $result->messages[0]->text);
        $this->assertNull($result->messages[0]->level);
    }

    public function testCanHandleTicketRepricePnrWithBookingClassErrResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketRepricePnrWithBookingClassErrResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_RepricePNRWithBookingClass');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('4845', $result->messages[0]->code);
        $this->assertEquals("INVALID PASSENGER SELECTION : ERROR FROM VALIDATION", $result->messages[0]->text);
        $this->assertNull($result->messages[0]->level);
    }

    public function testCanHandleTicketRepricePnrWithBookingClassPricingWarningResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketRepricePnrWithBookingClassPricingWarningResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_RepricePNRWithBookingClass');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertCount(0, $result->messages);

        /**
        $this->assertEquals(Result::STATUS_WARN, $result->status);

        $this->assertEquals(4, count($result->messages));
        $this->assertEquals('0', $result->messages[0]->code);
        $this->assertEquals(" - DATE OF ORIGIN", $result->messages[0]->text);
        $this->assertEquals('pricing', $result->messages[0]->level);

        $this->assertEquals('0', $result->messages[1]->code);
        $this->assertEquals("BG CXR", $result->messages[1]->text);
        $this->assertEquals('pricing', $result->messages[1]->level);

        $this->assertEquals('0', $result->messages[2]->code);
        $this->assertEquals("PRICED WITH VALIDATING CARRIER BA - REPRICE IF DIFFERENT VC", $result->messages[2]->text);
        $this->assertEquals('pricing', $result->messages[2]->level);

        $this->assertEquals('0', $result->messages[3]->code);
        $this->assertEquals("14JUN12 PER GAF REQUIREMENTS FARE NOT VALID UNTIL TICKETED", $result->messages[3]->text);
        $this->assertEquals('pricing', $result->messages[3]->level);*/
    }

    public function testCanHandleTicketCancelDocumentErrorResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketCancelDocumentErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_CancelDocument');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertCount(1, $result->messages);
        $this->assertEquals('118', $result->messages[0]->code);
        $this->assertEquals("", $result->messages[0]->text);
    }

    public function testCanHandleTicketProcessEDocErrorResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketProcessEDocErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_ProcessEDoc');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertCount(1, $result->messages);
        $this->assertEquals('118', $result->messages[0]->code);
        $this->assertEquals("SYSTEM UNABLE TO PROCESS", $result->messages[0]->text);
    }

    public function testCanHandleMiniRuleGetFromRecErrResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyMiniRuleGetFromRecErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'MiniRule_GetFromPricingRec');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('32660', $result->messages[0]->code);
        $this->assertEquals("INVALID CONTEXT - PNRRELOC MISMATCH", $result->messages[0]->text);
    }

    public function testCanHandleMiniRuleGetFromPricingRecErrResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyMiniRuleGetFromPricingRecErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'MiniRule_GetFromPricingRec');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('20', $result->messages[0]->code);
        $this->assertEquals("RESTRICTED", $result->messages[0]->text);
    }

    public function testCanHandleMiniRuleGetFromPricingErrorResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyMiniRuleGetFromPricingErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'MiniRule_GetFromPricing');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('20', $result->messages[0]->code);
        $this->assertEquals("RESTRICTED", $result->messages[0]->text);
    }

    public function testCanHandleMiniRuleGetFromETicketErrorResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('miniRuleGetFromETicketErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'MiniRule_GetFromETicket');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('29149', $result->messages[0]->code);
        $this->assertEquals("NO FARE RULES FOUND", $result->messages[0]->text);
    }

    public function testCanHandleInfoEncodeDecodeCityErrResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyInfoEncodeDecodeCityErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Info_EncodeDecodeCity');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('11177', $result->messages[0]->code);
        $this->assertEquals("Invalid value (TTT) for IATA code (TTT) field", $result->messages[0]->text);
    }

    public function testCanHandlePointOfRefSearchOkResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyPointOfRefSearchOkResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PointOfRef_Search');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanHandlePointOfRefSearchErrorResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyPointOfRefSearchErrorResponse.txt');
        $sendResult->exception = new \SoapFault("Server", "11185|Application|Result list type not supported:P");

        $result = $respHandler->analyzeResponse($sendResult, 'PointOfRef_Search');

        $this->assertEquals(Result::STATUS_FATAL, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('11185', $result->messages[0]->code);
        $this->assertEquals("Result list type not supported:P", $result->messages[0]->text);
    }

    public function testCanHandlePriceXplorerExtremeSearchErrResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyPriceXplorerExtremeSearchErrResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PriceXplorer_ExtremeSearch');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('1004', $result->messages[0]->code);
        $this->assertEquals("Invalid departure dates range", $result->messages[0]->text);
    }

    public function testCanHandleSalesReportsDisplayQueryReport()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummySalesReportsDisplayQueryReportErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'SalesReports_DisplayQueryReport');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('6466', $result->messages[0]->code);
        $this->assertEquals("NO DATA FOUND", $result->messages[0]->text);
    }

    public function testCanHandleSalesReportsDisplayDailyOrSummarizedReport()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummySalesReportsDisplayDailyOrSummarizedReportErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'SalesReports_DisplayDailyOrSummarizedReport');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('6466', $result->messages[0]->code);
        $this->assertEquals("NO DATA FOUND", $result->messages[0]->text);
    }

    public function testCanHandleSalesReportsDisplayNetRemitReport()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummySalesReportsDisplayNetRemitReportErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'SalesReports_DisplayNetRemitReport');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('6466', $result->messages[0]->code);
        $this->assertEquals("NO DATA FOUND", $result->messages[0]->text);
    }

    public function testCanHandleServiceBookPriceServiceError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('serviceBookPriceServiceErrorReply.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Service_BookPriceService');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(2, count($result->messages));

        $this->assertEquals('1', $result->messages[0]->code);
        $this->assertEquals("CHECK FORMAT", $result->messages[0]->text);

        $this->assertEquals('33017', $result->messages[1]->code);
        $this->assertEquals("SHOPPING BOX REJECT", $result->messages[1]->text);
    }

    public function testCanHandleServiceBookPriceServiceUnknownStatus()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('serviceBookPriceServiceErrorUnknown.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Service_BookPriceService');

        $this->assertEquals(Result::STATUS_UNKNOWN, $result->status);
    }

    public function testCanHandleServiceIntegratedPricing()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyServiceIntegratedPricingErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Service_IntegratedPricing');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('432', $result->messages[0]->code);
        $this->assertEquals("INVALID CURRENCY CODE", $result->messages[0]->text);
    }

    public function testCanHandleServiceIntegratedCatalogueError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyServiceIntegratedCatalogueErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Service_IntegratedCatalogue');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('432', $result->messages[0]->code);
        $this->assertEquals("INVALID CURRENCY CODE", $result->messages[0]->text);
    }

    public function testCanHandleFopCreateFormOfPaymentFopError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyCreateFormOfPaymentFopBackendErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'FOP_CreateFormOfPayment');

        $this->assertEquals(Result::STATUS_WARN, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('4800', $result->messages[0]->code);
        $this->assertEquals('deficient_fop', $result->messages[0]->level);
        $this->assertEquals("FP NOT ALLOWED FOR NEGOTIATED FARE", $result->messages[0]->text);
    }

    public function testCanHandleFopValidateFopErrorResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFopValidateFopErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'FOP_ValidateFOP');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(2, count($result->messages));
        $this->assertEquals('22389', $result->messages[0]->code);
        $this->assertEquals("INVALID CVV NUMBER - CHECK THE NUMBER AND TRY AGAIN", $result->messages[0]->text);

        $this->assertEquals('N', $result->messages[1]->code);
        $this->assertEquals("CCAVS NO MATCH - AQ ELEMENT NOT UPDATED", $result->messages[1]->text);
    }

    public function testCanHandleFopCreateFormOfPaymentMultiError()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyCreateFormOfPaymentMultiErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'FOP_CreateFormOfPayment');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(3, count($result->messages));
        $this->assertEquals('22427', $result->messages[0]->code);
        $this->assertEquals('general', $result->messages[0]->level);
        $this->assertEquals("PAYMENT FAILED - PLEASE CONTACT AIRLINE", $result->messages[0]->text);
        $this->assertEquals('25799', $result->messages[1]->code);
        $this->assertEquals('fp', $result->messages[1]->level);
        $this->assertEquals("ERROR AT FOP CREATION", $result->messages[1]->text);
        $this->assertEquals('313', $result->messages[2]->code);
        $this->assertEquals('authorization_failure', $result->messages[2]->level);
        $this->assertEquals("INVALID ACCOUNT NUMBER", $result->messages[2]->text);
    }

    public function testCanHandleFopCreateFormOfPaymentOkResponse()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyCreateFormOfPaymentOkResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'FOP_CreateFormOfPayment');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanHandleInvalidXmlDocument()
    {
        $this->setExpectedException('Amadeus\Client\Exception');

        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyInvalidXmlDocument.txt');

        $warningEnabledOrig = \PHPUnit_Framework_Error_Warning::$enabled;
        \PHPUnit_Framework_Error_Warning::$enabled = false;

        $respHandler->analyzeResponse($sendResult, 'Fare_PricePnrWithBookingClass');

        \PHPUnit_Framework_Error_Warning::$enabled = $warningEnabledOrig;
    }

    public function testCanHandleCommandCryptic()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->messageVersion = "7.3";
        $sendResult->responseXml = $this->getTestFile('dummyCommandCrypticResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Command_Cryptic');

        $this->assertEquals(Result::STATUS_UNKNOWN, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('0', $result->messages[0]->code);
        $this->assertEquals("Response handling not supported for cryptic entries", $result->messages[0]->text);
    }

    public function testCanTryAnalyzingSameMessageTwiceWillReuseHandler()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyServiceIntegratedPricingErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Service_IntegratedPricing');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('432', $result->messages[0]->code);
        $this->assertEquals("INVALID CURRENCY CODE", $result->messages[0]->text);

        $prop = $this->getProperty($respHandler, 'responseHandlers');
        $propval = $prop->getValue($respHandler);

        $this->assertInstanceOf('\Amadeus\Client\ResponseHandler\Service\HandlerIntegratedPricing', $propval['Service_IntegratedPricing']);

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyServiceIntegratedPricingErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Service_IntegratedPricing');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('432', $result->messages[0]->code);
        $this->assertEquals("INVALID CURRENCY CODE", $result->messages[0]->text);
    }

    public function testCanHandleOkTicketInitRefund()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketInitRefundResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_InitRefund');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanHandleFailTicketInitRefund()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketInitRefundFailResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_InitRefund');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
    }

    public function testCanHandleUnknownTicketInitRefund()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketInitRefundUnknownResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_InitRefund');

        $this->assertEquals(Result::STATUS_UNKNOWN, $result->status);
    }

    public function testCanHandleOkTicketUpdateRefund()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketUpdateRefundResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_UpdateRefund');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertCount(0, $result->messages);
    }

    public function testCanHandleFailTicketUpdateRefund()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketUpdateRefundFailResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_InitRefund');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertCount(1, $result->messages);
    }

    public function testCanHandleUnknownTicketUpdateRefund()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketUpdateRefundUnknownResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_InitRefund');

        $this->assertEquals(Result::STATUS_UNKNOWN, $result->status);
    }

    public function testCanHandleOkTicketIgnoreRefund()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketIgnoreRefundResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_IgnoreRefund');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanHandleFailTicketIgnoreRefund()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketIgnoreRefundFailResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_IgnoreRefund');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
    }

    public function testCanHandleUnknownTicketIgnoreRefund()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketIgnoreRefundUnknownResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_IgnoreRefund');

        $this->assertEquals(Result::STATUS_UNKNOWN, $result->status);
    }

    public function testCanHandleOkTicketProcessRefund()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketProcessRefundResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_ProcessRefund');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanHandleFailTicketProcessRefund()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketProcessRefundFailResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_ProcessRefund');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
    }

    public function testCanHandleUnknownTicketProcessRefund()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketProcessRefundUnknownResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_ProcessRefund');

        $this->assertEquals(Result::STATUS_UNKNOWN, $result->status);
    }

    public function testCanHandleTicketProcessETicket()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyTicketProcessETicketResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Ticket_ProcessETicket');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanHandleDocRefundIgnoreRefund()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyDocRefundIgnoreRefundResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'DocRefund_IgnoreRefund');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }

    public function testCanHandlePNRSplit()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyPNRSplitResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'PNR_Split');

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals(0, count($result->messages));
    }
}
