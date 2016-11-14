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

namespace Amadeus\Client\ResponseHandler\Queue;

use Amadeus\Client\Exception;
use Amadeus\Client\ResponseHandler\StandardResponseHandler;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * HandlerList
 *
 * @package Amadeus\Client\ResponseHandler\Queue
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class HandlerList extends StandardResponseHandler
{
    public function analyze(SendResult $response)
    {
        $analysisResponse = new Result($response);

        $domDoc = $this->loadDomDocument($response->responseXml);

        $errorCodeNode = $domDoc->getElementsByTagName("errorCode")->item(0);

        if (!is_null($errorCodeNode)) {
            $analysisResponse->status = Result::STATUS_WARN;

            $errorCode = $errorCodeNode->nodeValue;
            $errorMessage = $this->getErrorTextFromQueueErrorCode($errorCode);

            $analysisResponse->messages[] = new Result\NotOk($errorCode, $errorMessage);
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
            $errorMessage = "QUEUE ERROR '".$errorCode."' (Error message unavailable)";
        }

        return $errorMessage;
    }
}
