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

namespace Test\Amadeus\Client\Struct\HeaderV4;

use Amadeus\Client\Struct\HeaderV4\Security;
use Test\Amadeus\BaseTestCase;

/**
 * SecurityTest
 *
 * @package Test\Amadeus\Client\Struct\HeaderV4
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SecurityTest extends BaseTestCase
{
    public function testCanBuildSecurityHeader()
    {
        $sec = new Security('WSBEXXX', base64_encode('test'), 'dummynonce', 'dummyCreated');

        $this->assertInstanceOf('\SoapVar', $sec->UsernameToken);
        $this->assertInstanceOf('\Amadeus\Client\Struct\HeaderV4\UsernameToken', $sec->UsernameToken->enc_value);
        $this->assertEquals('UsernameToken', $sec->UsernameToken->enc_name);
        $this->assertEquals(SOAP_ENC_OBJECT, $sec->UsernameToken->enc_type);

        $this->assertInstanceOf('\SoapVar', $sec->UsernameToken->enc_value->Username);
        $this->assertEquals('WSBEXXX', $sec->UsernameToken->enc_value->Username->enc_value);

        $this->assertInstanceOf('\SoapVar', $sec->UsernameToken->enc_value->Password);
        $this->assertEquals(
            '<ns3:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wssusername-token-profile-1.0#PasswordDigest">'.base64_encode('test').'</ns3:Password>',
            $sec->UsernameToken->enc_value->Password->enc_value
        );

        $this->assertInstanceOf('\SoapVar', $sec->UsernameToken->enc_value->Nonce);
        $this->assertEquals('dummynonce', $sec->UsernameToken->enc_value->Nonce->enc_value);

        $this->assertInstanceOf('\SoapVar', $sec->UsernameToken->enc_value->Created);
        $this->assertEquals('dummyCreated', $sec->UsernameToken->enc_value->Created->enc_value);
    }
}
