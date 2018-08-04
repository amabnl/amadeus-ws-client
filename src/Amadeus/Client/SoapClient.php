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

namespace Amadeus\Client;

use Psr\Log;

/**
 * Amadeus Web Services SoapClient class
 *
 * This SoapClient will perform an XSLT transformation on the request to be sent to the amadeus server:
 * it will remove any empty XML tags except for some specific tags that may be required to be present and empty.
 *
 * If you do not remove empty XML tags, you will get SoapFaults back from the server when unexpected empty elements
 * are present in your request.
 *
 * @package Amadeus\Client
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SoapClient extends \SoapClient implements Log\LoggerAwareInterface
{
    use Log\LoggerAwareTrait;

    const REMOVE_EMPTY_XSLT_LOCATION = 'SoapClient/removeempty.xslt';

    /**
     * Construct a new SoapClient
     *
     * @param string $wsdl Location of WSDL file
     * @param array $options initialisation options
     * @param Log\LoggerInterface|null $logger Error logging object
     */
    public function __construct($wsdl, $options, Log\LoggerInterface $logger = null)
    {
        if (!($logger instanceof Log\LoggerInterface)) {
            $logger = new Log\NullLogger();
        }
        $this->setLogger($logger);

        parent::__construct($wsdl, $options);
    }

    /**
     * __doRequest override of SoapClient
     *
     * @param string $request The XML SOAP request.
     * @param string $location The URL to request.
     * @param string $action The SOAP action.
     * @param int $version The SOAP version.
     * @param int|null $oneWay
     * @uses parent::__doRequest
     * @return string The XML SOAP response.
     * @throws Exception When PHP XSL extension is not enabled or WSDL file isn't readable.
     */
    public function __doRequest($request, $location, $action, $version, $oneWay = null)
    {
        if (!extension_loaded('xsl')) {
            throw new Exception('PHP XSL extension is not enabled.');
        }

        $newRequest = $this->transformIncomingRequest($request);

        return parent::__doRequest($newRequest, $location, $action, $version, $oneWay);
    }

    /**
     * @param string $request
     * @return string
     * @throws Exception when XSLT file isn't readable
     */
    protected function transformIncomingRequest($request)
    {
        $newRequest = null;

        $xsltFile = dirname(__FILE__).DIRECTORY_SEPARATOR.self::REMOVE_EMPTY_XSLT_LOCATION;
        if (!is_readable($xsltFile)) {
            throw new Exception('XSLT file "'.$xsltFile.'" is not readable!');
        }

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadXML($request);
        $xslt = new \DOMDocument('1.0', 'UTF-8');

        $xslt->load($xsltFile);

        $processor = new \XSLTProcessor();
        $processor->importStylesheet($xslt);
        $transform = $processor->transformToXml($dom);
        if ($transform === false) {
            //On transform error: usually when modifying the XSLT transformation incorrectly...
            $this->logger->log(
                Log\LogLevel::ERROR,
                __METHOD__."__doRequest(): XSLTProcessor::transformToXml "
                . "returned FALSE: could not perform transformation!!"
            );
            $newRequest = $request;
        } else {
            $newDom = new \DOMDocument('1.0', 'UTF-8');
            $newDom->preserveWhiteSpace = false;
            $newDom->loadXML($transform);

            $newRequest = $newDom->saveXML();
        }

        unset($processor, $xslt, $dom, $transform);

        return $newRequest;
    }
}
