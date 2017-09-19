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
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * Default Response Handler
 *
 * Analyses the responses received from the Amadeus WS server and checks for error messages.
 * If errors are found, the error information will be extracted and the response status will be changed
 * accordingly.
 *
 * @package Amadeus\Client\ResponseHandler
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Base implements ResponseHandlerInterface
{
    /**
     * All response handlers already instantiated
     *
     * @var array
     */
    protected $responseHandlers = [];

    /**
     * Analyze the response from the server and throw an exception when an error has been detected.
     *
     * @param SendResult $sendResult The Send Result from the Session Handler
     * @param string $messageName The message that was called
     *
     * @throws Exception
     * @throws \RuntimeException
     * @return Result
     */
    public function analyzeResponse($sendResult, $messageName)
    {
        if (!empty($sendResult->exception)) {
            return $this->makeResultForException($sendResult);
        }

        $handler = $this->findHandlerForMessage($messageName);

        if ($handler instanceof MessageResponseHandler) {
            return $handler->analyze($sendResult);
        } else {
            return new Result($sendResult, Result::STATUS_UNKNOWN);
        }
    }

    /**
     * @param SendResult $sendResult
     * @return Result
     */
    protected function makeResultForException($sendResult)
    {
        $result = new Result($sendResult, Result::STATUS_FATAL);

        $result->messages[] = $this->makeMessageFromException($sendResult->exception);

        return $result;
    }

    /**
     * @param \Exception $exception
     * @return Result\NotOk
     * @throws Exception
     */
    protected function makeMessageFromException(\Exception $exception)
    {
        $message = new Result\NotOk();

        if ($exception instanceof \SoapFault) {
            $info = explode('|', $exception->getMessage());
            $message->code = $info[0];
            if (count($info) === 3) {
                $message->level = $info[1];
                $message->text = $info[2];
            }
        }

        return $message;
    }

    /**
     * Find or create the correct handler object for a given message
     *
     * @param string $messageName
     * @return MessageResponseHandler|null
     */
    private function findHandlerForMessage($messageName)
    {
        $handler = null;

        if (array_key_exists($messageName, $this->responseHandlers) &&
            $this->responseHandlers[$messageName] instanceof MessageResponseHandler
        ) {
            $handler = $this->responseHandlers[$messageName];
        } else {
            $section = substr($messageName, 0, strpos($messageName, '_'));
            $message = substr($messageName, strpos($messageName, '_') + 1);

            $handlerClass = __NAMESPACE__.'\\'.$section.'\\Handler'.$message;

            if (class_exists($handlerClass)) {
                /** @var MessageResponseHandler $handler */
                $handler = new $handlerClass();

                $this->responseHandlers[$messageName] = $handler;
            }
        }

        return $handler;
    }
}
