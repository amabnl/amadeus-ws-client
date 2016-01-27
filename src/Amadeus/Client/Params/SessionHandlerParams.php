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

namespace Amadeus\Client\Params;

use Amadeus\Client;
use Psr\Log\LoggerInterface;

/**
 * SessionHandlerParams contains all parameters for setting up the session handler
 *
 * @package Amadeus\Client\Params
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class SessionHandlerParams
{

    /**
     * Full file & path to the WSDL file to be used
     *
     * @var string
     */
    public $wsdl;

    /**
     * Which Soap Header version to be used
     * @var string
     */
    public $soapHeaderVersion = Client::HEADER_V4;

    /**
     * @var AuthParams
     */
    public $authParams;

    /**
     * The default setting for sending messages (if not specified)
     *
     * Set to FALSE to enable stateless messages - only applies to SOAP header v4 and higher!
     *
     * @var bool
     */
    public $stateful = true;


    /**
     * @var LoggerInterface
     */
    public $logger;

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
            if (isset($params['soapHeaderVersion'])) {
                $this->soapHeaderVersion = $params['soapHeaderVersion'];
            }
            $this->wsdl = (isset($params['wsdl'])) ? $params['wsdl'] : null;
            $this->stateful = (isset($params['stateful'])) ? $params['stateful'] : true;
            $this->logger = ($params['logger'] instanceof LoggerInterface) ? $params['logger'] : null;

            if (isset($params['authParams'])) {
                if ($params['authParams'] instanceof AuthParams) {
                    $this->authParams = $params['authParams'];
                } elseif (is_array($params['authParams'])) {
                    $this->authParams = new AuthParams($params['authParams']);
                }
            }
        }
    }
}
