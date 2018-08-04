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

/**
 * Request Creator Factory
 *
 * @package Amadeus\Client\RequestCreator
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Factory
{
    /**
     * Create a Request Creator
     *
     * @param RequestCreatorParams $params
     * @param string $libIdentifier
     * @return RequestCreatorInterface
     * @throws \InvalidArgumentException when the parameters to create the handler do not make sense.
     */
    public static function createRequestCreator($params, $libIdentifier)
    {
        $params->receivedFrom = self::makeReceivedFrom(
            $params->receivedFrom,
            $libIdentifier
        );

        $theRequestCreator = new Base($params);

        return $theRequestCreator;
    }

    /**
     * @param string $original
     * @param string $libIdentifier
     * @return string
     */
    protected static function makeReceivedFrom($original, $libIdentifier)
    {
        return $original." ".$libIdentifier;
    }
}
