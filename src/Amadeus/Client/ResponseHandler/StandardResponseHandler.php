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
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
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
     * @param string $qualifier
     * @return string Result::STATUS_*
     */
    protected function makeStatusFromErrorQualifier($qualifier)
    {
        $statusQualMapping = [
            'INF' => Result::STATUS_INFO,
            'WEC' => Result::STATUS_WARN,
            'WZZ' => Result::STATUS_WARN, //Mutually defined warning
            'WA' => Result::STATUS_WARN, //Info line Warning - PNR_AddMultiElements
            'W' => Result::STATUS_WARN,
            'EC' => Result::STATUS_ERROR,
            'X' => Result::STATUS_ERROR,
            '001' => Result::STATUS_ERROR, //Air_MultiAvailability
            'O' => Result::STATUS_OK,
            'ZZZ' => Result::STATUS_UNKNOWN
        ];

        if (array_key_exists($qualifier, $statusQualMapping)) {
            $status = $statusQualMapping[$qualifier];
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
