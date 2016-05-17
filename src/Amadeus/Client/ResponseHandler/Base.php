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
 * Base Response Handler
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
        $methodName = 'analyze' . str_replace('_', '', ucfirst($messageName)).'Response';

        if (method_exists($this, $methodName)) {
            return $this->$methodName(
                $sendResult
            );
        } else {
            return new Result($sendResult, Result::STATUS_UNKNOWN);
        }
    }

    /**
     * @param SendResult $response PNR_AddMultiElements result
     * @return Result
     */
    protected function analyzePnrAddMultiElementsResponse($response)
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

            $analyzeResponse->errors[] = new Result\NotOk($code, $message, 'general');
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

            $analyzeResponse->errors[] = new Result\NotOk($code, $message, 'segment');
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

            $analyzeResponse->errors[] = new Result\NotOk($code, $message, 'element');
        }


        return $analyzeResponse;
    }

    /**
     * @param SendResult $response Queue_List result
     * @return Result
     * @throws Exception
     */
    protected function analyzeQueueListResponse($response)
    {
        $analysisResponse = new Result($response);

        $domDoc = new \DOMDocument('1.0', 'UTF-8');
        $domDoc->loadXML($response->responseXml);

        $errorCodeNode = $domDoc->getElementsByTagName("errorCode")->item(0);

        if (!is_null($errorCodeNode)) {
            $analysisResponse->status = Result::STATUS_WARN;

            $errorCode = $errorCodeNode->nodeValue;
            $errorMessage = $this->getErrorTextFromQueueErrorCode($errorCode);

            $analysisResponse->warnings[] = new Result\NotOk($errorCode, $errorMessage);
        }

        return $analysisResponse;
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
     * Make a Xpath-queryable object for an XML string
     *
     * @param string $response
     * @return \DOMXPath
     * @throws Exception when there's a problem loading the message
     */
    protected function makeDomXpath($response)
    {
        $domDoc = new \DOMDocument('1.0', 'UTF-8');
        $domXpath = null;
        $loadResult = $domDoc->loadXML($response);

        if ($loadResult === true) {
            $uri = $domDoc->documentElement->lookupNamespaceUri(null);

            $domXpath = new \DOMXPath($domDoc);
            $domXpath->registerNamespace(self::XMLNS_PREFIX, $uri);
        } else {
            throw new Exception('Could not load response message into DOMDocument');
        }

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
                    return $item->nodeValue;
                },
                iterator_to_array($errorTextNodeList)
            )
        );
    }
}
