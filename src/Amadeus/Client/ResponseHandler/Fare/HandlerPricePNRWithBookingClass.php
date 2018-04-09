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
 * HandlerPricePNRWithBookingClass
 *
 * @package Amadeus\Client\ResponseHandler\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class HandlerPricePNRWithBookingClass extends StandardResponseHandler
{
    const Q_ERR_CODE = "//m:applicationError//m:errorOrWarningCodeDetails/m:errorDetails/m:errorCode";
    const Q_ERR_CAT = "//m:applicationError//m:errorOrWarningCodeDetails/m:errorDetails/m:errorCategory";
    const Q_ERR_MSG = "//m:applicationError/m:errorWarningDescription/m:freeText";


    const Q_OLD_ERR_CODE = "//m:applicationError//m:applicationErrorDetail/m:applicationErrorCode";
    const Q_OLD_ERR_CAT = "//m:applicationError//m:applicationErrorDetail/m:codeListQualifier";
    const Q_OLD_ERR_MSG = "//m:applicationError/m:errorText/m:errorFreeText";

    /**
     * @param SendResult $response
     * @return Result
     */
    public function analyze(SendResult $response)
    {
        $result = $this->analyzeWithErrCodeCategoryMsgQuery(
            $response,
            self::Q_ERR_CODE,
            self::Q_ERR_CAT,
            self::Q_ERR_MSG
        );

        if ($result->status === Result::STATUS_OK) {
            $result = $this->analyzeWithErrCodeCategoryMsgQuery(
                $response,
                self::Q_OLD_ERR_CODE,
                self::Q_OLD_ERR_CAT,
                self::Q_OLD_ERR_MSG
            );
        }

        return $result;
    }
}
