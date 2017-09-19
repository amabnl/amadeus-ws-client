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

namespace Amadeus\Client\ResponseHandler\PNR;

use Amadeus\Client\ResponseHandler\StandardResponseHandler;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * HandlerNameChange
 *
 * @package Amadeus\Client\ResponseHandler\PNR
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class HandlerNameChange extends StandardResponseHandler
{
    /**
     * Analysing a PNR_NameChangeReply
     *
     * @param SendResult $response
     * @return Result
     */
    public function analyze(SendResult $response)
    {
        $analyzeResponse = new Result($response);

        $domXpath = $this->makeDomXpath($response->responseXml);

        $qPassErrors = "//m:passengerErrorInEnhancedData//m:errorDetails/m:errorCode";
        $qPassErrorCat = "//m:passengerErrorInEnhancedData//m:errorDetails/m:errorCategory";
        $qPassErrorMsg = "//m:passengerErrorInEnhancedData//m:freeText";

        $errorCodeNodeList = $domXpath->query($qPassErrors);

        if ($errorCodeNodeList->length > 0) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $errorCatNode = $domXpath->query($qPassErrorCat)->item(0);
            if ($errorCatNode instanceof \DOMNode) {
                $analyzeResponse->status = $this->makeStatusFromErrorQualifier($errorCatNode->nodeValue);
            }

            $code = $errorCodeNodeList->item(0)->nodeValue;
            $errorTextNodeList = $domXpath->query($qPassErrorMsg);
            $message = $this->makeMessageFromMessagesNodeList($errorTextNodeList);

            $analyzeResponse->messages[] = new Result\NotOk($code, trim($message), 'passenger');
        }

        if (empty($analyzeResponse->messages) && $analyzeResponse->status === Result::STATUS_OK) {
            $analyzeResponse = $this->analyzeSimpleResponseErrorCodeAndMessage($response);
        }

        return $analyzeResponse;
    }
}
