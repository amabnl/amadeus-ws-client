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

    const CC_COMP_MASTERCARD_DIRECTORY_SERVER = "CADS";
    const CC_COMP_VISA_DIRECTORY_SERVER = "VIDS";

    const DATATYPE_BINARY = "B";
    const DATATYPE_EDIFACT = "E";

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
     * self::CC_COMP_*
     *
     * @var string
     */
    public $creditCardCompany;

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
