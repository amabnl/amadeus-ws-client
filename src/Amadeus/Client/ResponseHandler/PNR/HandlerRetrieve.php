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

namespace Amadeus\Client\ResponseHandler\PNR;

use Amadeus\Client\ResponseHandler\StandardResponseHandler;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * HandlerRetrieve
 *
 * @package Amadeus\Client\ResponseHandler\PNR
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class HandlerRetrieve extends StandardResponseHandler
{
    const Q_G_ERR = "//m:generalErrorInfo//m:errorOrWarningCodeDetails/m:errorDetails/m:errorCode";
    const Q_G_MSG = "//m:generalErrorInfo/m:errorWarningDescription/m:freeText";
    const Q_G_OLD_ERR = "//m:generalErrorInfo//m:messageErrorInformation/m:errorDetail/m:errorCode";
    const Q_G_OLD_MSG = "//m:generalErrorInfo/m:messageErrorText/m:text";
    const Q_S_ERR = "//m:originDestinationDetails//m:errorInfo/m:errorOrWarningCodeDetails/m:errorDetails/m:errorCode";
    const Q_S_MSG = "//m:originDestinationDetails//m:errorInfo/m:errorWarningDescription/m:freeText";
    const Q_E_ERR = "//m:dataElementsIndiv/m:elementErrorInformation/m:errorOrWarningCodeDetails//m:errorCode";
    const Q_E_MSG = "//m:dataElementsIndiv//m:elementErrorInformation/m:errorWarningDescription/m:freeText";
    const Q_P_ERR = "//m:travellerInfo/m:nameError/m:errorOrWarningCodeDetails//m:errorCode";
    const Q_P_MSG = "//m:travellerInfo/m:nameError/m:errorWarningDescription/m:freeText";

    /**
     * Analysing a PNR_Reply
     *
     * @param SendResult $response
     * @return Result
     */
    public function analyze(SendResult $response)
    {
        $analyzeResponse = new Result($response);

        $domXpath = $this->makeDomXpath($response->responseXml);

        //General Errors:
        $errorCodeNodeList = $domXpath->query(self::Q_G_ERR);

        if ($errorCodeNodeList->length > 0) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $code = $errorCodeNodeList->item(0)->nodeValue;
            $errorTextNodeList = $domXpath->query(self::Q_G_MSG);
            $message = $this->makeMessageFromMessagesNodeList($errorTextNodeList);

            $analyzeResponse->messages[] = new Result\NotOk($code, trim($message), 'general');
        }

        //General Errors - PNR_Reply v 14.1 and below:
        $errorCodeNodeList = $domXpath->query(self::Q_G_OLD_ERR);

        if ($errorCodeNodeList->length > 0) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $code = $errorCodeNodeList->item(0)->nodeValue;
            $errorTextNodeList = $domXpath->query(self::Q_G_OLD_MSG);
            $message = $this->makeMessageFromMessagesNodeList($errorTextNodeList);

            $analyzeResponse->messages[] = new Result\NotOk($code, trim($message), 'general');
        }

        //Passenger error
        $errorCodeNodeList = $domXpath->query(self::Q_P_ERR);

        if ($errorCodeNodeList->length > 0) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $code = $errorCodeNodeList->item(0)->nodeValue;
            $errorTextNodeList = $domXpath->query(self::Q_P_MSG);
            $message = $this->makeMessageFromMessagesNodeList($errorTextNodeList);

            $analyzeResponse->messages[] = new Result\NotOk($code, trim($message), 'passenger');
        }

        //Segment errors:
        $errorCodeNodeList = $domXpath->query(self::Q_S_ERR);

        if ($errorCodeNodeList->length > 0) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $code = $errorCodeNodeList->item(0)->nodeValue;
            $errorTextNodeList = $domXpath->query(self::Q_S_MSG);
            $message = $this->makeMessageFromMessagesNodeList($errorTextNodeList);

            $analyzeResponse->messages[] = new Result\NotOk($code, trim($message), 'segment');
        }

        //Element errors:
        $errorCodeNodeList = $domXpath->query(self::Q_E_ERR);

        if ($errorCodeNodeList->length > 0) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $code = $errorCodeNodeList->item(0)->nodeValue;

            $errorTextNodeList = $domXpath->query(self::Q_E_MSG);
            $message = $this->makeMessageFromMessagesNodeList($errorTextNodeList);

            $analyzeResponse->messages[] = new Result\NotOk($code, trim($message), 'element');
        }

        return $analyzeResponse;
    }
}
