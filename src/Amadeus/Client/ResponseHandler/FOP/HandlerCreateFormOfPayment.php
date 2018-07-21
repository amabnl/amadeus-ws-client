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

namespace Amadeus\Client\ResponseHandler\FOP;

use Amadeus\Client\Exception;
use Amadeus\Client\ResponseHandler\StandardResponseHandler;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * HandlerCreateFormOfPayment
 *
 * @package Amadeus\Client\ResponseHandler\FOP
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class HandlerCreateFormOfPayment extends StandardResponseHandler
{
    //General error
    const Q_G_ERR = "//m:transmissionError/m:errorOrWarningCodeDetails/m:errorDetails/m:errorCode";
    const Q_G_CAT = "//m:transmissionError/m:errorOrWarningCodeDetails/m:errorDetails/m:errorCategory";
    const Q_G_MSG = "//m:transmissionError/m:errorWarningDescription/m:freeText";
    //FP level error
    const Q_F_ERR = "//m:fopDescription//m:fpElementError/m:errorOrWarningCodeDetails/m:errorDetails/m:errorCode";
    const Q_F_CAT = "//m:fopDescription//m:fpElementError/m:errorOrWarningCodeDetails/m:errorDetails/m:errorCategory";
    const Q_F_MSG = "//m:fopDescription//m:fpElementError/m:errorWarningDescription/m:freeText";
    //Deficient FOP level:
    const Q_D_ERR = "//m:fopDescription/m:mopDescription/m:mopElementError/m:errorOrWarningCodeDetails//m:errorCode";
    const Q_D_CAT = "//m:fopDescription/m:mopDescription/m:mopElementError/m:errorOrWarningCodeDetails//m:errorCategory";
    const Q_D_MSG = "//m:fopDescription/m:mopDescription/m:mopElementError/m:errorWarningDescription/m:freeText";
    //Authorization failure:
    const Q_A_ERR = "//m:fopDescription/m:mopDescription/m:paymentModule//m:paymentStatusError/m:errorOrWarningCodeDetails//m:errorCode";
    const Q_A_CAT = "//m:fopDescription/m:mopDescription/m:paymentModule//m:paymentStatusError/m:errorOrWarningCodeDetails//m:errorCategory";
    const Q_A_MSG = "//m:fopDescription/m:mopDescription/m:paymentModule//m:paymentStatusError/m:errorWarningDescription/m:freeText";

    /**
     * FOP_CreateFormOfPayment Analyze the result from the message operation and check for any error messages
     *
     * @param SendResult $response
     * @return Result
     * @throws Exception
     */
    public function analyze(SendResult $response)
    {
        $analyzeResponse = new Result($response);

        $domXpath = $this->makeDomXpath($response->responseXml);

        //General error level in the transmissionError location:
        $errorCodeNodeList = $domXpath->query(self::Q_G_ERR);

        if ($errorCodeNodeList->length > 0) {
            $errorCatNode = $domXpath->query(self::Q_G_CAT)->item(0);
            $analyzeResponse->setStatus($this->makeStatusForPotentiallyNonExistent($errorCatNode));

            $code = $errorCodeNodeList->item(0)->nodeValue;
            $errorTextNodeList = $domXpath->query(self::Q_G_MSG);
            $message = $this->makeMessageFromMessagesNodeList($errorTextNodeList);

            $analyzeResponse->messages[] = new Result\NotOk($code, trim($message), 'general');
        }

        //FP level errors via the fopDescription/fpElementError data:
        $errorCodeNodeList = $domXpath->query(self::Q_F_ERR);

        if ($errorCodeNodeList->length > 0) {
            $errorCatNode = $domXpath->query(self::Q_F_CAT)->item(0);
            $analyzeResponse->setStatus($this->makeStatusForPotentiallyNonExistent($errorCatNode));

            $code = $errorCodeNodeList->item(0)->nodeValue;
            $errorTextNodeList = $domXpath->query(self::Q_F_MSG);
            $message = $this->makeMessageFromMessagesNodeList($errorTextNodeList);

            $analyzeResponse->messages[] = new Result\NotOk($code, trim($message), 'fp');
        }

        //Deficient FOP level errors:
        $errorCodeNodeList = $domXpath->query(self::Q_D_ERR);

        if ($errorCodeNodeList->length > 0) {
            $errorCatNode = $domXpath->query(self::Q_D_CAT)->item(0);
            $analyzeResponse->setStatus($this->makeStatusForPotentiallyNonExistent($errorCatNode));

            $code = $errorCodeNodeList->item(0)->nodeValue;

            $errorTextNodeList = $domXpath->query(self::Q_D_MSG);
            $message = $this->makeMessageFromMessagesNodeList($errorTextNodeList);

            $analyzeResponse->messages[] = new Result\NotOk($code, trim($message), 'deficient_fop');
        }

        //authorization failure:
        $errorCodeNodeList = $domXpath->query(self::Q_A_ERR);

        if ($errorCodeNodeList->length > 0) {
            $errorCatNode = $domXpath->query(self::Q_A_CAT)->item(0);
            $analyzeResponse->setStatus($this->makeStatusForPotentiallyNonExistent($errorCatNode));

            $code = $errorCodeNodeList->item(0)->nodeValue;

            $errorTextNodeList = $domXpath->query(self::Q_A_MSG);
            $message = $this->makeMessageFromMessagesNodeList($errorTextNodeList);

            $analyzeResponse->messages[] = new Result\NotOk($code, trim($message), 'authorization_failure');
        }

        return $analyzeResponse;
    }

    /**
     * Make status from a category DOMNode or default status.
     *
     * @param \DOMNode|null $errorCatNode
     * @return string
     */
    protected function makeStatusForPotentiallyNonExistent($errorCatNode)
    {
        if ($errorCatNode instanceof \DOMNode) {
            $status = $this->makeStatusFromErrorQualifier($errorCatNode->nodeValue);
        } else {
            $status = Result::STATUS_ERROR;
        }

        return $status;
    }
}
