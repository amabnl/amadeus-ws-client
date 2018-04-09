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

use Amadeus\Client\RequestOptions\Air\SellFromRecommendation\Segment as SegmentOptions;

/**
 * ItineraryInfo
 *
 * @package Amadeus\Client\Struct\Air\RebookAirSegment
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ItineraryInfo
{
    /**
     * @var FlightDetails
     */
    public $flightDetails;

    /**
     * @var RelatedFlightInfo
     */
    public $relatedFlightInfo;

    /**
     * ItineraryInfo constructor.
     *
     * @param SegmentOptions $seg
     */
    public function __construct(SegmentOptions $seg)
    {
        $this->flightDetails = new FlightDetails(
            $seg->departureDate,
            $seg->from,
            $seg->to,
            $seg->companyCode,
            $seg->flightNumber,
            $seg->bookingClass,
            $seg->arrivalDate,
            $seg->arrivalTime,
            $seg->dateVariation
        );

        $this->relatedFlightInfo = new RelatedFlightInfo(
            $seg->nrOfPassengers,
            $seg->statusCode
        );
    }
}
