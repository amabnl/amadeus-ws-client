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
use Test\Amadeus\BaseTestCase;

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

    public function testCanCreateClientWithOverriddenSessionHandlerAndRequestCreator()
    {
        $par = new Params([
            'sessionHandler' => $this->getMockBuilder('Amadeus\Client\Session\Handler\HandlerInterface')->getMock(),
            'requestCreator' => $this->getMockBuilder('Amadeus\Client\RequestCreator\RequestCreatorInterface')->getMock()
        ]);

        $client = new Client($par);

        $this->assertInstanceOf('Amadeus\Client', $client);
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

        $receivedFromElement = new Client\Struct\Pnr\AddMultiElements\DataElementsIndiv(Client\Struct\Pnr\AddMultiElements\ElementManagementData::SEGNAME_RECEIVE_FROM);
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

    protected function makePathToDummyWSDL()
    {
        return realpath(
            dirname(__FILE__).DIRECTORY_SEPARATOR."Client".
            DIRECTORY_SEPARATOR."testfiles".DIRECTORY_SEPARATOR."dummywsdl.wsdl"
        );
    }
}
