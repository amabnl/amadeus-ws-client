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

namespace Amadeus\Client\Struct\Air;

use Amadeus\Client\RequestOptions\AirRebookAirSegmentOptions;
use Amadeus\Client\Struct\Air\RebookAirSegment\BestPricerRecommendation;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Air_RebookAirSegment request structure
 *
 * @package Amadeus\Client\Struct\Air
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class RebookAirSegment extends BaseWsMessage
{
    /**
     * @var RebookAirSegment\BestPricerRecommendation
     */
    public $bestPricerRecommendation;

    /**
     * @var RebookAirSegment\OriginDestinationDetails[]
     */
    public $originDestinationDetails;

    /**
     * RebookAirSegment constructor.
     *
     * @param AirRebookAirSegmentOptions $options
     */
    public function __construct(AirRebookAirSegmentOptions $options)
    {
        if (!empty($options->bestPricerOption)) {
            $this->bestPricerRecommendation = new BestPricerRecommendation($options->bestPricerOption);
        }

        foreach ($options->itinerary as $itinerary) {
            $this->originDestinationDetails[] = new RebookAirSegment\OriginDestinationDetails($itinerary);
        }
    }
}
