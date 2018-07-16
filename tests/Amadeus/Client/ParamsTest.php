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

namespace Test\Amadeus\Client;

use Amadeus\Client\Params;
use Psr\Log\NullLogger;
use Test\Amadeus\BaseTestCase;

/**
 * ParamsTest
 *
 * @package Amadeus\Client
 */
class ParamsTest extends BaseTestCase
{
    public function testCanMakeParamsFromArray()
    {
        $theParamArray = [
            'sessionHandlerParams' => [
                'wsdl' => '/var/fake/file/path',
                'stateful' => true,
                'logger' => new NullLogger(),
                'authParams' => [
                    'officeId' => 'BRUXXXXXX',
                    'originatorTypeCode' => 'U',
                    'userId' => 'WSXXXXXX',
                    'organizationId' => 'NMC-XXXXXX',
                    'passwordLength' => '4',
                    'passwordData' => base64_encode('TEST')
                ]
            ],
            'requestCreatorParams' => [
                'originatorOfficeId' => 'BRUXXXXXX',
                'receivedFrom' => 'some RF string'
            ]
        ];

        $params = new Params($theParamArray);

        $this->assertNull($params->authParams);

        $this->assertNull($params->requestCreator);
        $this->assertNull($params->sessionHandler);

        $this->assertInstanceOf('Amadeus\Client\Params\SessionHandlerParams', $params->sessionHandlerParams);
        $this->assertTrue($params->sessionHandlerParams->stateful);
        $this->assertInstanceOf('Psr\Log\LoggerInterface', $params->sessionHandlerParams->logger);
        $this->assertInternalType('array', $params->sessionHandlerParams->wsdl);
        $this->assertCount(1, $params->sessionHandlerParams->wsdl);
        $this->assertEquals('/var/fake/file/path', $params->sessionHandlerParams->wsdl[0]);

        $this->assertInstanceOf('Amadeus\Client\Params\AuthParams', $params->sessionHandlerParams->authParams);
        $this->assertEquals('BRUXXXXXX', $params->sessionHandlerParams->authParams->officeId);
        $this->assertEquals('NMC-XXXXXX', $params->sessionHandlerParams->authParams->organizationId);
        $this->assertEquals('WSXXXXXX', $params->sessionHandlerParams->authParams->userId);
        $this->assertEquals('U', $params->sessionHandlerParams->authParams->originatorTypeCode);
        $this->assertEquals(base64_encode('TEST'), $params->sessionHandlerParams->authParams->passwordData);
        $this->assertEquals(4, $params->sessionHandlerParams->authParams->passwordLength);

        //Defaults:
        $this->assertTrue($params->returnXml);
        $this->assertEquals('SU', $params->sessionHandlerParams->authParams->dutyCode);
        $this->assertNull($params->sessionHandlerParams->authParams->nonceBase);

        $this->assertInstanceOf('Amadeus\Client\Params\RequestCreatorParams', $params->requestCreatorParams);
        $this->assertEquals('BRUXXXXXX', $params->requestCreatorParams->originatorOfficeId);
        $this->assertEquals('some RF string', $params->requestCreatorParams->receivedFrom);
    }


    public function testCanMakeParamsFromArrayDisableReturnXml()
    {
        $theParamArray = [
            'returnXml' => false,
            'authParams' => [
                'officeId' => 'BRUXXXXXX',
                'originatorTypeCode' => 'U',
                'userId' => 'WSXXXXXX',
                'organizationId' => 'NMC-XXXXXX',
                'passwordLength' => '4',
                'passwordData' => base64_encode('TEST')
            ],
            'sessionHandlerParams' => [
                'wsdl' => '/var/fake/file/path',
                'stateful' => true,
                'logger' => new NullLogger()
            ],
            'requestCreatorParams' => [
                'originatorOfficeId' => 'BRUXXXXXX',
                'receivedFrom' => 'some RF string'
            ]
        ];

        $params = new Params($theParamArray);

        $this->assertFalse($params->returnXml);

        //No Auth params on sessionhandlerparams level:
        $this->assertNull($params->sessionHandlerParams->authParams);

        //Auth Params on highest level:
        $this->assertInstanceOf('Amadeus\Client\Params\AuthParams', $params->authParams);
        $this->assertEquals('BRUXXXXXX', $params->authParams->officeId);
        $this->assertEquals('NMC-XXXXXX', $params->authParams->organizationId);
        $this->assertEquals('WSXXXXXX', $params->authParams->userId);
        $this->assertEquals('U', $params->authParams->originatorTypeCode);
        $this->assertEquals(base64_encode('TEST'), $params->authParams->passwordData);
        $this->assertEquals(4, $params->authParams->passwordLength);
        $this->assertEquals('SU', $params->authParams->dutyCode);
        $this->assertNull($params->authParams->nonceBase);

        $this->assertNull($params->requestCreator);
        $this->assertNull($params->sessionHandler);

        $this->assertInstanceOf('Amadeus\Client\Params\SessionHandlerParams', $params->sessionHandlerParams);
        $this->assertTrue($params->sessionHandlerParams->stateful);
        $this->assertInstanceOf('Psr\Log\LoggerInterface', $params->sessionHandlerParams->logger);
        $this->assertInternalType('array', $params->sessionHandlerParams->wsdl);
        $this->assertCount(1, $params->sessionHandlerParams->wsdl);
        $this->assertEquals('/var/fake/file/path', $params->sessionHandlerParams->wsdl[0]);

        //Defaults:
        $this->assertInstanceOf('Amadeus\Client\Params\RequestCreatorParams', $params->requestCreatorParams);
        $this->assertEquals('BRUXXXXXX', $params->requestCreatorParams->originatorOfficeId);
        $this->assertEquals('some RF string', $params->requestCreatorParams->receivedFrom);
    }

    /**
     * Auth params New location
     */
    public function testCanMakeParamsFromArraySupportForAuthParamsSoapHeader2()
    {
        $theParamArray = [
            'authParams' => [
                'officeId' => 'BRUXXXXXX',
                'originatorTypeCode' => 'U',
                'userId' => 'WSXXXXXX',
                'organizationId' => 'NMC-XXXXXX',
                'passwordLength' => '4',
                'passwordData' => base64_encode('TEST')
            ],
            'sessionHandlerParams' => [
                'wsdl' => '/var/fake/file/path',
                'stateful' => true,
                'logger' => new NullLogger(),

            ],
            'requestCreatorParams' => [
                'originatorOfficeId' => 'BRUXXXXXX',
                'receivedFrom' => 'some RF string'
            ]
        ];

        $params = new Params($theParamArray);

        $this->assertNull($params->requestCreator);
        $this->assertNull($params->sessionHandler);

        $this->assertInstanceOf('Amadeus\Client\Params\SessionHandlerParams', $params->sessionHandlerParams);
        $this->assertTrue($params->sessionHandlerParams->stateful);
        $this->assertInstanceOf('Psr\Log\LoggerInterface', $params->sessionHandlerParams->logger);
        $this->assertInternalType('array', $params->sessionHandlerParams->wsdl);
        $this->assertCount(1, $params->sessionHandlerParams->wsdl);
        $this->assertEquals('/var/fake/file/path', $params->sessionHandlerParams->wsdl[0]);

        //No Auth params on sessionhandlerparams level:
        $this->assertNull($params->sessionHandlerParams->authParams);

        //Auth Params on highest level:
        $this->assertInstanceOf('Amadeus\Client\Params\AuthParams', $params->authParams);
        $this->assertEquals('BRUXXXXXX', $params->authParams->officeId);
        $this->assertEquals('NMC-XXXXXX', $params->authParams->organizationId);
        $this->assertEquals('WSXXXXXX', $params->authParams->userId);
        $this->assertEquals('U', $params->authParams->originatorTypeCode);
        $this->assertEquals(base64_encode('TEST'), $params->authParams->passwordData);
        $this->assertEquals(4, $params->authParams->passwordLength);

        //Defaults:
        $this->assertEquals('SU', $params->authParams->dutyCode);
        $this->assertNull($params->authParams->nonceBase);

        $this->assertInstanceOf('Amadeus\Client\Params\RequestCreatorParams', $params->requestCreatorParams);
        $this->assertEquals('BRUXXXXXX', $params->requestCreatorParams->originatorOfficeId);
        $this->assertEquals('some RF string', $params->requestCreatorParams->receivedFrom);
    }

    public function testCanMakeParamsWithBaseNonceFromArray()
    {
        $theParamArray = [
            'sessionHandlerParams' => [
                'wsdl' => '/var/fake/file/path',
                'stateful' => true,
                'logger' => new NullLogger(),
                'authParams' => [
                    'officeId' => 'BRUXXXXXX',
                    'originatorTypeCode' => 'U',
                    'userId' => 'WSXXXXXX',
                    'organizationId' => 'NMC-XXXXXX',
                    'passwordLength' => '4',
                    'passwordData' => base64_encode('TEST'),
                    'nonceBase' => 'abloobloo'
                ]
            ],
            'requestCreatorParams' => [
                'originatorOfficeId' => 'BRUXXXXXX',
                'receivedFrom' => 'some RF string'
            ]
        ];

        $params = new Params($theParamArray);
        $this->assertEquals('abloobloo', $params->sessionHandlerParams->authParams->nonceBase);
    }

    public function testCanCreateParamsWithOverrideSessionHandlerAndRequestCreator()
    {
        $dummySessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();
        $dummyRequestCreator = $this->getMockBuilder('Amadeus\Client\RequestCreator\RequestCreatorInterface')->getMock();
        $dummyResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $theParamArray = [
            'sessionHandler' => $dummySessionHandler,
            'requestCreator' => $dummyRequestCreator,
            'responseHandler' => $dummyResponseHandler,
        ];

        $params = new Params($theParamArray);

        $this->assertNull($params->requestCreatorParams);
        $this->assertNull($params->sessionHandlerParams);
        $this->assertInstanceOf('Amadeus\Client\Session\Handler\HandlerInterface', $params->sessionHandler);
        $this->assertInstanceOf('Amadeus\Client\RequestCreator\RequestCreatorInterface', $params->requestCreator);
        $this->assertInstanceOf('Amadeus\Client\ResponseHandler\ResponseHandlerInterface', $params->responseHandler);
    }

    public function testCanLoadFromArrayParamObjects()
    {
        $theParamArray = [
            'sessionHandlerParams' => new Params\SessionHandlerParams(),
            'requestCreatorParams' => new Params\RequestCreatorParams()
        ];

        $params = new Params($theParamArray);

        $this->assertInstanceOf('Amadeus\Client\Params\SessionHandlerParams', $params->sessionHandlerParams);
        $this->assertInstanceOf('Amadeus\Client\Params\RequestCreatorParams', $params->requestCreatorParams);
    }

    public function testCanCreateParamsWithAuthParamsObject()
    {
        $authParams = new Params\AuthParams([
            'officeId' => 'BRUXXXXXX',
            'originatorTypeCode' => 'U',
            'userId' => 'WSXXXXXX',
            'organizationId' => 'NMC-XXXXXX',
            'passwordLength' => '4',
            'passwordData' => base64_encode('TEST')
        ]);

        $theParamArray = [
            'authParams' => $authParams,
            'sessionHandlerParams' => [
                'wsdl' => '/var/fake/file/path',
                'stateful' => true,
                'logger' => new NullLogger()
            ],
            'requestCreatorParams' => [
                'originatorOfficeId' => 'BRUXXXXXX',
                'receivedFrom' => 'some RF string'
            ]
        ];

        $params = new Params($theParamArray);

        $this->assertEquals($authParams, $params->authParams);
        $this->assertNull($params->sessionHandlerParams->authParams);
    }
}

