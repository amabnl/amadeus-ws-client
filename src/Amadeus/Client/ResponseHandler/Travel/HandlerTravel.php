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

namespace Amadeus\Client\ResponseHandler\Travel;

use Amadeus\Client\ResponseHandler\StandardResponseHandler;
use Amadeus\Client\Result;
use Amadeus\Client\Result\NotOk;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * HandlerTravel
 *
 * @package Amadeus\Client\ResponseHandler\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class HandlerTravel extends StandardResponseHandler
{
    public function analyze(SendResult $response)
    {
        $result = $this->analyzeSimpleResponseErrorCodeAndMessageStatusCode($response);

        $error = isset($response->responseObject->Error) ? $response->responseObject->Error : null;
        $rawErrors = [];

        if ($error === null) {
            $rawErrors = isset($response->responseObject->Errors->Error)
                ? $response->responseObject->Errors->Error
                : [];
            if (!is_array($rawErrors)) {
                $rawErrors = [$rawErrors];
            }
        }

        if (is_array($error)) {
            $rawErrors = array_merge($rawErrors, $error);
        } elseif ($error !== null) {
            $rawErrors[] = $error;
        }

        foreach ($rawErrors as $rawError) {
            $result->setStatus(Result::STATUS_ERROR);
            $result->messages[] = new NotOk(
                isset($rawError->Code) ? $rawError->Code : '',
                isset($rawError->DescText) ? $rawError->DescText : ''
            );
        }

        if ($response = isset($result->response->Response) ? $result->response->Response : null) {
            $result->response = $response;
        }

        return $result;
    }
}
