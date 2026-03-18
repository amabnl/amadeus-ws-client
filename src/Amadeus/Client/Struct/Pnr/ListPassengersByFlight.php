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

namespace Amadeus\Client\Struct\Pnr;

use Amadeus\Client\RequestOptions\PnrListPassengersByFlightOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\FlightDateQuery;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\OutputSelectionOption;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\SearchCriteria;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\OutputAggregationOption;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\QueueDetails;

/**
 * PNR_ListPassengersByFlight Request stucture
 *
 * @package Amadeus\Client\Struct\Pnr
 * @author Evan Chuang <ycchuang1999@gmail.com>
 */
class ListPassengersByFlight extends BaseWsMessage
{
    /**
     * @var FlightDateQuery
     */
    public $flightDateQuery;

    /**
     * @var OutputSelectionOption
     */
    public $outputSelectionOption;

    /**
     * @var SearchCriteria[]
     */
    public $searchCriteria = [];

    /**
     * @var OutputAggregationOption[]
     */
    public $outputAggregationOption = [];

    /**
     * @var QueueDetails
     */
    public $queueDetails;

    /**
     * ListPassengersByFlight constructor.
     *
     * @param PnrListPassengersByFlightOptions $options
     */
    public function __construct(PnrListPassengersByFlightOptions $options)
    {
        $this->flightDateQuery = new FlightDateQuery($options->flightIdentification, $options->dateIdentification);

        if ($options->outputSelection) {
            $this->outputSelectionOption = new OutputSelectionOption($options->outputSelection);
        }

        foreach ($options->searchCriteria as $criterion) {
            $this->searchCriteria[] = new SearchCriteria($criterion);
        }

        foreach ($options->outputAggregation as $agg) {
            $this->outputAggregationOption[] = new OutputAggregationOption($agg);
        }

        if ($options->queueDetails) {
            $this->queueDetails = new QueueDetails($options->queueDetails);
        }
    }
}
