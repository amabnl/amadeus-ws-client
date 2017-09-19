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

namespace Amadeus\Client\Struct\Fop;

use Amadeus\Client\RequestOptions\Fop\ThreeDSecureInfo;
use Amadeus\Client\Struct\WsMessageUtility;

/**
 * TdsInformation
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TdsInformation extends WsMessageUtility
{
    /**
     * @var AuthenticationData
     */
    public $authenticationData;

    /**
     * @var AcsUrl
     */
    public $acsURL;

    /**
     * @var TdsBlobData[]
     */
    public $tdsBlobData = [];

    /**
     * TdsInformation constructor.
     *
     * @param ThreeDSecureInfo $options
     */
    public function __construct(ThreeDSecureInfo $options)
    {
        if (!empty($options->acsUrl)) {
            $this->acsURL = new AcsUrl($options->acsUrl);
        }

        if ($this->checkAnyNotEmpty($options->veresStatus, $options->paresStatus, $options->creditCardCompany)) {
            $this->authenticationData = new AuthenticationData(
                $options->veresStatus,
                $options->paresStatus,
                $options->creditCardCompany
            );
        }

        if (!empty($options->transactionIdentifier)) {
            $this->tdsBlobData[] = new TdsBlobData(
                TdsReferenceDetails::REF_THREEDS_TRANSACTION_IDENTIFIER,
                $options->transactionIdentifier,
                $options->transactionIdentifierDataType,
                $options->transactionIdentifierLength
            );
        }

        if (!empty($options->paresAuthResponse)) {
            $this->tdsBlobData[] = new TdsBlobData(
                TdsReferenceDetails::REF_PARES,
                $options->paresAuthResponse,
                $options->paresAuthResponseDataType,
                $options->paresAuthResponseLength
            );
        }
    }
}
