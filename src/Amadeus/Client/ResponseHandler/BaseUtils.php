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
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * BaseUtils
 *
 * Provides utility functions for the Base Response Handler.
 *
 * @package Amadeus\Client\ResponseHandler
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
abstract class BaseUtils implements ResponseHandlerInterface
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
    abstract public function analyzeResponse($sendResult, $messageName);

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
     * @param SendResult $response
     * @return Result
     */
    protected function analyzeGenericOfferResponse($response)
    {
        $analyzeResponse = new Result($response);

        $domXpath = $this->makeDomXpath($response->responseXml);

        $msgNode = $domXpath->query('//m:errorsDescription/m:errorWarningDescription/m:freeText')->item(0);

        if ($msgNode instanceof \DOMNode) {
            if (trim($msgNode->nodeValue) === "OFFER CONFIRMED SUCCESSFULLY" ||
                trim($msgNode->nodeValue) === "OFFER VERIFIED SUCCESSFULLY"
            ) {
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
     * @param string $qualifier
     * @return string Result::STATUS_*
     */
    protected function makeStatusFromErrorQualifier($qualifier)
    {
        switch ($qualifier) {
            case 'INF':
                $status = Result::STATUS_INFO;
                break;
            case 'WEC':
            case 'WZZ': //Mutually defined warning
            case 'WA': //Info line Warning - PNR_AddMultiElements
            case 'W':
                $status = Result::STATUS_WARN;
                break;
            case 'EC':
            case 'X':
            case '001': //Air_MultiAvailability
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
                function ($item) {
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
