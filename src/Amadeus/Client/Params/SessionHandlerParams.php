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
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SessionHandlerParams
{

    /**
     * Full file & path to the WSDL file to be used
     *
     * @var string[]
     */
    public $wsdl = [];

    /**
     * Which Soap Header version to be used
     *
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
     * Override the default \SoapClient options
     *
     * used when constructing \SoapClient
     *
     * See Amadeus\Client\Session\Handler\Base::$soapClientOptions for defaults
     *
     * @var array
     */
    public $soapClientOptions = [];

    /**
     * Overridden soap client
     *
     * @var \SoapClient
     */
    public $overrideSoapClient;

    /**
     * Override SoapClient WSDL name
     *
     * @var string
     */
    public $overrideSoapClientWsdlName;

    /**
     * Enable the TransactionFlowLink SOAP Header?
     *
     * @var bool
     */
    public $enableTransactionFlowLink = false;

    /**
     * Consumer ID for Transaction Flow Link
     *
     * @var string|null
     */
    public $consumerId = null;

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
        if (count($params) > 0) {
            if (isset($params['soapHeaderVersion'])) {
                $this->soapHeaderVersion = $params['soapHeaderVersion'];
            }

            $this->loadWsdl($params);
            $this->loadStateful($params);
            $this->loadLogger($params);

            $this->loadAuthParams($params);

            $this->loadOverrideSoapClient($params);
            $this->loadSoapClientOptions($params);

            $this->loadTransactionFlowLink($params);
        }
    }

    /**
     * Load WSDL from config
     *
     * Either a single WSDL location as string or a list of WSDL locations as array.
     *
     * @param array $params
     * @return void
     */
    protected function loadWsdl($params)
    {
        if (isset($params['wsdl'])) {
            if (is_string($params['wsdl'])) {
                $this->wsdl = [
                    $params['wsdl']
                ];
            } elseif (is_array($params['wsdl'])) {
                $this->wsdl = $params['wsdl'];
            }
        }
    }

    /**
     * Load Stateful param from config
     *
     * @param array $params
     * @return void
     */
    protected function loadStateful($params)
    {
        $this->stateful = (isset($params['stateful'])) ? $params['stateful'] : true;
    }


    /**
     * Load Logger from config
     *
     * @param array $params
     * @return void
     */
    protected function loadLogger($params)
    {
        if ((isset($params['logger']) && $params['logger'] instanceof LoggerInterface)) {
            $this->logger = $params['logger'];
        }
    }

    /**
     * Load Authentication parameters from config
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
     * Load Override SoapClient parameter from config
     *
     * @param array $params
     * @return void
     */
    protected function loadOverrideSoapClient($params)
    {
        if (isset($params['overrideSoapClient']) && $params['overrideSoapClient'] instanceof \SoapClient) {
            $this->overrideSoapClient = $params['overrideSoapClient'];
        }
        if (isset($params['overrideSoapClientWsdlName'])) {
            $this->overrideSoapClientWsdlName = $params['overrideSoapClientWsdlName'];
        }
    }

    /**
     * Load SoapClient Options from config
     *
     * @param array $params
     * @return void
     */
    protected function loadSoapClientOptions($params)
    {
        if (isset($params['soapClientOptions']) && is_array($params['soapClientOptions'])) {
            $this->soapClientOptions = $params['soapClientOptions'];
        }
    }

    /**
     * Load TransactionFlowLink options from config
     *
     * @param array $params
     * @return void
     */
    protected function loadTransactionFlowLink($params)
    {
        if (isset($params['enableTransactionFlowLink']) && $params['enableTransactionFlowLink'] === true) {
            $this->enableTransactionFlowLink = true;
            $this->consumerId = (isset($params['consumerId'])) ? $params['consumerId'] : null;
        }
    }
}
