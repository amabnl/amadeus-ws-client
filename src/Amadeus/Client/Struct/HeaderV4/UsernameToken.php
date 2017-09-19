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

namespace Amadeus\Client\Struct\HeaderV4;

/**
 * UsernameToken
 *
 * @package Amadeus\Client\Struct\HeaderV4
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class UsernameToken
{
    /**
     * @var \SoapVar
     */
    public $Username;

    /**
     * @var \SoapVar
     */
    public $Password;

    /**
     * @var \SoapVar
     */
    public $Nonce;

    /**
     * @var \SoapVar
     */
    public $Created;

    /**
     * @param $userName
     * @param $password
     * @param $nonce
     * @param $created
     * @param $ns
     */
    public function __construct($userName, $password, $nonce, $created, $ns)
    {
        $this->Username = new \SoapVar($userName, XSD_STRING, null, null, 'Username', $ns);

        $passwordNode = '<ns3:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wssusername-token-profile-1.0#PasswordDigest">'.$password.'</ns3:Password>';

        $this->Password = new \SoapVar($passwordNode, XSD_ANYXML, null, null, 'Password');

        $this->Nonce = new \SoapVar($nonce, XSD_STRING, null, null, 'Nonce', $ns);

        $this->Created = new \SoapVar($created, XSD_STRING, null, null, 'Created', $ns);
    }
}
