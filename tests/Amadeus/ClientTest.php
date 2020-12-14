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

namespace Test\Amadeus;

use Amadeus\Client;
use Amadeus\Client\Params;
use Psr\Log\NullLogger;

/**
 * ClientTest
 *
 * @package Amadeus
 */
class ClientTest extends BaseTestCase
{
    public function testCanCreateClient()
    {
        $par = new Params([
            'sessionHandlerParams' => [
                'wsdl' => $this->makePathToDummyWSDL(),
                'stateful' => true,
                'logger' => new NullLogger(),
                'authParams' => [
                    'officeId' => 'BRUXXXXXX',
                    'originatorTypeCode' => 'U',
                    'userId' => 'WSXXXXXX',
                    'passwordData' => base64_encode('TEST')
                ]
            ],
            'requestCreatorParams' => [
                'receivedFrom' => 'some RF string'
            ]
        ]);

        $client = new Client($par);

        $this->assertTrue($client->isStateful());
    }

    public function testCanCreateClientWithOverriddenSessionHandlerRequestCreatorAndResponseHandler()
    {
        $par = new Params([
            'sessionHandler' => $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock(),
            'requestCreator' => $this->getMockBuilder('Amadeus\Client\RequestCreator\RequestCreatorInterface')->getMock(),
            'responseHandler' => $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock()
        ]);

        $client = new Client($par);

        $this->assertInstanceOf('Amadeus\Client\Session\Handler\HandlerInterface', $par->sessionHandler);
        $this->assertInstanceOf('Amadeus\Client\RequestCreator\RequestCreatorInterface', $par->requestCreator);
        $this->assertInstanceOf('Amadeus\Client\ResponseHandler\ResponseHandlerInterface', $par->responseHandler);

        $this->assertInstanceOf('Amadeus\Client', $client);
    }

    public function testCanCreateClientWithAuthOptionsAndSessionParams()
    {
        $par = new Params([
            'authParams' => [
                'officeId' => 'BRUXXXXXX',
                'originatorTypeCode' => 'U',
                'userId' => 'WSXXXXXX',
                'passwordData' => base64_encode('TEST')
            ],
            'sessionHandlerParams' => [
                'wsdl' => $this->makePathToDummyWSDL(),
                'stateful' => true,
                'logger' => new NullLogger()
            ],
            'requestCreatorParams' => [
                'receivedFrom' => 'some RF string'
            ]
        ]);

        $client = new Client($par);

        $this->assertTrue($client->isStateful());
    }

    /**
     * @dataProvider dataProviderMakeMessageOptions
     */
    public function testCanMakeMessageOptions($expected, $params)
    {
        $client = new Client($this->makeDummyParams());

        $meth = self::getMethod($client, 'makeMessageOptions');

        $result = $meth->invokeArgs($client, $params);

        $this->assertEquals($expected, $result);
    }

    public function testCanSetStateful()
    {
        $client = new Client($this->makeDummyParams());

        $current = $client->isStateful();

        $this->assertTrue($current);

        $client->setStateful(false);
        $current = $client->isStateful();

        $this->assertFalse($current);
    }

    public function testCanSetTfl()
    {
        $client = new Client($this->makeDummyParams());

        $current = $client->getConsumerId();

        $this->assertNull($current);

        $client->setConsumerId('dummy');
        $current = $client->getConsumerId();

        $this->assertEquals('dummy', $current);
    }

    public function testWillGetNullFromGetLastReqResWhenNoCallsWerMade()
    {
        $client = new Client($this->makeDummyParams());

        $last = $client->getLastRequest();

        $this->assertNull($last);

        $last = $client->getLastResponse();

        $this->assertNull($last);
    }

    public function testWillGetNullFromGetLastReqResHeadersWhenNoCallsWerMade()
    {
        $client = new Client($this->makeDummyParams());

        $last = $client->getLastRequestHeaders();

        $this->assertNull($last);

        $last = $client->getLastResponseHeaders();

        $this->assertNull($last);
    }

    public function testCanDoDummyPnrRetrieveCall()
    {
        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'A dummy message result'; // Not an actual XML reply.

        $messageResult = new Client\Result($mockedSendResult);

        $expectedPnrResult = new Client\Struct\Pnr\Retrieve(new Client\RequestOptions\PnrRetrieveOptions([
            'recordLocator' => 'ABC123'
        ]));

        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_Retrieve', $expectedPnrResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_Retrieve' => ['version' => "14.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'PNR_Retrieve')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->pnrRetrieve(new Client\RequestOptions\PnrRetrieveOptions(['recordLocator' => 'ABC123']));

        $this->assertEquals($messageResult, $response);
    }


    public function testCanDoDummyPnrRetrieveAndDisplayCall()
    {
        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'A dummy message result'; // Not an actual XML reply.
        $mockedSendResult->responseObject = new \stdClass();
        $mockedSendResult->responseObject->dummyprop = 'A dummy property'; // Not an actual response property.

        $messageResult = new Client\Result($mockedSendResult);

        $expectedPnrResult = new Client\Struct\Pnr\RetrieveAndDisplay('ABC123');

        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_RetrieveAndDisplay', $expectedPnrResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_RetrieveAndDisplay' => ['version' => "14.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'PNR_RetrieveAndDisplay')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->pnrRetrieveAndDisplay(new Client\RequestOptions\PnrRetrieveAndDisplayOptions(['recordLocator' => 'ABC123']));

        $this->assertEquals($messageResult, $response);
    }


    public function testCanDoCreatePnrCall()
    {
        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'A dummy message result'; // Not an actual XML reply.
        $mockedSendResult->responseObject = new \stdClass();
        $mockedSendResult->responseObject->dummyprop = 'A dummy message result'; // Not an actual response property

        $messageResult = new Client\Result($mockedSendResult);

        $options = new Client\RequestOptions\PnrCreatePnrOptions();
        $options->actionCode = 11; //11 End transact with retrieve (ER)
        $options->travellers[] = new Client\RequestOptions\Pnr\Traveller([
            'number' => 1,
            'firstName' => 'FirstName',
            'lastName' => 'LastName'
        ]);
        $options->itineraries = [
            new Client\RequestOptions\Pnr\Itinerary([
                'segments' => [
                    new Client\RequestOptions\Pnr\Segment\Miscellaneous([
                        'status ' => Client\RequestOptions\Pnr\Segment::STATUS_CONFIRMED,
                        'company' => '1A',
                        'date' => \DateTime::createFromFormat('Ymd', '20161022', new \DateTimeZone('UTC')),
                        'cityCode' => 'BRU',
                        'freeText' => 'DUMMY MISCELLANEOUS SEGMENT'
                    ])
                ]
            ])
        ];
        $options->elements[] = new Client\RequestOptions\Pnr\Element\Ticketing([
            'ticketMode' => 'OK'
        ]);
        $options->elements[] = new Client\RequestOptions\Pnr\Element\Contact([
            'type' => Client\RequestOptions\Pnr\Element\Contact::TYPE_PHONE_MOBILE,
            'value' => '+3222222222'
        ]);
        $options->defaultReceivedFrom = 'some RF string amabnl-amadeus-ws-client-' . Client::VERSION;
        $options->autoAddReceivedFrom = true;

        $expectedPnrResult = new Client\Struct\Pnr\AddMultiElements($options);

        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_AddMultiElements', $expectedPnrResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_AddMultiElements' => ['version' => "14.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'PNR_AddMultiElements')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->pnrCreatePnr($options);

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoAddMultiElementsSavePNR()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseObject = new \stdClass();
        $mockedSendResult->responseObject->dummyProp = 'A dummy message result'; // Not an actual Soap reply.
        $mockedSendResult->responseXml = 'A dummy message result'; // Not an actual XML reply

        $messageResult = new Client\Result($mockedSendResult);

        $options = new Client\RequestOptions\PnrAddMultiElementsOptions([
            'actionCode' => Client\RequestOptions\PnrAddMultiElementsOptions::ACTION_END_TRANSACT_RETRIEVE,
        ]);

        /** @var Client\RequestOptions\PnrAddMultiElementsOptions $expectedResultOpt */
        $expectedResultOpt = clone $options;
        $expectedResultOpt->receivedFrom = 'some RF string ' . Client::RECEIVED_FROM_IDENTIFIER . '-' . Client::VERSION;

        $expectedPnrResult = new Client\Struct\Pnr\AddMultiElements($expectedResultOpt);

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_AddMultiElements', $expectedPnrResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));

        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_AddMultiElements' => ['version' => "14.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'PNR_AddMultiElements')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->pnrAddMultiElements($options);

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoAddMultiElementsSavePNRWithRf()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseObject = new \stdClass();
        $mockedSendResult->responseObject->dummyProp = 'A dummy message result'; // Not an actual Soap reply.
        $mockedSendResult->responseXml = 'A dummy message result'; // Not an actual XML reply

        $messageResult = new Client\Result($mockedSendResult);

        $options = new Client\RequestOptions\PnrAddMultiElementsOptions();
        $options->actionCode = 11; //11 End transact with retrieve (ER)
        $options->receivedFrom = 'a unit test machine thingie';

        $expectedPnrResult = new Client\Struct\Pnr\AddMultiElements($options);

        $expectedPnrResult->dataElementsMaster = new Client\Struct\Pnr\AddMultiElements\DataElementsMaster();

        $receivedFromElement = new Client\Struct\Pnr\AddMultiElements\DataElementsIndiv(Client\Struct\Pnr\AddMultiElements\ElementManagementData::SEGNAME_RECEIVE_FROM, 2);
        $receivedFromElement->freetextData = new Client\Struct\Pnr\AddMultiElements\FreetextData(
            'a unit test machine thingie',
            Client\Struct\Pnr\AddMultiElements\FreetextDetail::TYPE_RECEIVE_FROM
        );

        $expectedPnrResult->dataElementsMaster->dataElementsIndiv[] = $receivedFromElement;

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_AddMultiElements', $expectedPnrResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));

        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_AddMultiElements' => ['version' => "14.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'PNR_AddMultiElements')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->pnrAddMultiElements($options);

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoDummyPnrCancelCall()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseObject = new \stdClass();
        $mockedSendResult->responseObject->dummyProp = 'A dummy message result'; // Not an actual Soap reply.
        $mockedSendResult->responseXml = 'A dummy message result'; // Not an actual XML reply

        $messageResult = new Client\Result($mockedSendResult);

        $expectedPnrResult = new Client\Struct\Pnr\Cancel(
            new Client\RequestOptions\PnrCancelOptions([
                'actionCode' => 10,
                'cancelItinerary' => true
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_Cancel', $expectedPnrResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_Cancel' => ['version' => "14.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'PNR_Cancel')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->pnrCancel(
            new Client\RequestOptions\PnrCancelOptions([
                'actionCode' => 10,
                'cancelItinerary' => true
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoDummyPnrSplit()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseObject = new \stdClass();
        $mockedSendResult->responseObject->dummyProp = 'A dummy message result'; // Not an actual Soap reply.
        $mockedSendResult->responseXml = 'A dummy message result'; // Not an actual XML reply

        $messageResult = new Client\Result($mockedSendResult);

        $expectedPnrResult = new Client\Struct\Pnr\Split(
            new Client\RequestOptions\PnrSplitOptions(['passengerTattoos' => [1, 2]])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_Split', $expectedPnrResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_Split' => ['version' => "14.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'PNR_Split')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->pnrSplit(
            new Client\RequestOptions\PnrSplitOptions(['passengerTattoos' => [1, 2]])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoDummyPnrDisplayHistoryCall()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseObject = new \stdClass();
        $mockedSendResult->responseObject->dummyProp = 'A dummy message result'; // Not an actual Soap reply.
        $mockedSendResult->responseXml = 'A dummy message result'; // Not an actual XML reply

        $messageResult = new Client\Result($mockedSendResult);

        $expectedPnrResult = new Client\Struct\Pnr\DisplayHistory(
            new Client\RequestOptions\PnrDisplayHistoryOptions([
                'recordLocator' => 'ABC123'
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_DisplayHistory', $expectedPnrResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_DisplayHistory' => ['version' => "14.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'PNR_DisplayHistory')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->pnrDisplayHistory(
            new Client\RequestOptions\PnrDisplayHistoryOptions([
                'recordLocator' => 'ABC123'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoDummyPnrTransferOwnershipCall()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseObject = new \stdClass();
        $mockedSendResult->responseObject->dummyProp = 'A dummy message result'; // Not an actual Soap reply.
        $mockedSendResult->responseXml = 'A dummy message result'; // Not an actual XML reply

        $messageResult = new Client\Result($mockedSendResult);

        $expectedPnrResult = new Client\Struct\Pnr\TransferOwnership(
            new Client\RequestOptions\PnrTransferOwnershipOptions([
                'recordLocator' => 'ABC123',
                'newOffice' => 'BRUXXXXXX'
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_TransferOwnership', $expectedPnrResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_TransferOwnership' => ['version' => "14.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'PNR_TransferOwnership')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->pnrTransferOwnership(
            new Client\RequestOptions\PnrTransferOwnershipOptions([
                'recordLocator' => 'ABC123',
                'newOffice' => 'BRUXXXXXX'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendPnrNameChange()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseObject = new \stdClass();
        $mockedSendResult->responseObject->dummyProp = 'A dummy message result'; // Not an actual Soap reply.
        $mockedSendResult->responseXml = $this->getTestFile('pnrNameChangeReply141.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedPnrResult = new Client\Struct\Pnr\NameChange(
            new Client\RequestOptions\PnrNameChangeOptions([
                'operation' => Client\RequestOptions\PnrNameChangeOptions::OPERATION_CHANGE,
                'passengers' => [
                    new Client\RequestOptions\Pnr\NameChange\Passenger([
                        'reference' => 1,
                        'type' => 'ADT',
                        'lastName' => 'SURNAME',
                        'firstName' => 'GIVENNAME MR'
                    ])
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_NameChange', $expectedPnrResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_NameChange' => ['version' => "14.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'PNR_NameChange')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->pnrNameChange(
            new Client\RequestOptions\PnrNameChangeOptions([
                'operation' => Client\RequestOptions\PnrNameChangeOptions::OPERATION_CHANGE,
                'passengers' => [
                    new Client\RequestOptions\Pnr\NameChange\Passenger([
                        'reference' => 1,
                        'type' => 'ADT',
                        'lastName' => 'SURNAME',
                        'firstName' => 'GIVENNAME MR'
                    ])
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }


    public function testCanDoDummyQueueListCall()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $lastResponse = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3" xmlns:wsa="http://www.w3.org/2005/08/addressing"><SOAP-ENV:Header><wsa:To>http://www.w3.org/2005/08/addressing/anonymous</wsa:To><wsa:From><wsa:Address>https://dummy.endpoint/ENDPOINT</wsa:Address></wsa:From><wsa:Action>http://webservices.amadeus.com/QDQLRQ_11_1_1A</wsa:Action><wsa:MessageID>urn:uuid:916bb446-a6fc-b8a4-b543-ce4b8ba124e1</wsa:MessageID><wsa:RelatesTo RelationshipType="http://www.w3.org/2005/08/addressing/reply">86653CF8-2017-2F7C-AFC2-BD07B22BD185</wsa:RelatesTo><awsse:Session TransactionStatusCode="End"><awsse:SessionId>SESSIONID</awsse:SessionId><awsse:SequenceNumber>1</awsse:SequenceNumber><awsse:SecurityToken>SECTOKEN</awsse:SecurityToken></awsse:Session></SOAP-ENV:Header><SOAP-ENV:Body><Queue_ListReply xmlns="http://xml.amadeus.com/QDQLRR_11_1_1A"><queueView><agent><originatorDetails><inHouseIdentification1>BRU1AXXXX</inHouseIdentification1></originatorDetails></agent><queueNumber><queueDetails><number>0</number></queueDetails></queueNumber><categoryDetails><subQueueInfoDetails><identificationType>C</identificationType><itemNumber>0</itemNumber></subQueueInfoDetails></categoryDetails><pnrCount><quantityDetails><numberOfUnit>1</numberOfUnit></quantityDetails></pnrCount><pnrCount><quantityDetails><numberOfUnit>1</numberOfUnit></quantityDetails></pnrCount><pnrCount><quantityDetails><numberOfUnit>1</numberOfUnit></quantityDetails></pnrCount><item><paxName><paxDetails><surname>TURBO</surname><type>0</type><quantity>0</quantity></paxDetails></paxName><recLoc><reservation><controlNumber>23TCZS</controlNumber></reservation></recLoc><agent><originatorDetails><inHouseIdentification1>BRU1AXXXX</inHouseIdentification1><inHouseIdentification2>WS</inHouseIdentification2></originatorDetails></agent><pnrdates><timeMode>700</timeMode><dateTime><year>2015</year><month>12</month><day>11</day></dateTime></pnrdates><pnrdates><timeMode>701</timeMode><dateTime><year>2016</year><month>1</month><day>5</day></dateTime></pnrdates><pnrdates><timeMode>711</timeMode><dateTime><year>2015</year><month>12</month><day>11</day><hour>12</hour><minutes>28</minutes></dateTime></pnrdates></item></queueView></Queue_ListReply></SOAP-ENV:Body></SOAP-ENV:Envelope>';
        $messageResult = '<Queue_ListReply xmlns="http://xml.amadeus.com/QDQLRR_11_1_1A"><queueView><agent><originatorDetails><inHouseIdentification1>BRU1AXXXX</inHouseIdentification1></originatorDetails></agent><queueNumber><queueDetails><number>0</number></queueDetails></queueNumber><categoryDetails><subQueueInfoDetails><identificationType>C</identificationType><itemNumber>0</itemNumber></subQueueInfoDetails></categoryDetails><pnrCount><quantityDetails><numberOfUnit>1</numberOfUnit></quantityDetails></pnrCount><pnrCount><quantityDetails><numberOfUnit>1</numberOfUnit></quantityDetails></pnrCount><pnrCount><quantityDetails><numberOfUnit>1</numberOfUnit></quantityDetails></pnrCount><item><paxName><paxDetails><surname>TURBO</surname><type>0</type><quantity>0</quantity></paxDetails></paxName><recLoc><reservation><controlNumber>23TCZS</controlNumber></reservation></recLoc><agent><originatorDetails><inHouseIdentification1>BRU1AXXXX</inHouseIdentification1><inHouseIdentification2>WS</inHouseIdentification2></originatorDetails></agent><pnrdates><timeMode>700</timeMode><dateTime><year>2015</year><month>12</month><day>11</day></dateTime></pnrdates><pnrdates><timeMode>701</timeMode><dateTime><year>2016</year><month>1</month><day>5</day></dateTime></pnrdates><pnrdates><timeMode>711</timeMode><dateTime><year>2015</year><month>12</month><day>11</day><hour>12</hour><minutes>28</minutes></dateTime></pnrdates></item></queueView></Queue_ListReply>';

        $sendResult = new Client\Session\Handler\SendResult();
        $sendResult->responseXml = $messageResult;

        $expected = new Client\Result($sendResult);

        $expectedMessageResult = new Client\Struct\Queue\QueueList(
            new Client\RequestOptions\QueueListOptions([
                'queue' => new Client\RequestOptions\Queue([
                    'queue' => 50,
                    'category' => 0
                ])
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Queue_List', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($sendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse')
            ->will($this->returnValue($lastResponse));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Queue_List' => ['version' => "11.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($sendResult, 'Queue_List')
            ->will($this->returnValue($expected));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->queueList(
            new Client\RequestOptions\QueueListOptions([
                'queue' => new Client\RequestOptions\Queue([
                    'queue' => 50,
                    'category' => 0
                ])
            ])
        );


        $this->assertEquals($expected, $response);
    }

    public function testCanDoPlacePNRCall()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dumyplacepnrmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Queue\PlacePnr(
            'ABC123',
            'BRUXX0000',
            new Client\RequestOptions\Queue([
                'queue' => 50,
                'category' => 0
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Queue_PlacePNR', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Queue_PlacePNR' => ['version' => "11.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Queue_PlacePNR')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->queuePlacePnr(
            new Client\RequestOptions\QueuePlacePnrOptions([
                'recordLocator' => 'ABC123',
                'sourceOfficeId' => 'BRUXX0000',
                'targetQueue' => new Client\RequestOptions\Queue(['queue' => 50, 'category' => 0])
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoQueueRemoveItemCall()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dumyremoveitemmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Queue\RemoveItem(new Client\RequestOptions\Queue(['queue' => 50, 'category' => 0]), 'ABC123', 'BRUXX0000');

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Queue_RemoveItem', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Queue_RemoveItem' => ['version' => "11.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Queue_RemoveItem')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->queueRemoveItem(
            new Client\RequestOptions\QueueRemoveItemOptions([
                'recordLocator' => 'ABC123',
                'originatorOfficeId' => 'BRUXX0000',
                'queue' => new Client\RequestOptions\Queue(['queue' => 50, 'category' => 0])
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoQueueMoveItemCall()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummymoveitemmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Queue\MoveItem('ABC123', 'BRUXX0000', new Client\RequestOptions\Queue(['queue' => 50, 'category' => 0]), new Client\RequestOptions\Queue(['queue' => 60, 'category' => 5]));

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Queue_MoveItem', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Queue_MoveItem' => ['version' => "11.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Queue_MoveItem')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->queueMoveItem(
            new Client\RequestOptions\QueueMoveItemOptions([
                'recordLocator' => 'ABC123',
                'officeId' => 'BRUXX0000',
                'sourceQueue' => new Client\RequestOptions\Queue(['queue' => 50, 'category' => 0]),
                'destinationQueue' => new Client\RequestOptions\Queue(['queue' => 60, 'category' => 5])
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanCrypticCall()
    {
        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummycrypticresponse';
        $mockedSendResult->responseObject = new \stdClass();
        $mockedSendResult->responseObject->dummyprop = 'dummycrypticresponse';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Command\Cryptic('DAC BRU');

        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Command_Cryptic', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Command_Cryptic' => ['version' => "5.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Command_Cryptic')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->commandCryptic(
            new Client\RequestOptions\CommandCrypticOptions([
                'entry' => 'DAC BRU'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendMiniRuleGetFromRec()
    {
        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('miniRuleGetFromRecReply18.1.txt');
        $mockedSendResult->responseObject = new \stdClass();

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\MiniRule\GetFromRec(
            new Client\RequestOptions\MiniRuleGetFromRecOptions([
                    'pricings' => [
                        new Client\RequestOptions\MiniRule\Pricing([
                            'type' => Client\RequestOptions\MiniRule\Pricing::TYPE_RECORD_LOCATOR,
                            'id' => 'XXXXXX'
                        ])
                    ]
                ]
            )
        );

        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('MiniRule_GetFromRec', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['MiniRule_GetFromRec' => ['version' => "18.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'MiniRule_GetFromRec')
            ->will($this->returnValue($messageResult));


        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->miniRuleGetFromRec(
            new Client\RequestOptions\MiniRuleGetFromRecOptions([
                'pricings' => [
                    new Client\RequestOptions\MiniRule\Pricing([
                        'type' => Client\RequestOptions\MiniRule\Pricing::TYPE_RECORD_LOCATOR,
                        'id' => "XXXXXX"
                    ])
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendMiniRuleGetFromPricingRec()
    {
        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyminiruleresponse';
        $mockedSendResult->responseObject = new \stdClass();
        $mockedSendResult->responseObject->dummyprop = 'dummyminiruleresponse';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\MiniRule\GetFromPricingRec(
            new Client\RequestOptions\MiniRuleGetFromPricingRecOptions([
                    'pricings' => [
                        new Client\RequestOptions\MiniRule\Pricing([
                            'type' => Client\RequestOptions\MiniRule\Pricing::TYPE_OFFER,
                            'id' => Client\RequestOptions\MiniRule\Pricing::ALL_PRICINGS
                        ])
                    ]
                ]
            )
        );

        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('MiniRule_GetFromPricingRec', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['MiniRule_GetFromPricingRec' => ['version' => "11.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'MiniRule_GetFromPricingRec')
            ->will($this->returnValue($messageResult));


        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->miniRuleGetFromPricingRec(
            new Client\RequestOptions\MiniRuleGetFromPricingRecOptions([
                'pricings' => [
                    new Client\RequestOptions\MiniRule\Pricing([
                        'type' => Client\RequestOptions\MiniRule\Pricing::TYPE_OFFER,
                        'id' => Client\RequestOptions\MiniRule\Pricing::ALL_PRICINGS
                    ])
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendMiniRuleGetFromPricing()
    {
        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('miniRuleGetFromPricing11Reply.txt');
        $mockedSendResult->responseObject = new \stdClass();

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\MiniRule\GetFromPricing(
            new Client\RequestOptions\MiniRuleGetFromPricingOptions([
                'pricings' => [1]
            ])
        );

        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('MiniRule_GetFromPricing', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['MiniRule_GetFromPricing' => ['version' => "11.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'MiniRule_GetFromPricing')
            ->will($this->returnValue($messageResult));


        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->miniRuleGetFromPricing(
            new Client\RequestOptions\MiniRuleGetFromPricingOptions([
                'pricings' => [1]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendMiniRuleGetFromETicket()
    {
        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('miniRuleGetFromETicketReply131.txt');
        $mockedSendResult->responseObject = new \stdClass();

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\MiniRule\GetFromETicket(
            new Client\RequestOptions\MiniRuleGetFromETicketOptions([
                'eTicket' => '1234567891987'
            ])
        );

        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('MiniRule_GetFromETicket', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['MiniRule_GetFromETicket' => ['version' => "13.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'MiniRule_GetFromETicket')
            ->will($this->returnValue($messageResult));


        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->miniRuleGetFromETicket(
            new Client\RequestOptions\MiniRuleGetFromETicketOptions([
                'eTicket' => '1234567891987'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoOfferCreateCall()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('offerCreateOfferReply132.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Offer\Create(
            new Client\RequestOptions\OfferCreateOptions([
                'airRecommendations' => [
                    new Client\RequestOptions\Offer\AirRecommendation([
                        'type' => Client\RequestOptions\Offer\AirRecommendation::TYPE_FARE_RECOMMENDATION_NR,
                        'id' => 2,
                        'paxReferences' => [
                            new Client\RequestOptions\Offer\PassengerDef([
                                'passengerTattoo' => 1
                            ])
                        ]
                    ])
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Offer_CreateOffer', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Offer_CreateOffer' => ['version' => "13.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Offer_CreateOffer')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->offerCreate(
            new Client\RequestOptions\OfferCreateOptions([
                'airRecommendations' => [
                    new Client\RequestOptions\Offer\AirRecommendation([
                        'type' => Client\RequestOptions\Offer\AirRecommendation::TYPE_FARE_RECOMMENDATION_NR,
                        'id' => 2,
                        'paxReferences' => [
                            new Client\RequestOptions\Offer\PassengerDef([
                                'passengerTattoo' => 1
                            ])
                        ]
                    ])
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoOfferVerifyCall()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyofferverifymessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Offer\Verify(
            1,
            Client\Struct\Offer\OfferTattoo::SEGMENT_AIR
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Offer_VerifyOffer', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Offer_VerifyOffer' => ['version' => "11.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Offer_VerifyOffer')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->offerVerify(
            new Client\RequestOptions\OfferVerifyOptions([
                'offerReference' => 1,
                'segmentName' => 'AIR'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoOfferConfirmHotelOffer()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyofferconfirmhotelmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Offer\ConfirmHotel(
            new Client\RequestOptions\OfferConfirmHotelOptions([
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Offer_ConfirmHotelOffer', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Offer_ConfirmHotelOffer' => ['version' => "11.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Offer_ConfirmHotelOffer')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->offerConfirmHotel(
            new Client\RequestOptions\OfferConfirmHotelOptions([
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoOfferConfirmCarOffer()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyofferconfirmcarmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Offer\ConfirmCar(
            new Client\RequestOptions\OfferConfirmCarOptions([
                'passengerTattoo' => 1,
                'offerTattoo' => 2
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Offer_ConfirmCarOffer', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Offer_ConfirmCarOffer' => ['version' => "11.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Offer_ConfirmCarOffer')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->offerConfirmCar(
            new Client\RequestOptions\OfferConfirmCarOptions([
                'passengerTattoo' => 1,
                'offerTattoo' => 2
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoInfoEncodeDecodeCity()
    {
        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyinfo-encodedecodecity-message';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Info\EncodeDecodeCity(
            new Client\RequestOptions\InfoEncodeDecodeCityOptions([
                'locationCode' => 'OPO'
            ])
        );

        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Info_EncodeDecodeCity', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Info_EncodeDecodeCity' => ['version' => "05.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Info_EncodeDecodeCity')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->infoEncodeDecodeCity(
            new Client\RequestOptions\InfoEncodeDecodeCityOptions([
                'locationCode' => 'OPO'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoPointOfRefSearch()
    {
        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyinfo-pointofrefsearch-message';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\PointOfRef\Search(
            new Client\RequestOptions\PointOfRefSearchOptions([
                'targetCategoryCode' => Client\RequestOptions\PointOfRefSearchOptions::TARGET_TRAIN,
                'latitude' => '5099155',
                'longitude' => '332824',
                'searchRadius' => '15000'
            ])
        );

        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PointOfRef_Search', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PointOfRef_Search' => ['version' => "02.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'PointOfRef_Search')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->pointOfRefSearch(
            new Client\RequestOptions\PointOfRefSearchOptions([
                'targetCategoryCode' => Client\RequestOptions\PointOfRefSearchOptions::TARGET_TRAIN,
                'latitude' => '5099155',
                'longitude' => '332824',
                'searchRadius' => '15000'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }


    public function testCanDoTicketCreateTSTFromPricing()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketCreateTSTFromPricingmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\CreateTSTFromPricing(
            new Client\RequestOptions\TicketCreateTstFromPricingOptions([
                'pricings' => [
                    new Client\RequestOptions\Ticket\Pricing([
                        'tstNumber' => 1
                    ])
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Ticket_CreateTSTFromPricing', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_CreateTSTFromPricing' => ['version' => "04.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_CreateTSTFromPricing')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketCreateTSTFromPricing(
            new Client\RequestOptions\TicketCreateTstFromPricingOptions([
                'pricings' => [
                    new Client\RequestOptions\Ticket\Pricing([
                        'tstNumber' => 1
                    ])
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoTicketCreateTSMFromPricing()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketCreateTSMFromPricingmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\CreateTSMFromPricing(
            new Client\RequestOptions\TicketCreateTsmFromPricingOptions([
                'pricings' => [
                    new Client\RequestOptions\Ticket\Pricing([
                        'tsmNumber' => 1
                    ])
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Ticket_CreateTSMFromPricing', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_CreateTSMFromPricing' => ['version' => "09.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_CreateTSMFromPricing')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketCreateTSMFromPricing(
            new Client\RequestOptions\TicketCreateTsmFromPricingOptions([
                'pricings' => [
                    new Client\RequestOptions\Ticket\Pricing([
                        'tsmNumber' => 1
                    ])
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoTicketCreateTSMFareElement()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketCreateTSMFareElementmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\CreateTSMFareElement(
            new Client\RequestOptions\TicketCreateTsmFareElOptions([
                'type' => Client\RequestOptions\TicketCreateTsmFareElOptions::TYPE_FORM_OF_PAYMENT,
                'tattoo' => '18',
                'info' => '#####',
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Ticket_CreateTSMFareElement', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_CreateTSMFareElement' => ['version' => "10.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_CreateTSMFareElement')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketCreateTSMFareElement(
            new Client\RequestOptions\TicketCreateTsmFareElOptions([
                'type' => Client\RequestOptions\TicketCreateTsmFareElOptions::TYPE_FORM_OF_PAYMENT,
                'tattoo' => '18',
                'info' => '#####',
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoTicketDeleteTST()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketDeleteTSTmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\DeleteTST(
            new Client\RequestOptions\TicketDeleteTstOptions([
                'deleteMode' => Client\RequestOptions\TicketDeleteTstOptions::DELETE_MODE_SELECTIVE,
                'tstNumber' => 1
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Ticket_DeleteTST', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_DeleteTST' => ['version' => "04.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_DeleteTST')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketDeleteTST(
            new Client\RequestOptions\TicketDeleteTstOptions([
                'deleteMode' => Client\RequestOptions\TicketDeleteTstOptions::DELETE_MODE_SELECTIVE,
                'tstNumber' => 1
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoTicketDeleteTSMP()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('ticketDeleteTsmpReply81.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\DeleteTSMP(
            new Client\RequestOptions\TicketDeleteTsmpOptions([
                'paxTattoos' => [1, 2]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Ticket_DeleteTSMP', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_DeleteTSMP' => ['version' => "08.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_DeleteTSMP')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketDeleteTSMP(
            new Client\RequestOptions\TicketDeleteTsmpOptions([
                'paxTattoos' => [1, 2]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoTicketDisplayTST()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketDisplayTSTmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\DisplayTST(
            new Client\RequestOptions\TicketDisplayTstOptions([
                'displayMode' => Client\RequestOptions\TicketDisplayTstOptions::MODE_ALL
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Ticket_DisplayTST', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_DisplayTST' => ['version' => "04.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_DisplayTST')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketDisplayTST(
            new Client\RequestOptions\TicketDisplayTstOptions([
                'displayMode' => Client\RequestOptions\TicketDisplayTstOptions::MODE_ALL
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoTicketDisplayTSMP()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketDisplayTSMPmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\DisplayTSMP(
            new Client\RequestOptions\TicketDisplayTsmpOptions([
                'tattoo' => 3
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Ticket_DisplayTSMP', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_DisplayTSMP' => ['version' => "13.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_DisplayTSMP')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketDisplayTSMP(
            new Client\RequestOptions\TicketDisplayTsmpOptions([
                'tattoo' => 3
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoTicketRetrieveListOfTSM()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketRetrieveListOfTSMmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\RetrieveListOfTSM(
            new Client\RequestOptions\TicketRetrieveListOfTSMOptions([
                'tattoo' => 3
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Ticket_RetrieveListOfTSM', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_RetrieveListOfTSM' => ['version' => "13.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_RetrieveListOfTSM')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketRetrieveListOfTSM(
            new Client\RequestOptions\TicketRetrieveListOfTSMOptions()
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoTicketDisplayTSMFareElement()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketDisplayTSMFareElementmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\DisplayTSMFareElement(
            new Client\RequestOptions\TicketDisplayTsmFareElOptions([
                'tattoo' => 18
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Ticket_DisplayTSMFareElement', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_DisplayTSMFareElement' => ['version' => "13.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_DisplayTSMFareElement')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketDisplayTSMFareElement(
            new Client\RequestOptions\TicketDisplayTsmFareElOptions([
                'tattoo' => 18
            ])
        );

        $this->assertEquals($messageResult, $response);
    }


    public function testCanDoTicketCheckEligibility()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketCheckEligibilitymessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\CheckEligibility(
            new Client\RequestOptions\TicketCheckEligibilityOptions([
                'nrOfRequestedPassengers' => 1,
                'passengers' => [
                    new Client\RequestOptions\Fare\MPPassenger([
                        'type' => Client\RequestOptions\Fare\MPPassenger::TYPE_ADULT,
                        'count' => 1
                    ])
                ],
                'flightOptions' => [
                    Client\RequestOptions\TicketCheckEligibilityOptions::FLIGHTOPT_PUBLISHED,
                ],
                'ticketNumbers' => [
                    '1722300000004'
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Ticket_CheckEligibility', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_CheckEligibility' => ['version' => "15.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_CheckEligibility')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketCheckEligibility(
            new Client\RequestOptions\TicketCheckEligibilityOptions([
                'nrOfRequestedPassengers' => 1,
                'passengers' => [
                    new Client\RequestOptions\Fare\MPPassenger([
                        'type' => Client\RequestOptions\Fare\MPPassenger::TYPE_ADULT,
                        'count' => 1
                    ])
                ],
                'flightOptions' => [
                    Client\RequestOptions\TicketCheckEligibilityOptions::FLIGHTOPT_PUBLISHED,
                ],
                'ticketNumbers' => [
                    '1722300000004'
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoTicketCreateTASF()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketCreateTASFmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\CreateTASF(
            new Client\RequestOptions\TicketCreateTasfOptions([
                'passengerTattoo' => new Client\RequestOptions\Ticket\PassengerTattoo([
                    'type' => Client\RequestOptions\Ticket\PassengerTattoo::TYPE_ADULT,
                    'value' => 1,
                ]),
                'monetaryInformation' => new Client\RequestOptions\Ticket\MonetaryInformation([
                    'amount' => 30,
                    'currency' => 'EUR',
                ]),
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Ticket_CreateTASF', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_CreateTASF' => ['version' => "12.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_CreateTASF')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketCreateTASF(
            new Client\RequestOptions\TicketCreateTasfOptions([
                'passengerTattoo' => new Client\RequestOptions\Ticket\PassengerTattoo([
                    'type' => Client\RequestOptions\Ticket\PassengerTattoo::TYPE_ADULT,
                    'value' => 1,
                ]),
                'monetaryInformation' => new Client\RequestOptions\Ticket\MonetaryInformation([
                    'amount' => 30,
                    'currency' => 'EUR',
                ]),
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoTicketAtcShopperMasterPricerTravelBoardSearch()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketAtcShopperMasterPricerTravelBoardSearchMessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\AtcShopperMasterPricerTravelBoardSearch(
            new Client\RequestOptions\TicketAtcShopperMpTbSearchOptions([

            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'Ticket_ATCShopperMasterPricerTravelBoardSearch',
                $expectedMessageResult,
                ['endSession' => false, 'returnXml' => true]
            )
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_ATCShopperMasterPricerTravelBoardSearch' => ['version' => "13.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_ATCShopperMasterPricerTravelBoardSearch')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketAtcShopperMasterPricerTravelBoardSearch(
            new Client\RequestOptions\TicketAtcShopperMpTbSearchOptions([

            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoTicketAtcShopperMasterPricerCalendar()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketAtcShopperMasterPricerCalendarMessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\AtcShopperMasterPricerCalendar(
            new Client\RequestOptions\TicketAtcShopperMpCalendarOptions([

            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'Ticket_ATCShopperMasterPricerCalendar',
                $expectedMessageResult,
                ['endSession' => false, 'returnXml' => true]
            )
            ->willReturn($mockedSendResult);

        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');

        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->willReturn([
                'Ticket_ATCShopperMasterPricerCalendar' => ['version' => '18.2', 'wsdl' => 'dc22e4ee']
            ]);

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_ATCShopperMasterPricerCalendar')
            ->willReturn($messageResult);

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'NYCXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketAtcShopperMasterPricerCalendar(
            new Client\RequestOptions\TicketAtcShopperMpCalendarOptions([

            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoTicketRepricePNRWithBookingClass()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketRepricePNRWithBookingClassMessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\RepricePnrWithBookingClass(
            new Client\RequestOptions\TicketRepricePnrWithBookingClassOptions([
                'exchangeInfo' => [
                    new Client\RequestOptions\Ticket\ExchangeInfoOptions([
                        'number' => 1,
                        'eTickets' => [
                            '9998550225521'
                        ]
                    ])
                ],
                'multiReferences' => [
                    new Client\RequestOptions\Ticket\MultiRefOpt([
                        'references' => [
                            new Client\RequestOptions\Ticket\PaxSegRef([
                                'reference' => 3,
                                'type' => Client\RequestOptions\Ticket\PaxSegRef::TYPE_SEGMENT
                            ]),
                            new Client\RequestOptions\Ticket\PaxSegRef([
                                'reference' => 4,
                                'type' => Client\RequestOptions\Ticket\PaxSegRef::TYPE_SEGMENT
                            ])
                        ]
                    ]),
                    new Client\RequestOptions\Ticket\MultiRefOpt([
                        'references' => [
                            new Client\RequestOptions\Ticket\PaxSegRef([
                                'reference' => 1,
                                'type' => Client\RequestOptions\Ticket\PaxSegRef::TYPE_PASSENGER_ADULT
                            ]),
                            new Client\RequestOptions\Ticket\PaxSegRef([
                                'reference' => 1,
                                'type' => Client\RequestOptions\Ticket\PaxSegRef::TYPE_SERVICE
                            ])
                        ]
                    ]),
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'Ticket_RepricePNRWithBookingClass',
                $expectedMessageResult,
                ['endSession' => false, 'returnXml' => true]
            )
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_RepricePNRWithBookingClass' => ['version' => "14.3", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_RepricePNRWithBookingClass')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketRepricePnrWithBookingClass(
            new Client\RequestOptions\TicketRepricePnrWithBookingClassOptions([
                'exchangeInfo' => [
                    new Client\RequestOptions\Ticket\ExchangeInfoOptions([
                        'number' => 1,
                        'eTickets' => [
                            '9998550225521'
                        ]
                    ])
                ],
                'multiReferences' => [
                    new Client\RequestOptions\Ticket\MultiRefOpt([
                        'references' => [
                            new Client\RequestOptions\Ticket\PaxSegRef([
                                'reference' => 3,
                                'type' => Client\RequestOptions\Ticket\PaxSegRef::TYPE_SEGMENT
                            ]),
                            new Client\RequestOptions\Ticket\PaxSegRef([
                                'reference' => 4,
                                'type' => Client\RequestOptions\Ticket\PaxSegRef::TYPE_SEGMENT
                            ])
                        ]
                    ]),
                    new Client\RequestOptions\Ticket\MultiRefOpt([
                        'references' => [
                            new Client\RequestOptions\Ticket\PaxSegRef([
                                'reference' => 1,
                                'type' => Client\RequestOptions\Ticket\PaxSegRef::TYPE_PASSENGER_ADULT
                            ]),
                            new Client\RequestOptions\Ticket\PaxSegRef([
                                'reference' => 1,
                                'type' => Client\RequestOptions\Ticket\PaxSegRef::TYPE_SERVICE
                            ])
                        ]
                    ]),
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoTicketReissueConfirmedPricing()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketReissueConfirmedPricingMessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\ReissueConfirmedPricing(
            new Client\RequestOptions\TicketReissueConfirmedPricingOptions([
                'eTickets' => ['0572146640300']
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'Ticket_ReissueConfirmedPricing',
                $expectedMessageResult,
                ['endSession' => false, 'returnXml' => true]
            )
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_ReissueConfirmedPricing' => ['version' => "13.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_ReissueConfirmedPricing')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketReissueConfirmedPricing(
            new Client\RequestOptions\TicketReissueConfirmedPricingOptions([
                'eTickets' => ['0572146640300']
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoTicketCancelDocument()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketCancelDocumentMessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\CancelDocument(
            new Client\RequestOptions\TicketCancelDocumentOptions([
                'eTicket' => '1721587458965',
                'airlineStockProvider' => '6X',
                'officeId' => 'NCE6X0100'
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'Ticket_CancelDocument',
                $expectedMessageResult,
                ['endSession' => false, 'returnXml' => true]
            )
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_CancelDocument' => ['version' => "11.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_CancelDocument')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketCancelDocument(
            new Client\RequestOptions\TicketCancelDocumentOptions([
                'eTicket' => '1721587458965',
                'airlineStockProvider' => '6X',
                'officeId' => 'NCE6X0100'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoTicketProcessEDocDocument()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketProcessEDocMessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\ProcessEDoc(
            new Client\RequestOptions\TicketProcessEDocOptions([
                'ticketNumber' => '1721587458965',
                'action' => Client\RequestOptions\TicketProcessEDocOptions::ACTION_EMD_DISPLAY,
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'Ticket_ProcessEDoc',
                $expectedMessageResult,
                ['endSession' => false, 'returnXml' => true]
            )
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_ProcessEDoc' => ['version' => "15.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_ProcessEDoc')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketProcessEDoc(
            new Client\RequestOptions\TicketProcessEDocOptions([
                'ticketNumber' => '1721587458965',
                'action' => Client\RequestOptions\TicketProcessEDocOptions::ACTION_EMD_DISPLAY,
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoTicketProcessETicketDocument()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketProcessETicketMessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\ProcessETicket(
            new Client\RequestOptions\TicketProcessETicketOptions([
                'ticketNumber' => '1721587458965',
                'action' => Client\RequestOptions\TicketProcessETicketOptions::ACTION_ETICKET_DISPLAY,
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'Ticket_ProcessETicket',
                $expectedMessageResult,
                ['endSession' => false, 'returnXml' => true]
            )
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_ProcessETicket' => ['version' => "15.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_ProcessETicket')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketProcessETicket(
            new Client\RequestOptions\TicketProcessETicketOptions([
                'ticketNumber' => '1721587458965',
                'action' => Client\RequestOptions\TicketProcessETicketOptions::ACTION_ETICKET_DISPLAY,
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoOfferConfirmAirOffer()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyofferconfirmairmessage';
        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Offer\ConfirmAir(
            new Client\RequestOptions\OfferConfirmAirOptions([
                'tattooNumber' => 1
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Offer_ConfirmAirOffer', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Offer_ConfirmAirOffer' => ['version' => "11.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Offer_ConfirmAirOffer')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->offerConfirmAir(
            new Client\RequestOptions\OfferConfirmAirOptions([
                'tattooNumber' => 1
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendAirSellFromRecommendation()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyairsellfromrecommendationrmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Air\SellFromRecommendation(
            new Client\RequestOptions\AirSellFromRecommendationOptions([
                'itinerary' => [
                    new Client\RequestOptions\Air\SellFromRecommendation\Itinerary([
                        'from' => 'BRU',
                        'to' => 'LON',
                        'segments' => [
                            new Client\RequestOptions\Air\SellFromRecommendation\Segment([
                                'departureDate' => \DateTime::createFromFormat('YmdHis', '20170120000000', new \DateTimeZone('UTC')),
                                'from' => 'BRU',
                                'to' => 'LGW',
                                'companyCode' => 'SN',
                                'flightNumber' => '123',
                                'bookingClass' => 'Y',
                                'nrOfPassengers' => 1,
                                'statusCode' => Client\RequestOptions\Air\SellFromRecommendation\Segment::STATUS_SELL_SEGMENT
                            ])
                        ]
                    ])
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Air_SellFromRecommendation', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Air_SellFromRecommendation' => ['version' => "5.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Air_SellFromRecommendation')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->airSellFromRecommendation(
            new Client\RequestOptions\AirSellFromRecommendationOptions([
                'itinerary' => [
                    new Client\RequestOptions\Air\SellFromRecommendation\Itinerary([
                        'from' => 'BRU',
                        'to' => 'LON',
                        'segments' => [
                            new Client\RequestOptions\Air\SellFromRecommendation\Segment([
                                'departureDate' => \DateTime::createFromFormat('YmdHis', '20170120000000', new \DateTimeZone('UTC')),
                                'from' => 'BRU',
                                'to' => 'LGW',
                                'companyCode' => 'SN',
                                'flightNumber' => '123',
                                'bookingClass' => 'Y',
                                'nrOfPassengers' => 1,
                                'statusCode' => Client\RequestOptions\Air\SellFromRecommendation\Segment::STATUS_SELL_SEGMENT
                            ])
                        ]
                    ])
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendAirRebookAirSegment()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyairrebookairsegmentmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Air\RebookAirSegment(
            new Client\RequestOptions\AirRebookAirSegmentOptions([
                'bestPricerOption' => 2,
                'itinerary' => [
                    new \Amadeus\Client\RequestOptions\Air\RebookAirSegment\Itinerary([
                        'from' => 'FRA',
                        'to' => 'BKK',
                        'segments' => [
                            new Client\RequestOptions\Air\SellFromRecommendation\Segment([
                                'departureDate' => \DateTime::createFromFormat('YmdHis', '20040308220000', new \DateTimeZone('UTC')),
                                'arrivalDate' => \DateTime::createFromFormat('YmdHis', '20040309141000', new \DateTimeZone('UTC')),
                                'dateVariation' => 1,
                                'from' => 'FRA',
                                'to' => 'BKK',
                                'companyCode' => 'LH',
                                'flightNumber' => '744',
                                'bookingClass' => 'F',
                                'nrOfPassengers' => 1,
                                'statusCode' => Client\RequestOptions\Air\SellFromRecommendation\Segment::STATUS_CANCEL_SEGMENT
                            ])
                        ]
                    ]),
                    new \Amadeus\Client\RequestOptions\Air\RebookAirSegment\Itinerary([
                        'from' => 'FRA',
                        'to' => 'BKK',
                        'segments' => [
                            new Client\RequestOptions\Air\SellFromRecommendation\Segment([
                                'departureDate' => \DateTime::createFromFormat('YmdHis', '20040308220000', new \DateTimeZone('UTC')),
                                'arrivalDate' => \DateTime::createFromFormat('YmdHis', '00000000141000', new \DateTimeZone('UTC')),
                                'from' => 'FRA',
                                'to' => 'BKK',
                                'companyCode' => 'LH',
                                'flightNumber' => '744',
                                'bookingClass' => 'C',
                                'nrOfPassengers' => 1,
                                'statusCode' => Client\RequestOptions\Air\SellFromRecommendation\Segment::STATUS_SELL_SEGMENT
                            ])
                        ]
                    ]),
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Air_RebookAirSegment', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Air_RebookAirSegment' => ['version' => "14.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Air_RebookAirSegment')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->airRebookAirSegment(
            new Client\RequestOptions\AirRebookAirSegmentOptions([
                'bestPricerOption' => 2,
                'itinerary' => [
                    new \Amadeus\Client\RequestOptions\Air\RebookAirSegment\Itinerary([
                        'from' => 'FRA',
                        'to' => 'BKK',
                        'segments' => [
                            new Client\RequestOptions\Air\SellFromRecommendation\Segment([
                                'departureDate' => \DateTime::createFromFormat('YmdHis', '20040308220000', new \DateTimeZone('UTC')),
                                'arrivalDate' => \DateTime::createFromFormat('YmdHis', '20040309141000', new \DateTimeZone('UTC')),
                                'dateVariation' => 1,
                                'from' => 'FRA',
                                'to' => 'BKK',
                                'companyCode' => 'LH',
                                'flightNumber' => '744',
                                'bookingClass' => 'F',
                                'nrOfPassengers' => 1,
                                'statusCode' => Client\RequestOptions\Air\SellFromRecommendation\Segment::STATUS_CANCEL_SEGMENT
                            ])
                        ]
                    ]),
                    new \Amadeus\Client\RequestOptions\Air\RebookAirSegment\Itinerary([
                        'from' => 'FRA',
                        'to' => 'BKK',
                        'segments' => [
                            new Client\RequestOptions\Air\SellFromRecommendation\Segment([
                                'departureDate' => \DateTime::createFromFormat('YmdHis', '20040308220000', new \DateTimeZone('UTC')),
                                'arrivalDate' => \DateTime::createFromFormat('YmdHis', '00000000141000', new \DateTimeZone('UTC')),
                                'from' => 'FRA',
                                'to' => 'BKK',
                                'companyCode' => 'LH',
                                'flightNumber' => '744',
                                'bookingClass' => 'C',
                                'nrOfPassengers' => 1,
                                'statusCode' => Client\RequestOptions\Air\SellFromRecommendation\Segment::STATUS_SELL_SEGMENT
                            ])
                        ]
                    ]),
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendAirFlightInfo()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyairflightinformessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Air\FlightInfo(
            new Client\RequestOptions\AirFlightInfoOptions([
                'airlineCode' => 'SN',
                'flightNumber' => '652',
                'departureDate' => \DateTime::createFromFormat('Y-m-d', '2016-05-18', new \DateTimeZone('UTC')),
                'departureLocation' => 'BRU',
                'arrivalLocation' => 'LIS'
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Air_FlightInfo', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Air_FlightInfo' => ['version' => "7.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Air_FlightInfo')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->airFlightInfo(
            new Client\RequestOptions\AirFlightInfoOptions([
                'airlineCode' => 'SN',
                'flightNumber' => '652',
                'departureDate' => \DateTime::createFromFormat('Y-m-d', '2016-05-18', new \DateTimeZone('UTC')),
                'departureLocation' => 'BRU',
                'arrivalLocation' => 'LIS'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendAirRetrieveSeatMap()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('airRetrieveSeatMapReply142.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Air\RetrieveSeatMap(
            new Client\RequestOptions\AirRetrieveSeatMapOptions([
                'flight' => new Client\RequestOptions\Air\RetrieveSeatMap\FlightInfo([
                    'airline' => 'SN',
                    'flightNumber' => '652',
                    'departureDate' => \DateTime::createFromFormat('Y-m-d', '2016-05-18', new \DateTimeZone('UTC')),
                    'departure' => 'BRU',
                    'arrival' => 'LIS'
                ])
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Air_RetrieveSeatMap', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Air_RetrieveSeatMap' => ['version' => "14.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Air_RetrieveSeatMap')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->airRetrieveSeatMap(
            new Client\RequestOptions\AirRetrieveSeatMapOptions([
                'flight' => new Client\RequestOptions\Air\RetrieveSeatMap\FlightInfo([
                    'airline' => 'SN',
                    'flightNumber' => '652',
                    'departureDate' => \DateTime::createFromFormat('Y-m-d', '2016-05-18', new \DateTimeZone('UTC')),
                    'departure' => 'BRU',
                    'arrival' => 'LIS'
                ])
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendAirMultiAvailability()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('AirMultiAvailabilityReply.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Air\MultiAvailability(
            new Client\RequestOptions\AirMultiAvailabilityOptions([
                'actionCode' => Client\RequestOptions\AirMultiAvailabilityOptions::ACTION_AVAILABILITY,
                'requestOptions' => [
                    new Client\RequestOptions\Air\MultiAvailability\RequestOptions([
                        'departureDate' => \DateTime::createFromFormat('Ymd-His', '20170320-000000', new \DateTimeZone('UTC')),
                        'from' => 'BRU',
                        'to' => 'LIS',
                        'requestType' => Client\RequestOptions\Air\MultiAvailability\RequestOptions::REQ_TYPE_NEUTRAL_ORDER
                    ])
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Air_MultiAvailability', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Air_MultiAvailability' => ['version' => "14.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Air_MultiAvailability')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->airMultiAvailability(
            new Client\RequestOptions\AirMultiAvailabilityOptions([
                'actionCode' => Client\RequestOptions\AirMultiAvailabilityOptions::ACTION_AVAILABILITY,
                'requestOptions' => [
                    new Client\RequestOptions\Air\MultiAvailability\RequestOptions([
                        'departureDate' => \DateTime::createFromFormat('Ymd-His', '20170320-000000', new \DateTimeZone('UTC')),
                        'from' => 'BRU',
                        'to' => 'LIS',
                        'requestType' => Client\RequestOptions\Air\MultiAvailability\RequestOptions::REQ_TYPE_NEUTRAL_ORDER
                    ])
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendFareMasterPricerExpertSearch()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('fareMasterPricerExpertSearchReply-12.4.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fare\MasterPricerExpertSearch(
            new Client\RequestOptions\FareMasterPricerExSearchOptions([
                'nrOfRequestedResults' => 200,
                'nrOfRequestedPassengers' => 1,
                'passengers' => [
                    new Client\RequestOptions\Fare\MPPassenger([
                        'type' => Client\RequestOptions\Fare\MPPassenger::TYPE_ADULT,
                        'count' => 1
                    ])
                ],
                'itinerary' => [
                    new Client\RequestOptions\Fare\MPItinerary([
                        'departureLocation' => new Client\RequestOptions\Fare\MPLocation(['city' => 'BRU']),
                        'arrivalLocation' => new Client\RequestOptions\Fare\MPLocation(['city' => 'LON']),
                        'date' => new Client\RequestOptions\Fare\MPDate([
                            'date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))
                        ])
                    ])
                ],
                'requestedFlightTypes' => [
                    Client\RequestOptions\FareMasterPricerExSearchOptions::FLIGHTTYPE_DIRECT
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_MasterPricerExpertSearch', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_MasterPricerExpertSearch' => ['version' => "12.3", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();
        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Fare_MasterPricerExpertSearch')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->fareMasterPricerExpertSearch(
            new Client\RequestOptions\FareMasterPricerExSearchOptions([
                'nrOfRequestedResults' => 200,
                'nrOfRequestedPassengers' => 1,
                'passengers' => [
                    new Client\RequestOptions\Fare\MPPassenger([
                        'type' => Client\RequestOptions\Fare\MPPassenger::TYPE_ADULT,
                        'count' => 1
                    ])
                ],
                'itinerary' => [
                    new Client\RequestOptions\Fare\MPItinerary([
                        'departureLocation' => new Client\RequestOptions\Fare\MPLocation(['city' => 'BRU']),
                        'arrivalLocation' => new Client\RequestOptions\Fare\MPLocation(['city' => 'LON']),
                        'date' => new Client\RequestOptions\Fare\MPDate([
                            'date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))
                        ])
                    ])
                ],
                'requestedFlightTypes' => [
                    Client\RequestOptions\FareMasterPricerExSearchOptions::FLIGHTTYPE_DIRECT
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendFareMasterPricerTravelBoardSearch()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyfarepricemasterpricertravelboardsearchresponsemessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fare\MasterPricerTravelBoardSearch(
            new Client\RequestOptions\FareMasterPricerTbSearch([
                'nrOfRequestedResults' => 200,
                'nrOfRequestedPassengers' => 1,
                'passengers' => [
                    new Client\RequestOptions\Fare\MPPassenger([
                        'type' => Client\RequestOptions\Fare\MPPassenger::TYPE_ADULT,
                        'count' => 1
                    ])
                ],
                'itinerary' => [
                    new Client\RequestOptions\Fare\MPItinerary([
                        'departureLocation' => new Client\RequestOptions\Fare\MPLocation(['city' => 'BRU']),
                        'arrivalLocation' => new Client\RequestOptions\Fare\MPLocation(['city' => 'LON']),
                        'date' => new Client\RequestOptions\Fare\MPDate([
                            'date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))
                        ])
                    ])
                ],
                'requestedFlightTypes' => [
                    Client\RequestOptions\FareMasterPricerTbSearch::FLIGHTTYPE_DIRECT
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_MasterPricerTravelBoardSearch', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_MasterPricerTravelBoardSearch' => ['version' => "12.3", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Fare_MasterPricerTravelBoardSearch')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->fareMasterPricerTravelBoardSearch(
            new Client\RequestOptions\FareMasterPricerTbSearch([
                'nrOfRequestedResults' => 200,
                'nrOfRequestedPassengers' => 1,
                'passengers' => [
                    new Client\RequestOptions\Fare\MPPassenger([
                        'type' => Client\RequestOptions\Fare\MPPassenger::TYPE_ADULT,
                        'count' => 1
                    ])
                ],
                'itinerary' => [
                    new Client\RequestOptions\Fare\MPItinerary([
                        'departureLocation' => new Client\RequestOptions\Fare\MPLocation(['city' => 'BRU']),
                        'arrivalLocation' => new Client\RequestOptions\Fare\MPLocation(['city' => 'LON']),
                        'date' => new Client\RequestOptions\Fare\MPDate([
                            'date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))
                        ])
                    ])
                ],
                'requestedFlightTypes' => [
                    Client\RequestOptions\FareMasterPricerTbSearch::FLIGHTTYPE_DIRECT
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendFareMasterPricerCalendar()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyfarepricemasterpricercalendarresponsemessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fare\MasterPricerCalendar(
            new Client\RequestOptions\FareMasterPricerCalendarOptions([
                'nrOfRequestedResults' => 200,
                'nrOfRequestedPassengers' => 1,
                'passengers' => [
                    new Client\RequestOptions\Fare\MPPassenger([
                        'type' => Client\RequestOptions\Fare\MPPassenger::TYPE_ADULT,
                        'count' => 1
                    ])
                ],
                'itinerary' => [
                    new Client\RequestOptions\Fare\MPItinerary([
                        'departureLocation' => new Client\RequestOptions\Fare\MPLocation(['city' => 'BRU']),
                        'arrivalLocation' => new Client\RequestOptions\Fare\MPLocation(['city' => 'LON']),
                        'date' => new Client\RequestOptions\Fare\MPDate([
                            'date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC')),
                            'rangeMode' => Client\RequestOptions\Fare\MPDate::RANGEMODE_MINUS_PLUS,
                            'range' => 3,
                        ])
                    ])
                ],
                'requestedFlightTypes' => [
                    Client\RequestOptions\FareMasterPricerTbSearch::FLIGHTTYPE_DIRECT
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_MasterPricerCalendar', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_MasterPricerCalendar' => ['version' => "14.3", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Fare_MasterPricerCalendar')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->fareMasterPricerCalendar(
            new Client\RequestOptions\FareMasterPricerCalendarOptions([
                'nrOfRequestedResults' => 200,
                'nrOfRequestedPassengers' => 1,
                'passengers' => [
                    new Client\RequestOptions\Fare\MPPassenger([
                        'type' => Client\RequestOptions\Fare\MPPassenger::TYPE_ADULT,
                        'count' => 1
                    ])
                ],
                'itinerary' => [
                    new Client\RequestOptions\Fare\MPItinerary([
                        'departureLocation' => new Client\RequestOptions\Fare\MPLocation(['city' => 'BRU']),
                        'arrivalLocation' => new Client\RequestOptions\Fare\MPLocation(['city' => 'LON']),
                        'date' => new Client\RequestOptions\Fare\MPDate([
                            'date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC')),
                            'rangeMode' => Client\RequestOptions\Fare\MPDate::RANGEMODE_MINUS_PLUS,
                            'range' => 3,
                        ])
                    ])
                ],
                'requestedFlightTypes' => [
                    Client\RequestOptions\FareMasterPricerTbSearch::FLIGHTTYPE_DIRECT
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendPriceXplorerExtremeSearch()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummypricexplorerextremesearchresponsemessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\PriceXplorer\ExtremeSearch(
            new Client\RequestOptions\PriceXplorerExtremeSearchOptions([
                'resultAggregationOption' => Client\RequestOptions\PriceXplorerExtremeSearchOptions::AGGR_COUNTRY,
                'origin' => 'BRU',
                'destinations' => ['SYD', 'CBR'],
                'earliestDepartureDate' => \DateTime::createFromFormat('Y-m-d', '2016-08-25', new \DateTimeZone('UTC')),
                'latestDepartureDate' => \DateTime::createFromFormat('Y-m-d', '2016-09-28', new \DateTimeZone('UTC')),
                'searchOffice' => 'LONBG2222'
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PriceXplorer_ExtremeSearch', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PriceXplorer_ExtremeSearch' => ['version' => "10.3", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'PriceXplorer_ExtremeSearch')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->priceXplorerExtremeSearch(
            new Client\RequestOptions\PriceXplorerExtremeSearchOptions([
                'resultAggregationOption' => Client\RequestOptions\PriceXplorerExtremeSearchOptions::AGGR_COUNTRY,
                'origin' => 'BRU',
                'destinations' => ['SYD', 'CBR'],
                'earliestDepartureDate' => \DateTime::createFromFormat('Y-m-d', '2016-08-25', new \DateTimeZone('UTC')),
                'latestDepartureDate' => \DateTime::createFromFormat('Y-m-d', '2016-09-28', new \DateTimeZone('UTC')),
                'searchOffice' => 'LONBG2222'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendSalesReportsDisplayQueryReport()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('SalesReportsDisplayQueryReportReply.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\SalesReports\DisplayQueryReport(
            new Client\RequestOptions\SalesReportsDisplayQueryReportOptions([
                'requestOptions' => [
                    Client\RequestOptions\SalesReportsDisplayQueryReportOptions::SELECT_OFFICE_ALL_AGENTS
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('SalesReports_DisplayQueryReport', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['SalesReports_DisplayQueryReport' => ['version' => "12.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'SalesReports_DisplayQueryReport')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->salesReportsDisplayQueryReport(
            new Client\RequestOptions\SalesReportsDisplayQueryReportOptions([
                'requestOptions' => [
                    Client\RequestOptions\SalesReportsDisplayQueryReportOptions::SELECT_OFFICE_ALL_AGENTS
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendSalesReportsDisplayDailyOrSummarizedReport()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('SalesReportsDisplayDailyOrSummarizedReportReply.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\SalesReports\DisplayDailyOrSummarizedReport(
            new Client\RequestOptions\SalesReportsDisplayDailyOrSummarizedReportOptions([
                'requestOptions' => [
                    Client\RequestOptions\SalesReportsDisplayDailyOrSummarizedReportOptions::SELECT_OFFICE_ALL_AGENTS
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('SalesReports_DisplayDailyOrSummarizedReport', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['SalesReports_DisplayDailyOrSummarizedReport' => ['version' => "12.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'SalesReports_DisplayDailyOrSummarizedReport')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->salesReportsDisplayDailyOrSummarizedReport(
            new Client\RequestOptions\SalesReportsDisplayDailyOrSummarizedReportOptions([
                'requestOptions' => [
                    Client\RequestOptions\SalesReportsDisplayDailyOrSummarizedReportOptions::SELECT_OFFICE_ALL_AGENTS
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendSalesReportsDisplayNetRemitReport()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('SalesReportsDisplayNetRemitReportReply.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\SalesReports\DisplayNetRemitReport(
            new Client\RequestOptions\SalesReportsDisplayNetRemitReportOptions([
                'requestOptions' => [
                    Client\RequestOptions\SalesReportsDisplayNetRemitReportOptions::SELECT_OFFICE_ALL_AGENTS
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('SalesReports_DisplayNetRemitReport', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['SalesReports_DisplayNetRemitReport' => ['version' => "12.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'SalesReports_DisplayNetRemitReport')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->salesReportsDisplayNetRemitReport(
            new Client\RequestOptions\SalesReportsDisplayNetRemitReportOptions([
                'requestOptions' => [
                    Client\RequestOptions\SalesReportsDisplayNetRemitReportOptions::SELECT_OFFICE_ALL_AGENTS
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanFareGetFareRules()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyfaregetfarerulesmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fare\GetFareRules(
            new Client\RequestOptions\FareGetFareRulesOptions([
                'ticketingDate' => \DateTime::createFromFormat('dmY', '23032011'),
                'fareBasis' => 'OA21ERD1',
                'ticketDesignator' => 'DISC',
                'airline' => 'AA',
                'origin' => 'DFW',
                'destination' => 'MKC'
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_GetFareRules', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_GetFareRules' => ['version' => "10.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Fare_GetFareRules')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->fareGetFareRules(
            new Client\RequestOptions\FareGetFareRulesOptions([
                'ticketingDate' => \DateTime::createFromFormat('dmY', '23032011'),
                'fareBasis' => 'OA21ERD1',
                'ticketDesignator' => 'DISC',
                'airline' => 'AA',
                'origin' => 'DFW',
                'destination' => 'MKC'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanFareCheckRules()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyfarecheckrulesmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fare\CheckRules(
            new Client\RequestOptions\FareCheckRulesOptions([
                'recommendations' => [1]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_CheckRules', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_CheckRules' => ['version' => "7.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Fare_CheckRules')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->fareCheckRules(
            new Client\RequestOptions\FareCheckRulesOptions([
                'recommendations' => [1]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanFareConvertCurrency()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyfareconvertcurrencymessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fare\ConvertCurrency(
            new Client\RequestOptions\FareConvertCurrencyOptions([
                'from' => 'EUR',
                'to' => 'USD',
                'amount' => '200',
                'rateOfConversion' => Client\RequestOptions\FareConvertCurrencyOptions::RATE_TYPE_BANKERS_SELLER_RATE
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_ConvertCurrency', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_ConvertCurrency' => ['version' => "8.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Fare_ConvertCurrency')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->fareConvertCurrency(
            new Client\RequestOptions\FareConvertCurrencyOptions([
                'from' => 'EUR',
                'to' => 'USD',
                'amount' => '200',
                'rateOfConversion' => Client\RequestOptions\FareConvertCurrencyOptions::RATE_TYPE_BANKERS_SELLER_RATE
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanFarePricePnrWithBookingClassVersion12()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyfarepricepnrwithbookingclassmessage';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fare\PricePNRWithBookingClass12(
            new Client\RequestOptions\FarePricePnrWithBookingClassOptions([
                'validatingCarrier' => 'SN'
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_PricePNRWithBookingClass', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_PricePNRWithBookingClass' => ['version' => "12.3", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Fare_PricePNRWithBookingClass')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->farePricePnrWithBookingClass(
            new Client\RequestOptions\FarePricePnrWithBookingClassOptions([
                'validatingCarrier' => 'SN'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanFarePricePnrWithBookingClassVersion14()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('farePricePnrWithBookingClassReply14.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fare\PricePNRWithBookingClass13(
            new Client\RequestOptions\FarePricePnrWithBookingClassOptions([
                'validatingCarrier' => 'SN'
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_PricePNRWithBookingClass', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));;
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_PricePNRWithBookingClass' => ['version' => "14.3", 'wsdl' => 'dc22e4ee']]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);


        $response = $client->farePricePnrWithBookingClass(
            new Client\RequestOptions\FarePricePnrWithBookingClassOptions([
                'validatingCarrier' => 'SN'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanFarePricePnrWithLowerFares14()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('farePricePnrWithLowerFaresReply14.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fare\PricePNRWithLowerFares13(
            new Client\RequestOptions\FarePricePnrWithLowerFaresOptions([
                'validatingCarrier' => 'SN'
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_PricePNRWithLowerFares', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));;
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_PricePNRWithLowerFares' => ['version' => "14.1", 'wsdl' => 'dc22e4ee']]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $response = $client->farePricePnrWithLowerFares(
            new Client\RequestOptions\FarePricePnrWithLowerFaresOptions([
                'validatingCarrier' => 'SN'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanFarePricePnrWithLowerFaresVersion12()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyfarepricepnrwithlowerfaresv12message';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fare\PricePNRWithLowerFares12(
            new Client\RequestOptions\FarePricePnrWithLowerFaresOptions([
                'validatingCarrier' => 'SN'
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_PricePNRWithLowerFares', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_PricePNRWithLowerFares' => ['version' => "12.4", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Fare_PricePNRWithLowerFares')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->farePricePnrWithLowerFares(
            new Client\RequestOptions\FarePricePnrWithLowerFaresOptions([
                'validatingCarrier' => 'SN'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanFarePricePnrWithLowestFare14()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('farePricePnrWithLowestFareReply14.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fare\PricePNRWithLowestFare13(
            new Client\RequestOptions\FarePricePnrWithLowestFareOptions([
                'validatingCarrier' => 'SN'
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_PricePNRWithLowestFare', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));;
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_PricePNRWithLowestFare' => ['version' => "14.1", 'wsdl' => 'dc22e4ee']]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $response = $client->farePricePnrWithLowestFare(
            new Client\RequestOptions\FarePricePnrWithLowestFareOptions([
                'validatingCarrier' => 'SN'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanFarePricePnrWithLowestFareVersion12()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyfarepricepnrwithlowestfarev12message';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fare\PricePNRWithLowestFare12(
            new Client\RequestOptions\FarePricePnrWithLowestFareOptions([
                'validatingCarrier' => 'SN'
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_PricePNRWithLowestFare', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_PricePNRWithLowestFare' => ['version' => "12.4", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Fare_PricePNRWithLowestFare')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->farePricePnrWithLowestFare(
            new Client\RequestOptions\FarePricePnrWithLowestFareOptions([
                'validatingCarrier' => 'SN'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanFareInformativePricingWithoutPnrVersion14()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('fareInformativePricingWithoutPnrReply14.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fare\InformativePricingWithoutPNR13(
            new Client\RequestOptions\FareInformativePricingWithoutPnrOptions([
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_InformativePricingWithoutPNR', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));;
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_InformativePricingWithoutPNR' => ['version' => "15.1", 'wsdl' => 'dc22e4ee']]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);


        $response = $client->fareInformativePricingWithoutPnr(
            new Client\RequestOptions\FareInformativePricingWithoutPnrOptions([
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanFareInformativeBestPricingWithoutPnrVersion15()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('fareInformativeBestPricingWithoutPnrReply14.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fare\InformativeBestPricingWithoutPNR13(
            new Client\RequestOptions\FareInformativeBestPricingWithoutPnrOptions([
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_InformativeBestPricingWithoutPNR', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));;
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_InformativeBestPricingWithoutPNR' => ['version' => "14.1", 'wsdl' => 'dc22e4ee']]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);


        $response = $client->fareInformativeBestPricingWithoutPnr(
            new Client\RequestOptions\FareInformativeBestPricingWithoutPnrOptions([
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanFarePriceUpsellWithoutPnrVersion18()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('farePriceUpsellWithoutPnrReply18.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fare\PriceUpsellWithoutPNR(
            new Client\RequestOptions\FarePriceUpsellWithoutPnrOptions([])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_PriceUpsellWithoutPNR', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));;
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_PriceUpsellWithoutPNR' => ['version' => '18.1', 'wsdl' => 'dc22e4ee']]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);


        $response = $client->farePriceUpsellWithoutPnr(
            new Client\RequestOptions\FarePriceUpsellWithoutPnrOptions([])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanFareGetFamilyDescriptionVersion18()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('fareGetFareFamilyDescriptionReply18.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fare\GetFareFamilyDescription(
            new Client\RequestOptions\FareGetFareFamilyDescriptionOptions([])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_GetFareFamilyDescription', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_GetFareFamilyDescription' => ['version' => '18.1', 'wsdl' => 'dc22e4ee']]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $response = $client->fareGetFareFamilyDescription(
            new Client\RequestOptions\FareGetFareFamilyDescriptionOptions([])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendServiceIntegratedPricing()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('serviceIntegratedPricingReply151.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Service\IntegratedPricing(
            new Client\RequestOptions\ServiceIntegratedPricingOptions([
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Service_IntegratedPricing', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));;
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Service_IntegratedPricing' => ['version' => "15.1", 'wsdl' => 'dc22e4ee']]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);


        $response = $client->serviceIntegratedPricing(
            new Client\RequestOptions\ServiceIntegratedPricingOptions([
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendServiceIntegratedCatalogue()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('serviceIntegratedCatalogueReply142.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Service\IntegratedCatalogue(
            new Client\RequestOptions\ServiceIntegratedCatalogueOptions([
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Service_IntegratedCatalogue', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));;
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Service_IntegratedCatalogue' => ['version' => "14.2", 'wsdl' => 'dc22e4ee']]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);


        $response = $client->serviceIntegratedCatalogue(
            new Client\RequestOptions\ServiceIntegratedCatalogueOptions([
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendServiceBookPriceService()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('serviceBookPriceServiceReply.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $opts = new Client\RequestOptions\ServiceBookPriceServiceOptions([
            'services' => [new Client\RequestOptions\Service\BookPriceService\Service([
                'TID' => 1,
                'serviceProvider' => 'LH',
                'identifier' => new Client\RequestOptions\Service\BookPriceService\Identifier([
                    'bookingMethod' => '01',
                    'RFIC' => 'F',
                    'RFISC' => '040'
                ])
            ])]
        ]);
        $expectedMessageResult = new Client\Struct\Service\BookPriceService($opts);

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Service_BookPriceService', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));;
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Service_BookPriceService' => ['version' => "14.2", 'wsdl' => 'dc22e4ee']]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);


        $response = $client->serviceBookPriceService(
            new Client\RequestOptions\ServiceBookPriceServiceOptions($opts)
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendServiceStandaloneCatalogue()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('serviceStandaloneCatalogueReply.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Service\StandaloneCatalogue(
            new Client\RequestOptions\ServiceStandaloneCatalogueOptions([
                'passengers' => [
                    new Client\RequestOptions\Service\StandaloneCatalogue\ServicePassenger([
                        'reference' => 1,
                        'type' => Client\RequestOptions\Service\StandaloneCatalogue\ServicePassenger::TYPE_ADULT
                    ])
                ],
                'segments' => [
                    new Client\RequestOptions\Fare\InformativePricing\Segment([
                        'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2019-06-13 10:10:00'),
                        'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2019-06-13 13:40:00'),
                        'from' => 'BOM',
                        'to' => 'DXB',
                        'marketingCompany' => 'EK',
                        'operatingCompany' => 'EK',
                        'flightNumber' => '505',
                        'bookingClass' => 'K',
                        'groupNumber' => 'V',
                        'segmentTattoo' => 1
                    ]),
                    new Client\RequestOptions\Fare\InformativePricing\Segment([
                        'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2019-06-27 21:55:00'),
                        'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2019-06-28 02:30:00'),
                        'from' => 'DXB',
                        'to' => 'BOM',
                        'marketingCompany' => 'EK',
                        'operatingCompany' => 'EK',
                        'flightNumber' => '500',
                        'bookingClass' => 'B',
                        'groupNumber' => 'V',
                        'segmentTattoo' => 2
                    ])
                ],
                'pricingOptions' => new Client\RequestOptions\Service\StandaloneCatalogue\ServiceStandalonePricingOptions([
                    'pricingsFareBasis' => [
                        new Client\RequestOptions\Fare\PricePnr\FareBasis([
                            'fareBasisCode' => 'KEXESIN1',
                        ])
                    ],
                    'references' => [
                        new Client\RequestOptions\Service\PaxSegRef([
                            'reference' => 1,
                            'type' => 'S'
                        ]),
                        new Client\RequestOptions\Service\PaxSegRef([
                            'reference' => 2,
                            'type' => 'S'
                        ]),
                        new Client\RequestOptions\Service\PaxSegRef([
                            'reference' => 1,
                            'type' => 'P'
                        ])
                    ]
                ])
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Service_StandaloneCatalogue', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));;
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Service_StandaloneCatalogue' => ['version' => "14.2", 'wsdl' => 'dc22e4ee']]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $response = $client->serviceStandaloneCatalogue(
            new Client\RequestOptions\ServiceStandaloneCatalogueOptions([
                'passengers' => [
                    new Client\RequestOptions\Service\StandaloneCatalogue\ServicePassenger([
                        'reference' => 1,
                        'type' => Client\RequestOptions\Service\StandaloneCatalogue\ServicePassenger::TYPE_ADULT
                    ])
                ],
                'segments' => [
                    new Client\RequestOptions\Fare\InformativePricing\Segment([
                        'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2019-06-13 10:10:00'),
                        'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2019-06-13 13:40:00'),
                        'from' => 'BOM',
                        'to' => 'DXB',
                        'marketingCompany' => 'EK',
                        'operatingCompany' => 'EK',
                        'flightNumber' => '505',
                        'bookingClass' => 'K',
                        'groupNumber' => 'V',
                        'segmentTattoo' => 1
                    ]),
                    new Client\RequestOptions\Fare\InformativePricing\Segment([
                        'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2019-06-27 21:55:00'),
                        'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2019-06-28 02:30:00'),
                        'from' => 'DXB',
                        'to' => 'BOM',
                        'marketingCompany' => 'EK',
                        'operatingCompany' => 'EK',
                        'flightNumber' => '500',
                        'bookingClass' => 'B',
                        'groupNumber' => 'V',
                        'segmentTattoo' => 2
                    ])
                ],
                'pricingOptions' => new Client\RequestOptions\Service\StandaloneCatalogue\ServiceStandalonePricingOptions([
                    'pricingsFareBasis' => [
                        new Client\RequestOptions\Fare\PricePnr\FareBasis([
                            'fareBasisCode' => 'KEXESIN1',
                        ])
                    ],
                    'references' => [
                        new Client\RequestOptions\Service\PaxSegRef([
                            'reference' => 1,
                            'type' => 'S'
                        ]),
                        new Client\RequestOptions\Service\PaxSegRef([
                            'reference' => 2,
                            'type' => 'S'
                        ]),
                        new Client\RequestOptions\Service\PaxSegRef([
                            'reference' => 1,
                            'type' => 'P'
                        ])
                    ]
                ])
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendFopCreateFormOfPayment()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = $this->getTestFile('fopCreateForpOfPaymentReply154.txt');

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fop\CreateFormOfPayment(
            new Client\RequestOptions\FopCreateFopOptions([
                'transactionCode' => Client\RequestOptions\FopCreateFopOptions::TRANS_CREATE_FORM_OF_PAYMENT,
                'fopGroup' => [
                    new Client\RequestOptions\Fop\Group([
                        'elementRef' => [
                            new Client\RequestOptions\Fop\ElementRef([
                                'type' => Client\RequestOptions\Fop\ElementRef::TYPE_TST_NUMBER,
                                'value' => 1
                            ])
                        ],
                        'mopInfo' => [
                            new Client\RequestOptions\Fop\MopInfo([
                                'sequenceNr' => 1,
                                'fopCode' => 'VI',
                                'freeFlowText' => 'VI4541099100010016/0919'
                            ]),
                            new Client\RequestOptions\Fop\MopInfo([
                                'sequenceNr' => 2,
                                'fopCode' => 'VI',
                                'freeFlowText' => 'VI4541099100010024/0919/EUR20'
                            ]),
                        ]
                    ])
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('FOP_CreateFormOfPayment', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));;
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['FOP_CreateFormOfPayment' => ['version' => "15.4", 'wsdl' => 'dc22e4ee']]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);


        $response = $client->fopCreateFormOfPayment(
            new Client\RequestOptions\FopCreateFopOptions([
                'transactionCode' => Client\RequestOptions\FopCreateFopOptions::TRANS_CREATE_FORM_OF_PAYMENT,
                'fopGroup' => [
                    new Client\RequestOptions\Fop\Group([
                        'elementRef' => [
                            new Client\RequestOptions\Fop\ElementRef([
                                'type' => Client\RequestOptions\Fop\ElementRef::TYPE_TST_NUMBER,
                                'value' => 1
                            ])
                        ],
                        'mopInfo' => [
                            new Client\RequestOptions\Fop\MopInfo([
                                'sequenceNr' => 1,
                                'fopCode' => 'VI',
                                'freeFlowText' => 'VI4541099100010016/0919'
                            ]),
                            new Client\RequestOptions\Fop\MopInfo([
                                'sequenceNr' => 2,
                                'fopCode' => 'VI',
                                'freeFlowText' => 'VI4541099100010024/0919/EUR20'
                            ]),
                        ]
                    ])
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoSignOutCall()
    {
        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = '<ns1:Security_SignOut/>';
        $mockedSendResult->responseObject = new \stdClass();
        $mockedSendResult->responseObject->processStatus = new \stdClass();
        $mockedSendResult->responseObject->processStatus->statusCode = 'P';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Security\SignOut();

        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Security_SignOut', $expectedMessageResult, ['endSession' => true, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Security_SignOut' => ['version' => "4.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Security_SignOut')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->securitySignOut();

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoAuthenticateCall()
    {

        $authParams = new Params\AuthParams([
            'officeId' => 'BRUXXXXXX',
            'originatorTypeCode' => 'U',
            'userId' => 'WSXXXXXX',
            'passwordData' => base64_encode('TEST'),
            'passwordLength' => 4,
            'dutyCode' => 'SU',
            'organizationId' => 'DUMMY-ORG',
        ]);

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummy auth response xml';
        $mockedSendResult->responseObject = new \stdClass();
        $mockedSendResult->responseObject->processStatus = new \stdClass();
        $mockedSendResult->responseObject->processStatus->statusCode = 'P';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Security\Authenticate(
            new Client\RequestOptions\SecurityAuthenticateOptions(
                $authParams
            )
        );

        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Security_Authenticate', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Security_Authenticate' => ['version' => "6.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Security_Authenticate')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->authParams = $authParams;
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->securityAuthenticate();

        $this->assertEquals($messageResult, $response);
    }

    /**
     * Github issue #40
     * backwards compatibility for AuthParams at SessionHandlerParams level which must fall back to client authparams
     *
     * to be deprecated with version 2.0
     *
     * @deprecated To be removed with version 2.0
     */
    public function testCanDoAuthenticateCallWithAuthParamsOnSessionHandlerSoapHeader2()
    {
        $sessionHandlerParams = new Params\SessionHandlerParams([
            'authParams' => [
                'officeId' => 'BRUXXXXXX',
                'originatorTypeCode' => 'U',
                'userId' => 'WSXXXXXX',
                'passwordData' => base64_encode('TEST'),
                'passwordLength' => 4,
                'dutyCode' => 'SU',
                'organizationId' => 'DUMMY-ORG',
            ]
        ]);

        $authParams = new Params\AuthParams([
            'officeId' => 'BRUXXXXXX',
            'originatorTypeCode' => 'U',
            'userId' => 'WSXXXXXX',
            'passwordData' => base64_encode('TEST'),
            'passwordLength' => 4,
            'dutyCode' => 'SU',
            'organizationId' => 'DUMMY-ORG',
        ]);

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummy auth response xml';
        $mockedSendResult->responseObject = new \stdClass();
        $mockedSendResult->responseObject->processStatus = new \stdClass();
        $mockedSendResult->responseObject->processStatus->statusCode = 'P';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Security\Authenticate(
            new Client\RequestOptions\SecurityAuthenticateOptions(
                $authParams
            )
        );

        $mockSessionHandler = $this->getMock(
            'Amadeus\Client\Session\Handler\SoapHeader2',
            ['Security_Authenticate', 'getLastResponse', 'getMessagesAndVersions', 'sendMessage'],
            [$sessionHandlerParams],
            '',
            true
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Security_Authenticate', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->atLeastOnce())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Security_Authenticate' => ['version' => "6.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Security_Authenticate')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->sessionHandlerParams = $sessionHandlerParams;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->securityAuthenticate();

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSetSessionData()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $dummySessionData = [
            'sessionId' => 'XFHZEKLRZHREJ',
            'sequenceNumber' => 12,
            'securityToken' => 'RKLERJEZLKRHZEJKLRHEZJKLREZRHEZK'
        ];

        $mockSessionHandler
            ->expects($this->once())
            ->method('setSessionData')
            ->with($dummySessionData)
            ->will($this->returnValue(true));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $result = $client->setSessionData($dummySessionData);

        $this->assertTrue($result);
    }

    public function testCanGetSessionInfo()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSession = [
            'sessionId' => '01ZWHV5EMT',
            'sequenceNumber' => '1',
            'securityToken' => '3WY60GB9B0FX2SLIR756QZ4G2'
        ];

        $mockSessionHandler
            ->expects($this->once())
            ->method('getSessionData')
            ->will($this->returnValue($mockedSession));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $actual = $client->getSessionData();

        $this->assertEquals($mockedSession, $actual);
    }

    public function testWillGetErrorOnInvalidSessionHandlerParams()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid parameters');
        $par = new Params();
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $client->airFlightInfo(new Client\RequestOptions\AirFlightInfoOptions());
    }

    public function testCanSendDocIssuanceIssueTicket()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummydocissuanceissueticketresponse';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\DocIssuance\IssueTicket(
            new Client\RequestOptions\DocIssuanceIssueTicketOptions([
                'options' => [Client\RequestOptions\DocIssuanceIssueTicketOptions::OPTION_ETICKET]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('DocIssuance_IssueTicket', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['DocIssuance_IssueTicket' => ['version' => "9.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'DocIssuance_IssueTicket')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->docIssuanceIssueTicket(
            new Client\RequestOptions\DocIssuanceIssueTicketOptions([
                'options' => [Client\RequestOptions\DocIssuanceIssueTicketOptions::OPTION_ETICKET]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendDocIssuanceIssueMiscellaneousDocuments()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummydocissuanceissuemiscdocresponse';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\DocIssuance\IssueMiscellaneousDocuments(
            new Client\RequestOptions\DocIssuanceIssueMiscDocOptions([
                'options' => [Client\RequestOptions\DocIssuanceIssueMiscDocOptions::OPTION_EMD_ISSUANCE]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('DocIssuance_IssueMiscellaneousDocuments', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['DocIssuance_IssueMiscellaneousDocuments' => ['version' => "15.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'DocIssuance_IssueMiscellaneousDocuments')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->docIssuanceIssueMiscellaneousDocuments(
            new Client\RequestOptions\DocIssuanceIssueMiscDocOptions([
                'options' => [Client\RequestOptions\DocIssuanceIssueMiscDocOptions::OPTION_EMD_ISSUANCE]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendDocIssuanceIssueCombined()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummydocissuanceissuecombinedresponse';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\DocIssuance\IssueCombined(
            new Client\RequestOptions\DocIssuanceIssueCombinedOptions([
                'options' => [
                    new Client\RequestOptions\DocIssuance\Option([
                        'indicator' => Client\RequestOptions\DocIssuance\Option::INDICATOR_DOCUMENT_RECEIPT,
                        'subCompoundType' => 'EMPRA'
                    ])
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('DocIssuance_IssueCombined', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['DocIssuance_IssueCombined' => ['version' => "15.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'DocIssuance_IssueCombined')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->docIssuanceIssueCombined(
            new Client\RequestOptions\DocIssuanceIssueCombinedOptions([
                'options' => [
                    new Client\RequestOptions\DocIssuance\Option([
                        'indicator' => Client\RequestOptions\DocIssuance\Option::INDICATOR_DOCUMENT_RECEIPT,
                        'subCompoundType' => 'EMPRA'
                    ])
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendDocRefundInitRefund()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummydocrefundinitrefundresponse';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\DocRefund\InitRefund(
            new Client\RequestOptions\DocRefundInitRefundOptions([
                'ticketNumber' => '5272404450587',
                'actionCodes' => [
                    Client\RequestOptions\DocRefundInitRefundOptions::ACTION_ATC_REFUND
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('DocRefund_InitRefund', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['DocRefund_InitRefund' => ['version' => "14.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'DocRefund_InitRefund')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->docRefundInitRefund(
            new Client\RequestOptions\DocRefundInitRefundOptions([
                'ticketNumber' => '5272404450587',
                'actionCodes' => [
                    Client\RequestOptions\DocRefundInitRefundOptions::ACTION_ATC_REFUND
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendDocRefundIgnoreRefund()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummydocrefundignorerefundresponse';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\DocRefund\IgnoreRefund(
            new Client\RequestOptions\DocRefundIgnoreRefundOptions()
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('DocRefund_IgnoreRefund', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['DocRefund_IgnoreRefund' => ['version' => "14.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'DocRefund_IgnoreRefund')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->docRefundIgnoreRefund(
            new Client\RequestOptions\DocRefundIgnoreRefundOptions()
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendDocRefundUpdateRefund()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummydocrefundupdaterefundresponse';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\DocRefund\UpdateRefund(
            new Client\RequestOptions\DocRefundUpdateRefundOptions([
                'originator' => '0001AA',
                'originatorId' => '23491193',
                'refundDate' => \DateTime::createFromFormat('Ymd', '20031125'),
                'ticketedDate' => \DateTime::createFromFormat('Ymd', '20030522'),
                'references' => [
                    new Client\RequestOptions\DocRefund\Reference([
                        'type' => Client\RequestOptions\DocRefund\Reference::TYPE_TKT_INDICATOR,
                        'value' => 'Y'
                    ]),
                    new Client\RequestOptions\DocRefund\Reference([
                        'type' => Client\RequestOptions\DocRefund\Reference::TYPE_DATA_SOURCE,
                        'value' => 'F'
                    ])
                ],
                'tickets' => [
                    new Client\RequestOptions\DocRefund\Ticket([
                        'number' => '22021541124593',
                        'ticketGroup' => [
                            new Client\RequestOptions\DocRefund\TickGroupOpt([
                                'couponNumber' => Client\RequestOptions\DocRefund\TickGroupOpt::COUPON_1,
                                'couponStatus' => Client\RequestOptions\DocRefund\TickGroupOpt::STATUS_REFUNDED,
                                'boardingPriority' => 'LH07A'
                            ]),
                            new Client\RequestOptions\DocRefund\TickGroupOpt([
                                'couponNumber' => Client\RequestOptions\DocRefund\TickGroupOpt::COUPON_2,
                                'couponStatus' => Client\RequestOptions\DocRefund\TickGroupOpt::STATUS_REFUNDED,
                                'boardingPriority' => 'LH07A'
                            ]),
                            new Client\RequestOptions\DocRefund\TickGroupOpt([
                                'couponNumber' => Client\RequestOptions\DocRefund\TickGroupOpt::COUPON_3,
                                'couponStatus' => Client\RequestOptions\DocRefund\TickGroupOpt::STATUS_REFUNDED,
                                'boardingPriority' => 'LH07A'
                            ]),
                            new Client\RequestOptions\DocRefund\TickGroupOpt([
                                'couponNumber' => Client\RequestOptions\DocRefund\TickGroupOpt::COUPON_4,
                                'couponStatus' => Client\RequestOptions\DocRefund\TickGroupOpt::STATUS_REFUNDED,
                                'boardingPriority' => 'LH07A'
                            ])
                        ]
                    ]),
                    new Client\RequestOptions\DocRefund\Ticket([
                        'number' => '22021541124604',
                        'ticketGroup' => [
                            new Client\RequestOptions\DocRefund\TickGroupOpt([
                                'couponNumber' => Client\RequestOptions\DocRefund\TickGroupOpt::COUPON_1,
                                'couponStatus' => Client\RequestOptions\DocRefund\TickGroupOpt::STATUS_REFUNDED,
                                'boardingPriority' => 'LH07A'
                            ]),
                            new Client\RequestOptions\DocRefund\TickGroupOpt([
                                'couponNumber' => Client\RequestOptions\DocRefund\TickGroupOpt::COUPON_2,
                                'couponStatus' => Client\RequestOptions\DocRefund\TickGroupOpt::STATUS_REFUNDED,
                                'boardingPriority' => 'LH07A'
                            ])
                        ]
                    ])
                ],
                'travellerPrioDateOfJoining' => \DateTime::createFromFormat('Ymd', '20070101'),
                'travellerPrioReference' => '0077701F',
                'monetaryData' => [
                    new Client\RequestOptions\DocRefund\MonetaryData([
                        'type' => Client\RequestOptions\DocRefund\MonetaryData::TYPE_BASE_FARE,
                        'amount' => 401.00,
                        'currency' => 'EUR'
                    ]),
                    new Client\RequestOptions\DocRefund\MonetaryData([
                        'type' => Client\RequestOptions\DocRefund\MonetaryData::TYPE_FARE_USED,
                        'amount' => 0.00,
                        'currency' => 'EUR'
                    ]),
                    new Client\RequestOptions\DocRefund\MonetaryData([
                        'type' => Client\RequestOptions\DocRefund\MonetaryData::TYPE_FARE_REFUND,
                        'amount' => 401.00,
                        'currency' => 'EUR'
                    ]),
                    new Client\RequestOptions\DocRefund\MonetaryData([
                        'type' => Client\RequestOptions\DocRefund\MonetaryData::TYPE_REFUND_TOTAL,
                        'amount' => 457.74,
                        'currency' => 'EUR'
                    ]),
                    new Client\RequestOptions\DocRefund\MonetaryData([
                        'type' => Client\RequestOptions\DocRefund\MonetaryData::TYPE_TOTAL_TAXES,
                        'amount' => 56.74,
                        'currency' => 'EUR'
                    ]),
                    new Client\RequestOptions\DocRefund\MonetaryData([
                        'type' => 'TP',
                        'amount' => 56.74,
                        'currency' => 'EUR'
                    ]),
                    new Client\RequestOptions\DocRefund\MonetaryData([
                        'type' => 'OBP',
                        'amount' => 0.00,
                        'currency' => 'EUR'
                    ]),
                    new Client\RequestOptions\DocRefund\MonetaryData([
                        'type' => 'TGV',
                        'amount' => 374.93,
                        'currency' => 'EUR'
                    ])
                ],
                'taxData' => [
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => 'H',
                        'rate' => 16.14,
                        'currencyCode' => 'EUR',
                        'type' => 'DE'
                    ]),
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => 'H',
                        'rate' => 3.45,
                        'currencyCode' => 'EUR',
                        'type' => 'YC'
                    ]),
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => 'H',
                        'rate' => 9.67,
                        'currencyCode' => 'EUR',
                        'type' => 'US'
                    ]),
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => 'H',
                        'rate' => 9.67,
                        'currencyCode' => 'EUR',
                        'type' => 'US'
                    ]),
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => 'H',
                        'rate' => 3.14,
                        'currencyCode' => 'EUR',
                        'type' => 'XA'
                    ]),
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => 'H',
                        'rate' => 4.39,
                        'currencyCode' => 'EUR',
                        'type' => 'XY'
                    ]),
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => 'H',
                        'rate' => 6.28,
                        'currencyCode' => 'EUR',
                        'type' => 'AY'
                    ]),
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => 'H',
                        'rate' => 4.00,
                        'currencyCode' => 'EUR',
                        'type' => 'DU'
                    ]),
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => '701',
                        'rate' => 56.74,
                        'currencyCode' => 'EUR',
                        'type' => Client\RequestOptions\DocRefund\TaxData::TYPE_EXTENDED_TAXES
                    ])
                ],
                'formOfPayment' => [
                    new Client\RequestOptions\DocRefund\FopOpt([
                        'fopType' => Client\RequestOptions\DocRefund\FopOpt::TYPE_MISCELLANEOUS,
                        'fopAmount' => 457.74,
                        'freeText' => [
                            new Client\RequestOptions\DocRefund\FreeTextOpt([
                                'type' => 'CFP',
                                'freeText' => '##0##'
                            ]),
                            new Client\RequestOptions\DocRefund\FreeTextOpt([
                                'type' => 'CFP',
                                'freeText' => 'IDBANK'
                            ])
                        ]
                    ])
                ],
                'refundedRouteStations' => [
                    'FRA',
                    'MUC',
                    'JFK',
                    'BKK',
                    'FRA'
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('DocRefund_UpdateRefund', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['DocRefund_UpdateRefund' => ['version' => "14.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'DocRefund_UpdateRefund')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->docRefundUpdateRefund(
            new Client\RequestOptions\DocRefundUpdateRefundOptions([
                'originator' => '0001AA',
                'originatorId' => '23491193',
                'refundDate' => \DateTime::createFromFormat('Ymd', '20031125'),
                'ticketedDate' => \DateTime::createFromFormat('Ymd', '20030522'),
                'references' => [
                    new Client\RequestOptions\DocRefund\Reference([
                        'type' => Client\RequestOptions\DocRefund\Reference::TYPE_TKT_INDICATOR,
                        'value' => 'Y'
                    ]),
                    new Client\RequestOptions\DocRefund\Reference([
                        'type' => Client\RequestOptions\DocRefund\Reference::TYPE_DATA_SOURCE,
                        'value' => 'F'
                    ])
                ],
                'tickets' => [
                    new Client\RequestOptions\DocRefund\Ticket([
                        'number' => '22021541124593',
                        'ticketGroup' => [
                            new Client\RequestOptions\DocRefund\TickGroupOpt([
                                'couponNumber' => Client\RequestOptions\DocRefund\TickGroupOpt::COUPON_1,
                                'couponStatus' => Client\RequestOptions\DocRefund\TickGroupOpt::STATUS_REFUNDED,
                                'boardingPriority' => 'LH07A'
                            ]),
                            new Client\RequestOptions\DocRefund\TickGroupOpt([
                                'couponNumber' => Client\RequestOptions\DocRefund\TickGroupOpt::COUPON_2,
                                'couponStatus' => Client\RequestOptions\DocRefund\TickGroupOpt::STATUS_REFUNDED,
                                'boardingPriority' => 'LH07A'
                            ]),
                            new Client\RequestOptions\DocRefund\TickGroupOpt([
                                'couponNumber' => Client\RequestOptions\DocRefund\TickGroupOpt::COUPON_3,
                                'couponStatus' => Client\RequestOptions\DocRefund\TickGroupOpt::STATUS_REFUNDED,
                                'boardingPriority' => 'LH07A'
                            ]),
                            new Client\RequestOptions\DocRefund\TickGroupOpt([
                                'couponNumber' => Client\RequestOptions\DocRefund\TickGroupOpt::COUPON_4,
                                'couponStatus' => Client\RequestOptions\DocRefund\TickGroupOpt::STATUS_REFUNDED,
                                'boardingPriority' => 'LH07A'
                            ])
                        ]
                    ]),
                    new Client\RequestOptions\DocRefund\Ticket([
                        'number' => '22021541124604',
                        'ticketGroup' => [
                            new Client\RequestOptions\DocRefund\TickGroupOpt([
                                'couponNumber' => Client\RequestOptions\DocRefund\TickGroupOpt::COUPON_1,
                                'couponStatus' => Client\RequestOptions\DocRefund\TickGroupOpt::STATUS_REFUNDED,
                                'boardingPriority' => 'LH07A'
                            ]),
                            new Client\RequestOptions\DocRefund\TickGroupOpt([
                                'couponNumber' => Client\RequestOptions\DocRefund\TickGroupOpt::COUPON_2,
                                'couponStatus' => Client\RequestOptions\DocRefund\TickGroupOpt::STATUS_REFUNDED,
                                'boardingPriority' => 'LH07A'
                            ])
                        ]
                    ])
                ],
                'travellerPrioDateOfJoining' => \DateTime::createFromFormat('Ymd', '20070101'),
                'travellerPrioReference' => '0077701F',
                'monetaryData' => [
                    new Client\RequestOptions\DocRefund\MonetaryData([
                        'type' => Client\RequestOptions\DocRefund\MonetaryData::TYPE_BASE_FARE,
                        'amount' => 401.00,
                        'currency' => 'EUR'
                    ]),
                    new Client\RequestOptions\DocRefund\MonetaryData([
                        'type' => Client\RequestOptions\DocRefund\MonetaryData::TYPE_FARE_USED,
                        'amount' => 0.00,
                        'currency' => 'EUR'
                    ]),
                    new Client\RequestOptions\DocRefund\MonetaryData([
                        'type' => Client\RequestOptions\DocRefund\MonetaryData::TYPE_FARE_REFUND,
                        'amount' => 401.00,
                        'currency' => 'EUR'
                    ]),
                    new Client\RequestOptions\DocRefund\MonetaryData([
                        'type' => Client\RequestOptions\DocRefund\MonetaryData::TYPE_REFUND_TOTAL,
                        'amount' => 457.74,
                        'currency' => 'EUR'
                    ]),
                    new Client\RequestOptions\DocRefund\MonetaryData([
                        'type' => Client\RequestOptions\DocRefund\MonetaryData::TYPE_TOTAL_TAXES,
                        'amount' => 56.74,
                        'currency' => 'EUR'
                    ]),
                    new Client\RequestOptions\DocRefund\MonetaryData([
                        'type' => 'TP',
                        'amount' => 56.74,
                        'currency' => 'EUR'
                    ]),
                    new Client\RequestOptions\DocRefund\MonetaryData([
                        'type' => 'OBP',
                        'amount' => 0.00,
                        'currency' => 'EUR'
                    ]),
                    new Client\RequestOptions\DocRefund\MonetaryData([
                        'type' => 'TGV',
                        'amount' => 374.93,
                        'currency' => 'EUR'
                    ])
                ],
                'taxData' => [
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => 'H',
                        'rate' => 16.14,
                        'currencyCode' => 'EUR',
                        'type' => 'DE'
                    ]),
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => 'H',
                        'rate' => 3.45,
                        'currencyCode' => 'EUR',
                        'type' => 'YC'
                    ]),
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => 'H',
                        'rate' => 9.67,
                        'currencyCode' => 'EUR',
                        'type' => 'US'
                    ]),
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => 'H',
                        'rate' => 9.67,
                        'currencyCode' => 'EUR',
                        'type' => 'US'
                    ]),
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => 'H',
                        'rate' => 3.14,
                        'currencyCode' => 'EUR',
                        'type' => 'XA'
                    ]),
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => 'H',
                        'rate' => 4.39,
                        'currencyCode' => 'EUR',
                        'type' => 'XY'
                    ]),
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => 'H',
                        'rate' => 6.28,
                        'currencyCode' => 'EUR',
                        'type' => 'AY'
                    ]),
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => 'H',
                        'rate' => 4.00,
                        'currencyCode' => 'EUR',
                        'type' => 'DU'
                    ]),
                    new Client\RequestOptions\DocRefund\TaxData([
                        'category' => '701',
                        'rate' => 56.74,
                        'currencyCode' => 'EUR',
                        'type' => Client\RequestOptions\DocRefund\TaxData::TYPE_EXTENDED_TAXES
                    ])
                ],
                'formOfPayment' => [
                    new Client\RequestOptions\DocRefund\FopOpt([
                        'fopType' => Client\RequestOptions\DocRefund\FopOpt::TYPE_MISCELLANEOUS,
                        'fopAmount' => 457.74,
                        'freeText' => [
                            new Client\RequestOptions\DocRefund\FreeTextOpt([
                                'type' => 'CFP',
                                'freeText' => '##0##'
                            ]),
                            new Client\RequestOptions\DocRefund\FreeTextOpt([
                                'type' => 'CFP',
                                'freeText' => 'IDBANK'
                            ])
                        ]
                    ])
                ],
                'refundedRouteStations' => [
                    'FRA',
                    'MUC',
                    'JFK',
                    'BKK',
                    'FRA'
                ]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendDocRefundProcessRefund()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummydocrefundprocessrefundresponse';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\DocRefund\ProcessRefund(
            new Client\RequestOptions\DocRefundProcessRefundOptions([
                'statusIndicators' => [Client\RequestOptions\DocRefundProcessRefundOptions::STATUS_INHIBIT_REFUND_NOTICE]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('DocRefund_ProcessRefund', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['DocRefund_ProcessRefund' => ['version' => "13.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'DocRefund_ProcessRefund')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->docRefundProcessRefund(
            new Client\RequestOptions\DocRefundProcessRefundOptions([
                'statusIndicators' => [Client\RequestOptions\DocRefundProcessRefundOptions::STATUS_INHIBIT_REFUND_NOTICE]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendFopValidateFop()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyfopvalidatefopresponse';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Fop\ValidateFormOfPayment(
            new Client\RequestOptions\FopValidateFopOptions([
                'fopGroup' => [new Client\RequestOptions\Fop\Group([
                    'fopRef' => new Client\RequestOptions\Fop\FopRef([
                        'qualifier' => Client\RequestOptions\Fop\FopRef::QUAL_FORM_OF_PAYMENT_TATTOO,
                        'number' => 1
                    ])
                ])]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('FOP_ValidateFOP', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['FOP_ValidateFOP' => ['version' => "13.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'FOP_ValidateFOP')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->fopValidateFOP(
            new Client\RequestOptions\FopValidateFopOptions([
                'fopGroup' => [new Client\RequestOptions\Fop\Group([
                    'fopRef' => new Client\RequestOptions\Fop\FopRef([
                        'qualifier' => Client\RequestOptions\Fop\FopRef::QUAL_FORM_OF_PAYMENT_TATTOO,
                        'number' => 1
                    ])
                ])]
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendTicketInitRefund()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyticketinitrefundresponse';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\InitRefund(
            new Client\RequestOptions\TicketInitRefundOptions([])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Ticket_InitRefund', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_InitRefund' => ['version' => "14.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_InitRefund')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketInitRefund(
            new Client\RequestOptions\TicketInitRefundOptions([])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendTicketUpdateRefund()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyTicketUpdateRefundResponse';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\UpdateRefund(
            new Client\RequestOptions\TicketUpdateRefundOptions([

            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Ticket_UpdateRefund', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->willReturn($mockedSendResult);
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->willReturn(['Ticket_UpdateRefund' => ['version' => '3.0', 'wsdl' => 'dc22e4ee']]);

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')
            ->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_UpdateRefund')
            ->willReturn($messageResult);

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'NYCXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketUpdateRefund(
            new Client\RequestOptions\TicketUpdateRefundOptions([

            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendTicketIgnoreRefund()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyticketignorerefundresponse';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\IgnoreRefund(
            new Client\RequestOptions\TicketIgnoreRefundOptions([])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Ticket_IgnoreRefund', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_IgnoreRefund' => ['version' => "14.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_IgnoreRefund')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketIgnoreRefund(
            new Client\RequestOptions\TicketIgnoreRefundOptions([])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendTicketProcessRefund()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'dummyticketprocessrefundresponse';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\Ticket\ProcessRefund(
            new Client\RequestOptions\TicketProcessRefundOptions([])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Ticket_ProcessRefund', $expectedMessageResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_ProcessRefund' => ['version' => "14.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'Ticket_ProcessRefund')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->ticketProcessRefund(
            new Client\RequestOptions\TicketProcessRefundOptions([])
        );

        $this->assertEquals($messageResult, $response);
    }

    /**
     * Testing the scenario where a user requests no responseXML string in the Result object.
     */
    public function testCanSendAnyMessageRequestNoResponseXml()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseObject = new \stdClass();
        $mockedSendResult->responseObject->dummy = new \stdClass();
        $mockedSendResult->responseObject->dummy->value = 'this is a dummy value';
        $mockedSendResult->responseXml = 'this is a dummy result which should be removed because we requested no response XML';

        $messageResult = new Client\Result($mockedSendResult);

        $expectedMessageResult = new Client\Struct\DocIssuance\IssueCombined(
            new Client\RequestOptions\DocIssuanceIssueCombinedOptions([
                'options' => [
                    new Client\RequestOptions\DocIssuance\Option([
                        'indicator' => Client\RequestOptions\DocIssuance\Option::INDICATOR_DOCUMENT_RECEIPT,
                        'subCompoundType' => 'EMPRA'
                    ])
                ]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('DocIssuance_IssueCombined', $expectedMessageResult, ['endSession' => false, 'returnXml' => false])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['DocIssuance_IssueCombined' => ['version' => "15.1", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'DocIssuance_IssueCombined')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);
        $par->responseHandler = $mockResponseHandler;

        $expectedMessage = clone $messageResult;
        $expectedMessage->responseXml = null;


        $client = new Client($par);

        $response = $client->docIssuanceIssueCombined(
            new Client\RequestOptions\DocIssuanceIssueCombinedOptions([
                'options' => [
                    new Client\RequestOptions\DocIssuance\Option([
                        'indicator' => Client\RequestOptions\DocIssuance\Option::INDICATOR_DOCUMENT_RECEIPT,
                        'subCompoundType' => 'EMPRA'
                    ])
                ]
            ]),
            [
                'returnXml' => false
            ]
        );

        $this->assertEquals($expectedMessage, $response);
    }

    public function testCanDoIgnorePnrCall()
    {
        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'A dummy message result'; // Not an actual XML reply.
        $mockedSendResult->responseObject = new \stdClass();
        $mockedSendResult->responseObject->dummyprop = 'A dummy message result'; // Not an actual response property

        $messageResult = new Client\Result($mockedSendResult);

        $options = new Client\RequestOptions\PnrIgnoreOptions();
        $options->actionRequest = Client\Struct\Pnr\Ignore\ClearInformation::CODE_IGNORE;

        $expectedPnrResult = new Client\Struct\Pnr\Ignore($options);

        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_Ignore', $expectedPnrResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_Ignore' => ['version' => "14.2", 'wsdl' => 'dc22e4ee']]));

        $mockResponseHandler = $this->getMockBuilder('Amadeus\Client\ResponseHandler\ResponseHandlerInterface')->getMock();

        $mockResponseHandler
            ->expects($this->once())
            ->method('analyzeResponse')
            ->with($mockedSendResult, 'PNR_Ignore')
            ->will($this->returnValue($messageResult));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams(['receivedFrom' => 'some RF string', 'originatorOfficeId' => 'BRUXXXXXX']);
        $par->responseHandler = $mockResponseHandler;

        $client = new Client($par);

        $response = $client->pnrIgnore($options);

        $this->assertEquals($messageResult, $response);
    }

    /**
     * @return array
     */
    public function dataProviderMakeMessageOptions()
    {
        return [
            //No special message options: result is the default
            [
                ['endSession' => false, 'returnXml' => true],
                [
                    []
                ]
            ],
            //Override returnXml by user:
            [
                ['endSession' => false, 'returnXml' => true],
                [
                    ['returnXml' => true]
                ]
            ],
            //Override returnXml by user:
            [
                ['endSession' => false, 'returnXml' => false],
                [
                    ['returnXml' => false]
                ]
            ],
            //Override endSession in message definition:
            [
                ['endSession' => false, 'returnXml' => true],
                [
                    [],
                    false
                ]
            ],
            //Override endSession by user:
            [
                ['endSession' => true, 'returnXml' => true],
                [
                    ['endSession' => true]
                ]
            ],
            //Override endSession in message definition:
            [
                ['endSession' => true, 'returnXml' => true],
                [
                    [],
                    true
                ]
            ]
        ];
    }

    /**
     * @return Params
     */
    protected function makeDummyParams()
    {
        return new Params([
            'sessionHandlerParams' => [
                'wsdl' => realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . "Client" . DIRECTORY_SEPARATOR . "testfiles" . DIRECTORY_SEPARATOR . "dummywsdl.wsdl"),
                'stateful' => true,
                'logger' => new NullLogger(),
                'authParams' => [
                    'officeId' => 'BRUXXXXXX',
                    'userId' => 'WSXXXXXX',
                    'passwordData' => base64_encode('TEST')
                ]
            ],
            'requestCreatorParams' => [
                'receivedFrom' => 'some RF string'
            ]
        ]);
    }

    /**
     * @return Client
     */
    protected function makeClientWithMockedSessionHandler()
    {
        $par = new Params();
        $par->sessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        return new Client($par);
    }

    /**
     * @return string
     */
    protected function makePathToDummyWSDL()
    {
        return realpath(
            dirname(__FILE__) . DIRECTORY_SEPARATOR . "Client" .
            DIRECTORY_SEPARATOR . "testfiles" . DIRECTORY_SEPARATOR . "dummywsdl.wsdl"
        );
    }
}
