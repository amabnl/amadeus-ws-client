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

namespace Amadeus\Client\RequestCreator;

use Amadeus\Client\RequestOptions\PnrRetrieveRequestOptions;
use Amadeus\Client\RequestOptions\RequestOptionsInterface;
use Amadeus\Client\Struct\Pnr\Retrieve;

/**
 * Base request creator - the default request creator.
 *
 * @package Amadeus\Client\RequestCreator
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Base implements RequestCreatorInterface
{
    /**
     * @param string $messageName
     * @param RequestOptionsInterface $params
     * @return mixed the created request
     */
    public function createRequest($messageName, RequestOptionsInterface $params)
    {
        $methodName = 'create' . ucfirst($messageName);

        if (method_exists($this, $methodName)) {
            if(!is_array($params)) {
                $params = [$params];
            }
            return $this->$methodName($params);
        } else {
            throw new \RuntimeException('Message ' . $methodName . ' is not implemented in ' . __CLASS__);
        }
    }

    protected function createSecurityAuthenticate()
    {

    }

    /**
     * Create request object for PNR_Retrieve message
     *
     * @param PnrRetrieveRequestOptions $params
     * @return Retrieve
     */
    protected function createRetrievePnr(PnrRetrieveRequestOptions $params)
    {
        $retrieveRequest = new Retrieve(
            Retrieve::RETR_TYPE_BY_RECLOC,
            $params->recordLocator
        );

        return $retrieveRequest;
    }

}