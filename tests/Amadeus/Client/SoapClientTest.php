<?php
/**
 * Created by PhpStorm.
 * User: dieter
 * Date: 13/01/2016
 * Time: 17:01
 */

namespace Test\Amadeus\Client;

use Amadeus\Client\SoapClient;


class SoapClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get a protected or private method from given class
     *
     * @param string $helper
     * @param string $name
     * @return \ReflectionMethod
     */
    protected static function getMethod($helper, $name)
    {
        $method = new \ReflectionMethod($helper, $name);
        $method->setAccessible(true);
        return $method;
    }

    public function testCanTransformRequestWithXslt()
    {
        $client = new SoapClient(
            dirname(__FILE__) . DIRECTORY_SEPARATOR . 'testfiles' . DIRECTORY_SEPARATOR . 'dummywsdl.wsdl',
            [
                'trace' 		=> 1,
                'exceptions' 	=> 1,
                'soap_version' 	=> SOAP_1_1
            ]
        );

        $request = '<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://xml.amadeus.com/PNRRET_11_3_1A" xmlns:ns2="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:ns3="http://www.w3.org/2005/08/addressing" xmlns:ns4="http://xml.amadeus.com/2010/06/Security_v1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<SOAP-ENV:Header>
		<ns2:Security>
			<ns2:UsernameToken>
				<ns2:Username>WSBENACS</ns2:Username>
				<ns2:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">
MjAwMDY2MzQ0MmY1MzAzYjU5MDc2YTMrerREZMGVmNzk0Mw==</ns2:Password>
				<ns2:Nonce>OTk1OTg3Y2DQSDQSDQSEyMWM0MmE1NjQwOTc4NDdjYjFmYWU1MQ==</ns2:Nonce>
				<ns2:Created>2016-01-13T14:56:05:238Z</ns2:Created>
			</ns2:UsernameToken>
		</ns2:Security>
		<ns3:MessageID>62BEF248-2156-15D2-2056-4044AF358A02</ns3:MessageID>
		<ns3:To>http://dummy.url</ns3:To>
		<ns3:Action>http://dummy.soapAction</ns3:Action>
		<ns4:AMA_SecurityHostedUser>
		<ns4:UserID RequestorType="U" PseudoCityCode="BRUBG21HE" POS_Type="1"/>
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
        <ns2:Username>WSBENACS</ns2:Username>
        <ns2:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">
MjAwMDY2MzQ0MmY1MzAzYjU5MDc2YTMrerREZMGVmNzk0Mw==</ns2:Password>
        <ns2:Nonce>OTk1OTg3Y2DQSDQSDQSEyMWM0MmE1NjQwOTc4NDdjYjFmYWU1MQ==</ns2:Nonce>
        <ns2:Created>2016-01-13T14:56:05:238Z</ns2:Created>
      </ns2:UsernameToken>
    </ns2:Security>
    <ns3:MessageID>62BEF248-2156-15D2-2056-4044AF358A02</ns3:MessageID>
    <ns3:To>http://dummy.url</ns3:To>
    <ns3:Action>http://dummy.soapAction</ns3:Action>
    <ns4:AMA_SecurityHostedUser>
      <ns4:UserID POS_Type="1" PseudoCityCode="BRUBG21HE" RequestorType="U"/>
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
}