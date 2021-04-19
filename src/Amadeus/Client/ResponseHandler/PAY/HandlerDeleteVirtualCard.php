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

namespace Amadeus\Client\ResponseHandler\PAY;

use Amadeus\Client\Exception;
use Amadeus\Client\ResponseHandler\StandardResponseHandler;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * HandlerDeleteVirtualCard
 *
 * @package Amadeus\Client\ResponseHandler\PAY
 * @author Konstantin Bogomolov <bog.konstantin@gmail.com>
 */
class HandlerDeleteVirtualCard extends StandardResponseHandler
{
    /**
     * Analyze the result from the message operation and check for any error messages
     *
     * @param SendResult $response
     * @return Result
     * @throws Exception
     */
    public function analyze(SendResult $response)
    {
        $analyzeResponse = new Result($response);

        $code = null;
        $message = null;

        $domXpath = $this->makeDomXpath($response->responseXml);

        $categoryNodes = $domXpath->query('//ama:Errors/ama:Error/@Type');
        if ($categoryNodes->length > 0) {
            $analyzeResponse->status = $this->makeStatusFromErrorQualifier($categoryNodes->item(0)->nodeValue);
        }

        $codeNodes = $domXpath->query('//ama:Errors/ama:Error/@Code');
        if ($codeNodes->length > 0) {
            $code = $codeNodes->item(0)->nodeValue;
        }

        $messageNodes = $domXpath->query('//ama:Errors/ama:Error/@ShortText');
        if ($messageNodes->length > 0) {
            $message = $this->makeMessageFromMessagesNodeList($messageNodes);
        }

        if (!is_null($message) && !is_null($code)) {
            $analyzeResponse->messages[] = new Result\NotOk($code, $message);
        }

        return $analyzeResponse;
    }
}
