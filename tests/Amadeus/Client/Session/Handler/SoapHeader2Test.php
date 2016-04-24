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

use Amadeus\Client;
use Amadeus\Client\Params\SessionHandlerParams;
use Psr\Log\NullLogger;
use Test\Amadeus\BaseTestCase;

/**
 * SoapHeader2Test
 *
 * @package Amadeus\Client\Session\Handler
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class SoapHeader2Test extends BaseTestCase
{

    public function testCanTrySendMessageWhenNotAuthenticated()
    {
        $this->setExpectedException('Amadeus\Client\Session\Handler\InvalidSessionException');

        $handler = new SoapHeader2($this->makeSessionHandlerParams());

        $handler->sendMessage(
            'PNR_Retrieve',
            new Client\Struct\Pnr\Retrieve(Client\Struct\Pnr\Retrieve::RETR_TYPE_BY_RECLOC, 'ABC123'),
            [
                'asString' => false,
                'endSession' => false
            ]
        );
    }

    public function testCanTryPrepareNextMessageWhenAuthenticated()
    {
        $overrideSoapClient = $this->getMock(
            'Amadeus\Client\SoapClient',
            ['__getLastRequest', '__getLastResponse', 'PNR_Retrieve'],
            [],
            '',
            false
        );

        $dummyPnrRequest = $this->getTestFile('dummyPnrRequestsoapheader2.txt');
        $dummyPnrReply = $this->getTestFile('dummyPnrReplysoapheader2.txt');
        $dummyPnrReplyExtractedMessage = $this->getTestFile('dummyPnrReplyExtractedMessageSoapHeader2.txt');

        $overrideSoapClient
            ->expects($this->atLeastOnce())
            ->method('__getLastRequest')
            ->will($this->returnValue($dummyPnrRequest));

        $overrideSoapClient
            ->expects($this->atLeastOnce())
            ->method('__getLastResponse')
            ->will($this->returnValue($dummyPnrReply));

        $overrideSoapClient
            ->expects($this->any())
            ->method('PNR_Retrieve')
            ->will($this->returnValue(''));

        $handler = new SoapHeader2($this->makeSessionHandlerParams(
            $overrideSoapClient
        ));
        $handler->setSessionData([
            'sessionId' => 'XFHZEKLRZHREJ',
            'sequenceNumber' => 12,
            'securityToken' => 'RKLERJEZLKRHZEJKLRHEZJKLREZRHEZK'
        ]);

        $messageResponse = $handler->sendMessage(
            'PNR_Retrieve',
            new Client\Struct\Pnr\Retrieve(Client\Struct\Pnr\Retrieve::RETR_TYPE_BY_RECLOC, 'ABC123'),
            [
                'asString' => true,
                'endSession' => false
            ]
        );

        $this->assertInternalType('string', $messageResponse);
        $this->assertEquals($dummyPnrReplyExtractedMessage, $messageResponse);
    }

    public function testCanSendAuthCallAndStartSession()
    {
        $overrideSoapClient = $this->getMock(
            'Amadeus\Client\SoapClient',
            ['__getLastRequest', '__getLastResponse', 'Security_Authenticate'],
            [],
            '',
            false
        );

        $dummyRequest = $this->getTestFile('soapheader2' . DIRECTORY_SEPARATOR . 'dummySecurityAuth.txt');
        $dummyReply = $this->getTestFile('soapheader2' . DIRECTORY_SEPARATOR . 'dummySecurityAuthReply.txt');

        $messageResult = new \stdClass();
        $messageResult->processStatus = new \stdClass();
        $messageResult->processStatus->statusCode = 'P';

        $overrideSoapClient
            ->expects($this->atLeastOnce())
            ->method('__getLastRequest')
            ->will($this->returnValue($dummyRequest));

        $overrideSoapClient
            ->expects($this->atLeastOnce())
            ->method('__getLastResponse')
            ->will($this->returnValue($dummyReply));

        $overrideSoapClient
            ->expects($this->any())
            ->method('Security_Authenticate')
            ->will($this->returnValue($messageResult));

        $handlerParams = $this->makeSessionHandlerParams(
            $overrideSoapClient
        );

        $handler = new SoapHeader2($handlerParams);

        //SEND MESSAGE AND CHECK RESULT
        $actualSendResult = $handler->sendMessage(
            'Security_Authenticate',
            new Client\Struct\Security\Authenticate(
                new Client\RequestOptions\SecurityAuthenticateOptions(
                    $handlerParams->authParams
                )
            ),
            [
                'asString' => false,
                'endSession' => false
            ]
        );

        $this->assertEquals($messageResult, $actualSendResult);

        //ASSERT THAT THE SESSION HAS BEEN STARTED CORRECTLY
        $expectedSessionData = [
            'sessionId' => 'IROZERIIOP',
            'sequenceNumber' => 1,
            'securityToken' => 'FDKLSDMJFSMLRJEZRHZJ'
        ];

        $this->assertEquals(
            $expectedSessionData,
            $handler->getSessionData()
        );
    }

    public function testGetLastRequestEmptyWithNoMessages()
    {

        $handlerParams = $this->makeSessionHandlerParams();

        $handler = new SoapHeader2($handlerParams);

        $result = $handler->getLastRequest();

        $this->assertNull($result);
    }

    /**
     * @param \SoapClient|null $overrideSoapClient
     * @return SessionHandlerParams
     */
    protected function makeSessionHandlerParams($overrideSoapClient = null)
    {
        $wsdlpath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'testfiles' . DIRECTORY_SEPARATOR . 'soapheader2' . DIRECTORY_SEPARATOR . 'testwsdlsoapheader2.wsdl';

        $par = new SessionHandlerParams([
            'wsdl' => realpath($wsdlpath),
            'soapHeaderVersion' => Client::HEADER_V2,
            'receivedFrom' => 'unittests',
            'logger' => new NullLogger(),
            'overrideSoapClient' => $overrideSoapClient,
            'authParams' => [
                'officeId' => 'BRUXX0000',
                'originatorTypeCode' => 'U',
                'userId' => 'DUMMYORIG',
                'organizationId' => 'DUMMYORG',
                'passwordLength' => 12,
                'passwordData' => 'dGhlIHBhc3N3b3Jk'
            ]
        ]);

        return $par;
    }
}
