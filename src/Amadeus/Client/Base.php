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

use Amadeus\Client\RequestCreator\RequestCreatorInterface;
use Amadeus\Client\ResponseHandler\ResponseHandlerInterface;
use Amadeus\Client\Session\Handler\HandlerFactory;
use Amadeus\Client\Session\Handler\HandlerInterface;
use Amadeus\Client\RequestCreator\Factory as RequestCreatorFactory;
use Amadeus\Client\ResponseHandler\Base as ResponseHandlerBase;

/**
 * Base Client
 *
 * Responsible for loading constructor params etc.
 *
 * @package Amadeus\Client
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Base
{
    /**
     * Session Handler will be sending all the messages and handling all session-related things.
     *
     * @var HandlerInterface
     */
    protected $sessionHandler;

    /**
     * Request Creator is will create the correct message structure to send to the SOAP server.
     *
     * @var RequestCreatorInterface
     */
    protected $requestCreator;

    /**
     * Response Handler will check the received response for errors.
     *
     * @var ResponseHandlerInterface
     */
    protected $responseHandler;

    /**
     * Authentication parameters
     *
     * @var Params\AuthParams
     */
    protected $authParams;

    /**
     * Whether to return the response of a message as XML as well as PHP object
     *
     * @var bool
     */
    protected $returnResultXml;


    /**
     * Loads Client parameters
     *
     * @param Params $params
     * @param string $receivedFromIdentifier
     * @param string $version
     */
    protected function loadClientParams(Params $params, $receivedFromIdentifier, $version)
    {
        if ($params->authParams instanceof Params\AuthParams) {
            $this->authParams = $params->authParams;
            if (isset($params->sessionHandlerParams) &&
                $params->sessionHandlerParams instanceof Params\SessionHandlerParams
            ) {
                $params->sessionHandlerParams->authParams = $this->authParams;
            }
        } elseif (isset($params->sessionHandlerParams) &&
            $params->sessionHandlerParams->authParams instanceof Params\AuthParams
        ) {
            //@deprecated - Provide backwards compatibility with old authparams structure.
            //Github Issue 40 - retrieve AuthParams from sessionhandlerparams if not generally available
            $this->authParams = $params->sessionHandlerParams->authParams;
        }

        $this->sessionHandler = $this->loadSessionHandler(
            $params->sessionHandler,
            $params->sessionHandlerParams
        );

        $this->requestCreator = $this->loadRequestCreator(
            $params->requestCreator,
            $params->requestCreatorParams,
            $receivedFromIdentifier."-".$version,
            $this->sessionHandler->getOriginatorOffice(),
            $this->sessionHandler->getMessagesAndVersions()
        );

        $this->responseHandler = $this->loadResponseHandler(
            $params->responseHandler
        );

        $this->returnResultXml = $params->returnXml;
    }

    /**
     * Load the session handler
     *
     * Either load the provided session handler or create one depending on incoming parameters.
     *
     * @param HandlerInterface|null $sessionHandler
     * @param Params\SessionHandlerParams|null $params
     * @return HandlerInterface
     */
    protected function loadSessionHandler($sessionHandler, $params)
    {
        if ($sessionHandler instanceof HandlerInterface) {
            $newSessionHandler = $sessionHandler;
        } else {
            $newSessionHandler = HandlerFactory::createHandler($params);
        }

        return $newSessionHandler;
    }

    /**
     * Load a request creator
     *
     * A request creator is responsible for generating the correct request to send.
     *
     * @param RequestCreatorInterface|null $requestCreator
     * @param Params\RequestCreatorParams $params
     * @param string $libIdentifier Library identifier & version string (for Received From)
     * @param string $originatorOffice The Office we are signed in with.
     * @param array $mesVer Messages & Versions array of active messages in the WSDL
     * @return RequestCreatorInterface
     * @throws \RuntimeException
     */
    protected function loadRequestCreator($requestCreator, $params, $libIdentifier, $originatorOffice, $mesVer)
    {
        if ($requestCreator instanceof RequestCreatorInterface) {
            $newRequestCreator = $requestCreator;
        } else {
            $params->originatorOfficeId = $originatorOffice;
            $params->messagesAndVersions = $mesVer;

            $newRequestCreator = RequestCreatorFactory::createRequestCreator(
                $params,
                $libIdentifier
            );
        }

        return $newRequestCreator;
    }

    /**
     * Load a response handler
     *
     * @param ResponseHandlerInterface|null $responseHandler
     * @return ResponseHandlerInterface
     * @throws \RuntimeException
     */
    protected function loadResponseHandler($responseHandler)
    {
        if ($responseHandler instanceof ResponseHandlerInterface) {
            $newResponseHandler = $responseHandler;
        } else {
            $newResponseHandler = new ResponseHandlerBase();
        }

        return $newResponseHandler;
    }
}
