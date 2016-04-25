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
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $messageResult = 'A dummy message result'; // Not an actual XML reply.

        $expectedPnrResult = new Client\Struct\Pnr\Retrieve(Client\Struct\Pnr\Retrieve::RETR_TYPE_BY_RECLOC,'ABC123');

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_Retrieve', $expectedPnrResult, ['asString' => true, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_Retrieve' => '14.2']));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $response = $client->pnrRetrieve(new Client\RequestOptions\PnrRetrieveOptions(['recordLocator'=>'ABC123']));

        $this->assertEquals($messageResult, $response);
    }


    public function testCanDoDummyPnrRetrieveAndDisplayCall()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $messageResult = 'A dummy message result'; // Not an actual XML reply.

        $expectedPnrResult = new Client\Struct\Pnr\RetrieveAndDisplay('ABC123');

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_RetrieveAndDisplay', $expectedPnrResult, ['asString' => true, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_RetrieveAndDisplay' => '14.2']));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $response = $client->pnrRetrieveAndDisplay(new Client\RequestOptions\PnrRetrieveAndDisplayOptions(['recordLocator'=>'ABC123']));

        $this->assertEquals($messageResult, $response);
    }


    public function testCanDoCreatePnrCall()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $messageResult = 'A dummy message result'; // Not an actual XML reply.

        $options = new Client\RequestOptions\PnrCreatePnrOptions();
        $options->actionCode = 11; //11 End transact with retrieve (ER)
        $options->travellers[] = new Client\RequestOptions\Pnr\Traveller([
            'number' => 1,
            'firstName' => 'FirstName',
            'lastName' => 'LastName'
        ]);
        $options->tripSegments[] = new Client\RequestOptions\Pnr\Segment\Miscellaneous([
            'status ' => Client\RequestOptions\Pnr\Segment::STATUS_CONFIRMED,
            'company' => '1A',
            'date' => \DateTime::createFromFormat('Ymd', '20161022', new \DateTimeZone('UTC')),
            'cityCode' => 'BRU',
            'freeText' => 'DUMMY MISCELLANEOUS SEGMENT'
        ]);

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
            'some RF string amabnl-amadeus-ws-client-0.0.1dev',
            Client\Struct\Pnr\AddMultiElements\FreetextDetail::TYPE_RECEIVE_FROM
        );
        $expectedPnrResult->dataElementsMaster->dataElementsIndiv[] = $receivedFromElement;

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_AddMultiElements', $expectedPnrResult, ['asString' => true, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_AddMultiElements' => '14.2']));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $response = $client->pnrCreatePnr($options);

        $this->assertEquals($messageResult, $response);

    }

    public function testCanDoAddMultiElementsSavePNR()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $messageResult = 'A dummy message result'; // Not an actual XML reply.

        $options = new Client\RequestOptions\PnrAddMultiElementsOptions();
        $options->actionCode = 11; //11 End transact with retrieve (ER)

        $expectedPnrResult = new Client\Struct\Pnr\AddMultiElements($options);

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_AddMultiElements', $expectedPnrResult, ['asString' => true, 'endSession' => false])
            ->will($this->returnValue($messageResult));

        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_AddMultiElements' => '14.2']));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $response = $client->pnrAddMultiElements($options);

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoAddMultiElementsSavePNRWithRf()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $messageResult = 'A dummy message result'; // Not an actual XML reply.

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
            ->with('PNR_AddMultiElements', $expectedPnrResult, ['asString' => true, 'endSession' => false])
            ->will($this->returnValue($messageResult));

        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_AddMultiElements' => '14.2']));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $response = $client->pnrAddMultiElements($options);

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoDummyPnrCancelCall()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $messageResult = 'A dummy message result'; // Not an actual XML reply.

        $expectedPnrResult = new Client\Struct\Pnr\Cancel(
            new Client\RequestOptions\PnrCancelOptions([
                'actionCode' => 10,
                'cancelItinerary' => true
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('PNR_Cancel', $expectedPnrResult, ['asString' => true, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PNR_Cancel' => '14.2']));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $response = $client->pnrCancel(
            new Client\RequestOptions\PnrCancelOptions([
                'actionCode' => 10,
                'cancelItinerary' => true
            ])
        );

        $this->assertEquals($messageResult, $response);
    }


    public function testCanDoDummyQueueListCall()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $lastResponse = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3" xmlns:wsa="http://www.w3.org/2005/08/addressing"><SOAP-ENV:Header><wsa:To>http://www.w3.org/2005/08/addressing/anonymous</wsa:To><wsa:From><wsa:Address>https://nodeD1.test.webservices.amadeus.com/ENDPOINT</wsa:Address></wsa:From><wsa:Action>http://webservices.amadeus.com/QDQLRQ_11_1_1A</wsa:Action><wsa:MessageID>urn:uuid:916bb446-a6fc-b8a4-b543-ce4b8ba124e1</wsa:MessageID><wsa:RelatesTo RelationshipType="http://www.w3.org/2005/08/addressing/reply">86653CF8-2017-2F7C-AFC2-BD07B22BD185</wsa:RelatesTo><awsse:Session TransactionStatusCode="End"><awsse:SessionId>SESSIONID</awsse:SessionId><awsse:SequenceNumber>1</awsse:SequenceNumber><awsse:SecurityToken>SECTOKEN</awsse:SecurityToken></awsse:Session></SOAP-ENV:Header><SOAP-ENV:Body><Queue_ListReply xmlns="http://xml.amadeus.com/QDQLRR_11_1_1A"><queueView><agent><originatorDetails><inHouseIdentification1>BRU1A0980</inHouseIdentification1></originatorDetails></agent><queueNumber><queueDetails><number>0</number></queueDetails></queueNumber><categoryDetails><subQueueInfoDetails><identificationType>C</identificationType><itemNumber>0</itemNumber></subQueueInfoDetails></categoryDetails><pnrCount><quantityDetails><numberOfUnit>1</numberOfUnit></quantityDetails></pnrCount><pnrCount><quantityDetails><numberOfUnit>1</numberOfUnit></quantityDetails></pnrCount><pnrCount><quantityDetails><numberOfUnit>1</numberOfUnit></quantityDetails></pnrCount><item><paxName><paxDetails><surname>TURBO</surname><type>0</type><quantity>0</quantity></paxDetails></paxName><recLoc><reservation><controlNumber>23TCZS</controlNumber></reservation></recLoc><agent><originatorDetails><inHouseIdentification1>BRU1A0980</inHouseIdentification1><inHouseIdentification2>WS</inHouseIdentification2></originatorDetails></agent><pnrdates><timeMode>700</timeMode><dateTime><year>2015</year><month>12</month><day>11</day></dateTime></pnrdates><pnrdates><timeMode>701</timeMode><dateTime><year>2016</year><month>1</month><day>5</day></dateTime></pnrdates><pnrdates><timeMode>711</timeMode><dateTime><year>2015</year><month>12</month><day>11</day><hour>12</hour><minutes>28</minutes></dateTime></pnrdates></item></queueView></Queue_ListReply></SOAP-ENV:Body></SOAP-ENV:Envelope>';
        $messageResult = '<Queue_ListReply xmlns="http://xml.amadeus.com/QDQLRR_11_1_1A"><queueView><agent><originatorDetails><inHouseIdentification1>BRU1A0980</inHouseIdentification1></originatorDetails></agent><queueNumber><queueDetails><number>0</number></queueDetails></queueNumber><categoryDetails><subQueueInfoDetails><identificationType>C</identificationType><itemNumber>0</itemNumber></subQueueInfoDetails></categoryDetails><pnrCount><quantityDetails><numberOfUnit>1</numberOfUnit></quantityDetails></pnrCount><pnrCount><quantityDetails><numberOfUnit>1</numberOfUnit></quantityDetails></pnrCount><pnrCount><quantityDetails><numberOfUnit>1</numberOfUnit></quantityDetails></pnrCount><item><paxName><paxDetails><surname>TURBO</surname><type>0</type><quantity>0</quantity></paxDetails></paxName><recLoc><reservation><controlNumber>23TCZS</controlNumber></reservation></recLoc><agent><originatorDetails><inHouseIdentification1>BRU1A0980</inHouseIdentification1><inHouseIdentification2>WS</inHouseIdentification2></originatorDetails></agent><pnrdates><timeMode>700</timeMode><dateTime><year>2015</year><month>12</month><day>11</day></dateTime></pnrdates><pnrdates><timeMode>701</timeMode><dateTime><year>2016</year><month>1</month><day>5</day></dateTime></pnrdates><pnrdates><timeMode>711</timeMode><dateTime><year>2015</year><month>12</month><day>11</day><hour>12</hour><minutes>28</minutes></dateTime></pnrdates></item></queueView></Queue_ListReply>';

        $expectedMessageResult = new Client\Struct\Queue\QueueList(50, 0);

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Queue_List', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getLastResponse')
            ->will($this->returnValue($lastResponse));
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Queue_List' => "11.1"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $response = $client->queueList(
            new Client\RequestOptions\QueueListOptions([
                'queue' => new Client\RequestOptions\Queue(['queue' => 50, 'category' => 0])
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoPlacePNRCall()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $messageResult = 'dumyplacepnrmessage';

        $expectedMessageResult = new Client\Struct\Queue\PlacePnr('ABC123', 'BRUXX0000', new Client\RequestOptions\Queue(['queue'=> 50, 'category' => 0]));

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Queue_PlacePNR', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Queue_PlacePNR' => "11.1"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

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

        $messageResult = 'dumyremveitemmessage';

        $expectedMessageResult = new Client\Struct\Queue\RemoveItem(new Client\RequestOptions\Queue(['queue'=> 50, 'category' => 0]), 'ABC123', 'BRUXX0000');

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Queue_RemoveItem', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Queue_RemoveItem' => "11.1"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

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

        $messageResult = 'dummymoveitemmessage';

        $expectedMessageResult = new Client\Struct\Queue\MoveItem('ABC123', 'BRUXX0000', new Client\RequestOptions\Queue(['queue'=> 50, 'category' => 0]), new Client\RequestOptions\Queue(['queue'=> 60, 'category' => 5]));

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Queue_MoveItem', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Queue_MoveItem' => "11.1"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

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
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $messageResult = 'dummycrypticresponse';

        $expectedMessageResult = new Client\Struct\Command\Cryptic('DAC BRU');

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Command_Cryptic', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Command_Cryptic' => "5.1"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

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
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $messageResult = 'dummycrypticresponse';

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

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('MiniRule_GetFromPricingRec', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['MiniRule_GetFromPricingRec' => "5.1"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

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

    public function testCanDoOfferVerifyCall()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $messageResult = 'dummyofferverifymessage';

        $expectedMessageResult = new Client\Struct\Offer\Verify(
            1,
            Client\Struct\Offer\OfferTattoo::SEGMENT_AIR
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Offer_VerifyOffer', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Offer_VerifyOffer' => "11.1"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

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

        $messageResult = 'dummyofferconfirmhotelmessage';

        $expectedMessageResult = new Client\Struct\Offer\ConfirmHotel(
            new Client\RequestOptions\OfferConfirmHotelOptions([
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Offer_ConfirmHotelOffer', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Offer_ConfirmHotelOffer' => "11.1"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

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

        $messageResult = 'dummyofferconfirmcarmessage';

        $expectedMessageResult = new Client\Struct\Offer\ConfirmCar(
            new Client\RequestOptions\OfferConfirmCarOptions([
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Offer_ConfirmCarOffer', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Offer_ConfirmCarOffer' => "11.1"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $response = $client->offerConfirmCar(
            new Client\RequestOptions\OfferConfirmCarOptions([
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoInfoEncodeDecodeCity()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $messageResult = 'dummyinfo-encodedecodecity-message';

        $expectedMessageResult = new Client\Struct\Info\EncodeDecodeCity(
            new Client\RequestOptions\InfoEncodeDecodeCityOptions([
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Info_EncodeDecodeCity', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Info_EncodeDecodeCity' => "05.1"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $response = $client->infoEncodeDecodeCity(
            new Client\RequestOptions\InfoEncodeDecodeCityOptions([
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoTicketCreateTSTFromPricing()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $messageResult = 'dummyTicketCreateTSTFromPricingmessage';

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
            ->with('Ticket_CreateTSTFromPricing', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Ticket_CreateTSTFromPricing' => "04.1"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

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

    public function testCanDoOfferConfirmAirOffer()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $messageResult = 'dummyofferconfirmairmessage';

        $expectedMessageResult = new Client\Struct\Offer\ConfirmAir(
            new Client\RequestOptions\OfferConfirmAirOptions([
                'tattooNumber' => 1
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Offer_ConfirmAirOffer', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Offer_ConfirmAirOffer' => "11.1"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

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

        $messageResult = 'dummyairsellfromrecommendationrmessage';

        $expectedMessageResult = new Client\Struct\Air\SellFromRecommendation(
            new Client\RequestOptions\AirSellFromRecommendationOptions([
                'itinerary' => [
                    new Client\RequestOptions\Air\SellFromRecommendation\Itinerary([
                        'from' => 'BRU',
                        'to' => 'LON',
                        'segments' => [
                            new Client\RequestOptions\Air\SellFromRecommendation\Segment([
                                'departureDate' => \DateTime::createFromFormat('Ymd','20170120', new \DateTimeZone('UTC')),
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
            ->with('Air_SellFromRecommendation', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Air_SellFromRecommendation' => "5.2"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $response = $client->airSellFromRecommendation(
            new Client\RequestOptions\AirSellFromRecommendationOptions([
                'itinerary' => [
                    new Client\RequestOptions\Air\SellFromRecommendation\Itinerary([
                        'from' => 'BRU',
                        'to' => 'LON',
                        'segments' => [
                            new Client\RequestOptions\Air\SellFromRecommendation\Segment([
                                'departureDate' => \DateTime::createFromFormat('Ymd','20170120', new \DateTimeZone('UTC')),
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

        $messageResult = 'dummyairflightinformessage';

        $expectedMessageResult = new Client\Struct\Air\FlightInfo(
            new Client\RequestOptions\AirFlightInfoOptions([
                'airlineCode' => 'SN',
                'flightNumber' => '652',
                'departureDate' => \DateTime::createFromFormat('Y-m-d', '2016-05-18'),
                'departureLocation' => 'BRU',
                'arrivalLocation' => 'LIS'
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Air_FlightInfo', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Air_FlightInfo' => "7.1"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $response = $client->airFlightInfo(
            new Client\RequestOptions\AirFlightInfoOptions([
                'airlineCode' => 'SN',
                'flightNumber' => '652',
                'departureDate' => \DateTime::createFromFormat('Y-m-d', '2016-05-18'),
                'departureLocation' => 'BRU',
                'arrivalLocation' => 'LIS'
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendFareMasterPricerTravelBoardSearch()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $messageResult = 'dummyfarepricemasterpricertravelboardsearchresponsemessage';

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
                'requestedFlightOptions' => Client\RequestOptions\FareMasterPricerTbSearch::FLIGHTTYPE_DIRECT
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_MasterPricerTravelBoardSearch', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_MasterPricerTravelBoardSearch' => "12.3"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

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
                'requestedFlightOptions' => Client\RequestOptions\FareMasterPricerTbSearch::FLIGHTTYPE_DIRECT
            ])
        );

        $this->assertEquals($messageResult, $response);
    }

    public function testCanSendPriceXplorerExtremeSearch()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $messageResult = 'dummyfarepricemasterpricertravelboardsearchresponsemessage';

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
            ->with('PriceXplorer_ExtremeSearch', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['PriceXplorer_ExtremeSearch' => "10.3"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

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

    public function testCanFareCheckRules()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $messageResult = 'dummyfarecheckrulesmessage';

        $expectedMessageResult = new Client\Struct\Fare\CheckRules(
            new Client\RequestOptions\FareCheckRulesOptions([
                'recommendations' => [1]
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_CheckRules', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_CheckRules' => "7.1"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

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

        $messageResult = 'dummyfareconvertcurrencymessage';

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
            ->with('Fare_ConvertCurrency', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_ConvertCurrency' => "8.1"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

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

        $messageResult = 'dummyfarepricepnrwithbookingclassmessage';

        $expectedMessageResult = new Client\Struct\Fare\PricePNRWithBookingClass12(
            new Client\RequestOptions\FarePricePnrWithBookingClassOptions([
                'validatingCarrier' => 'SN'
            ])
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Fare_PricePNRWithBookingClass', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Fare_PricePNRWithBookingClass' => "12.3"]));

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

    public function testCanFarePricePnrWithBookingClassVersion14()
    {
        $this->setExpectedException('\Amadeus\Client\RequestCreator\MessageVersionUnsupportedException');

        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        //$messageResult = 'dummyfarepricepnrwithbookingclassmessage';

        $mockSessionHandler
            ->expects($this->never())
            ->method('sendMessage');
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

        $client->farePricePnrWithBookingClass(
            new Client\RequestOptions\FarePricePnrWithBookingClassOptions([
                'validatingCarrier' => 'SN'
            ])
        );
    }

    public function testCanDoSignOutCall()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $messageResult = new \stdClass();
        $messageResult->processStatus = new \stdClass();
        $messageResult->processStatus->statusCode = 'P';

        $expectedMessageResult = new Client\Struct\Security\SignOut();

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Security_SignOut', $expectedMessageResult, ['asString' => false, 'endSession' => true])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Security_SignOut' => "4.1"]));

        $par = new Params();
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

        $client = new Client($par);

        $response = $client->securitySignOut();

        $this->assertEquals($messageResult, $response);
    }

    public function testCanDoAuthenticateCall()
    {
        $mockSessionHandler = $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock();

        $authParams = new Params\AuthParams([
            'officeId' => 'BRUXXXXXX',
            'originatorTypeCode' => 'U',
            'userId' => 'WSXXXXXX',
            'passwordData' => base64_encode('TEST'),
            'passwordLength' => 4,
            'dutyCode' => 'SU',
            'organizationId' => 'DUMMY-ORG',
        ]);

        $messageResult = new \stdClass();
        $messageResult->processStatus = new \stdClass();
        $messageResult->processStatus->statusCode = 'P';

        $expectedMessageResult = new Client\Struct\Security\Authenticate(
            new Client\RequestOptions\SecurityAuthenticateOptions(
                $authParams
            )
        );

        $mockSessionHandler
            ->expects($this->once())
            ->method('sendMessage')
            ->with('Security_Authenticate', $expectedMessageResult, ['asString' => false, 'endSession' => false])
            ->will($this->returnValue($messageResult));
        $mockSessionHandler
            ->expects($this->never())
            ->method('getLastResponse');
        $mockSessionHandler
            ->expects($this->once())
            ->method('getMessagesAndVersions')
            ->will($this->returnValue(['Security_Authenticate' => "6.1"]));

        $par = new Params();
        $par->authParams = $authParams;
        $par->sessionHandler = $mockSessionHandler;
        $par->requestCreatorParams = new Params\RequestCreatorParams([
            'receivedFrom' => 'some RF string',
            'originatorOfficeId' => 'BRUXXXXXX'
        ]);

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

        $actual = $client->getSessionInfo();

        $this->assertEquals($mockedSession, $actual);
    }


    /**
     * @return array
     */
    public function dataProviderMakeMessageOptions()
    {
        return [
            //No special message options: result is the default
            [
                ['asString' => false, 'endSession' => false],
                [
                    []
                ]
            ],
            //Override asString by user:
            [
                ['asString' => true, 'endSession' => false],
                [
                    ['asString' => true]
                ]
            ],
            //Override asString in message definition:
            [
                ['asString' => true, 'endSession' => false],
                [
                    [],
                    true
                ]
            ],
            //Override endSession by user:
            [
                ['asString' => false, 'endSession' => true],
                [
                    ['endSession' => true]
                ]
            ],
            //Override endSession in message definition:
            [
                ['asString' => false, 'endSession' => true],
                [
                    [],
                    false,
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
