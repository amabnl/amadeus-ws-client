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

namespace Amadeus\Client;

use Amadeus\Client\Result\NotOk;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * Result object to encapsulate the Web Services response to library users.
 *
 * @package Amadeus\Client
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Result
{
    /**
     * Status indicator for a success situation
     */
    const STATUS_OK = 'OK';
    /**
     * Status indicator for an informational message situation.
     */
    const STATUS_INFO = 'INFO';
    /**
     * Status indicator for a warning situation.
     */
    const STATUS_WARN = 'WARN';
    /**
     * Status indicator for an error response.
     */
    const STATUS_ERROR = 'ERR';
    /**
     * Status indicator for a FATAL error response.
     */
    const STATUS_FATAL = 'FATAL';
    /**
     * Status indicator for a response which could not be checked for warnings/errors.
     */
    const STATUS_UNKNOWN = 'UNKNOWN';

    /**
     * Status of the result
     *
     * see self::STATUS_*
     *
     * @var string
     */
    public $status;

    /**
     * Array of errors or warnings found
     *
     * @var NotOk[]
     */
    public $messages = [];

    /**
     * The actual result received after performing the web service call.
     *
     * @var \stdClass|array
     */
    public $response;

    /**
     * The raw contents of the Soap Envelope received after performing the web service call.
     *
     * @var string
     */
    public $responseXml;

    /**
     * Result constructor.
     *
     * @param SendResult $sendResult
     * @param string $status
     */
    public function __construct($sendResult, $status = self::STATUS_OK)
    {
        $this->response = $sendResult->responseObject;
        $this->responseXml = $sendResult->responseXml;
        $this->status = $status;
    }

    /**
     * Sets error status.
     *
     * Will not override a more severe status.
     *
     * @param string $newStatus
     */
    public function setStatus($newStatus)
    {
        if ($this->isWorseStatus($newStatus, $this->status)) {
            $this->status = $newStatus;
        }
    }

    /**
     * Checks if new status is worse than current status
     *
     * @param string $newStatus
     * @param string $currentStatus
     * @return bool true if newStatus is worse than old status.
     */
    protected function isWorseStatus($newStatus, $currentStatus)
    {
        $levels = [
            self::STATUS_UNKNOWN => -1,
            self::STATUS_OK => 0,
            self::STATUS_INFO => 2,
            self::STATUS_WARN => 5,
            self::STATUS_ERROR => 10,
            self::STATUS_FATAL => 20,
        ];

        return ($currentStatus === null || $levels[$newStatus] > $levels[$currentStatus]);
    }
}
