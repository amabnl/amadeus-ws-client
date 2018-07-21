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

namespace Test\Amadeus\Client;

use Amadeus\Client\SoapClient;
use Test\Amadeus\BaseTestCase;

/**
 * SoapClientTest
 *
 * @package Test\Amadeus\Client
 */
class SoapClientTest extends BaseTestCase
{
    public function testCanTransformRequestWithXslt()
    {
        $client = new SoapClient(
            dirname(__FILE__) . DIRECTORY_SEPARATOR . 'testfiles' . DIRECTORY_SEPARATOR . 'dummywsdl.wsdl',
            [
                'trace' => 1,
                'exceptions' => 1,
                'soap_version' => SOAP_1_1
            ]
        );

        $request = '<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://xml.amadeus.com/PNRRET_11_3_1A" xmlns:ns2="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:ns3="http://www.w3.org/2005/08/addressing" xmlns:ns4="http://xml.amadeus.com/2010/06/Security_v1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
 <SOAP-ENV:Header>
  <ns2:Security>
   <ns2:UsernameToken>
    <ns2:Username>WSXXXXXX</ns2:Username>
    <ns2:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">MjAwMDY2MzQ0MmY1MzAzYjU5MDc2YTMrerREZMGVmNzk0Mw==</ns2:Password>
    <ns2:Nonce>OTk1OTg3Y2DQSDQSDQSEyMWM0MmE1NjQwOTc4NDdjYjFmYWU1MQ==</ns2:Nonce>
    <ns2:Created>2016-01-13T14:56:05:238Z</ns2:Created>
   </ns2:UsernameToken>
  </ns2:Security>
  <ns3:MessageID>62BEF248-2156-15D2-2056-4044AF358A02</ns3:MessageID>
  <ns3:To>http://dummy.url</ns3:To>
  <ns3:Action>http://dummy.soapAction</ns3:Action>
  <ns4:AMA_SecurityHostedUser>
   <ns4:UserID RequestorType="U" PseudoCityCode="BRUBGXXXX" POS_Type="1"/>
  </ns4:AMA_SecurityHostedUser>
 </SOAP-ENV:Header>
 <SOAP-ENV:Body>
  <ns1:PNR_Retrieve>
   <ns1:retrievalFacts>
    <ns1:retrieve>
     <ns1:type>2</ns1:type>
    </ns1:retrieve>
    <ns1:reservationOrProfileIdentifier>
     <ns1:reservation>
      <ns1:controlNumber>ABC123</ns1:controlNumber>
     </ns1:reservation>
    </ns1:reservationOrProfileIdentifier>
   </ns1:retrievalFacts>
  </ns1:PNR_Retrieve>
 </SOAP-ENV:Body>
</SOAP-ENV:Envelope>';

        $meth = self::getMethod($client, 'transformIncomingRequest');

        $result = $meth->invoke($client, $request);

        $expected = '<?xml version="1.0"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://xml.amadeus.com/PNRRET_11_3_1A" xmlns:ns2="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:ns3="http://www.w3.org/2005/08/addressing" xmlns:ns4="http://xml.amadeus.com/2010/06/Security_v1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <SOAP-ENV:Header>
    <ns2:Security>
      <ns2:UsernameToken>
        <ns2:Username>WSXXXXXX</ns2:Username>
        <ns2:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">MjAwMDY2MzQ0MmY1MzAzYjU5MDc2YTMrerREZMGVmNzk0Mw==</ns2:Password>
        <ns2:Nonce>OTk1OTg3Y2DQSDQSDQSEyMWM0MmE1NjQwOTc4NDdjYjFmYWU1MQ==</ns2:Nonce>
        <ns2:Created>2016-01-13T14:56:05:238Z</ns2:Created>
      </ns2:UsernameToken>
    </ns2:Security>
    <ns3:MessageID>62BEF248-2156-15D2-2056-4044AF358A02</ns3:MessageID>
    <ns3:To>http://dummy.url</ns3:To>
    <ns3:Action>http://dummy.soapAction</ns3:Action>
    <ns4:AMA_SecurityHostedUser>
      <ns4:UserID POS_Type="1" PseudoCityCode="BRUBGXXXX" RequestorType="U"/>
    </ns4:AMA_SecurityHostedUser>
  </SOAP-ENV:Header>
  <SOAP-ENV:Body>
    <ns1:PNR_Retrieve>
      <ns1:retrievalFacts>
        <ns1:retrieve>
          <ns1:type>2</ns1:type>
        </ns1:retrieve>
        <ns1:reservationOrProfileIdentifier>
          <ns1:reservation>
            <ns1:controlNumber>ABC123</ns1:controlNumber>
          </ns1:reservation>
        </ns1:reservationOrProfileIdentifier>
      </ns1:retrievalFacts>
    </ns1:PNR_Retrieve>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>';

        $this->assertXmlStringEqualsXmlString($expected, $result);
    }

    public function testCanParseStuff()
    {
        $input = '<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://xml.amadeus.com/PNRRET_11_3_1A" xmlns:ns3="http://www.w3.org/2005/08/addressing" xmlns:ns4="http://xml.amadeus.com/2010/06/Security_v1">
 <SOAP-ENV:Header>
  <oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsswssecurity-secext-1.0.xsd" xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
   <oas:UsernameToken oas1:Id="UsernameToken-1">
    <oas:Username>WSXXXXXX</oas:Username>
    <oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsssoap-message-security-1.0#Base64Binary">SxnySEw+ZSVy1GXRwGEBlFnZaJw=</oas:Nonce>
    <oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wssusername-token-profile-1.0#PasswordDigest">pyXXutmMTWKPYgelVmUG3hN6aoY=</oas:Password>
    <oas1:Created>2016-01-15T13:13:13:326Z</oas1:Created>
   </oas:UsernameToken>
  </oas:Security>
  <ns3:MessageID>FF93A63C-9002-942A-13D5-90BCA97D3C15</ns3:MessageID>
  <ns3:To>https://dummy.endpoint/1ASIWXXXXXX</ns3:To>
  <ns3:Action>http://webservices.amadeus.com/PNRRET_11_3_1A</ns3:Action>
  <ns4:AMA_SecurityHostedUser>
   <ns4:UserID POS_Type="1" PseudoCityCode="BRUBGXXXX" RequestorType="U"/>
  </ns4:AMA_SecurityHostedUser>
 </SOAP-ENV:Header>
 <SOAP-ENV:Body>
  <ns1:PNR_Retrieve>
   <ns1:retrievalFacts>
    <ns1:retrieve>
     <ns1:type>2</ns1:type>
    </ns1:retrieve>
    <ns1:reservationOrProfileIdentifier>
     <ns1:reservation>
      <ns1:controlNumber>ABC123</ns1:controlNumber>
     </ns1:reservation>
    </ns1:reservationOrProfileIdentifier>
   </ns1:retrievalFacts>
  </ns1:PNR_Retrieve>
 </SOAP-ENV:Body>
</SOAP-ENV:Envelope>';

        $client = new SoapClient(
            dirname(__FILE__) . DIRECTORY_SEPARATOR . 'testfiles' . DIRECTORY_SEPARATOR . 'dummywsdl.wsdl',
            [
                'trace' => 1,
                'exceptions' => 1,
                'soap_version' => SOAP_1_1
            ]
        );

        $meth = self::getMethod($client, 'transformIncomingRequest');

        $result = $meth->invoke($client, $input);

        $expected = '<?xml version="1.0"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://xml.amadeus.com/PNRRET_11_3_1A" xmlns:ns3="http://www.w3.org/2005/08/addressing" xmlns:ns4="http://xml.amadeus.com/2010/06/Security_v1">
  <SOAP-ENV:Header>
    <oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsswssecurity-secext-1.0.xsd" xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
      <oas:UsernameToken Id="UsernameToken-1">
        <oas:Username>WSXXXXXX</oas:Username>
        <oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wsssoap-message-security-1.0#Base64Binary">SxnySEw+ZSVy1GXRwGEBlFnZaJw=</oas:Nonce>
        <oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wssusername-token-profile-1.0#PasswordDigest">pyXXutmMTWKPYgelVmUG3hN6aoY=</oas:Password>
        <oas1:Created>2016-01-15T13:13:13:326Z</oas1:Created>
      </oas:UsernameToken>
    </oas:Security>
    <ns3:MessageID>FF93A63C-9002-942A-13D5-90BCA97D3C15</ns3:MessageID>
    <ns3:To>https://dummy.endpoint/1ASIWXXXXXX</ns3:To>
    <ns3:Action>http://webservices.amadeus.com/PNRRET_11_3_1A</ns3:Action>
    <ns4:AMA_SecurityHostedUser>
      <ns4:UserID POS_Type="1" PseudoCityCode="BRUBGXXXX" RequestorType="U"/>
    </ns4:AMA_SecurityHostedUser>
  </SOAP-ENV:Header>
  <SOAP-ENV:Body>
    <ns1:PNR_Retrieve>
      <ns1:retrievalFacts>
        <ns1:retrieve>
          <ns1:type>2</ns1:type>
        </ns1:retrieve>
        <ns1:reservationOrProfileIdentifier>
          <ns1:reservation>
            <ns1:controlNumber>ABC123</ns1:controlNumber>
          </ns1:reservation>
        </ns1:reservationOrProfileIdentifier>
      </ns1:retrievalFacts>
    </ns1:PNR_Retrieve>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>';

        $this->assertXmlStringEqualsXmlString($expected, $result);
    }

    /**
     * Actually test the processing of the XSLT transformation
     *
     * The sample Queue_List message being transformed has both empty nodes that should be removed
     * as well as an empty node that should NOT be removed ("dumbo").
     */
    public function testXsltProcessorCanRemoveEmptyNodes()
    {
        $client = new SoapClient(
            dirname(__FILE__) . DIRECTORY_SEPARATOR . 'testfiles' . DIRECTORY_SEPARATOR . 'dummywsdl.wsdl',
            [
                'trace' => 1,
                'exceptions' => 1,
                'soap_version' => SOAP_1_1
            ]
        );

        $request = '<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://xml.amadeus.com/PNRRET_11_3_1A" xmlns:ns2="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:ns3="http://www.w3.org/2005/08/addressing" xmlns:ns4="http://xml.amadeus.com/2010/06/Security_v1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <SOAP-ENV:Header>
    <ns2:Security>
      <ns2:UsernameToken>
        <ns2:Username>WSXXXXXX</ns2:Username>
        <ns2:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">MjAwMDY2MzQ0MmY1MzAzYjU5MDc2YTMrerREZMGVmNzk0Mw==</ns2:Password>
        <ns2:Nonce>OTk1OTg3Y2DQSDQSDQSEyMWM0MmE1NjQwOTc4NDdjYjFmYWU1MQ==</ns2:Nonce>
        <ns2:Created>2016-01-13T14:56:05:238Z</ns2:Created>
      </ns2:UsernameToken>
    </ns2:Security>
    <ns3:MessageID>62BEF248-2156-15D2-2056-4044AF358A02</ns3:MessageID>
    <ns3:To>http://dummy.url</ns3:To>
    <ns3:Action>http://dummy.soapAction</ns3:Action>
    <ns4:AMA_SecurityHostedUser>
      <ns4:UserID RequestorType="U" PseudoCityCode="BRUBGXXXX" POS_Type="1"/>
    </ns4:AMA_SecurityHostedUser>
  </SOAP-ENV:Header>
  <SOAP-ENV:Body>
    <ns1:Queue_List>
      <ns1:scroll/>
      <ns1:targetOffice/>
      <ns1:queueNumber>
        <ns1:queueDetails>
          <ns1:number>50</ns1:number>
        </ns1:queueDetails>
      </ns1:queueNumber>
      <ns1:categoryDetails>
        <ns1:subQueueInfoDetails>
          <ns1:identificationType>C</ns1:identificationType>
          <ns1:itemNumber>3</ns1:itemNumber>
          <ns1:itemDescription/>
        </ns1:subQueueInfoDetails>
      </ns1:categoryDetails>
      <ns1:date/>
      <ns1:scanRange/>
      <ns1:searchCriteria>
        <ns1:sortOption>
          <ns1:selectionDetails>
            <ns1:option>CD</ns1:option>
          </ns1:selectionDetails>
        </ns1:sortOption>
      </ns1:searchCriteria>
      <ns1:sortCriteria>
        <ns1:dumbo/>
      </ns1:sortCriteria>
    </ns1:Queue_List>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>';

        $meth = self::getMethod($client, 'transformIncomingRequest');

        $result = $meth->invoke($client, $request);

        $expected = '<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://xml.amadeus.com/PNRRET_11_3_1A" xmlns:ns2="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:ns3="http://www.w3.org/2005/08/addressing" xmlns:ns4="http://xml.amadeus.com/2010/06/Security_v1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <SOAP-ENV:Header>
    <ns2:Security>
      <ns2:UsernameToken>
        <ns2:Username>WSXXXXXX</ns2:Username>
        <ns2:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">MjAwMDY2MzQ0MmY1MzAzYjU5MDc2YTMrerREZMGVmNzk0Mw==</ns2:Password>
        <ns2:Nonce>OTk1OTg3Y2DQSDQSDQSEyMWM0MmE1NjQwOTc4NDdjYjFmYWU1MQ==</ns2:Nonce>
        <ns2:Created>2016-01-13T14:56:05:238Z</ns2:Created>
      </ns2:UsernameToken>
    </ns2:Security>
    <ns3:MessageID>62BEF248-2156-15D2-2056-4044AF358A02</ns3:MessageID>
    <ns3:To>http://dummy.url</ns3:To>
    <ns3:Action>http://dummy.soapAction</ns3:Action>
    <ns4:AMA_SecurityHostedUser>
      <ns4:UserID RequestorType="U" PseudoCityCode="BRUBGXXXX" POS_Type="1"/>
    </ns4:AMA_SecurityHostedUser>
  </SOAP-ENV:Header>
  <SOAP-ENV:Body>
    <ns1:Queue_List>
      <ns1:queueNumber>
        <ns1:queueDetails>
          <ns1:number>50</ns1:number>
        </ns1:queueDetails>
      </ns1:queueNumber>
      <ns1:categoryDetails>
        <ns1:subQueueInfoDetails>
          <ns1:identificationType>C</ns1:identificationType>
          <ns1:itemNumber>3</ns1:itemNumber>
        </ns1:subQueueInfoDetails>
      </ns1:categoryDetails>
      <ns1:searchCriteria>
        <ns1:sortOption>
          <ns1:selectionDetails>
            <ns1:option>CD</ns1:option>
          </ns1:selectionDetails>
        </ns1:sortOption>
      </ns1:searchCriteria>
      <ns1:sortCriteria>
        <ns1:dumbo/>
      </ns1:sortCriteria>
    </ns1:Queue_List>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>';

        $this->assertXmlStringEqualsXmlString($expected, $result);
    }
}
