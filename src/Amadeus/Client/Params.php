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

use Amadeus\Client\Params\RequestCreatorParams;
use Amadeus\Client\Params\SessionHandlerParams;
use Amadeus\Client\RequestCreator\RequestCreatorInterface;
use Amadeus\Client\ResponseHandler\ResponseHandlerInterface;
use Amadeus\Client\Session;

/**
 * Params
 *
 * @package Amadeus\Client
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
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
    protected function loadFromArray(array $params) {
        if (isset($params['requestCreator']) && $params['requestCreator'] instanceof RequestCreatorInterface) {
            $this->requestCreator = $params['requestCreator'];
        }
        if (isset($params['sessionHandler']) && $params['sessionHandler'] instanceof Session\Handler\HandlerInterface) {
            $this->sessionHandler = $params['sessionHandler'];
        }
        if (isset($params['responseHandler']) && $params['responseHandler'] instanceof ResponseHandlerInterface) {
            $this->responseHandler = $params['responseHandler'];
        }

        if (isset($params['sessionHandlerParams'])) {
            if ($params['sessionHandlerParams'] instanceof SessionHandlerParams) {
                $this->sessionHandlerParams = $params['sessionHandlerParams'];
            } elseif (is_array($params['sessionHandlerParams'])) {
                $this->sessionHandlerParams = new SessionHandlerParams($params['sessionHandlerParams']);
            }
        }

        if (isset($params['requestCreatorParams'])) {
            if ($params['requestCreatorParams'] instanceof RequestCreatorParams) {
                $this->requestCreatorParams = $params['requestCreatorParams'];
            } elseif (is_array($params['requestCreatorParams'])) {
                $this->requestCreatorParams = new RequestCreatorParams($params['requestCreatorParams']);
            }
        }
    }
}
