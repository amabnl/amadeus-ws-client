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

namespace Amadeus\Client\ResponseHandler\Fare;

use Amadeus\Client\ResponseHandler\StandardResponseHandler;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * HandlerMasterPricerTravelBoardSearch
 *
 * @package Amadeus\Client\ResponseHandler\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class HandlerMasterPricerTravelBoardSearch extends StandardResponseHandler
{
    /**
     * @param SendResult $response
     * @return Result
     */
    public function analyze(SendResult $response)
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
}
