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

namespace Amadeus\Client\ResponseHandler;

use Amadeus\Client\Exception;
use Amadeus\Client\ResponseHandler\Air\RetrieveSeatMap;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * Default Response Handler
 *
 * @package Amadeus\Client\ResponseHandler
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Base implements ResponseHandlerInterface
{
    /**
     * Default namespace prefix we'll be using for xpath queries
     *
     * Why not "m"? It's as good as any other letter.
     */
    const XMLNS_PREFIX = "m";

    /**
     * Analyze the response from the server and throw an exception when an error has been detected.
     *
     * @param SendResult $sendResult The Send Result from the Session Handler
     * @param string $messageName The message that was called
     *
     * @throws Exception
     * @throws \RuntimeException
     * @return Result
     */
    public function analyzeResponse($sendResult, $messageName)
    {
        $methodName = 'analyze' . str_replace('_', '', ucfirst($messageName)) . 'Response';

        if (!empty($sendResult->exception)) {
            return $this->makeResultForException($sendResult);
        } elseif (method_exists($this, $methodName)) {
            return $this->$methodName(
                $sendResult
            );
        } else {
            return new Result($sendResult, Result::STATUS_UNKNOWN);
        }
    }

    /**
     * Analysing a Security_Authenticate
     *
     * @param SendResult $response Security_Authenticate result
     * @return Result
     */
    protected function analyzeSecurityAuthenticateResponse($response)
    {
        return $this->analyzeSimpleResponseErrorCodeAndMessage($response);
    }

    /**
     * Analysing a Security_Authenticate
     *
     * @param SendResult $response Security_Authenticate result
     * @return Result
     */
    protected function analyzeSecuritySignOutResponse($response)
    {
        return $this->analyzeSimpleResponseErrorCodeAndMessage($response);
    }

    /**
     * Unknown response for Command_Cryptic because you need to analyse the cryptic response yourself
     *
     * @param SendResult $response
     * @return Result
     */
    protected function analyzeCommandCrypticResponse($response)
    {
        $ccResult = new Result($response, Result::STATUS_UNKNOWN);
        $ccResult->messages[] = new Result\NotOk(
            0,
            "Response handling not supported for cryptic entries"
        );

        return $ccResult;
    }

    protected function analyzeAirSellFromRecommendationResponse($response)
    {
        $analyzeResponse = new Result($response);

        $errMsgMap = [
            "288" => "UNABLE TO SATISFY, NEED CONFIRMED FLIGHT STATUS",
            "390" => "UNABLE TO REFORMAT"
        ];

        $domXpath = $this->makeDomXpath($response->responseXml);

        $codeNode = $domXpath->query("//m:errorSegment/m:errorDetails/m:errorCode")->item(0);
        if ($codeNode instanceof \DOMNode) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $categoryNode = $domXpath->query("//m:errorSegment/m:errorDetails/m:errorCategory")->item(0);
            if ($categoryNode instanceof \DOMNode) {
                $analyzeResponse->status = $this->makeStatusFromErrorQualifier($categoryNode->nodeValue);
            }

            $message = (array_key_exists($codeNode->nodeValue, $errMsgMap)) ? $errMsgMap[$codeNode->nodeValue] : 'UNKNOWN ERROR';

            $analyzeResponse->messages [] = new Result\NotOk($codeNode->nodeValue, $message);
        }

        return $analyzeResponse;
    }

    /**
     * @param SendResult $response
     * @return Result
     */
    protected function analyzeAirFlightInfoResponse($response)
    {
        $analyzeResponse = new Result($response);

        $code = null;
        $message = null;

        $domXpath = $this->makeDomXpath($response->responseXml);

        $categoryNodes = $domXpath->query('//m:responseError/m:errorInfo/m:errorDetails/m:errorCategory');
        if ($categoryNodes->length > 0) {
            $analyzeResponse->status = $this->makeStatusFromErrorQualifier($categoryNodes->item(0)->nodeValue);
        }

        $codeNodes = $domXpath->query('//m:responseError/m:errorInfo/m:errorDetails/m:errorCode');
        if ($codeNodes->length > 0) {
            $code = $codeNodes->item(0)->nodeValue;
        }

        $messageNodes = $domXpath->query('//m:responseError/m:interactiveFreeText/m:freeText');
        if ($messageNodes->length > 0) {
            $message = $this->makeMessageFromMessagesNodeList($messageNodes);
        }

        if (!is_null($message) && !is_null($code)) {
            $analyzeResponse->messages[] = new Result\NotOk($code, $message);
        }

        return $analyzeResponse;
    }

    /**
     * @param SendResult $response
     * @return Result
     */
    protected function analyzeAirRetrieveSeatMapResponse($response)
    {
        $analyzeResponse = new Result($response);

        $code = null;
        $message = null;

        $domXpath = $this->makeDomXpath($response->responseXml);

        $errorCodeNode = $domXpath->query('//m:errorInformation/m:errorDetails/m:code');
        if ($errorCodeNode->length > 0) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $errCode = $errorCodeNode->item(0)->nodeValue;
            $level = null;
            $errDesc = null;

            $errorLevelNode = $domXpath->query('//m:errorInformation/m:errorDetails/m:processingLevel');
            if ($errorLevelNode->length > 0) {
                $level = RetrieveSeatMap::decodeProcessingLevel($errorLevelNode->item(0)->nodeValue);
            }

            $errorDescNode = $domXpath->query('//m:errorInformation/m:errorDetails/m:description');
            if ($errorDescNode->length > 0) {
                $errDesc = $errorDescNode->item(0)->nodeValue;
            } else {
                $errDesc = RetrieveSeatMap::findMessage($errCode);
            }

            $analyzeResponse->messages[] = new Result\NotOk(
                $errCode,
                $errDesc,
                $level
            );
        }

        $codeNode = $domXpath->query('//m:warningInformation/m:warningDetails/m:number');
        if ($codeNode->length > 0) {
            $analyzeResponse->status = Result::STATUS_WARN;

            $warnCode = $codeNode->item(0)->nodeValue;
            $level = null;
            $warnDesc = null;

            $levelNode = $domXpath->query('//m:warningInformation/m:warningDetails/m:processingLevel');
            if ($levelNode->length > 0) {
                $level = RetrieveSeatMap::decodeProcessingLevel($levelNode->item(0)->nodeValue);
            }

            $descNode = $domXpath->query('//m:warningInformation/m:warningDetails/m:description');
            if ($descNode->length > 0) {
                $warnDesc = $descNode->item(0)->nodeValue;
            } else {
                $warnDesc = RetrieveSeatMap::findMessage($warnCode);
            }

            $analyzeResponse->messages[] = new Result\NotOk(
                $warnCode,
                $warnDesc,
                $level
            );
        }

        return $analyzeResponse;
    }

    /**
     * Analysing a PNR_Retrieve response
     *
     * @param SendResult $response PNR_Retrieve result
     * @return Result
     */
    protected function analyzePnrRetrieveResponse($response)
    {
        return $this->analyzePnrReply($response);
    }

    /**
     * @param SendResult $response PNR_AddMultiElements result
     * @return Result
     */
    protected function analyzePnrAddMultiElementsResponse($response)
    {
        return $this->analyzePnrReply($response);
    }

    /**
     * @param SendResult $response PNR_Cancel result
     * @return Result
     */
    protected function analyzePnrCancelResponse($response)
    {
        return $this->analyzePnrReply($response);
    }

    /**
     * Analysing a PNR_Reply
     *
     * @param SendResult $response PNR_Retrieve result
     * @return Result
     */
    protected function analyzePnrReply($response)
    {
        $analyzeResponse = new Result($response);

        $domXpath = $this->makeDomXpath($response->responseXml);

        //General Errors:
        $queryAllErrorCodes = "//m:generalErrorInfo//m:errorOrWarningCodeDetails/m:errorDetails/m:errorCode";
        $queryAllErrorMsg = "//m:generalErrorInfo/m:errorWarningDescription/m:freeText";

        $errorCodeNodeList = $domXpath->query($queryAllErrorCodes);

        if ($errorCodeNodeList->length > 0) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $code = $errorCodeNodeList->item(0)->nodeValue;
            $errorTextNodeList = $domXpath->query($queryAllErrorMsg);
            $message = $this->makeMessageFromMessagesNodeList($errorTextNodeList);

            $analyzeResponse->messages[] = new Result\NotOk($code, trim($message), 'general');
        }

        //Segment errors:
        $querySegmentErrorCodes = "//m:originDestinationDetails//m:errorInfo/m:errorOrWarningCodeDetails/m:errorDetails/m:errorCode";
        $querySegmentErrorMsg = "//m:originDestinationDetails//m:errorInfo/m:errorWarningDescription/m:freeText";

        $errorCodeNodeList = $domXpath->query($querySegmentErrorCodes);

        if ($errorCodeNodeList->length > 0) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $code = $errorCodeNodeList->item(0)->nodeValue;
            $errorTextNodeList = $domXpath->query($querySegmentErrorMsg);
            $message = $this->makeMessageFromMessagesNodeList($errorTextNodeList);

            $analyzeResponse->messages[] = new Result\NotOk($code, trim($message), 'segment');
        }

        //Element errors:
        $queryElementErrorCodes = "//m:dataElementsIndiv/m:elementErrorInformation/m:errorOrWarningCodeDetails/m:errorDetails/m:errorCode";
        $queryElementErrorMsg = "//m:dataElementsIndiv//m:elementErrorInformation/m:errorWarningDescription/m:freeText";

        $errorCodeNodeList = $domXpath->query($queryElementErrorCodes);

        if ($errorCodeNodeList->length > 0) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $code = $errorCodeNodeList->item(0)->nodeValue;

            $errorTextNodeList = $domXpath->query($queryElementErrorMsg);
            $message = $this->makeMessageFromMessagesNodeList($errorTextNodeList);

            $analyzeResponse->messages[] = new Result\NotOk($code, trim($message), 'element');
        }

        return $analyzeResponse;
    }

    /**
     * @param SendResult $response Pnr_RetrieveAndDisplay response
     * @return Result
     * @throws Exception
     */
    protected function analyzePnrRetrieveAndDisplayResponse($response)
    {
        return $this->analyzeSimpleResponseErrorCodeAndMessage($response);
    }

    /**
     * Analysing a PNR_DisplayHistoryReply
     *
     * @param SendResult $response PNR_DisplayHistoryReply result
     * @return Result
     * @throws Exception
     */
    protected function analyzePnrDisplayHistoryResponse($response)
    {
        $analyzeResponse = new Result($response);

        $domXpath = $this->makeDomXpath($response->responseXml);

        $queryAllErrorCodes = "//m:generalErrorGroup//m:errorNumber/m:errorDetails/m:errorCode";
        $queryAllErrorMsg = "//m:generalErrorGroup/m:genrealErrorText/m:freeText";

        $errorCodeNodeList = $domXpath->query($queryAllErrorCodes);

        if ($errorCodeNodeList->length > 0) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $code = $errorCodeNodeList->item(0)->nodeValue;
            $errorTextNodeList = $domXpath->query($queryAllErrorMsg);
            $message = $this->makeMessageFromMessagesNodeList($errorTextNodeList);

            $analyzeResponse->messages[] = new Result\NotOk($code, trim($message));
        }

        return $analyzeResponse;
    }

    /**
     * @param SendResult $response Queue_RemoveItem response
     * @return Result
     * @throws Exception
     */
    protected function analyzeQueueRemoveItemResponse($response)
    {
        return $this->analyzeGenericQueueResponse($response);
    }

    /**
     * @param SendResult $response Queue_MoveItem response
     * @return Result
     * @throws Exception
     */
    protected function analyzeQueueMoveItemResponse($response)
    {
        return $this->analyzeGenericQueueResponse($response);
    }

    /**
     * @param SendResult $response Queue_PlacePNR response
     * @return Result
     * @throws Exception
     */
    protected function analyzeQueuePlacePNRResponse($response)
    {
        return $this->analyzeGenericQueueResponse($response);
    }

    /**
     * @param SendResult $response Queue_List result
     * @return Result
     * @throws Exception
     */
    protected function analyzeQueueListResponse($response)
    {
        return $this->analyzeGenericQueueResponse($response);
    }

    /**
     * Analyze a generic Queue response
     *
     * @param SendResult $response Queue_*Reply result
     * @return Result
     * @throws Exception
     */
    protected function analyzeGenericQueueResponse($response)
    {
        $analysisResponse = new Result($response);

        $domDoc = $this->loadDomDocument($response->responseXml);

        $errorCodeNode = $domDoc->getElementsByTagName("errorCode")->item(0);

        if (!is_null($errorCodeNode)) {
            $analysisResponse->status = Result::STATUS_WARN;

            $errorCode = $errorCodeNode->nodeValue;
            $errorMessage = $this->getErrorTextFromQueueErrorCode($errorCode);

            $analysisResponse->messages[] = new Result\NotOk($errorCode, $errorMessage);
        }

        return $analysisResponse;
    }


    /**
     *
     * <Fare_PricePNRWithBookingClassReply xmlns="http://xml.amadeus.com/TPCBRR_13_2_1A">
     * <applicationError>
     * <errorOrWarningCodeDetails>
     * <errorDetails>
     * <errorCode>00477</errorCode>
     * <errorCategory>EC</errorCategory>
     * <errorCodeOwner>1A</errorCodeOwner>
     * </errorDetails>
     * </errorOrWarningCodeDetails>
     * <errorWarningDescription>
     * <freeText>INVALID FORMAT</freeText>
     * </errorWarningDescription>
     * </applicationError>
     * </Fare_PricePNRWithBookingClassReply>
     *
     * @param SendResult $response Fare_PricePNRWithBookingClass result
     * @return Result
     * @throws Exception
     */
    protected function analyzeFarePricePNRWithBookingClassResponse($response)
    {
        $analyzeResponse = new Result($response);

        $domXpath = $this->makeDomXpath($response->responseXml);

        $queryErrorCode = "//m:applicationError//m:errorOrWarningCodeDetails/m:errorDetails/m:errorCode";
        $queryErrorCategory = "//m:applicationError//m:errorOrWarningCodeDetails/m:errorDetails/m:errorCategory";
        $queryErrorMsg = "//m:applicationError/m:errorWarningDescription/m:freeText";

        $errorCodeNodeList = $domXpath->query($queryErrorCode);

        if ($errorCodeNodeList->length > 0) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $errorCatNode = $domXpath->query($queryErrorCategory)->item(0);
            if ($errorCatNode instanceof \DOMNode) {
                $analyzeResponse->status = $this->makeStatusFromErrorQualifier($errorCatNode->nodeValue);
            }

            $analyzeResponse->messages[] = new Result\NotOk(
                $errorCodeNodeList->item(0)->nodeValue,
                $this->makeMessageFromMessagesNodeList(
                    $domXpath->query($queryErrorMsg)
                )
            );
        }

        return $analyzeResponse;
    }

    protected function analyzeFareMasterPricerTravelBoardSearchResponse($response)
    {
        $analyzeResponse = new Result($response);

        $domXpath = $this->makeDomXpath($response->responseXml);

        $queryErrCode = "//m:applicationError//m:applicationErrorDetail/m:error";
        $queryErrMsg = "//m:errorMessageText/m:description";

        $codeNode = $domXpath->query($queryErrCode)->item(0);

        if ($codeNode instanceof \DOMNode) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $errMsg = '';
            $errMsgNode = $domXpath->query($queryErrMsg)->item(0);
            if ($errMsgNode instanceof \DOMNode) {
                $errMsg = $errMsgNode->nodeValue;
            }

            $analyzeResponse->messages[] = new Result\NotOk(
                $codeNode->nodeValue,
                $errMsg
            );
        }

        return $analyzeResponse;
    }

    /**
     * @param SendResult $response
     * @return Result
     */
    protected function analyzeFareConvertCurrencyResponse($response)
    {
        return $this->analyzeSimpleResponseErrorCodeAndMessage($response);
    }

    /**
     * @param SendResult $response
     * @return Result
     */
    protected function analyzeFareCheckRulesResponse($response)
    {
        return $this->analyzeSimpleResponseErrorCodeAndMessage($response);
    }

    /**
     * @param SendResult $response
     * @return Result
     */
    protected function analyzeFareInformativePricingWithoutPNRResponse($response)
    {
        return $this->analyzeSimpleResponseErrorCodeAndMessage($response);
    }


    /**
     * @param SendResult $response
     * @return Result
     */
    protected function analyzeDocIssuanceIssueTicketResponse($response)
    {
        return $this->analyzeSimpleResponseErrorCodeAndMessageStatusCode($response);
    }

    protected function analyzeTicketCreateTSTFromPricingResponse($response)
    {
        $analyzeResponse = new Result($response);

        $domDoc = $this->loadDomDocument($response->responseXml);

        $errorCodeNode = $domDoc->getElementsByTagName("applicationErrorCode")->item(0);

        if (!is_null($errorCodeNode)) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $errorCatNode = $domDoc->getElementsByTagName("codeListQualifier")->item(0);
            if ($errorCatNode instanceof \DOMNode) {
                $analyzeResponse->status = $this->makeStatusFromErrorQualifier($errorCatNode->nodeValue);
            }

            $errorCode = $errorCodeNode->nodeValue;
            $errorTextNodeList = $domDoc->getElementsByTagName("errorFreeText");

            $analyzeResponse->messages[] = new Result\NotOk(
                $errorCode,
                $this->makeMessageFromMessagesNodeList($errorTextNodeList)
            );
        }

        return $analyzeResponse;
    }

    /**
     * @param SendResult $response
     * @return Result
     */
    protected function analyzeOfferConfirmCarOfferResponse($response)
    {
        return $this->analyzeGenericOfferResponse($response);
    }

    /**
     * @param SendResult $response
     * @return Result
     */
    protected function analyzeOfferConfirmHotelOfferResponse($response)
    {
        $analyzeResponse = new Result($response);

        $domXpath = $this->makeDomXpath($response->responseXml);

        $codeNode = $domXpath->query("//m:errorDetails/m:errorCode")->item(0);
        if ($codeNode instanceof \DOMNode) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $categoryNode = $domXpath->query("//m:errorDetails/m:errorCategory")->item(0);
            if ($categoryNode instanceof \DOMNode) {
                $analyzeResponse->status = $this->makeStatusFromErrorQualifier($categoryNode->nodeValue);
            }

            $msgNode = $domXpath->query('//m:errorDescription/m:freeText')->item(0);

            $analyzeResponse->messages[] = new Result\NotOk(
                $codeNode->nodeValue,
                trim($msgNode->nodeValue)
            );
        }

        return $analyzeResponse;
    }

    /**
     * @param SendResult $response
     * @return Result
     */
    protected function analyzeOfferConfirmAirOfferResponse($response)
    {
        return $this->analyzeGenericOfferResponse($response);
    }

    /**
     * @param SendResult $response
     * @return Result
     */
    protected function analyzeOfferVerifyOfferResponse($response)
    {
        return $this->analyzeGenericOfferResponse($response);
    }

    /**
     * @param SendResult $response
     * @return Result
     */
    protected function analyzeGenericOfferResponse($response)
    {
        $analyzeResponse = new Result($response);

        $domXpath = $this->makeDomXpath($response->responseXml);

        $msgNode = $domXpath->query('//m:errorsDescription/m:errorWarningDescription/m:freeText')->item(0);

        if ($msgNode instanceof \DOMNode) {
            if (trim($msgNode->nodeValue) === "OFFER CONFIRMED SUCCESSFULLY" || trim($msgNode->nodeValue) === "OFFER VERIFIED SUCCESSFULLY" ) {
                $analyzeResponse->messages[] = new Result\NotOk(
                    0,
                    trim($msgNode->nodeValue)
                );
                return $analyzeResponse;
            }

            $categoryNode = $domXpath->query('//m:errorDetails/m:errorCategory')->item(0);
            if ($categoryNode instanceof \DOMNode) {
                $analyzeResponse->status = $this->makeStatusFromErrorQualifier($categoryNode->nodeValue);
            }

            $codeNode = $domXpath->query('//m:errorDetails/m:errorCode')->item(0);

            $analyzeResponse->messages[] = new Result\NotOk(
                $codeNode->nodeValue,
                trim($msgNode->nodeValue)
            );
        }

        return $analyzeResponse;
    }

    protected function analyzeMiniRuleGetFromPricingRecResponse($response)
    {
        $analyzeResponse = new Result($response);

        $domXpath = $this->makeDomXpath($response->responseXml);

        $statusNode = $domXpath->query('//m:responseDetails/m:statusCode')->item(0);
        if ($statusNode instanceof \DOMNode) {
            $code = $statusNode->nodeValue;

            if ($code !== 'O') {
                $categoryNode = $domXpath->query('//m:errorOrWarningCodeDetails/m:errorDetails/m:errorCategory')->item(0);
                $analyzeResponse->status = $this->makeStatusFromErrorQualifier($categoryNode->nodeValue);

                $codeNode = $domXpath->query('//m:errorOrWarningCodeDetails/m:errorDetails/m:errorCode')->item(0);
                $msgNode = $domXpath->query('//m:errorWarningDescription/m:freeText')->item(0);

                if ($codeNode instanceof \DOMNode && $msgNode instanceof \DOMNode) {
                    $analyzeResponse->messages[] = new Result\NotOk(
                        $codeNode->nodeValue,
                        $msgNode->nodeValue
                    );
                }
            }
        }

        return $analyzeResponse;
    }

    /**
     * @param SendResult $response
     * @return Result
     */
    protected function analyzeInfoEncodeDecodeCityResponse($response)
    {
        return $this->analyzeSimpleResponseErrorCodeAndMessage($response);
    }

    /**
     * @param SendResult $response
     * @return Result
     */
    protected function analyzePriceXplorerExtremeSearchResponse($response)
    {
        return $this->analyzeSimpleResponseErrorCodeAndMessage($response);
    }

    /**
     * @param SendResult $response WebService message Send Result
     * @return Result
     * @throws Exception
     */
    protected function analyzeSimpleResponseErrorCodeAndMessage($response)
    {
        $analyzeResponse = new Result($response);

        $domDoc = $this->loadDomDocument($response->responseXml);

        $errorCodeNode = $domDoc->getElementsByTagName("errorCode")->item(0);

        if (!is_null($errorCodeNode)) {
            $errorCatNode = $domDoc->getElementsByTagName("errorCategory")->item(0);
            if ($errorCatNode instanceof \DOMNode) {
                $analyzeResponse->status = $this->makeStatusFromErrorQualifier($errorCatNode->nodeValue);
            } else {
                $analyzeResponse->status = Result::STATUS_ERROR;
            }

            $errorCode = $errorCodeNode->nodeValue;
            $errorTextNodeList = $domDoc->getElementsByTagName("freeText");

            $analyzeResponse->messages[] = new Result\NotOk(
                $errorCode,
                $this->makeMessageFromMessagesNodeList($errorTextNodeList)
            );
        }

        return $analyzeResponse;
    }

    /**
     * @param SendResult $response WebService message Send Result
     * @return Result
     * @throws Exception
     */
    protected function analyzeSimpleResponseErrorCodeAndMessageStatusCode($response)
    {
        $analyzeResponse = new Result($response);

        $domDoc = $this->loadDomDocument($response->responseXml);

        $errorCodeNode = $domDoc->getElementsByTagName("errorCode")->item(0);

        if (!is_null($errorCodeNode)) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $errorCatNode = $domDoc->getElementsByTagName("statusCode")->item(0);
            if ($errorCatNode instanceof \DOMNode) {
                $analyzeResponse->status = $this->makeStatusFromErrorQualifier($errorCatNode->nodeValue);
            }

            $errorCode = $errorCodeNode->nodeValue;
            $errorTextNodeList = $domDoc->getElementsByTagName("freeText");

            $analyzeResponse->messages[] = new Result\NotOk(
                $errorCode,
                $this->makeMessageFromMessagesNodeList($errorTextNodeList)
            );
        }

        return $analyzeResponse;
    }

    /**
     * Returns the errortext from a Queue_*Reply errorcode
     *
     * This function is necessary because the core only responds
     * with errorcode but does not send an errortext.
     *
     * The errorcodes for all Queue_*Reply messages are the same.
     *
     * @link https://webservices.amadeus.com/extranet/viewArea.do?id=10
     * @param string $errorCode
     * @return string the errortext for this errorcode.
     */
    protected function getErrorTextFromQueueErrorCode($errorCode)
    {
        $recognizedErrors = [
            '723' => "Invalid category",
            '911' => "Unable to process - system error",
            '913' => "Item/data not found or data not existing in processing host",
            '79D' => "Queue identifier has not been assigned for specified office identification",
            '91C' => "invalid record locator",
            '91F' => "Invalid queue number",
            '921' => "target not specified",
            '922' => "Targetted queue has wrong queue type",
            '926' => "Queue category empty",
            '928' => "Queue category not assigned",
            '92A' => "Queue category full",
        ];

        $errorMessage = (array_key_exists($errorCode, $recognizedErrors)) ? $recognizedErrors[$errorCode] : '';

        if ($errorMessage === '') {
            $errorMessage = "QUEUE ERROR '" . $errorCode . "' (Error message unavailable)";
        }

        return $errorMessage;
    }

    /**
     * @param string $response
     * @return \DOMDocument
     * @throws Exception when there's a problem loading the message
     */
    protected function loadDomDocument($response)
    {
        $domDoc = new \DOMDocument('1.0', 'UTF-8');

        $loadResult = $domDoc->loadXML($response);
        if ($loadResult === false) {
            throw new Exception('Could not load response message into DOMDocument');
        }

        return $domDoc;
    }

    /**
     * @param $qualifier
     * @return string Result::STATUS_*
     */
    protected function makeStatusFromErrorQualifier($qualifier)
    {
        $status = null;

        switch ($qualifier) {
            case 'INF':
                $status = Result::STATUS_INFO;
                break;
            case 'WEC':
            case 'WZZ': //Mutually defined warning
            case 'W':
                $status = Result::STATUS_WARN;
                break;
            case 'EC':
            case 'X':
                $status = Result::STATUS_ERROR;
                break;
            case 'O':
                $status = Result::STATUS_OK;
                break;
            case 'ZZZ': //Mutually defined
            default:
                $status = Result::STATUS_UNKNOWN;
                break;
        }

        return $status;
    }


    /**
     * Make a Xpath-queryable object for an XML string
     *
     * registers TNS namespace with prefix self::XMLNS_PREFIX
     *
     * @param string $response
     * @return \DOMXPath
     * @throws Exception when there's a problem loading the message
     */
    protected function makeDomXpath($response)
    {
        $domDoc = $this->loadDomDocument($response);
        $domXpath = new \DOMXPath($domDoc);

        $domXpath->registerNamespace(
            self::XMLNS_PREFIX,
            $domDoc->documentElement->lookupNamespaceUri(null)
        );

        return $domXpath;
    }

    /**
     * Convert a DomNodeList of nodes containing a (potentially partial) error message into a string.
     *
     * @param \DOMNodeList $errorTextNodeList
     * @return string|null
     */
    protected function makeMessageFromMessagesNodeList($errorTextNodeList)
    {
        return implode(
            ' - ',
            array_map(
                function($item) {
                    return trim($item->nodeValue);
                },
                iterator_to_array($errorTextNodeList)
            )
        );
    }

    /**
     * @param SendResult $sendResult
     * @return Result
     */
    protected function makeResultForException($sendResult)
    {
        $result = new Result($sendResult, Result::STATUS_FATAL);

        $result->messages[] = $this->makeMessageFromException($sendResult->exception);

        return $result;
    }

    /**
     * @param \Exception $exception
     * @return Result\NotOk
     * @throws Exception
     */
    protected function makeMessageFromException(\Exception $exception)
    {
        $message = new Result\NotOk();

        if ($exception instanceof \SoapFault) {
            $info = explode('|', $exception->getMessage());
            $message->code = $info[0];
            if (count($info) === 3) {
                $message->level = $info[1];
                $message->text = $info[2];
            }
        }

        return $message;
    }
}
