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

namespace Amadeus\Client\RequestOptions\Fop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * ThreeDSecureInfo
 *
 * @package Amadeus\Client\RequestOptions\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ThreeDSecureInfo extends LoadParamsFromArray
{
    const VERES_ERROR_FROM_DIRECTORY_SERVER = "E";
    const VERES_CARD_HOLDER_NOT_PARTICIPATING = "N";
    const VERES_UNABLE_TO_AUTHENTICATE = "U";
    const VERES_AUTHENTICATION_AVAILABLE = "Y";

    const PARES_ATTEMPTED_AUTHENTICATION = "A";
    const PARES_AUTHENTICATION_FAILED = "N";
    const PARES_AUTHENTICATION_UNABLE = "U";
    const PARES_AUTHENTICATION_SUCCESSFUL = "Y";

    const TRANSACTION_STATUS_ATTEMPTED_AUTHENTICATION = "A";
    const TRANSACTION_STATUS_AUTHENTICATION_FAILED = "N";
    const TRANSACTION_STATUS_AUTHENTICATION_UNABLE = "U";
    const TRANSACTION_STATUS_AUTHENTICATION_SUCCESSFUL = "Y";

    const CC_COMP_MASTERCARD_DIRECTORY_SERVER = "CADS";
    const CC_COMP_VISA_DIRECTORY_SERVER = "VIDS";

    const DATATYPE_BINARY = "B";
    const DATATYPE_EDIFACT = "E";

    const CREDIT_CARD_COMPANY_VISA = 'VIDS';
    const CREDIT_CARD_COMPANY_MASTER_CARD = 'CADS';
    const CREDIT_CARD_COMPANY_AMERICAN_EXPRESS = 'AXDS';
    const CREDIT_CARD_COMPANY_DINERS = 'DCDS';
    const CREDIT_CARD_COMPANY_JCB = 'JCDS';

    const AUTHENTICATION_VERIFICATION_CODE_VISA = 'CAVV';
    const AUTHENTICATION_VERIFICATION_CODE_AMERICAN_EXPRESS  = 'AEVV';
    const AUTHENTICATION_VERIFICATION_CODE_MASTERCARD  = 'AAV';

    /**
     * VERES status
     *
     * self::VERES_*
     *
     * @var string
     */
    public $veresStatus;

    /**
     * PARES status
     *
     * self::PARES_*
     *
     * @var string
     */
    public $paresStatus;

    /**
     * Transaction status
     *
     * self::TRANSACTION_STATUS_*
     *
     * @var string
     */
    public $transactionsStatus;
    /**
     * self::CC_COMP_*
     *
     * @var string
     */
    public $creditCardCompany;

    /**
     * Indicates the status of the enrolment and authentication phases
     *
     * @var string
     */
    public $authenticationIndicator;

    /**
     * Access Control Server URL
     *
     * @var string
     */
    public $acsUrl;

    /**
     * 3DS transaction identifier
     *
     * Must be a Base64 encoded binary string
     *
     * @var string
     */
    public $transactionIdentifier;

    /**
     * @var string
     */
    public $transactionIdentifierDataType = self::DATATYPE_BINARY;

    /**
     * Length of Transaction Identifier string
     *
     * @var int
     */
    public $transactionIdentifierLength;

    /**
     * 3DS protocol version
     *
     * @var string
     */
    public $tdsVersion;

    /**
     * 3DS 2.0 partner transaction identifier
     *
     * @var string
     */
    public $tdsServerTransactionId;

    /**
     * @var string
     */
    public $tdsServerTransactionIdDataType = self::DATATYPE_BINARY;

    /**
     * Length of 3DS 2.0 partner transaction identifier
     *
     * @var string
     */
    public $tdsServerTransactionIdLength;

    /**
     * 3DS authentication verification code
     *
     * @var string
     */
    public $tdsAuthenticationVerificationCode;

    /**
     * 3DS authentication verification code reference
     * (CAVV : Visa, Diners, JCB AEVV : American Express AAV : Mastercard)
     *
     * @var string
     */
    public $tdsAuthenticationVerificationCodeReference;

    /**
     * @var string
     */
    public $tdsAuthenticationVerificationCodeDataType =  self::DATATYPE_BINARY;

    /**
     * 3DS authentication verification code
     *
     * @var int
     */
    public $tdsAuthenticationVerificationCodeLength;

    /**
     * Transaction identifier related to the Directory Server
     *
     * @package Amadeus\Client\RequestOptions\Fop
     *
     * @var string
     */
    public $directoryServerTransactionId;

    /**
     * @var string
     */
    public $directoryServerTransactionIdDataType = self::DATATYPE_BINARY;

    /**
     * Length of Directory Server Transaction Identifier string
     *
     * @var int
     */
    public $directoryServerTransactionIdLength;

    /**
     * PARES Authentication response
     *
     * Must be a Base64 encoded binary string
     *
     * @var string
     */
    public $paresAuthResponse;


    /**
     * @var string
     */
    public $paresAuthResponseDataType = self::DATATYPE_BINARY;

    /**
     * Length of PARES Response string
     *
     * @var int
     */
    public $paresAuthResponseLength;
}
