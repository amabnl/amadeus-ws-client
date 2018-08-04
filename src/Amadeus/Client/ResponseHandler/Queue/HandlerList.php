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

use Amadeus\Client\ResponseHandler\StandardResponseHandler;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * HandlerList
 *
 * @package Amadeus\Client\ResponseHandler\Queue
 * @author Dieter Devlieghere <dermikagh@gmail.com>
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
            '1' => 'Invalid date',
            '360' => 'Invalid PNR file address',
            '723' => 'Invalid category',
            '727' => 'Invalid amount',
            '79A' => 'Invalid office identification',
            '79B' => 'Already working another queue',
            '79C' => 'Not allowed to access queues for specified office identification',
            '79D' => 'Queue identifier has not been assigned for specified office identification',
            '79E' => 'Attempting to perform a queue function when not associated with a queue',
            '79F' => 'Queue placement or add new queue item is not allowed for the specified office and queue',
            '911' => 'Unable to process - system error',
            '912' => 'Incomplete message - data missing in query',
            '913' => 'Item/data not found or data not existing in processing host',
            '914' => 'Invalid format/data - data does not match EDIFACT rules',
            '915' => 'No action - processing host cannot support function',
            '916' => 'EDIFACT version not supported',
            '917' => 'EDIFACT message size exceeded',
            '918' => 'Enter message in remarks',
            '919' => 'No PNR in AAA',
            '91A' => 'Inactive queue bank',
            '91B' => 'Nickname not found',
            '91C' => 'Invalid record locator',
            '91D' => 'Invalid format',
            '91F' => 'Invalid queue number',
            '920' => 'Queue/date range empty',
            '921' => 'Target not specified',
            '922' => 'Targetted queue has wrong queue type',
            '923' => 'Invalid time',
            '924' => 'Invalid date range',
            '925' => 'Queue number not specified',
            '926' => 'Queue category empty',
            '927' => 'No items exist',
            '928' => 'Queue category not assigned',
            '929' => 'No more items',
            '92A' => '>ueue category full'
        ];

        $errorMessage = (array_key_exists($errorCode, $recognizedErrors)) ? $recognizedErrors[$errorCode] : '';

        if ($errorMessage === '') {
            $errorMessage = "QUEUE ERROR '".$errorCode."' (Error message unavailable)";
        }

        return $errorMessage;
    }
}
