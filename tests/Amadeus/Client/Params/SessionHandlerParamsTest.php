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

namespace Test\Amadeus\Client\Params;

use Amadeus\Client;
use Amadeus\Client\Params;
use Psr\Log\NullLogger;
use Test\Amadeus\BaseTestCase;

/**
 * SessionHandlerParamsTest
 *
 * @package Test\Amadeus\Client\Params
 */
class SessionHandlerParamsTest extends BaseTestCase
{
    public function testCanMakeSessionHandlerEmpty()
    {
        $par = new Params\SessionHandlerParams();

        $this->assertNull($par->authParams);
        $this->assertNull($par->logger);
        $this->assertNull($par->overrideSoapClient);
        $this->assertNull($par->overrideSoapClientWsdlName);
        $this->assertTrue($par->stateful);
        $this->assertEmpty($par->wsdl);
        $this->assertEquals(Client::HEADER_V4,$par->soapHeaderVersion);
    }

    public function testCanMakeSessionHandlerParamsWithAuthParamsInstance()
    {
        $par = new Params\SessionHandlerParams([
            'wsdl' => realpath(dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . "testfiles" . DIRECTORY_SEPARATOR . "dummywsdl.wsdl"),
            'stateful' => true,
            'logger' => new NullLogger(),
            'authParams' => new Params\AuthParams([
                'officeId' => 'BRUXXXXXX',
                'userId' => 'WSXXXXXX',
                'passwordData' => base64_encode('TEST')
            ])
        ]);

        $this->assertInstanceOf(
            'Amadeus\Client\Params\AuthParams',
            $par->authParams
        );
        $this->assertEquals('BRUXXXXXX', $par->authParams->officeId);
        $this->assertEquals('WSXXXXXX', $par->authParams->userId);
        $this->assertEquals(base64_encode('TEST'), $par->authParams->passwordData);
        $this->assertInstanceOf('\Psr\Log\LoggerInterface', $par->logger);
        $this->assertTrue($par->stateful);
        $this->assertEquals(Client::HEADER_V4,$par->soapHeaderVersion);
        $this->assertNull($par->overrideSoapClient);
    }

    public function testCanMakeSessionHandlerParamsWithoutLogger()
    {
        $par = new Params\SessionHandlerParams([
            'wsdl' => realpath(dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . "testfiles" . DIRECTORY_SEPARATOR . "dummywsdl.wsdl"),
            'stateful' => true,
            'authParams' => new Params\AuthParams([
                'officeId' => 'BRUXXXXXX',
                'userId' => 'WSXXXXXX',
                'passwordData' => base64_encode('TEST')
            ])
        ]);

        $this->assertInstanceOf(
            'Amadeus\Client\Params\AuthParams',
            $par->authParams
        );
        $this->assertEquals('BRUXXXXXX', $par->authParams->officeId);
        $this->assertEquals('WSXXXXXX', $par->authParams->userId);
        $this->assertEquals(base64_encode('TEST'), $par->authParams->passwordData);
        $this->assertNull($par->logger);
        $this->assertTrue($par->stateful);
        $this->assertEquals(Client::HEADER_V4,$par->soapHeaderVersion);
        $this->assertNull($par->overrideSoapClient);
    }

    public function testCanMakeSessionHandlerParamsWithSoapClientOptions()
    {
        $par = new Params\SessionHandlerParams([
            'wsdl' => realpath(dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . "testfiles" . DIRECTORY_SEPARATOR . "dummywsdl.wsdl"),
            'stateful' => true,
            'authParams' => new Params\AuthParams([
                'officeId' => 'BRUXXXXXX',
                'userId' => 'WSXXXXXX',
                'passwordData' => base64_encode('TEST')
            ]),
            'soapClientOptions' => [
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP
            ]
        ]);

        $this->assertInternalType('array', $par->soapClientOptions);
        $this->assertNotEmpty($par->soapClientOptions);
        $this->assertEquals(
            SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
            $par->soapClientOptions['compression']
        );
    }

    public function testCanMakeSessionHandlerWithTransactionFlowLink()
    {
        $par = new Params\SessionHandlerParams([
            'wsdl' => realpath(dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . "testfiles" . DIRECTORY_SEPARATOR . "dummywsdl.wsdl"),
            'stateful' => true,
            'authParams' => new Params\AuthParams([
                'officeId' => 'BRUXXXXXX',
                'userId' => 'WSXXXXXX',
                'passwordData' => base64_encode('TEST')
            ]),
            'enableTransactionFlowLink' => true,
            'consumerId' => 'dummy-consumer-id',
        ]);

        $this->assertTrue($par->enableTransactionFlowLink);
        $this->assertEquals('dummy-consumer-id', $par->consumerId);
    }
}
