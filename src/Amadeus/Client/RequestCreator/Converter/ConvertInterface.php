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

namespace Amadeus\Client\RequestCreator\Converter;

use Amadeus\Client\Params\RequestCreatorParams;
use Amadeus\Client\RequestOptions\RequestOptionsInterface;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * ConvertInterface
 *
 * Interface to be used when implementing conversion of a messages' request options
 * to the actual message structure.
 *
 * @package Amadeus\Client\RequestCreator\Converter
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
interface ConvertInterface
{
    /**
     * Convert request options into a WS Request structure
     *
     * @param RequestOptionsInterface $requestOptions The request options to build the message
     * @param string|int $version The message version in the WSDL
     * @return BaseWsMessage Message request structure to be sent to the SOAP Server
     */
    public function convert($requestOptions, $version);

    /**
     * Load Request Creator params
     *
     * @param RequestCreatorParams $params
     * @return void
     */
    public function setParams(RequestCreatorParams $params);
}
