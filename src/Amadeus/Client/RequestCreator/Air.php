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

use Amadeus\Client\RequestOptions\AirFlightInfoOptions;
use Amadeus\Client\RequestOptions\AirMultiAvailabilityOptions;
use Amadeus\Client\RequestOptions\AirRetrieveSeatMapOptions;
use Amadeus\Client\RequestOptions\AirSellFromRecommendationOptions;
use Amadeus\Client\Struct;

/**
 * Air Request Creator
 *
 * Responsible for creating all "Air_" messages
 *
 * methods for creation must have the correct name
 * 'create'<message name without underscores>
 *
 * @package Amadeus\Client\RequestCreator
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Air
{
    /**
     * Air_MultiAvailability
     *
     * @param AirMultiAvailabilityOptions $params
     * @return Struct\Air\MultiAvailability
     */
    public function createAirMultiAvailability(AirMultiAvailabilityOptions $params)
    {
        return new Struct\Air\MultiAvailability($params);
    }

    /**
     * Air_SellFromRecommendation
     *
     * @param AirSellFromRecommendationOptions $params
     * @return Struct\Air\SellFromRecommendation
     */
    public function createAirSellFromRecommendation(AirSellFromRecommendationOptions $params)
    {
        return new Struct\Air\SellFromRecommendation($params);
    }

    /**
     * Air_FlightInfo
     *
     * @param AirFlightInfoOptions $params
     * @return Struct\Air\FlightInfo
     */
    public function createAirFlightInfo(AirFlightInfoOptions $params)
    {
        return new Struct\Air\FlightInfo($params);
    }

    /**
     * Air_RetrieveSeatMap
     *
     * @param AirRetrieveSeatMapOptions $params
     * @return Struct\Air\RetrieveSeatMap
     */
    public function createAirRetrieveSeatMap(AirRetrieveSeatMapOptions $params)
    {
        return new Struct\Air\RetrieveSeatMap($params);
    }
}
