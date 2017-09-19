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
 * The standard response handler for an individual message. Includes some basic functionality to build on.
 *
 * @package Amadeus\Client\ResponseHandler
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
abstract class StandardResponseHandler implements MessageResponseHandler
{
    /**
     * Default namespace prefix we'll be using for xpath queries
     *
     * Why not "m"? It's as good as any other letter.
     */
    const XMLNS_PREFIX = "m";

    /**
     * Analyze response by looking for error, category and message with the provided XPATH queries
     *
     * xpath queries must be prefixed with the namespace self::XMLNS_PREFIX
     *
     * @param SendResult $response
     * @param string $qErr XPATH query for fetching error code (first node is used)
     * @param string $qCat XPATH query for fetching error category (first node is used)
     * @param string $qMsg XPATH query for fetching error messages (all nodes are used)
     * @param string|null $errLevel Optional custom error level string.
     * @return Result
     */
    protected function analyzeWithErrCodeCategoryMsgQuery(SendResult $response, $qErr, $qCat, $qMsg, $errLevel = null)
    {
        $analyzeResponse = new Result($response);

        $domXpath = $this->makeDomXpath($response->responseXml);

        $errorCodeNodeList = $domXpath->query($qErr);

        if ($errorCodeNodeList->length > 0) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $errorCatNode = $domXpath->query($qCat)->item(0);
            if ($errorCatNode instanceof \DOMNode) {
                $analyzeResponse->status = $this->makeStatusFromErrorQualifier($errorCatNode->nodeValue);
            }

            $analyzeResponse->messages[] = new Result\NotOk(
                $errorCodeNodeList->item(0)->nodeValue,
                $this->makeMessageFromMessagesNodeList(
                    $domXpath->query($qMsg)
                ),
                $errLevel
            );
        }

        return $analyzeResponse;
    }

    /**
     * Analyze response by looking for error, message and level with the provided XPATH queries
     *
     * Result status defaults to Result::STATUS_ERROR if any error is found.
     *
     * xpath queries must be prefixed with the namespace self::XMLNS_PREFIX
     *
     * @param SendResult $response
     * @param string $qErr XPATH query for fetching error code (first node is used)
     * @param string $qMsg XPATH query for fetching error messages (all nodes are used)
     * @param string $qLvl  XPATH query for fetching error level (first node is used)
     * @param array $lvlToText Level-to-text translation
     * @return Result
     */
    protected function analyzeWithErrorCodeMsgQueryLevel(SendResult $response, $qErr, $qMsg, $qLvl, $lvlToText)
    {
        $analyzeResponse = new Result($response);

        $domXpath = $this->makeDomXpath($response->responseXml);

        $errorCodeNodeList = $domXpath->query($qErr);

        if ($errorCodeNodeList->length > 0) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $lvlNodeList = $domXpath->query($qLvl);

            $level = null;
            if ($lvlNodeList->length > 0) {
                if (array_key_exists($lvlNodeList->item(0)->nodeValue, $lvlToText)) {
                    $level = $lvlToText[$lvlNodeList->item(0)->nodeValue];
                }
            }

            $analyzeResponse->messages[] = new Result\NotOk(
                $errorCodeNodeList->item(0)->nodeValue,
                $this->makeMessageFromMessagesNodeList(
                    $domXpath->query($qMsg)
                ),
                $level
            );
        }

        return $analyzeResponse;
    }

    /**
     * Analyse with XPATH queries for error code and message, provide fixed category
     *
     * @param SendResult $response
     * @param string $qErr XPATH query for fetching error code (first node is used)
     * @param string $qMsg XPATH query for fetching error messages (all nodes are used)
     * @param string $category Result::STATUS_* The fixed error category (status)
     * @return Result
     */
    public function analyzeWithErrCodeAndMsgQueryFixedCat(SendResult $response, $qErr, $qMsg, $category)
    {
        $analyzeResponse = new Result($response);

        $domXpath = $this->makeDomXpath($response->responseXml);

        $errorCodeNodeList = $domXpath->query($qErr);
        $errorMsgNodeList = $domXpath->query($qMsg);

        if ($errorCodeNodeList->length > 0 || $errorMsgNodeList->length > 0) {
            $analyzeResponse->status = $category;

            $errorCode = ($errorCodeNodeList->length > 0) ? $errorCodeNodeList->item(0)->nodeValue : null;

            $analyzeResponse->messages[] = new Result\NotOk(
                $errorCode,
                $this->makeMessageFromMessagesNodeList($errorMsgNodeList)
            );
        }

        return $analyzeResponse;
    }

    /**
     * Analyze response by looking for error, category and message in nodes specified by name
     *
     * @param SendResult $response
     * @param string $nodeErr Node name of the node containing the error code (first node is used)
     * @param string $nodeCat Node name of the node containing the error category (first node is used)
     * @param string $nodeMsg Node name of the node containing the error messages (all nodes are used)
     * @return Result
     */
    protected function analyzeWithErrCodeCategoryMsgNodeName(SendResult $response, $nodeErr, $nodeCat, $nodeMsg)
    {
        $analyzeResponse = new Result($response);

        $domDoc = $this->loadDomDocument($response->responseXml);

        $errorCodeNode = $domDoc->getElementsByTagName($nodeErr)->item(0);

        if (!is_null($errorCodeNode)) {
            $errorCatNode = $domDoc->getElementsByTagName($nodeCat)->item(0);
            if ($errorCatNode instanceof \DOMNode) {
                $analyzeResponse->status = $this->makeStatusFromErrorQualifier($errorCatNode->nodeValue);
            } else {
                $analyzeResponse->status = Result::STATUS_ERROR;
            }

            $errorCode = $errorCodeNode->nodeValue;
            $errorTextNodeList = $domDoc->getElementsByTagName($nodeMsg);

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
    protected function analyzeSimpleResponseErrorCodeAndMessage($response)
    {
        return $this->analyzeWithErrCodeCategoryMsgNodeName(
            $response,
            "errorCode",
            "errorCategory",
            "freeText"
        );
    }

    /**
     * @param SendResult $response WebService message Send Result
     * @return Result
     * @throws Exception
     */
    protected function analyzeSimpleResponseErrorCodeAndMessageStatusCode($response)
    {
        return $this->analyzeWithErrCodeCategoryMsgNodeName(
            $response,
            "errorCode",
            "statusCode",
            "freeText"
        );
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
     * Converts a status code found in an error message to the appropriate status level
     *
     * if no node found (= $qualifier is a null), $defaultStatus will be used
     *
     * @param string|null $qualifier
     * @param string $defaultStatus the default status to fall back to if no qualifier is present
     * @return string Result::STATUS_*
     */
    protected function makeStatusFromErrorQualifier($qualifier, $defaultStatus = Result::STATUS_ERROR)
    {
        $statusQualMapping = [
            'INF' => Result::STATUS_INFO,
            'WEC' => Result::STATUS_WARN,
            'WZZ' => Result::STATUS_WARN, //Mutually defined warning
            'WA' => Result::STATUS_WARN, //Info line Warning - PNR_AddMultiElements
            'W' => Result::STATUS_WARN,
            'EC' => Result::STATUS_ERROR,
            'ERR' => Result::STATUS_ERROR, //DocRefund_UpdateRefund
            'ERC' => Result::STATUS_ERROR, //DocRefund_UpdateRefund
            'X' => Result::STATUS_ERROR,
            '001' => Result::STATUS_ERROR, //Air_MultiAvailability
            'O' => Result::STATUS_OK,
            'STA' => Result::STATUS_OK,
            'ZZZ' => Result::STATUS_UNKNOWN
        ];

        if (array_key_exists($qualifier, $statusQualMapping)) {
            $status = $statusQualMapping[$qualifier];
        } elseif (is_null($qualifier)) {
            $status = $defaultStatus;
        } else {
            $status = Result::STATUS_UNKNOWN;
        }

        return $status;
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
}
