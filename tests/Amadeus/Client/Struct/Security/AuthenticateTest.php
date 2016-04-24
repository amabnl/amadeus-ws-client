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

namespace Test\Amadeus\Client\Struct\Security;

use Amadeus\Client\Params\AuthParams;
use Amadeus\Client\RequestOptions\SecurityAuthenticateOptions;
use Amadeus\Client\Struct\Security\Authenticate;
use Amadeus\Client\Struct\Security\PasswordInfo;
use Test\Amadeus\BaseTestCase;

/**
 * Authenticate
 *
 * @package Amadeus\Client\Struct\Security
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class AuthenticateTest extends BaseTestCase
{

    public function testCanConstructAuthMessage()
    {
        $authParams = new AuthParams([
            'officeId' => 'BRUXXXXXX',
            'originatorTypeCode' => 'U',
            'userId' => 'WSXXXXXX',
            'passwordData' => base64_encode('TEST'),
            'passwordLength' => 4,
            'dutyCode' => 'SU',
            'organizationId' => 'DUMMY-ORG',
        ]);

        $reqOpt = new SecurityAuthenticateOptions($authParams);

        $message = new Authenticate($reqOpt);

        $this->assertEquals('BRUXXXXXX' , $message->userIdentifier->originIdentification->sourceOffice);
        $this->assertEquals('U' , $message->userIdentifier->originatorTypeCode);
        $this->assertEquals('WSXXXXXX' , $message->userIdentifier->originator);

        $this->assertEquals('DUT' , $message->dutyCode->dutyCodeDetails->referenceQualifier);
        $this->assertEquals('SU' , $message->dutyCode->dutyCodeDetails->referenceIdentifier);

        $this->assertEquals('DUMMY-ORG' , $message->systemDetails->organizationDetails->organizationId);

        $this->assertEquals(PasswordInfo::DATA_TYPE_EDIFACT , $message->passwordInfo->dataType);
        $this->assertEquals(4 , $message->passwordInfo->dataLength);
        $this->assertEquals(base64_encode('TEST') , $message->passwordInfo->binaryData);

    }
}
