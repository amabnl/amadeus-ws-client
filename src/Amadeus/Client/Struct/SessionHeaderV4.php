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

namespace Amadeus\Client\Struct;

/**
 * SessionHeaderV4
 *
 * @todo to implement in Amadeus\Client\Session\Handler\SoapHeader4::createSoapHeaders
 * @package Amadeus\Client\Struct
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SessionHeaderV4
{
    /**
     * @var HeaderV4\Security
     */
    public $Security;

    /**
     * @var \SoapVar
     */
    public $MessageID;

    /**
     * @var \SoapVar
     */
    public $To;

    /**
     * @var \SoapVar
     */
    public $Action;

    /**
     * @var HeaderV4\SecurityHostedUser
     */
    public $AMA_SecurityHostedUser;
}
