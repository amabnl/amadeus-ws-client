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
 * @package Test\Amadeus
 * @license https://opensource.org/licenses/Apache-2.0 Apache 2.0
 */

namespace Test\Amadeus\Client\Session\Handler;

use Amadeus\Client;
use Amadeus\Client\Params\SessionHandlerParams;
use Amadeus\Client\Session\Handler\SoapHeader4;
use Psr\Log\NullLogger;
use Test\Amadeus\BaseTestCase;

/**
 * SoapHeader4Test
 *
 * @package Test\Amadeus\Client\Session\Handler
 */
class SoapHeader4Test extends BaseTestCase
{
    public function testCanCreateSoapHeadersForStatelessCall()
    {
        $expectedSecurityNodeStructureXml = '<oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsswssecurity-secext-1.0.xsd"
xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
 <oas:UsernameToken oas1:Id="UsernameToken-1">
 <oas:Username>WSYYYXXX</oas:Username>
 <oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsssoap-message-security-1.0#Base64Binary">c2VjcmV0bm9uY2UxMDExMQ==</oas:Nonce>
 <oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wssusername-token-profile-1.0#PasswordDigest">+LzcaRc+ndGAcZIXmq/N7xGes+k=</oas:Password>
 <oas1:Created>2015-09-30T14:12:15Z</oas1:Created>
 </oas:UsernameToken>
 </oas:Security>';

        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $meth = self::getMethod($sessionHandler, 'createSoapHeaders');

        /** @var \SoapHeader[] $result */
        $result = $meth->invoke(
            $sessionHandler,
            ['sessionId' => null, 'sequenceNumber' => null, 'securityToken' => null],
            $sessionHandlerParams,
            'PNR_Retrieve',
            []
        );

        $this->assertCount(5, $result);
        foreach ($result as $tmp) {
            $this->assertInstanceOf('\SoapHeader', $tmp);
        }

        $this->assertInternalType('string', $result[0]->data);
        $this->assertTrue($this->isValidGuid($result[0]->data));
        $this->assertEquals('MessageID', $result[0]->name);
        $this->assertEquals('http://www.w3.org/2005/08/addressing', $result[0]->namespace);

        $this->assertInternalType('string', $result[1]->data);
        $this->assertEquals('http://webservices.amadeus.com/PNRRET_11_3_1A', $result[1]->data);
        $this->assertEquals('Action', $result[1]->name);
        $this->assertEquals('http://www.w3.org/2005/08/addressing', $result[1]->namespace);

        $this->assertInternalType('string', $result[2]->data);
        $this->assertEquals('https://dummy.webservices.endpoint.com/SOAPADDRESS', $result[2]->data);
        $this->assertEquals('To', $result[2]->name);
        $this->assertEquals('http://www.w3.org/2005/08/addressing', $result[2]->namespace);

        $this->assertInstanceOf('\SoapVar', $result[3]->data);
        $this->assertEquals(XSD_ANYXML, $result[3]->data->enc_type);
        $this->assertEqualXMLStructure($this->toDomElement($expectedSecurityNodeStructureXml), $this->toDomElement($result[3]->data->enc_value), true);
        $this->assertEquals('Security', $result[3]->name);
        $this->assertEquals('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsswssecurity-secext-1.0.xsd',
            $result[3]->namespace);

        $this->assertInstanceOf('Amadeus\Client\Struct\HeaderV4\SecurityHostedUser', $result[4]->data);
        $this->assertEquals('AMA_SecurityHostedUser', $result[4]->name);
        $this->assertEquals('http://xml.amadeus.com/2010/06/Security_v1', $result[4]->namespace);
    }

    /**
     * Testing soap header generation when we're about to make the first call in stateful mode.
     */
    public function testCanCreateSoapHeadersForStatefullCallAuth()
    {
        $expectedSecurityNodeStructureXml = '<oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsswssecurity-secext-1.0.xsd"
xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
 <oas:UsernameToken oas1:Id="UsernameToken-1">
 <oas:Username>WSYYYXXX</oas:Username>
 <oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsssoap-message-security-1.0#Base64Binary">c2VjcmV0bm9uY2UxMDExMQ==</oas:Nonce>
 <oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wssusername-token-profile-1.0#PasswordDigest">+LzcaRc+ndGAcZIXmq/N7xGes+k=</oas:Password>
 <oas1:Created>2015-09-30T14:12:15Z</oas1:Created>
 </oas:UsernameToken>
 </oas:Security>';

        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandler = new SoapHeader4($sessionHandlerParams);
        $sessionHandler->setStateful(true);

        $meth = self::getMethod($sessionHandler, 'createSoapHeaders');

        /** @var \SoapHeader[] $result */
        $result = $meth->invoke(
            $sessionHandler,
            ['sessionId' => null, 'sequenceNumber' => null, 'securityToken' => null],
            $sessionHandlerParams,
            'PNR_Retrieve',
            []
        );

        $this->assertCount(6, $result);
        foreach ($result as $tmp) {
            $this->assertInstanceOf('\SoapHeader', $tmp);
        }

        $this->assertInternalType('string', $result[0]->data);
        $this->assertTrue($this->isValidGuid($result[0]->data));
        $this->assertEquals('MessageID', $result[0]->name);
        $this->assertEquals('http://www.w3.org/2005/08/addressing', $result[0]->namespace);

        $this->assertInternalType('string', $result[1]->data);
        $this->assertEquals('http://webservices.amadeus.com/PNRRET_11_3_1A', $result[1]->data);
        $this->assertEquals('Action', $result[1]->name);
        $this->assertEquals('http://www.w3.org/2005/08/addressing', $result[1]->namespace);

        $this->assertInternalType('string', $result[2]->data);
        $this->assertEquals('https://dummy.webservices.endpoint.com/SOAPADDRESS', $result[2]->data);
        $this->assertEquals('To', $result[2]->name);
        $this->assertEquals('http://www.w3.org/2005/08/addressing', $result[2]->namespace);

        $this->assertInstanceOf('\SoapVar', $result[3]->data);
        $this->assertEquals(XSD_ANYXML, $result[3]->data->enc_type);
        $this->assertEqualXMLStructure($this->toDomElement($expectedSecurityNodeStructureXml), $this->toDomElement($result[3]->data->enc_value), true);
        $this->assertEquals('Security', $result[3]->name);
        $this->assertEquals('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsswssecurity-secext-1.0.xsd',
            $result[3]->namespace);

        $this->assertInstanceOf('Amadeus\Client\Struct\HeaderV4\Session', $result[4]->data);
        $this->assertEquals('Start', $result[4]->data->TransactionStatusCode);

        $this->assertInstanceOf('Amadeus\Client\Struct\HeaderV4\SecurityHostedUser', $result[5]->data);
        $this->assertEquals('AMA_SecurityHostedUser', $result[5]->name);
        $this->assertEquals('http://xml.amadeus.com/2010/06/Security_v1', $result[5]->namespace);
    }

    /**
     * Testing soap header generation when we're about to make the subsequent calls in stateful mode with an active session.
     */
    public function testCanCreateSoapHeadersWhenStatefulAndAuthenticated()
    {
        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandler = new SoapHeader4($sessionHandlerParams);
        $sessionHandler->setStateful(true);

        $prop = self::getProperty($sessionHandler, 'isAuthenticated');
        $prop->setValue($sessionHandler, true);

        $meth = self::getMethod($sessionHandler, 'createSoapHeaders');

        /** @var \SoapHeader[] $result */
        $result = $meth->invoke(
            $sessionHandler,
            ['sessionId' => '01ZWHV5EMT', 'sequenceNumber' => 2, 'securityToken' => '3WY60GB9B0FX2SLIR756QZ4G2'],
            $sessionHandlerParams,
            'PNR_Retrieve',
            []
        );

        $this->assertCount(4, $result);
        foreach ($result as $tmp) {
            $this->assertInstanceOf('\SoapHeader', $tmp);
        }

        $this->assertInternalType('string', $result[0]->data);
        $this->assertTrue($this->isValidGuid($result[0]->data));
        $this->assertEquals('MessageID', $result[0]->name);
        $this->assertEquals('http://www.w3.org/2005/08/addressing', $result[0]->namespace);

        $this->assertInternalType('string', $result[1]->data);
        $this->assertEquals('http://webservices.amadeus.com/PNRRET_11_3_1A', $result[1]->data);
        $this->assertEquals('Action', $result[1]->name);
        $this->assertEquals('http://www.w3.org/2005/08/addressing', $result[1]->namespace);

        $this->assertInternalType('string', $result[2]->data);
        $this->assertEquals('https://dummy.webservices.endpoint.com/SOAPADDRESS', $result[2]->data);
        $this->assertEquals('To', $result[2]->name);
        $this->assertEquals('http://www.w3.org/2005/08/addressing', $result[2]->namespace);

        $this->assertInstanceOf('Amadeus\Client\Struct\HeaderV4\Session', $result[3]->data);
        $this->assertEquals('InSeries', $result[3]->data->TransactionStatusCode);
        $this->assertEquals('01ZWHV5EMT', $result[3]->data->SessionId);
        $this->assertEquals(2, $result[3]->data->SequenceNumber);
        $this->assertEquals('3WY60GB9B0FX2SLIR756QZ4G2', $result[3]->data->SecurityToken);
    }

    public function testCanMakeSoapHeadersWithTransactionFlowLink()
    {
        $expectedSecurityNodeStructureXml = '<oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsswssecurity-secext-1.0.xsd"
xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
 <oas:UsernameToken oas1:Id="UsernameToken-1">
 <oas:Username>WSYYYXXX</oas:Username>
 <oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsssoap-message-security-1.0#Base64Binary">c2VjcmV0bm9uY2UxMDExMQ==</oas:Nonce>
 <oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wssusername-token-profile-1.0#PasswordDigest">+LzcaRc+ndGAcZIXmq/N7xGes+k=</oas:Password>
 <oas1:Created>2015-09-30T14:12:15Z</oas1:Created>
 </oas:UsernameToken>
 </oas:Security>';

        $sessionHandlerParams = $this->makeSessionHandlerParams(null, null, 'dummy');
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $meth = self::getMethod($sessionHandler, 'createSoapHeaders');

        /** @var \SoapHeader[] $result */
        $result = $meth->invoke(
            $sessionHandler,
            ['sessionId' => null, 'sequenceNumber' => null, 'securityToken' => null],
            $sessionHandlerParams,
            'PNR_Retrieve',
            []
        );

        $this->assertCount(6, $result);
        foreach ($result as $tmp) {
            $this->assertInstanceOf('\SoapHeader', $tmp);
        }

        $this->assertInternalType('string', $result[0]->data);
        $this->assertTrue($this->isValidGuid($result[0]->data));
        $this->assertEquals('MessageID', $result[0]->name);
        $this->assertEquals('http://www.w3.org/2005/08/addressing', $result[0]->namespace);

        $this->assertInternalType('string', $result[1]->data);
        $this->assertEquals('http://webservices.amadeus.com/PNRRET_11_3_1A', $result[1]->data);
        $this->assertEquals('Action', $result[1]->name);
        $this->assertEquals('http://www.w3.org/2005/08/addressing', $result[1]->namespace);

        $this->assertInternalType('string', $result[2]->data);
        $this->assertEquals('https://dummy.webservices.endpoint.com/SOAPADDRESS', $result[2]->data);
        $this->assertEquals('To', $result[2]->name);
        $this->assertEquals('http://www.w3.org/2005/08/addressing', $result[2]->namespace);


        $this->assertInstanceOf('Amadeus\Client\Struct\HeaderV4\TransactionFlowLink', $result[3]->data);
        $this->assertEquals('dummy', $result[3]->data->Consumer->UniqueID);
        $this->assertEquals('TransactionFlowLink', $result[3]->name);
        $this->assertEquals('http://wsdl.amadeus.com/2010/06/ws/Link_v1', $result[3]->namespace);

        $this->assertInstanceOf('\SoapVar', $result[4]->data);
        $this->assertEquals(XSD_ANYXML, $result[4]->data->enc_type);
        $this->assertEqualXMLStructure($this->toDomElement($expectedSecurityNodeStructureXml), $this->toDomElement($result[4]->data->enc_value), true);
        $this->assertEquals('Security', $result[4]->name);
        $this->assertEquals('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsswssecurity-secext-1.0.xsd',
            $result[4]->namespace);

        $this->assertInstanceOf('Amadeus\Client\Struct\HeaderV4\SecurityHostedUser', $result[5]->data);
        $this->assertEquals('AMA_SecurityHostedUser', $result[5]->name);
        $this->assertEquals('http://xml.amadeus.com/2010/06/Security_v1', $result[5]->namespace);
    }

    public function testCanMakeSoapHeadersWithTransactionFlowLinkGeneratedGuid()
    {
        $expectedSecurityNodeStructureXml = '<oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsswssecurity-secext-1.0.xsd"
xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
 <oas:UsernameToken oas1:Id="UsernameToken-1">
 <oas:Username>WSYYYXXX</oas:Username>
 <oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsssoap-message-security-1.0#Base64Binary">c2VjcmV0bm9uY2UxMDExMQ==</oas:Nonce>
 <oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wssusername-token-profile-1.0#PasswordDigest">+LzcaRc+ndGAcZIXmq/N7xGes+k=</oas:Password>
 <oas1:Created>2015-09-30T14:12:15Z</oas1:Created>
 </oas:UsernameToken>
 </oas:Security>';

        $sessionHandlerParams = $this->makeSessionHandlerParams(null, null, true);
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $meth = self::getMethod($sessionHandler, 'createSoapHeaders');

        /** @var \SoapHeader[] $result */
        $result = $meth->invoke(
            $sessionHandler,
            ['sessionId' => null, 'sequenceNumber' => null, 'securityToken' => null],
            $sessionHandlerParams,
            'PNR_Retrieve',
            []
        );

        $this->assertCount(6, $result);
        foreach ($result as $tmp) {
            $this->assertInstanceOf('\SoapHeader', $tmp);
        }

        $this->assertInternalType('string', $result[0]->data);
        $this->assertTrue($this->isValidGuid($result[0]->data));
        $this->assertEquals('MessageID', $result[0]->name);
        $this->assertEquals('http://www.w3.org/2005/08/addressing', $result[0]->namespace);

        $this->assertInternalType('string', $result[1]->data);
        $this->assertEquals('http://webservices.amadeus.com/PNRRET_11_3_1A', $result[1]->data);
        $this->assertEquals('Action', $result[1]->name);
        $this->assertEquals('http://www.w3.org/2005/08/addressing', $result[1]->namespace);

        $this->assertInternalType('string', $result[2]->data);
        $this->assertEquals('https://dummy.webservices.endpoint.com/SOAPADDRESS', $result[2]->data);
        $this->assertEquals('To', $result[2]->name);
        $this->assertEquals('http://www.w3.org/2005/08/addressing', $result[2]->namespace);


        $this->assertInstanceOf('Amadeus\Client\Struct\HeaderV4\TransactionFlowLink', $result[3]->data);
        $this->assertInternalType('string', $result[3]->data->Consumer->UniqueID);
        $this->assertEquals('TransactionFlowLink', $result[3]->name);
        $this->assertEquals('http://wsdl.amadeus.com/2010/06/ws/Link_v1', $result[3]->namespace);

        $this->assertInstanceOf('\SoapVar', $result[4]->data);
        $this->assertEquals(XSD_ANYXML, $result[4]->data->enc_type);
        $this->assertEqualXMLStructure($this->toDomElement($expectedSecurityNodeStructureXml), $this->toDomElement($result[4]->data->enc_value), true);
        $this->assertEquals('Security', $result[4]->name);
        $this->assertEquals('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsswssecurity-secext-1.0.xsd',
            $result[4]->namespace);

        $this->assertInstanceOf('Amadeus\Client\Struct\HeaderV4\SecurityHostedUser', $result[5]->data);
        $this->assertEquals('AMA_SecurityHostedUser', $result[5]->name);
        $this->assertEquals('http://xml.amadeus.com/2010/06/Security_v1', $result[5]->namespace);
    }

    /**
     * Test that the security soap header is not present on stateful Security_Authenticate requests,
     * but because of them being stateful the Session.TransactionStatusCode has to be "Start".
     */
    public function testCanMakeSoapHeadersWithStatefulSecurityAuthenticate()
    {
        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandler = new SoapHeader4($sessionHandlerParams);
        $sessionHandler->setStateful(true);

        $meth = self::getMethod($sessionHandler, 'createSoapHeaders');

        /** @var \SoapHeader[] $result */
        $result = $meth->invoke(
            $sessionHandler,
            ['sessionId' => null, 'sequenceNumber' => null, 'securityToken' => null],
            $sessionHandlerParams,
            'Security_Authenticate',
            []
        );

        // expect 4 Soap-Headers (being: MessageID, Action, To, Session)
        $this->assertCount(4, $result);

        // we assert non-existence of *Security* and *AMA_SecurityHostedUser*
        foreach ($result as $res) {
            $this->assertNotEquals('Security', $res->name);
            $this->assertNotEquals('AMA_SecurityHostedUser', $res->name);
        }

        $this->assertEquals('Session', $result[3]->name);
        $this->assertEquals('Start', $result[3]->data->TransactionStatusCode);
    }

    /**
     * Test for TransactionStatusCode in Soap-Headers for a PNR_Retrieve request with endSession set to *true*.
     */
    public function testCanMakeSoapHeadersWithStatefulPNRRetrieveEndSessionTrue()
    {
        $sessionData = [
            'sessionId' => '01ZWHV5EMT',
            'sequenceNumber' => '1',
            'securityToken' => '3WY60GB9B0FX2SLIR756QZ4G2'
        ];
        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandler = new SoapHeader4($sessionHandlerParams);
        $sessionHandler->setStateful(true);
        $sessionHandler->setSessionData($sessionData);

        $meth = self::getMethod($sessionHandler, 'createSoapHeaders');

        /** @var \SoapHeader[] $result */
        $result = $meth->invoke(
            $sessionHandler,
            $sessionData,
            $sessionHandlerParams,
            'PNR_Retrieve',
            ['endSession' => true]
        );

        // expect 4 Soap-Headers (being: MessageID, Action, To, Session)
        $this->assertCount(4, $result);

        $this->assertEquals('Session', $result[3]->name);
        $this->assertEquals('End', $result[3]->data->TransactionStatusCode);
    }

    public function dataProviderGenerateDigest()
    {
        return [
            [
                "WBSPassword",
                "2013-01-11T09:41:03Z",
                base64_decode("PZgFvh5439plJpKpIyf5ucmXhNU="),
                "ic3AOJElVpvkz9ZBKd105Siry28="
            ],
            ["AMADEUS", "2015-09-30T14:12:15Z", "secretnonce10111", "+LzcaRc+ndGAcZIXmq/N7xGes+k="],
            [
                "AMADEUS",
                "2015-09-30T14:15:11Z",
                base64_decode("NjPfanrqSdmXuFWgPoQlAsHOUbjOBg=="),
                "k/upHztkhZzrsqAKsOBUa45+j1w="
            ],
            [
                base64_decode('VnA3ZjN1T0k='),
                "2016-01-15T10:16:41:553Z",
                base64_decode("ZjViYjdiZGJmOTMwY2FhYzQ5Zjk2NTEzMjhmYTdjMjUzN2NlMzI2ZQ=="),
                "CELeKeKpVxMV3xvxhfVvvl/ayIA="
            ],
            [
                base64_decode('VnA3ZjN1T0k='),
                "2016-01-15T11:06:30:321Z",
                base64_decode("oD07B/1XeFLCsuKhB2NXdwtvMJY="),
                "hbgT0fpvlBCRF+J/EV/4XwCRxdw="
            ],
            [
                "AMADEUS",
                "2015-09-30T14:12:15Z",
                base64_decode("c2VjcmV0bm9uY2UxMDExMQ=="),
                "+LzcaRc+ndGAcZIXmq/N7xGes+k="
            ]
        ];
    }


    /**
     * @dataProvider dataProviderGenerateDigest
     */
    public function testCanGenerateCorrectPasswordDigest($password, $creationString, $plainNonce, $expectedResult)
    {
        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $meth = self::getMethod($sessionHandler, 'generatePasswordDigest');

        $result = $meth->invoke(
            $sessionHandler,
            $password,
            $creationString,
            $plainNonce
        );

        $this->assertEquals($expectedResult, $result);
    }

    public function dataProviderGenerateSecHeader()
    {
        return [
            [
                "BRU1AXXXX",
                "PZgFvh5439plJpKpIyf5ucmXhNU=",
                "ic3AOJElVpvkz9ZBKd105Siry28=",
                "2013-01-11T09:41:03Z",
                "<oas:Security xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\">
	<oas:UsernameToken xmlns:oas1=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd\" oas1:Id=\"UsernameToken-1\">
		<oas:Username>BRU1AXXXX</oas:Username>
		<oas:Nonce EncodingType=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary\">PZgFvh5439plJpKpIyf5ucmXhNU=</oas:Nonce>
		<oas:Password Type=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest\">ic3AOJElVpvkz9ZBKd105Siry28=</oas:Password>
		<oas1:Created>2013-01-11T09:41:03Z</oas1:Created>
	</oas:UsernameToken>
</oas:Security>"
            ],
        ];
    }

    /**
     * @dataProvider dataProviderGenerateSecHeader
     */
    public function testCanGenerateSecurityHeader($originator, $encodedNonce, $digest, $creationString, $expectedXml)
    {
        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $method = self::getMethod($sessionHandler, 'generateSecurityHeaderRawXml');


        $result = $method->invoke(
            $sessionHandler,
            $originator,
            $encodedNonce,
            $digest,
            $creationString
        );

        $this->assertXmlStringEqualsXmlString($expectedXml, $result);
    }

    public function testCanSetStateful()
    {
        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $sessionHandler->setStateful(false);
        $this->assertFalse($sessionHandler->isStateful());
        $sessionHandler->setStateful(true);
        $this->assertTrue($sessionHandler->isStateful());
    }

    public function testCanHandleDummyPostMessage()
    {
        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandler = new SoapHeader4($sessionHandlerParams);
        $sessionHandler->setStateful(true);

        $method = self::getMethod($sessionHandler, 'handlePostMessage');

        $actual = $method->invoke($sessionHandler, 'PNR_Retrieve', $this->getTestFile('dummyPnrResponse.txt'), null, null);

        $this->assertNull($actual);

        $authProp = self::getProperty($sessionHandler, 'isAuthenticated');
        $authPropValue = $authProp->getValue($sessionHandler);
        $this->assertTrue($authPropValue);

        $sessionProp = self::getProperty($sessionHandler, 'sessionData');
        $sessionPropValue = $sessionProp->getValue($sessionHandler);
        $this->assertEquals(['sessionId' => '01ZWHV5EMT', 'sequenceNumber' => '1', 'securityToken' => '3WY60GB9B0FX2SLIR756QZ4G2'], $sessionPropValue);
    }

    public function testCanHandleDummyPostMessageSessionEnd()
    {
        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandler = new SoapHeader4($sessionHandlerParams);
        $sessionHandler->setStateful(true);

        $method = self::getMethod($sessionHandler, 'handlePostMessage');

        $actual = $method->invoke($sessionHandler, 'PNR_Retrieve', $this->getTestFile('dummyPnrResponseEnd.txt'), null, null);

        $this->assertNull($actual);

        $authProp = self::getProperty($sessionHandler, 'isAuthenticated');
        $authPropValue = $authProp->getValue($sessionHandler);
        $this->assertFalse($authPropValue);

        $sessionProp = self::getProperty($sessionHandler, 'sessionData');
        $sessionPropValue = $sessionProp->getValue($sessionHandler);
        $this->assertEquals(['sessionId' => null, 'sequenceNumber' => null, 'securityToken' => null], $sessionPropValue);
    }

    public function testCanReadSessionEndFromResponse()
    {
        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $method = self::getMethod($sessionHandler, 'getSessionDataFromHeader');

        $expected = [
            'sessionId' => null,
            'sequenceNumber' => null,
            'securityToken' => null
        ];

        $xml = $this->getTestFile("dummyPnrResponseEnd.txt");

        $actual = $method->invoke($sessionHandler, $xml);

        $this->assertInternalType('array', $actual);
        $this->assertEquals($expected, $actual);
    }

    public function testCanReadSessionDataFromResponse()
    {
        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandler = new SoapHeader4($sessionHandlerParams);
        $method = self::getMethod($sessionHandler, 'getSessionDataFromHeader');

        $expected = [
            'sessionId' => '01ZWHV5EMT',
            'sequenceNumber' => 1,
            'securityToken' => '3WY60GB9B0FX2SLIR756QZ4G2'
        ];

        $xml = $this->getTestFile("dummyPnrResponse.txt");

        $actual = $method->invoke($sessionHandler, $xml);

        $this->assertInternalType('array', $actual);
        $this->assertEquals($expected, $actual);
    }

    public function testCanSendMessage()
    {
        $overrideSoapClient = $this->getMock(
            'Amadeus\Client\SoapClient',
            ['__getLastRequest', '__getLastResponse', 'PNR_Retrieve'],
            [],
            '',
            false
        );

        $dummyPnrRequest = $this->getTestFile('dummyPnrRequest.txt');
        $dummyPnrReply = $this->getTestFile('sessionheadertestresponse.txt');
        $dummyPnrReplyExtractedMessage = $this->getTestFile('dummyPnrReplyExtractedMessage.txt');

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
            ->will($this->returnValue(new \stdClass()));

        $sessionHandlerParams = $this->makeSessionHandlerParams($overrideSoapClient);
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $pnrRetrieveMessage = new Client\Struct\Pnr\Retrieve(
            new Client\RequestOptions\PnrRetrieveOptions(['recordLocator' => 'ABC123'])
        );

        $messageResponse = $sessionHandler->sendMessage(
            'PNR_Retrieve',
            $pnrRetrieveMessage,
            ['endSession'=>false]
        );

        $expectedResult = new Client\Session\Handler\SendResult();
        $expectedResult->responseXml = $dummyPnrReplyExtractedMessage;
        $expectedResult->responseObject = new \stdClass();
        $expectedResult->messageVersion = '11.3';


        $this->assertEquals($expectedResult, $messageResponse);
    }

    public function testCanSendMessageInExistingSession()
    {
        $overrideSoapClient = $this->getMock(
            'Amadeus\Client\SoapClient',
            ['__getLastRequest', '__getLastResponse', 'PNR_Retrieve'],
            [],
            '',
            false
        );

        $dummyPnrRequest = $this->getTestFile('dummyPnrRequest.txt');
        $dummyPnrReply = $this->getTestFile('sessionheadertestresponse.txt');
        $dummyPnrReplyExtractedMessage = $this->getTestFile('dummyPnrReplyExtractedMessage.txt');

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
            ->will($this->returnValue(new \stdClass()));

        $sessionHandlerParams = $this->makeSessionHandlerParams($overrideSoapClient);
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $sessionHandler->setSessionData([
            'sessionId' => '01ZWHV5EMT',
            'sequenceNumber' => 1,
            'securityToken' => '3WY60GB9B0FX2SLIR756QZ4G2'
        ]);

        $pnrRetrieveMessage = new Client\Struct\Pnr\Retrieve(
            new Client\RequestOptions\PnrRetrieveOptions(['recordLocator' => 'ABC123'])
        );

        $messageResponse = $sessionHandler->sendMessage(
            'PNR_Retrieve',
            $pnrRetrieveMessage,
            ['endSession'=>false]
        );

        $expectedResult = new Client\Session\Handler\SendResult();
        $expectedResult->responseXml = $dummyPnrReplyExtractedMessage;
        $expectedResult->responseObject = new \stdClass();
        $expectedResult->messageVersion = '11.3';

        $this->assertEquals($expectedResult, $messageResponse);

        $postMessageSession = $sessionHandler->getSessionData();

        $this->assertEquals(
            [
                'sessionId' => '01ZWHV5EMT',
                'sequenceNumber' => 2,
                'securityToken' => '3WY60GB9B0FX2SLIR756QZ4G2'
            ],
            $postMessageSession
        );
    }

    public function testCanHandleMessageWithSoapFault()
    {
        $overrideSoapClient = $this->getMock(
            'Amadeus\Client\SoapClient',
            ['__getLastRequest', '__getLastResponse', 'PNR_Retrieve'],
            [],
            '',
            false
        );

        $dummyPnrRequest = $this->getTestFile('dummyPnrRequest.txt');
        $dummyPnrReply = $this->getTestFile('sessionheadertestresponse.txt');
        //$dummyPnrReplyExtractedMessage = $this->getTestFile('dummyPnrReplyExtractedMessage.txt');

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
            ->will($this->throwException(new \SoapFault("Sender", "284|Application|SECURED PNR")));

        $sessionHandlerParams = $this->makeSessionHandlerParams($overrideSoapClient);
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $pnrRetrieveMessage = new Client\Struct\Pnr\Retrieve(
            new Client\RequestOptions\PnrRetrieveOptions(['recordLocator' => 'ABC123'])
        );

        $sendResult = $sessionHandler->sendMessage(
            'PNR_Retrieve',
            $pnrRetrieveMessage,
            ['endSession'=>false]
        );

        $this->assertInstanceOf('Amadeus\Client\Session\Handler\SendResult', $sendResult);
        $this->assertInstanceOf('\SoapFault', $sendResult->exception);
        $this->assertEquals('284|Application|SECURED PNR', $sendResult->exception->getMessage());
        $this->assertEquals('11.3', $sendResult->messageVersion);
        $extractor = new Client\Util\MsgBodyExtractor();
        $this->assertEquals($extractor->extract($dummyPnrReply), $sendResult->responseXml);
    }

    public function testCanHandleMessageThrowingNonSoapFaultException()
    {
        $this->setExpectedException('\Amadeus\Client\Exception');

        $overrideSoapClient = $this->getMock(
            'Amadeus\Client\SoapClient',
            ['__getLastRequest', '__getLastResponse', 'PNR_Retrieve'],
            [],
            '',
            false
        );

        $dummyPnrRequest = $this->getTestFile('dummyPnrRequest.txt');
        $dummyPnrReply = $this->getTestFile('sessionheadertestresponse.txt');
        //$dummyPnrReplyExtractedMessage = $this->getTestFile('dummyPnrReplyExtractedMessage.txt');

        $overrideSoapClient
            ->expects($this->atLeastOnce())
            ->method('__getLastRequest')
            ->will($this->returnValue($dummyPnrRequest));

        $overrideSoapClient
            ->expects($this->atLeastOnce())
            ->method('__getLastResponse')
            ->will($this->returnValue($dummyPnrReply));

        $overrideSoapClient
            ->expects($this->once())
            ->method('PNR_Retrieve')
            ->will($this->throwException(new \InvalidArgumentException("Something is invalid, don't ask me")));

        $sessionHandlerParams = $this->makeSessionHandlerParams($overrideSoapClient);
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $pnrRetrieveMessage = new Client\Struct\Pnr\Retrieve(
            new Client\RequestOptions\PnrRetrieveOptions(['recordLocator' => 'ABC123'])
        );

        $sessionHandler->sendMessage(
            'PNR_Retrieve',
            $pnrRetrieveMessage,
            ['endSession'=>false]
        );
    }

    public function testCanExtractSessionDataAfterCall()
    {
        $overrideSoapClient = $this->getMock(
            'Amadeus\Client\SoapClient',
            ['__getLastRequest', '__getLastResponse', 'PNR_Retrieve'],
            [],
            '',
            false
        );

        $dummyPnrRequest = $this->getTestFile('dummyPnrRequest.txt');
        $overrideSoapClient
            ->expects($this->atLeastOnce())
            ->method('__getLastRequest')
            ->will($this->returnValue($dummyPnrRequest));

        $overrideSoapClient
            ->expects($this->atLeastOnce())
            ->method('__getLastResponse')
            ->will($this->returnValue($this->getTestFile('acspnr.xml')));

        $overrideSoapClient
            ->expects($this->any())
            ->method('PNR_Retrieve')
            ->will($this->returnValue($this->getTestFile('acspnr.xml')));

        $sessionHandlerParams = $this->makeSessionHandlerParams($overrideSoapClient);
        $sessionHandlerParams->stateful = true;
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $pnrRetrieveMessage = new Client\Struct\Pnr\Retrieve(
            new Client\RequestOptions\PnrRetrieveOptions(['recordLocator' => 'ABC123'])
        );

        $messageResponse = $sessionHandler->sendMessage(
            'PNR_Retrieve',
            $pnrRetrieveMessage,
            ['endSession' => false]
        );

        $expectedResult = new Client\Session\Handler\SendResult();
        $expectedResult->responseXml = $this->getTestFile('acspnrreply.xml');
        $expectedResult->responseObject = $this->getTestFile('acspnr.xml');
        $expectedResult->messageVersion = '11.3';

        $this->assertEquals($expectedResult, $messageResponse);

        $sessionData = $sessionHandler->getSessionData();

        $expectedSession = [
            'sessionId' => '002C3V0DMO',
            'sequenceNumber' => 1,
            'securityToken' => '1UPC20RZJXARQ20H3J0TMJQU4H',
        ];

        $this->assertEquals($expectedSession, $sessionData);

    }

    public function testCanExtractMessagesAndVersions()
    {
        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $actual = $sessionHandler->getMessagesAndVersions();

        $this->assertCount(3, $actual);
        $this->assertEquals(['PNR_Retrieve', 'Security_SignOut', 'Security_Authenticate'], array_keys($actual));
        $this->assertEquals('11.3', $actual['PNR_Retrieve']['version']);
        $this->assertInternalType('string', $actual['PNR_Retrieve']['wsdl']);
        $this->assertEquals('4.1', $actual['Security_SignOut']['version']);
        $this->assertInternalType('string', $actual['Security_SignOut']['wsdl']);
        $this->assertEquals('6.1', $actual['Security_Authenticate']['version']);
        $this->assertInternalType('string', $actual['Security_Authenticate']['wsdl']);
    }

    public function testCanHandleInvalidWsdlWhenLoadingMessagesAndVersions()
    {
        \PHPUnit_Framework_Error_Warning::$enabled = FALSE;

        $this->setExpectedException('\Amadeus\Client\InvalidWsdlFileException', 'could not be loaded');

        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandlerParams->wsdl[] = __DIR__. DIRECTORY_SEPARATOR . 'invalidwsdl.wsdl';
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $sessionHandler->getMessagesAndVersions();
    }

    public function testCanHandleInvalidImportWsdlWhenLoadingMessagesAndVersions()
    {
        \PHPUnit_Framework_Error_Warning::$enabled = FALSE;

        $this->setExpectedException('\Amadeus\Client\InvalidWsdlFileException', 'import could not be loaded');

        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandlerParams->wsdl[] = __DIR__. DIRECTORY_SEPARATOR . 'testfiles' . DIRECTORY_SEPARATOR . 'mediawsdl'.DIRECTORY_SEPARATOR.'DUMMYWSAP_MediaServer_invalid.wsdl';
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $sessionHandler->getMessagesAndVersions();
    }

    public function testCanExtractMessagesAndVersionsFromMediaWsdl()
    {
        $sessionHandlerParams = $this->makeSessionHandlerParams(null, true);
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $actual = $sessionHandler->getMessagesAndVersions();

        $this->assertInternalType('array', $actual);
        $this->assertCount(4, $actual);
        $this->assertEquals(['PNR_Retrieve', 'Security_SignOut', 'Security_Authenticate', 'Media_GetMedia'], array_keys($actual));
        $this->assertEquals('11.3', $actual['PNR_Retrieve']['version']);
        $this->assertInternalType('string', $actual['PNR_Retrieve']['wsdl']);
        $this->assertEquals('4.1', $actual['Security_SignOut']['version']);
        $this->assertInternalType('string', $actual['Security_SignOut']['wsdl']);
        $this->assertEquals('1.000', $actual['Media_GetMedia']['version']);
        $this->assertInternalType('string', $actual['Media_GetMedia']['wsdl']);
        $this->assertNotEquals($actual['PNR_Retrieve']['wsdl'], $actual['Media_GetMedia']['wsdl']);
    }

    public function testCanMakeSoapClientOptionsWithOverrides()
    {
        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandlerParams->soapClientOptions['compression'] = SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP;

        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $expected = [
            'trace' 		=> 1,
            'exceptions' 	=> 1,
            'soap_version' 	=> SOAP_1_1,
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
            'classmap' => Client\Session\Handler\Classmap::$soapheader4map
        ];

        $meth = self::getMethod($sessionHandler, 'makeSoapClientOptions');
        $result = $meth->invoke($sessionHandler);

        $this->assertEquals($expected, $result);
    }

    public function testCanMakeSessionHandlerWithoutLogger()
    {
        $overrideSoapClient = $this->getMock(
            'Amadeus\Client\SoapClient',
            ['__getLastRequest', '__getLastResponse', 'PNR_Retrieve'],
            [],
            '',
            false
        );

        $dummyPnrRequest = $this->getTestFile('dummyPnrRequest.txt');
        $dummyPnrReply = $this->getTestFile('sessionheadertestresponse.txt');
        $dummyPnrReplyExtractedMessage = $this->getTestFile('dummyPnrReplyExtractedMessage.txt');

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
            ->will($this->returnValue(new \stdClass()));

        $sessionHandlerParams = $this->makeSessionHandlerParams($overrideSoapClient);
        $sessionHandlerParams->logger = null;

        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $pnrRetrieveMessage = new Client\Struct\Pnr\Retrieve(
            new Client\RequestOptions\PnrRetrieveOptions(['recordLocator' => 'ABC123'])
        );

        $messageResponse = $sessionHandler->sendMessage(
            'PNR_Retrieve',
            $pnrRetrieveMessage,
            ['endSession'=>false]
        );

        $expectedResult = new Client\Session\Handler\SendResult();
        $expectedResult->responseXml = $dummyPnrReplyExtractedMessage;
        $expectedResult->responseObject = new \stdClass();
        $expectedResult->messageVersion = '11.3';

        $this->assertEquals($expectedResult, $messageResponse);
    }

    public function testCanTryAuthenticateWithInvalidSessionData()
    {
        $invalidSessionData = [
            'noSessionID' => 'ABCA2312KJL',
            'wrongSequence' => 3
        ];

        $sessionHandlerParams = $this->makeSessionHandlerParams();

        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $isAuthenticated = $sessionHandler->setSessionData($invalidSessionData);

        $this->assertFalse($isAuthenticated);
    }

    public function testGetLastRequestEmptyWithNoMessages()
    {

        $handlerParams = $this->makeSessionHandlerParams();

        $handler = new SoapHeader4($handlerParams);

        $result = $handler->getLastRequest('PNR_Retrieve');

        $this->assertNull($result);
    }

    public function testCanGetLastRequestHeaders()
    {
        $overrideSoapClient = $this->getMock(
            'Amadeus\Client\SoapClient',
            ['__getLastRequest', '__getLastResponse', '__getLastRequestHeaders', '__getLastResponseHeaders', 'PNR_Retrieve'],
            [],
            '',
            false
        );

        $dummyPnrRequest = $this->getTestFile('dummyPnrRequest.txt');
        $dummyPnrReply = $this->getTestFile('sessionheadertestresponse.txt');

        $dummyRequestHeaders = <<<EOT
POST https://dummy.endpoint/ HTTP/1.1
Accept-Encoding: gzip,deflate
Content-Type: text/xml;charset=UTF-8
SOAPAction: "http://webservices.amadeus.com/PNRADD_11_3_1A"
Content-Length: 2166
Host: dummy.endpoint
Connection: Keep-Alive
User-Agent: DummyUserAgent
EOT;

        $overrideSoapClient
            ->expects($this->atLeastOnce())
            ->method('__getLastRequest')
            ->will($this->returnValue($dummyPnrRequest));

        $overrideSoapClient
            ->expects($this->atLeastOnce())
            ->method('__getLastRequestHeaders')
            ->will($this->returnValue($dummyRequestHeaders));


        $overrideSoapClient
            ->expects($this->never())
            ->method('__getLastResponseHeaders');

        $overrideSoapClient
            ->expects($this->atLeastOnce())
            ->method('__getLastResponse')
            ->will($this->returnValue($dummyPnrReply));

        $overrideSoapClient
            ->expects($this->any())
            ->method('PNR_Retrieve')
            ->will($this->returnValue(new \stdClass()));

        $sessionHandlerParams = $this->makeSessionHandlerParams($overrideSoapClient);
        $sessionHandlerParams->logger = null;

        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $pnrRetrieveMessage = new Client\Struct\Pnr\Retrieve(
            new Client\RequestOptions\PnrRetrieveOptions(['recordLocator' => 'ABC123'])
        );

        $messageResponse = $sessionHandler->sendMessage(
            'PNR_Retrieve',
            $pnrRetrieveMessage,
            ['endSession'=>false]
        );

        $reqHeaders = $sessionHandler->getLastRequestHeaders('PNR_Retrieve');

        $this->assertEquals($dummyRequestHeaders, $reqHeaders);
    }

    public function testCanGetLastResponseHeaders()
    {
        $overrideSoapClient = $this->getMock(
            'Amadeus\Client\SoapClient',
            ['__getLastRequest', '__getLastResponse', '__getLastResponseHeaders', '__getLastRequestHeaders', 'PNR_Retrieve'],
            [],
            '',
            false
        );

        $dummyPnrRequest = $this->getTestFile('dummyPnrRequest.txt');
        $dummyPnrReply = $this->getTestFile('sessionheadertestresponse.txt');

        $dummyResponseHeaders = <<<EOT
dummy
response
headers
EOT;

        $overrideSoapClient
            ->expects($this->atLeastOnce())
            ->method('__getLastRequest')
            ->will($this->returnValue($dummyPnrRequest));

        $overrideSoapClient
            ->expects($this->atLeastOnce())
            ->method('__getLastResponseHeaders')
            ->will($this->returnValue($dummyResponseHeaders));

        $overrideSoapClient
            ->expects($this->never())
            ->method('__getLastRequestHeaders');

        $overrideSoapClient
            ->expects($this->atLeastOnce())
            ->method('__getLastResponse')
            ->will($this->returnValue($dummyPnrReply));

        $overrideSoapClient
            ->expects($this->any())
            ->method('PNR_Retrieve')
            ->will($this->returnValue(new \stdClass()));

        $sessionHandlerParams = $this->makeSessionHandlerParams($overrideSoapClient);
        $sessionHandlerParams->logger = null;

        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $pnrRetrieveMessage = new Client\Struct\Pnr\Retrieve(
            new Client\RequestOptions\PnrRetrieveOptions(['recordLocator' => 'ABC123'])
        );

        $messageResponse = $sessionHandler->sendMessage(
            'PNR_Retrieve',
            $pnrRetrieveMessage,
            ['endSession'=>false]
        );

        $resHeaders = $sessionHandler->getLastResponseHeaders('PNR_Retrieve');

        $this->assertEquals($dummyResponseHeaders, $resHeaders);
    }

    /**
     * @param \SoapClient|null $overrideSoapClient
     * @param bool $withMediaWsdl
     * @param string|null $tfl
     * @return SessionHandlerParams
     */
    protected function makeSessionHandlerParams($overrideSoapClient = null, $withMediaWsdl = false, $tfl = null)
    {
        $wsdlpath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'testfiles' . DIRECTORY_SEPARATOR . 'testwsdl.wsdl';

        $par = new SessionHandlerParams([
            'wsdl' => realpath($wsdlpath),
            'stateful' => false,
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

        if (!is_null($overrideSoapClient)) {
            $par->overrideSoapClient = $overrideSoapClient;
            $par->overrideSoapClientWsdlName  = sprintf('%x', crc32($wsdlpath));
        }

        if(!is_null($tfl)) {
            $par->enableTransactionFlowLink = true;
            $par->consumerId = ($tfl === true) ? null : $tfl;
        }

        if ($withMediaWsdl) {
            $par->wsdl[] = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'testfiles' .
                DIRECTORY_SEPARATOR . 'mediawsdl' . DIRECTORY_SEPARATOR . 'DUMMYWSAP_MediaServer.wsdl';
        }

        return $par;
    }

    /**
     * @param string $guid
     * @return bool
     */
    protected function isValidGuid($guid)
    {
        $valid = false;

        if (preg_match('/^\{?[A-F0-9]{8}-[A-F0-9]{4}-[A-F0-9]{4}-[A-F0-9]{4}-[A-F0-9]{12}\}?$/', $guid)) {
            $valid = true;
        }

        return $valid;
    }

    /**
     * @param $xmlString
     * @return \DOMElement
     */
    protected function toDomElement($xmlString)
    {
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $doc->loadXML($xmlString);

        return $doc->firstChild;
    }
}
