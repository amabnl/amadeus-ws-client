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

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_PricePnrWithBookingClass');

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

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_PricePnrWithBookingClass');

        $this->assertEquals(Result::STATUS_ERROR, $result->status);
        $this->assertEquals(1, count($result->messages));
        $this->assertEquals('00477', $result->messages[0]->code);
        $this->assertEquals("INVALID FORMAT", $result->messages[0]->text);
    }

    public function testCanFindFarePricePnrWithLowerFares14Error()
    {
        $respHandler = new ResponseHandler\Base();

        $sendResult = new SendResult();
        $sendResult->responseXml = $this->getTestFile('dummyFarePricePnrWithLowerFares14ErrorResponse.txt');

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_PricePnrWithLowerFares');

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

        $result = $respHandler->analyzeResponse($sendResult, 'Fare_PricePnrWithLowestFare');

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

    public function testCanGetUnknownStatusForUnknownErrorCode()
    {
        //Sweet sweet 100% coverage

        $respHandler = new ResponseHandler\Base();

        $meth = $this->getMethod('Amadeus\Client\ResponseHandler\Base', 'makeStatusFromErrorQualifier');

        $result = $meth->invoke($respHandler, ['ZZZ']);

        $this->assertEquals(Result::STATUS_UNKNOWN, $result);
    }
}
