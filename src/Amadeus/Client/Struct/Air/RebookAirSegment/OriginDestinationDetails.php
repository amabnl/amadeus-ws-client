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

namespace Amadeus\Client\Struct\Air\RebookAirSegment;

use Amadeus\Client\RequestOptions\Air\RebookAirSegment\Itinerary;
use Amadeus\Client\Struct\Pnr\AddMultiElements\OriginDestination;

/**
 * OriginDestinationDetails
 *
 * @package Amadeus\Client\Struct\Air\RebookAirSegment
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class OriginDestinationDetails
{
    /**
     * @var OriginDestination
     */
    public $originDestination;

    /**
     * @var ItineraryInfo[]
     */
    public $itineraryInfo = [];

    /**
     * OriginDestinationDetails constructor.
     *
     * @param Itinerary $itinerary
     */
    public function __construct(Itinerary $itinerary)
    {
        $this->originDestination = new OriginDestination($itinerary->from, $itinerary->to);

        foreach ($itinerary->segments as $segment) {
            $this->itineraryInfo[] = new ItineraryInfo($segment);
        }
    }
}
