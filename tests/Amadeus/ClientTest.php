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

    public function testWillGetNullFromGetLastReqResWhenNoCallsWerMade()
    {
        $client = new Client($this->makeDummyParams());

        $last = $client->getLastRequest();

        $this->assertNull($last);

        $last = $client->getLastResponse();

        $this->assertNull($last);
    }

    public function testCanDoDummyPnrRetrieveCall()
    {
        $mockedSendResult = new Client\Session\Handler\SendResult();
        $mockedSendResult->responseXml = 'A dummy message result'; // Not an actual XML reply.

        $messageResult = new Client\Result($mockedSendResult);

        $expectedPnrResult = new Client\Struct\Pnr\Retrieve(Client\Struct\Pnr\Retrieve::RETR_TYPE_BY_RECLOC,'ABC123');

        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_Retrieve', $expectedPnrResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_Retrieve' => '14.2']));

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

        $response = $client->pnrRetrieve(new Client\RequestOptions\PnrRetrieveOptions(['recordLocator'=>'ABC123']));

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
            ->will($this->returnValue(['PNR_RetrieveAndDisplay' => '14.2']));

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

        $response = $client->pnrRetrieveAndDisplay(new Client\RequestOptions\PnrRetrieveAndDisplayOptions(['recordLocator'=>'ABC123']));

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

        $expectedPnrResult = new Client\Struct\Pnr\AddMultiElements($options);

        $receivedFromElement = new Client\Struct\Pnr\AddMultiElements\DataElementsIndiv(Client\Struct\Pnr\AddMultiElements\ElementManagementData::SEGNAME_RECEIVE_FROM, 4);
        $receivedFromElement->freetextData = new Client\Struct\Pnr\AddMultiElements\FreetextData(
            'some RF string amabnl-amadeus-ws-client-'.Client::VERSION,
            Client\Struct\Pnr\AddMultiElements\FreetextDetail::TYPE_RECEIVE_FROM
        );
        $expectedPnrResult->dataElementsMaster->dataElementsIndiv[] = $receivedFromElement;

        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_AddMultiElements', $expectedPnrResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_AddMultiElements' => '14.2']));

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

        $options = new Client\RequestOptions\PnrAddMultiElementsOptions();
        $options->actionCode = 11; //11 End transact with retrieve (ER)
        $expectedPnrResult = new Client\Struct\Pnr\AddMultiElements($options);

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_AddMultiElements', $expectedPnrResult, ['endSession' => false, 'returnXml' => true])
            ->will($this->returnValue($mockedSendResult));

        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_AddMultiElements' => '14.2']));

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
            ->will($this->returnValue(['PNR_AddMultiElements' => '14.2']));

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
            ->will($this->returnValue(['PNR_Cancel' => '14.2']));

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
            ->will($this->returnValue(['PNR_DisplayHistory' => '14.2']));

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
            ->will($this->returnValue(['PNR_TransferOwnership' => '14.1']));

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
            ->will($this->returnValue(['PNR_NameChange' => '14.1']));

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
            ->will($this->returnValue(['Queue_List' => "11.1"]));

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
                'queue'=> 50,
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
            ->will($this->returnValue(['Queue_PlacePNR' => "11.1"]));

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

        $expectedMessageResult = new Client\Struct\Queue\RemoveItem(new Client\RequestOptions\Queue(['queue'=> 50, 'category' => 0]), 'ABC123', 'BRUXX0000');

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
            ->will($this->returnValue(['Queue_RemoveItem' => "11.1"]));

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

        $expectedMessageResult = new Client\Struct\Queue\MoveItem('ABC123', 'BRUXX0000', new Client\RequestOptions\Queue(['queue'=> 50, 'category' => 0]), new Client\RequestOptions\Queue(['queue'=> 60, 'category' => 5]));

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
            ->will($this->returnValue(['Queue_MoveItem' => "11.1"]));

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
            ->will($this->returnValue(['Command_Cryptic' => "5.1"]));

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
            ->will($this->returnValue(['MiniRule_GetFromPricingRec' => "5.1"]));

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
            ->will($this->returnValue(['MiniRule_GetFromPricing' => "11.1"]));

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
            ->will($this->returnValue(['Offer_CreateOffer' => "13.2"]));

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
            ->will($this->returnValue(['Offer_VerifyOffer' => "11.1"]));

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
            ->will($this->returnValue(['Offer_ConfirmHotelOffer' => "11.1"]));

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
            ->will($this->returnValue(['Offer_ConfirmCarOffer' => "11.1"]));

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
            ->will($this->returnValue(['Info_EncodeDecodeCity' => "05.1"]));

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
            ->will($this->returnValue(['PointOfRef_Search' => "02.1"]));

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
            ->will($this->returnValue(['Ticket_CreateTSTFromPricing' => "04.1"]));

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
            ->will($this->returnValue(['Ticket_CreateTSMFromPricing' => "09.1"]));

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
            ->will($this->returnValue(['Ticket_DeleteTST' => "04.1"]));

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
            ->will($this->returnValue(['Ticket_DeleteTSMP' => "08.1"]));

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
            ->will($this->returnValue(['Ticket_DisplayTST' => "04.1"]));

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
            ->will($this->returnValue(['Ticket_DisplayTSMP' => "13.2"]));

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
            ->will($this->returnValue(['Ticket_DisplayTSMFareElement' => "13.1"]));

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
            ->will($this->returnValue(['Offer_ConfirmAirOffer' => "11.1"]));

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
                                'departureDate' => \DateTime::createFromFormat('YmdHis','20170120000000', new \DateTimeZone('UTC')),
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
            ->will($this->returnValue(['Air_SellFromRecommendation' => "5.2"]));

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
                                'departureDate' => \DateTime::createFromFormat('YmdHis','20170120000000', new \DateTimeZone('UTC')),
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
            ->will($this->returnValue(['Air_FlightInfo' => "7.1"]));

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
            ->will($this->returnValue(['Air_RetrieveSeatMap' => "14.2"]));

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
            ->will($this->returnValue(['Air_MultiAvailability' => "14.1"]));

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
            ->will($this->returnValue(['Fare_MasterPricerTravelBoardSearch' => "12.3"]));

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
            ->will($this->returnValue(['Fare_MasterPricerCalendar' => "14.3"]));

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
                'earliestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-08-25', new \DateTimeZone('UTC')),
                'latestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-09-28', new \DateTimeZone('UTC')),
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
            ->will($this->returnValue(['PriceXplorer_ExtremeSearch' => "10.3"]));

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
                'earliestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-08-25', new \DateTimeZone('UTC')),
                'latestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-09-28', new \DateTimeZone('UTC')),
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
            ->will($this->returnValue(['SalesReports_DisplayQueryReport' => "12.1"]));

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
            ->will($this->returnValue(['Fare_CheckRules' => "7.1"]));

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
            ->will($this->returnValue(['Fare_ConvertCurrency' => "8.1"]));

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
            ->will($this->returnValue(['Fare_PricePNRWithBookingClass' => "12.3"]));

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
            ->will($this->returnValue(['Fare_PricePNRWithBookingClass' => "14.3"]));

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
            ->will($this->returnValue(['Fare_PricePNRWithLowerFares' => "14.1"]));

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
            ->will($this->returnValue(['Fare_PricePNRWithLowerFares' => "12.4"]));

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
            ->will($this->returnValue(['Fare_PricePNRWithLowestFare' => "14.1"]));

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
            ->will($this->returnValue(['Fare_PricePNRWithLowestFare' => "12.4"]));

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
            ->will($this->returnValue(['Fare_InformativePricingWithoutPNR' => "15.1"]));

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
            ->will($this->returnValue(['Fare_InformativeBestPricingWithoutPNR' => "14.1"]));

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
            ->will($this->returnValue(['Service_IntegratedPricing' => "15.1"]));

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
            ->will($this->returnValue(['FOP_CreateFormOfPayment' => "15.4"]));

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
            ->will($this->returnValue(['Security_SignOut' => "4.1"]));

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
            ->will($this->returnValue(['Security_Authenticate' => "6.1"]));

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
            ->will($this->returnValue(['DocIssuance_IssueTicket' => "9.1"]));

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
            ->will($this->returnValue(['DocIssuance_IssueMiscellaneousDocuments' => "15.1"]));

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
            ->will($this->returnValue(['DocIssuance_IssueCombined' => "15.1"]));

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
            ->will($this->returnValue(['DocIssuance_IssueCombined' => "15.1"]));

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
            dirname(__FILE__).DIRECTORY_SEPARATOR."Client".
            DIRECTORY_SEPARATOR."testfiles".DIRECTORY_SEPARATOR."dummywsdl.wsdl"
        );
    }
}
