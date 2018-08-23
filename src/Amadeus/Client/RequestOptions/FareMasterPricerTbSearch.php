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

namespace Amadeus\Client\RequestOptions;

use Amadeus\Client\RequestOptions\FareMasterPricerExSearchOptions as FareMasterPricerExSearchOptions;

/**
 * Request Options for Fare_MasterPricerTravelboardSearch
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FareMasterPricerTbSearch extends FareMasterPricerExSearchOptions
{

    /**
     * Set to true to disallow connecting flight to change airports within a city.
     *
     * @var bool
     */
    public $noAirportChange = false;

    /**
     * Specify a maximum elapsed flying time (EFT): This is a percentage of the shortest EFT returned by the journey server
     *
     * @var int
     */
    public $maxElapsedFlyingTime;
}
