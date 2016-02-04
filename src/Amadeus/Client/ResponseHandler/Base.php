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
use Amadeus\Client\Util\MsgBodyExtractor;

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
     * @param string $response The last response received by the client
     * @param string $messageName The message that was called
     *
     * @throws Exception
     * @throws \RuntimeException
     * @return bool
     */
    public function analyzeResponse($response, $messageName)
    {
        $methodName = 'analyze' . str_replace('_', '', ucfirst($messageName)).'Response';

        if (method_exists($this, $methodName)) {
            return $this->$methodName(
                MsgBodyExtractor::extract($response)
            );
        } else {
            throw new \RuntimeException('Response checker for ' . $messageName . ' is not implemented');
        }
    }

    /**
     * @param string $response Queue_List XML string
     * @return bool
     * @throws Exception
     */
    protected function analyzeQueueListResponse($response)
    {
        $analysisResponse = true;

        $domDoc = new \DOMDocument('1.0', 'UTF-8');
        $domDoc->loadXML($response);

        $errorCodeNode = $domDoc->getElementsByTagName("errorCode")->item(0);

        if (!is_null($errorCodeNode)) {
            $errorCode = $errorCodeNode->nodeValue;
            $errorMessage = $this->getErrorTextFromQueueErrorCode($errorCode);

            throw new Exception($errorMessage, $errorCode);
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
            $errorMessage = " QUEUE ERROR '" . $errorCode . "' (Error message unavailable)";
        }

        return $errorMessage;
    }


    /**
     * @param string $response
     * @return \DOMXPath
     */
    protected function makeDomXpath($response)
    {
        $domDoc = new \DOMDocument('1.0', 'UTF-8');
        $domDoc->loadXML($response);
        $uri = $domDoc->documentElement->lookupnamespaceURI(null);

        $domXpath = new \DOMXPath($domDoc);
        $domXpath->registerNamespace(self::XMLNS_PREFIX, $uri);

        return $domXpath;
    }
}
