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
use Amadeus\Client\Session\Handler\HandlerInterface;

/**
 * Params
 *
 * @package Amadeus\Client
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
     * @var HandlerInterface
     */
    public $sessionHandler;

    /**
     * @var Params\SessionHandlerParams
     */
    public $sessionHandlerParams;

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
        if (count($params) > 0) {
            $this->requestCreator = $params['requestCreator'];
            $this->sessionHandler = $params['sessionHandler'];
            $this->sessionHandlerParams = $params['sessionHandlerParams'];
        }
    }
}
