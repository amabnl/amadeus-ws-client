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

use Amadeus\Client\Params\AuthParams;
use Amadeus\Client\Params\RequestCreatorParams;
use Amadeus\Client\Params\SessionHandlerParams;
use Amadeus\Client\RequestCreator\RequestCreatorInterface;
use Amadeus\Client\ResponseHandler\ResponseHandlerInterface;
use Amadeus\Client\Session;

/**
 * Params
 *
 * @package Amadeus\Client
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Params
{
    /**
     * For injecting a custom Request Creator
     *
     * @var RequestCreatorInterface
     */
    public $requestCreator;

    /**
     * For injecting a custom session handler
     *
     * @var Session\Handler\HandlerInterface
     */
    public $sessionHandler;

    /**
     * For injecting a custom response handler
     *
     * @var ResponseHandlerInterface
     */
    public $responseHandler;

    /**
     * Parameters for authenticating to the Amadeus Web Services
     *
     * @var Params\AuthParams
     */
    public $authParams;

    /**
     * Parameters required to create the Session Handler
     *
     * @var Params\SessionHandlerParams
     */
    public $sessionHandlerParams;

    /**
     * Parameters required to create the Request Creator
     *
     * @var Params\RequestCreatorParams
     */
    public $requestCreatorParams;

    /**
     * Whether to request the response XML string in the response by default or not.
     *
     * If true, the Amadeus\Client\Result object will
     * contain the response XML message in the 'responseXml' property by default.
     *
     * This can be overridden for specific messages by adding a 'returnXml' key with a boolean in
     * the second parameter of a message call.
     *
     * @var bool
     */
    public $returnXml = true;


    /**
     * @param array $params
     */
    public function __construct($params = [])
    {
        $this->loadFromArray($params);
    }

    /**
     * Load parameters from an associative array
     *
     * @param array $params
     * @return void
     */
    protected function loadFromArray(array $params)
    {
        if (isset($params['returnXml']) && is_bool($params['returnXml'])) {
            $this->returnXml = $params['returnXml'];
        }

        $this->loadRequestCreator($params);
        $this->loadSessionHandler($params);
        $this->loadResponseHandler($params);

        $this->loadAuthParams($params);

        $this->loadSessionHandlerParams($params);
        $this->loadRequestCreatorParams($params);
    }

    /**
     * Load Request Creator
     *
     * @param array $params
     * @return void
     */
    protected function loadRequestCreator($params)
    {
        if (isset($params['requestCreator']) && $params['requestCreator'] instanceof RequestCreatorInterface) {
            $this->requestCreator = $params['requestCreator'];
        }
    }


    /**
     * Load Session Handler
     *
     * @param array $params
     * @return void
     */
    protected function loadSessionHandler($params)
    {
        if (isset($params['sessionHandler']) && $params['sessionHandler'] instanceof Session\Handler\HandlerInterface) {
            $this->sessionHandler = $params['sessionHandler'];
        }
    }

    /**
     * Load Response Handler
     *
     * @param array $params
     * @return void
     */
    protected function loadResponseHandler($params)
    {
        if (isset($params['responseHandler']) && $params['responseHandler'] instanceof ResponseHandlerInterface) {
            $this->responseHandler = $params['responseHandler'];
        }
    }

    /**
     * Load Authentication Parameters
     *
     * @param array $params
     * @return void
     */
    protected function loadAuthParams($params)
    {
        if (isset($params['authParams'])) {
            if ($params['authParams'] instanceof AuthParams) {
                $this->authParams = $params['authParams'];
            } elseif (is_array($params['authParams'])) {
                $this->authParams = new AuthParams($params['authParams']);
            }
        }
    }

    /**
     * Load Session Handler Parameters
     *
     * @param array $params
     * @return void
     */
    protected function loadSessionHandlerParams($params)
    {
        if (isset($params['sessionHandlerParams'])) {
            if ($params['sessionHandlerParams'] instanceof SessionHandlerParams) {
                $this->sessionHandlerParams = $params['sessionHandlerParams'];
            } elseif (is_array($params['sessionHandlerParams'])) {
                $this->sessionHandlerParams = new SessionHandlerParams($params['sessionHandlerParams']);
            }
        }
    }

    /**
     * Load Request Creator Parameters
     *
     * @param array $params
     * @return void
     */
    protected function loadRequestCreatorParams($params)
    {
        if (isset($params['requestCreatorParams'])) {
            if ($params['requestCreatorParams'] instanceof RequestCreatorParams) {
                $this->requestCreatorParams = $params['requestCreatorParams'];
            } elseif (is_array($params['requestCreatorParams'])) {
                $this->requestCreatorParams = new RequestCreatorParams($params['requestCreatorParams']);
            }
        }
    }
}
