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

use Amadeus\Client\Params\RequestCreatorParams;
use Amadeus\Client\RequestOptions\RequestOptionsInterface;

/**
 * RequestCreatorInterface is an interface for creating requests for various messages based on certain parameters
 *
 * @package Amadeus\Client\RequestCreator
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
interface RequestCreatorInterface
{
    /**
     * Create Request Creator object and provide initialization params
     *
     * @param RequestCreatorParams $params
     */
    public function __construct(RequestCreatorParams $params);

    /**
     * Create a request for a given message with a given set of parameters for that message
     *
     * @param string $messageName
     * @param RequestOptionsInterface $params
     * @throws \Amadeus\Client\Struct\InvalidArgumentException when providing invalid parameters
     * @throws \Amadeus\Client\InvalidMessageException when trying to create a request for a message
     *                                                 that is not in your WSDL.
     * @throws MessageVersionUnsupportedException When trying to create a request for a message's version
     *                                            which isn't supported.
     * @return mixed
     */
    public function createRequest($messageName, RequestOptionsInterface $params);
}
