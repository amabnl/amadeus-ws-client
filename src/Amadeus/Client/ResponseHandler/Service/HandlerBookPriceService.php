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

namespace Amadeus\Client\ResponseHandler\Service;

use Amadeus\Client\ResponseHandler\StandardResponseHandler;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * HandlerBookPriceService
 *
 * @package Amadeus\Client\ResponseHandler\Service
 * @author Mike Hernas <m@hern.as>
 */
class HandlerBookPriceService extends StandardResponseHandler
{
    /**
     * @param SendResult $response
     * @return Result
     */
    public function analyze(SendResult $response)
    {
        $analyzeResponse = new Result($response);
        $domXpath = $this->makeDomXpath($response->responseXml);

        $errorCodeNodeList = $domXpath->query("//ama:Error");

        if ($errorCodeNodeList->length > 0) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            foreach (iterator_to_array($errorCodeNodeList) as $msg) {
                $analyzeResponse->messages[] = new Result\NotOk(
                    $msg->getAttribute('Code'),
                    trim($msg->getAttribute('ShortText'))
                );
            }

            return $analyzeResponse;
        }

        $success = $domXpath->query('//m:Success');
        if ($success->length > 0) {
            $analyzeResponse->status = Result::STATUS_OK;
            return $analyzeResponse;
        }

        $analyzeResponse->status = Result::STATUS_UNKNOWN;
        return $analyzeResponse;
    }
}
