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

namespace Amadeus\Client\Params;

/**
 * AuthParams defines necessary authentication parameters for Amadeus Web Service client authentication.
 *
 * @package Amadeus\Client\Params
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AuthParams
{
    /**
     * The Amadeus Office ID to sign in to
     *
     * @var string
     */
    public $officeId;

    /**
     * Originator Typecode
     *
     * @var string
     */
    public $originatorTypeCode = "U";

    /**
     * Duty code
     *
     * @var string
     */
    public $dutyCode = "SU";

    /**
     * User ID / Originator
     *
     * @var string
     */
    public $userId;

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
     * Custom Nonce base to use when generating nonces for authentication
     *
     * Only applies to Soap Header V4
     *
     * If you do not provide one, the Session HandlerFactory will generate a random string.
     *
     * @var string
     */
    public $nonceBase;

    /**
     * @param array $params
     */
    public function __construct($params = [])
    {
        $this->loadFromArray($params);
    }

    /**
     * Load parameters from an associative array
     *
     * @param array $params
     * @return void
     */
    protected function loadFromArray(array $params)
    {
        if (count($params) > 0) {
            $this->officeId = $params['officeId'];
            $this->originatorTypeCode = (isset($params['originatorTypeCode'])) ? $params['originatorTypeCode'] : "U";
            $this->dutyCode = (isset($params['dutyCode'])) ? $params['dutyCode'] : "SU";
            $this->userId = $params['userId'];
            $this->organizationId = (isset($params['organizationId'])) ? $params['organizationId'] : null;
            $this->passwordLength = (isset($params['passwordLength'])) ? $params['passwordLength'] : null;
            $this->passwordData = $params['passwordData'];

            if (isset($params['nonceBase'])) {
                $this->nonceBase = $params['nonceBase'];
            }
        }
    }
}
