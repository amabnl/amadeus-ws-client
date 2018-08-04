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

namespace Amadeus\Client\Struct\Security;

use Amadeus\Client\RequestOptions\SecurityAuthenticateOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Authenticate
 *
 * @package Amadeus\Client\Struct\Security
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Authenticate extends BaseWsMessage
{
    /**
     * @var ConversationClt
     */
    public $conversationClt;
    /**
     * @var UserIdentifier
     */
    public $userIdentifier;
    /**
     * @var DutyCode
     */
    public $dutyCode;
    /**
     * @var SystemDetails
     */
    public $systemDetails;
    /**
     * @var PasswordInfo
     */
    public $passwordInfo;
    /**
     * @var mixed
     */
    public $fullLocation;
    /**
     * @var ApplicationId
     */
    public $applicationId;

    /**
     * Authenticate constructor.
     *
     * @param SecurityAuthenticateOptions $params
     */
    public function __construct(SecurityAuthenticateOptions $params)
    {
        $this->userIdentifier = new UserIdentifier(
            $params->officeId,
            $params->originatorTypeCode,
            $params->userId
        );

        $this->dutyCode = new DutyCode($params->dutyCode);

        $this->systemDetails = new SystemDetails($params->organizationId);

        $this->passwordInfo = new PasswordInfo(
            $params->passwordData,
            $params->passwordLength
        );
    }
}
