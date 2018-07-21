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

/**
 * LoadParamsFromArray
 *
 * Provides the ability to load parameters in the constructor through an associative array
 *
 * The keys in the associative array should be property names, and if that matches, the values will be set
 * to those properties.
 *
 * @package Amadeus\Client
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class LoadParamsFromArray
{
    /**
     * Construct Request Options object with initialization array
     *
     * @param array $params Initialization parameters
     */
    public function __construct($params = [])
    {
        foreach ($params as $propName => $propValue) {
            if (property_exists($this, $propName)) {
                $this->$propName = $propValue;
                //TODO support for objects that must be instantiated???
            }
        }
    }
}
