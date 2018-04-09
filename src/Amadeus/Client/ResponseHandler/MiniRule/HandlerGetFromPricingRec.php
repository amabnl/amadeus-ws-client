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

namespace Amadeus\Client\ResponseHandler\MiniRule;

use Amadeus\Client\ResponseHandler\StandardResponseHandler;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * HandlerGetFromPricingRec
 *
 * @package Amadeus\Client\ResponseHandler\MiniRule
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class HandlerGetFromPricingRec extends StandardResponseHandler
{
    /**
     * @param SendResult $response
     * @return Result
     */
    public function analyze(SendResult $response)
    {
        $analyzeResponse = new Result($response);

        $domXpath = $this->makeDomXpath($response->responseXml);

        $statusNode = $domXpath->query('//m:responseDetails/m:statusCode')->item(0);
        if ($statusNode instanceof \DOMNode) {
            $code = $statusNode->nodeValue;

            if ($code !== 'O') {
                $categoryNode = $domXpath->query(
                    '//m:errorOrWarningCodeDetails/m:errorDetails/m:errorCategory'
                )->item(0);
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
}
