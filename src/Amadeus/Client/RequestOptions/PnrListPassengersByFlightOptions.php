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
 * PNR_ListPassengersByFlight Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Evan Chuang <ycchuang1999@gmail.com>
 */
class PnrListPassengersByFlightOptions extends Base
{
    /**
     * Main search criterion: flight number and Airline Code
     *
     * @var Pnr\ListPassengersByFlight\FlightIdentification
     */
    public $flightIdentification;

    /**
     * Main search criterion: flight date
     *
     * @var Pnr\ListPassengersByFlight\DateIdentification
     */
    public $dateIdentification;

    /**
     * Drives the content of the response.
     *
     * @var Pnr\ListPassengersByFlight\OutputSelection
     */
    public $outputSelection;

    /**
     * Primary and secondary search criteria. Logical
     * AND between primary search criteria is performed implicitly.
     *
     * @var Pnr\ListPassengersByFlight\SearchCriteria[]
     */
    public $searchCriteria = [];



    /**
     * To select the way output data have to be aggregated (e.g. first by flight segment, then by class).
     * When this group is not present in the request, the output is "flat" (no specific aggregation strategy is applied).
     *
     * @var Pnr\ListPassengersByFlight\OutputAggregation[]
     */
    public $outputAggregation = [];

    /**
     * To convey Queue Details when Queue is selected as Output mode
     *
     * @var Pnr\ListPassengersByFlight\QueueDetails
     */
    public $queueDetails;
}
