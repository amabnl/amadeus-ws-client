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

namespace Test\Amadeus\Client\Session\Handler;

use Amadeus\Client;
use Amadeus\Client\Params\SessionHandlerParams;
use Amadeus\Client\Session\Handler\HandlerFactory;
use Psr\Log\NullLogger;
use Test\Amadeus\BaseTestCase;

/**
 * HandlerFactoryTest
 *
 * @package Test\Amadeus\Client\Session\Handler
 */
class HandlerFactoryTest extends BaseTestCase
{

    public function testCreateWithoutAuthWillThrowException()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $params = $par = new SessionHandlerParams([
            'wsdl' => '/dummy/path',
            'stateful' => false,
            'receivedFrom' => 'unittests',
            'logger' => new NullLogger()
        ]);

        HandlerFactory::createHandler($params);
    }

    public function testCreateSoapHeader4WillCreateSoapHeader4Handler()
    {
        $params = $par = new SessionHandlerParams([
            'wsdl' => '/dummy/path',
            'soapHeaderVersion' => Client::HEADER_V4,
            'receivedFrom' => 'unittests',
            'logger' => new NullLogger(),
            'authParams' => [
                'officeId' => 'BRUXX0000',
                'originatorTypeCode' => 'U',
                'userId' => 'DUMMYORIG',
                'organizationId' => 'DUMMYORG',
                'passwordLength' => 12,
                'passwordData' => 'dGhlIHBhc3N3b3Jk'
            ]
        ]);

        $createdHandler = HandlerFactory::createHandler($params);

        $this->assertInstanceOf('Amadeus\Client\Session\Handler\SoapHeader4', $createdHandler);
    }

    public function testCreateSoapHeader4WithWsdlListWillCreateSoapHeader4Handler()
    {
        $params = $par = new SessionHandlerParams([
            'wsdl' => [
                '/dummy/path',
                '/dummy/path/wsdl2'
            ],
            'soapHeaderVersion' => Client::HEADER_V4,
            'receivedFrom' => 'unittests',
            'logger' => new NullLogger(),
            'authParams' => [
                'officeId' => 'BRUXX0000',
                'originatorTypeCode' => 'U',
                'userId' => 'DUMMYORIG',
                'organizationId' => 'DUMMYORG',
                'passwordLength' => 12,
                'passwordData' => 'dGhlIHBhc3N3b3Jk'
            ]
        ]);

        $createdHandler = HandlerFactory::createHandler($params);

        $this->assertInstanceOf('Amadeus\Client\Session\Handler\SoapHeader4', $createdHandler);
    }

    public function testCreateSoapHeader2WillCreateSoapHeader2Handler()
    {
        $params = $par = new SessionHandlerParams([
            'wsdl' => '/dummy/path',
            'soapHeaderVersion' => Client::HEADER_V2,
            'receivedFrom' => 'unittests',
            'logger' => new NullLogger(),
            'authParams' => [
                'officeId' => 'BRUXX0000',
                'originatorTypeCode' => 'U',
                'userId' => 'DUMMYORIG',
                'organizationId' => 'DUMMYORG',
                'passwordLength' => 12,
                'passwordData' => 'dGhlIHBhc3N3b3Jk'
            ]
        ]);

        $createdHandler = HandlerFactory::createHandler($params);

        $this->assertInstanceOf('Amadeus\Client\Session\Handler\SoapHeader2', $createdHandler);
    }

    public function testCreateSoapHeader1WillThrowException()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $params = $par = new SessionHandlerParams([
            'wsdl' => '/dummy/path',
            'soapHeaderVersion' => Client::HEADER_V1,
            'receivedFrom' => 'unittests',
            'logger' => new NullLogger(),
            'authParams' => [
                'officeId' => 'BRUXX0000',
                'originatorTypeCode' => 'U',
                'userId' => 'DUMMYORIG',
                'organizationId' => 'DUMMYORG',
                'passwordLength' => 12,
                'passwordData' => 'dGhlIHBhc3N3b3Jk'
            ]
        ]);

        HandlerFactory::createHandler($params);
    }
}
