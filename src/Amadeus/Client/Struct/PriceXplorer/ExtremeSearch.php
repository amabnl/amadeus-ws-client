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

namespace Amadeus\Client\Struct\PriceXplorer;

use Amadeus\Client\RequestOptions\PriceXplorerExtremeSearchOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * ExtremeSearch
 *
 * @package Amadeus\Client\Struct\PriceXplorer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ExtremeSearch extends BaseWsMessage
{
    /**
     * Itinerary information group
     *
     * @var ItineraryGrp[]
     */
    public $itineraryGrp = [];

    /**
     * Budget info
     *
     * @var Budget
     */
    public $budget;

    /**
     * Departure dates ranges
     *
     * @var TravelDates
     */
    public $travelDates;

    /**
     * Stay duration and flexibility
     *
     * @var StayDuration
     */
    public $stayDuration;

    /**
     * Attribute Information
     *
     * @var AttributeInfo[]
     */
    public $attributeInfo = [];

    /**
     * Option description : Price result distribution, ...
     *
     * @var SelectionDetailsGroup[]
     */
    public $selectionDetailsGroup = [];

    /**
     * List of departure days
     *
     * @var DepartureDays[]
     */
    public $departureDays = [];

    /**
     * Airline information
     *
     * @var AirlineInfo[]
     */
    public $airlineInfo = [];

    /**
     * List of Office Id Details
     *
     * @var OfficeIdInfo[]
     */
    public $officeIdInfo = [];

    /**
     * Construct PriceXplorer_ExtremeSearch Request message
     *
     * @param PriceXplorerExtremeSearchOptions $params
     */
    public function __construct(PriceXplorerExtremeSearchOptions $params)
    {
        $this->itineraryGrp[] = new ItineraryGrp($params->origin);

        $this->loadDepartureDateLimits($params);

        if ($params->searchOffice !== null) {
            $this->officeIdInfo[] = new OfficeIdInfo($params->searchOffice);
        }

        $this->loadBudget($params->maxBudget, $params->minBudget, $params->currency);

        $this->loadDestinations($params);

        $this->loadDepartureDaysOutIn($params);

        $this->loadStayDuration($params->stayDurationDays, $params->stayDurationFlexibilityDays);

        $this->loadCheapestQualifiers($params->returnCheapestNonStop, $params->returnCheapestOverall);

        $this->loadResultAggregation($params->resultAggregationOption);
    }

    /**
     * @param int|null $maxBudget
     * @param int|null $minBudget
     * @param string|null $currency
     */
    protected function loadBudget($maxBudget, $minBudget, $currency)
    {
        if (($maxBudget !== null || $minBudget !== null) && $currency !== null) {
            $this->budget = new Budget(
                $maxBudget,
                $minBudget,
                $currency
            );
        }
    }

    /**
     * @param int|null $stayDuration
     * @param int|null $flexibility
     */
    protected function loadStayDuration($stayDuration, $flexibility)
    {
        if ($stayDuration !== null) {
            $this->stayDuration = new StayDuration($stayDuration);

            if ($flexibility !== null) {
                $this->stayDuration->flexibilityInfo = new FlexibilityInfo($flexibility);
            }
        }
    }

    /**
     * @param bool $cheapestNonStop
     * @param bool $cheapestOverall
     */
    protected function loadCheapestQualifiers($cheapestNonStop, $cheapestOverall)
    {
        if ($cheapestNonStop || $cheapestOverall) {
            $this->selectionDetailsGroup[] = new SelectionDetailsGroup($cheapestNonStop, $cheapestOverall);
        }
    }

    /**
     * @param string $resultAggregationOption
     */
    protected function loadResultAggregation($resultAggregationOption)
    {
        if ($resultAggregationOption !== null) {
            $groupTypes = $this->makeAggregationGroupTypes($resultAggregationOption);

            $this->attributeInfo[] = new AttributeInfo(
                AttributeInfo::FUNC_GROUPING,
                $groupTypes
            );
        }
    }

    /**
     * @param string $groupTypeString
     * @return array
     */
    protected function makeAggregationGroupTypes($groupTypeString)
    {
        $result = [];

        switch ($groupTypeString) {
            case PriceXplorerExtremeSearchOptions::AGGR_DEST:
                $result[] = AttributeDetails::TYPE_DESTINATION;
                break;
            case PriceXplorerExtremeSearchOptions::AGGR_COUNTRY:
                $result[] = AttributeDetails::TYPE_COUNTRY;
                break;
            case PriceXplorerExtremeSearchOptions::AGGR_DEST_WEEK:
                $result[] = AttributeDetails::TYPE_DESTINATION;
                $result[] = AttributeDetails::TYPE_WEEK;
                break;
            case PriceXplorerExtremeSearchOptions::AGGR_DEST_WEEK_DEPART:
                $result[] = AttributeDetails::TYPE_DESTINATION;
                $result[] = AttributeDetails::TYPE_WEEK;
                $result[] = AttributeDetails::TYPE_DEPARTURE_DAY;
                break;
            case PriceXplorerExtremeSearchOptions::AGGR_DEST_WEEK_DEPART_STAY:
                $result[] = AttributeDetails::TYPE_DESTINATION;
                $result[] = AttributeDetails::TYPE_WEEK;
                $result[] = AttributeDetails::TYPE_DEPARTURE_DAY;
                $result[] = AttributeDetails::TYPE_STAY_DURATION;
                break;
        }

        return $result;
    }

    /**
     * @param PriceXplorerExtremeSearchOptions $params
     *
     */
    protected function loadDepartureDateLimits(PriceXplorerExtremeSearchOptions $params)
    {
        if ($params->earliestDepartureDate instanceof \DateTime || $params->latestDepartureDate instanceof \DateTime) {
            $this->travelDates = new TravelDates($params->earliestDepartureDate, $params->latestDepartureDate);
        }
    }

    /**
     * @param PriceXplorerExtremeSearchOptions $params
     *
     */
    protected function loadDestinations(PriceXplorerExtremeSearchOptions $params)
    {
        foreach ($params->destinations as $destination) {
            $this->itineraryGrp[] = new ItineraryGrp(null, $destination);
        }

        foreach ($params->destinationCountries as $destinationCountry) {
            $tmpGrp = new ItineraryGrp();

            $tmpGrp->locationInfo = new LocationInfo(LocationInfo::LOC_COUNTRY);

            $tmpGrp->locationInfo->locationDescription = new LocationIdentificationType();
            $tmpGrp->locationInfo->locationDescription->qualifier = LocationIdentificationType::QUAL_DESTINATION;
            $tmpGrp->locationInfo->locationDescription->code = $destinationCountry;

            $this->itineraryGrp[] = $tmpGrp;
        }
    }

    /**
     * @param PriceXplorerExtremeSearchOptions $params
     *
     */
    protected function loadDepartureDaysOutIn(PriceXplorerExtremeSearchOptions $params)
    {
        if (!empty($params->departureDaysInbound)) {
            $this->departureDays[] = new DepartureDays(
                $params->departureDaysInbound,
                SelectionDetails::OPT_INBOUND_DEP_DAYS
            );
        }
        if (!empty($params->departureDaysOutbound)) {
            $this->departureDays[] = new DepartureDays(
                $params->departureDaysOutbound,
                SelectionDetails::OPT_OUTBOUND_DEP_DAYS
            );
        }
    }
}
