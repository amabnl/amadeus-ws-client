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

namespace Amadeus;

use Amadeus\Client\Exception;
use Amadeus\Client\Params;
use Amadeus\Client\RequestCreator\RequestCreatorInterface;
use Amadeus\Client\RequestOptions\PnrRetrieveRequestOptions;
use Amadeus\Client\Session\Handler\HandlerFactory;
use Amadeus\Client\Session\Handler\HandlerInterface;

/**
 * Amadeus Web Service Client.
 *
 * TODO:
 * - have a solution for session pooling for stateful sessions
 * - support older versions of SoapHeader (1, 2)
 *
 * @package Amadeus
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Client
{
    /**
     * Amadeus SOAP header version 1
     */
    const HEADER_V1 = "1";
    /**
     * Amadeus SOAP header version 2
     */
    const HEADER_V2 = "2";
    /**
     * Amadeus SOAP header version 4
     */
    const HEADER_V4 = "4";

    /**
     * Version string
     *
     * @var string
     */
    const version = "0.0.1dev";
    /**
     * An identifier string for the library (to be used in Received From entries)
     *
     * @var string
     */
    const receivedFromIdentifier = "amabnl/amadeus-ws-client";

    /**
     * @var HandlerInterface
     */
    protected $sessionHandler;

    /**
     * @var RequestCreatorInterface
     */
    protected $requestCreator;

    /**
     * @param Params $params
     */
    public function __construct($params)
    {
        $this->requestCreator = $this->loadRequestCreator($params->requestCreator);
        $this->sessionHandler = $this->loadSessionHandler(
            $params->sessionHandler,
            $params->sessionHandlerParams,
            self::receivedFromIdentifier . "-" .self::version
        );
    }

    /**
     * Retrieve an Amadeus PNR by record locator
     *
     * By default, the result will be the PNR_Reply XML as string.
     * That way you can easily parse the PNR's contents with XPath.
     *
     * Set $responseAsString FALSE to get the response as a PHP object.
     *
     * @param string $recordLocator Amadeus Record Locator for PNR
     * @param bool $responseAsString (OPTIONAL) set to 'false' to get PNR_Reply as a PHP object.
     * @return string|\stdClass|null
     * @throws Exception
     */
    public function retrievePnr($recordLocator, $responseAsString = true)
    {
        return $this->sessionHandler->sendMessage(
            'retrievePnr',
            $this->requestCreator->createRequest(
                'retrievePnr',
                new PnrRetrieveRequestOptions($recordLocator)
            ),
            ['asString' => $responseAsString]
        );
    }

    /**
     * @param HandlerInterface|null $sessionHandler
     * @param Params\SessionHandlerParams $params
     * @param string $libIdentifier Library identifier & version string (for Received From)
     * @return HandlerInterface
     */
    protected function loadSessionHandler($sessionHandler, $params, $libIdentifier)
    {
        $newSessionHandler = null;

        if ($sessionHandler instanceof HandlerInterface) {
            $newSessionHandler = $sessionHandler;
        } else {
            $newSessionHandler = HandlerFactory::createHandler($params, $libIdentifier);
        }

        return $newSessionHandler;
    }

    /**
     * @param RequestCreatorInterface|null $requestCreator
     * @return RequestCreatorInterface
     * @throws \RuntimeException
     */
    protected function loadRequestCreator($requestCreator)
    {
        $newRequestCreator = null;

        if ($requestCreator instanceof RequestCreatorInterface) {
            $newRequestCreator = $requestCreator;
        } else {
            $newRequestCreator = new Client\RequestCreator\Base();
        }

        return $newRequestCreator;
    }
}
