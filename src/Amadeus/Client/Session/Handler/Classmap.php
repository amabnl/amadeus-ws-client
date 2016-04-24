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

namespace Amadeus\Client\Session\Handler;

/**
 * Class Map for mapping PHP objects to WSDL objects.
 *
 * @package Amadeus\Client\Session\Handler
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Classmap
{
    /**
     * The PHP -> WSDL translation classmap for Soap Header 4 specific message parts
     *
     * @var array
     */
    public static $soapheader4map = [
        'AMA_SecurityHostedUser' => 'Amadeus\Client\Struct\HeaderV4\SecurityHostedUser',
        'UserID' => 'Amadeus\Client\Struct\HeaderV4\UserId',
        'Security' => 'Amadeus\Client\Struct\HeaderV4\Security',
        'UsernameToken' => 'Amadeus\Client\Struct\HeaderV4\UsernameToken',
        'Session' => 'Amadeus\Client\Struct\HeaderV4\Session',
    ];

    /**
     * The PHP -> WSDL translation classmap for Soap Header 2 specific message parts
     *
     * @var array
     */
    public static $soapheader2map = [
        'Session' => 'Amadeus\Client\Struct\HeaderV2\Session',
    ];

    /**
     * The PHP -> WSDL translation classmap for the Amadeus WS Client
     *
     * Contains all non-soapheader-specific mapping
     *
     * @var array
     */
    public static $map = [

    ];
}
