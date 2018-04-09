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

/**
 * Pnr_CreatePnr Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PnrCreatePnrOptions extends PnrAddMultiElementsBase
{
    /**
     * A group of travellers
     *
     * @var Pnr\TravellerGroup
     */
    public $travellerGroup;

    /**
     * Non-group travellers (max 9)
     *
     * @var Pnr\Traveller[]
     */
    public $travellers = [];

    /**
     * (originDestinationDetails)
     *
     * WARNING: IMPLIES NO CONNECTED FLIGHTS, USE $this->itinerary instead!
     *
     * @deprecated use $this->itinerary instead
     * @var Pnr\Segment[]
     */
    public $tripSegments = [];

    /**
     * Itineraries in the PNR.
     *
     * Used for grouping segments together
     *
     * @var Pnr\Itinerary[]
     */
    public $itineraries = [];

    /**
     * (dataElementsMaster\dataElementsIndiv)
     *
     * @var Pnr\Element[]
     */
    public $elements = [];
}
