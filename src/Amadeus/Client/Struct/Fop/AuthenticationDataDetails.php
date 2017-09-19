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

/**
 * AuthenticationDataDetails
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AuthenticationDataDetails
{

    /**
     * E Error message received from directory server (Internal status)
     * N Card holder not participating
     * U Unable to authenticate
     * Y Authentication available
     *
     * @var string
     */
    public $veres;

    /**
     * A Attempted authentication
     * N Authentication failed
     * U Authentication could not be performed (unable)
     * Y Authentication successful
     *
     * @var string
     */
    public $pares;

    /**
     * CADS MasterCard Directory Server
     * VIDS VISA Directory Server
     *
     * 'VISA' - Visa directory
     * 'MAST' - MasterCard directory server
     *
     * @var string
     */
    public $creditCardCompany;

    /**
     *
     * 00 Failed authentication (Visa / MasterCard)
     * 01 Incomplete authentication (MasterCard)
     * 02 Successful authentication (MasterCard)
     * 05 Successful authentication (Visa)
     * 06 Authentication attempted (Visa)
     * 07 Unable to authenticate (Visa)
     *
     * @var string
     */
    public $authenticationIndicator;

    /**
     * 0 HMAC
     * 1 CVV
     * 2 CVV with ATN
     * 3 Mastercard SPA algorithm
     *
     * @var int
     */
    public $caavAlgorithm;

    /**
     * AuthenticationDataDetails constructor.
     *
     * @param string|null $veresStatus
     * @param string|null $paresStatus
     * @param string|null $company
     */
    public function __construct($veresStatus, $paresStatus, $company)
    {
        $this->veres = $veresStatus;
        $this->pares = $paresStatus;
        $this->creditCardCompany = $company;
    }
}
