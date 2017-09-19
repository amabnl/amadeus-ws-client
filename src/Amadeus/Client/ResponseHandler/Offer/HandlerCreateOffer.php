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

namespace Amadeus\Client\ResponseHandler\Offer;

use Amadeus\Client\ResponseHandler\StandardResponseHandler;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * HandlerCreateOffer
 *
 * @package Amadeus\Client\ResponseHandler\Offer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class HandlerCreateOffer extends StandardResponseHandler
{
    /**
     * @param SendResult $response
     * @return Result
     */
    public function analyze(SendResult $response)
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
}
