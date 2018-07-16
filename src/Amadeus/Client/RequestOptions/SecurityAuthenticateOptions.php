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

namespace Amadeus\Client\RequestOptions;

use Amadeus\Client\Params\AuthParams;

/**
 * Security_Authenticate Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SecurityAuthenticateOptions extends Base
{
    /**
     * The Amadeus Office ID to sign in to
     *
     * @var string
     */
    public $officeId;

    /**
     * User ID / Originator
     *
     * @var string
     */
    public $userId;

    /**
     * Originator Typecode
     *
     * @var string
     */
    public $originatorTypeCode;

    /**
     * Duty code
     *
     * @var string
     */
    public $dutyCode;

    /**
     * Organization ID
     *
     * @var string
     */
    public $organizationId;

    /**
     * Password Length of plain password.
     *
     * Only for SoapHeader < 4.
     *
     * @var int
     */
    public $passwordLength;

    /**
     * Password Data (base-64 encoded password)
     *
     * @var string
     */
    public $passwordData;

    /**
     * SecurityAuthenticateOptions constructor.
     *
     * @param AuthParams|null $authParams
     */
    public function __construct(AuthParams $authParams = null)
    {
        if ($authParams instanceof AuthParams) {
            $this->loadFromAuthParams($authParams);
        }

        parent::__construct([]);
    }

    /**
     * Load security authenticate options from auth params
     *
     * @param AuthParams $authParams
     */
    protected function loadFromAuthParams(AuthParams $authParams)
    {
        $this->officeId = $authParams->officeId;
        $this->dutyCode = $authParams->dutyCode;
        $this->organizationId = $authParams->organizationId;
        $this->originatorTypeCode = $authParams->originatorTypeCode;
        $this->userId = $authParams->userId;
        $this->passwordLength = $authParams->passwordLength;
        $this->passwordData = $authParams->passwordData;
    }
}
