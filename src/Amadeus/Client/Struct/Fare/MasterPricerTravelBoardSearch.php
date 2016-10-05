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

namespace Amadeus\Client\Struct\Fare;

use Amadeus\Client\RequestOptions\Fare\MPItinerary;
use Amadeus\Client\RequestOptions\Fare\MPPassenger;
use Amadeus\Client\RequestOptions\FareMasterPricerCalendarOptions;
use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Fare\MasterPricer;

/**
 * Fare_MasterPricerTravelBoardSearch message structure
 *
 * @package Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class MasterPricerTravelBoardSearch extends BaseWsMessage
{
    /**
     * Number of seats, recommendations.
     *
     * @var MasterPricer\NumberOfUnit
     */
    public $numberOfUnit;
    /**
     * @var mixed
     */
    public $globalOptions;
    /**
     * Traveler Details
     *
     * @var MasterPricer\PaxReference[]
     */
    public $paxReference = [];
    /**
     * @var mixed
     */
    public $customerRef;
    /**
     * @var mixed
     */
    public $formOfPaymentByPassenger;
    /**
     * @var mixed
     */
    public $solutionFamily;
    /**
     * @var mixed[]
     */
    public $passengerInfoGrp = [];
    /**
     * @var MasterPricer\FareFamilies[]
     */
    public $fareFamilies = [];
    /**
     * @var MasterPricer\FareOptions
     */
    public $fareOptions;
    /**
     * @var MasterPricer\PriceToBeat
     */
    public $priceToBeat;
    /**
     * @var mixed
     */
    public $taxInfo;
    /**
     * @var MasterPricer\TravelFlightInfo
     */
    public $travelFlightInfo;
    /**
     * @var array
     */
    public $valueSearch = [];
    /**
     * @var array
     */
    public $buckets = [];
    /**
     * Itinerary
     *
     * @var MasterPricer\Itinerary[]
     */
    public $itinerary = [];
    /**
     * @var mixed
     */
    public $ticketChangeInfo;
    /**
     * @var mixed
     */
    public $combinationFareFamilies;
    /**
     * @var mixed
     */
    public $feeOption;
    /**
     * @var mixed
     */
    public $officeIdDetails;

    /**
     * MasterPricerTravelBoardSearch constructor.
     *
     * @param FareMasterPricerTbSearch|FareMasterPricerCalendarOptions|null $options
     */
    public function __construct($options = null)
    {
        if ($options instanceof FareMasterPricerTbSearch || $options instanceof FareMasterPricerCalendarOptions) {
            $this->loadOptions($options);
        }
    }

    /**
     * @param FareMasterPricerTbSearch|FareMasterPricerCalendarOptions $options
     */
    protected function loadOptions($options)
    {
        $this->loadNrOfPaxAndResults($options);

        if ($options->doTicketabilityPreCheck === true ||
            $this->checkAnyNotEmpty($options->corporateCodesUnifares, $options->flightOptions)
        ) {
            $this->fareOptions = new MasterPricer\FareOptions(
                $options->flightOptions,
                $options->corporateCodesUnifares,
                $options->doTicketabilityPreCheck
            );
        }

        $passengerCounter = 1;
        $infantCounter = 1;
        foreach ($options->passengers as $passenger) {
            $this->loadPassenger($passenger, $passengerCounter, $infantCounter);
        }

        $segmentCounter = 1;
        foreach ($options->itinerary as $itinerary) {
            $this->loadItinerary($itinerary, $segmentCounter);
        }

        if ($this->checkAnyNotEmpty(
            $options->cabinClass,
            $options->cabinOption,
            $options->requestedFlightTypes,
            $options->airlineOptions
        )) {
            $this->travelFlightInfo = new MasterPricer\TravelFlightInfo(
                $options->cabinClass,
                $options->cabinOption,
                $options->requestedFlightTypes,
                $options->airlineOptions
            );
        }

        if (!empty($options->priceToBeat)) {
            $this->priceToBeat = new MasterPricer\PriceToBeat(
                $options->priceToBeat,
                $options->priceToBeatCurrency
            );
        }
    }

    /**
     * @param MPPassenger $passenger
     * @param int $counter BYREF
     * @param int $infantCounter BYREF
     */
    protected function loadPassenger($passenger, &$counter, &$infantCounter)
    {
        $isInfant = ($passenger->type === 'INF');

        $paxRef = new MasterPricer\PaxReference(
            $isInfant ? $infantCounter : $counter,
            $isInfant,
            $passenger->type
        );

        if ($isInfant) {
            $infantCounter++;
        } else {
            $counter++;
        }

        if ($passenger->count > 1) {
            for ($i = 2; $i <= $passenger->count; $i++) {
                $tmpCount = ($isInfant) ? $infantCounter : $counter;
                $paxRef->traveller[] = new MasterPricer\Traveller($tmpCount, $isInfant);

                if ($isInfant) {
                    $infantCounter++;
                } else {
                    $counter++;
                }
            }
        }

        $this->paxReference[] = $paxRef;
    }

    /**
     * @param MPItinerary $itineraryOptions
     * @param int $counter BYREF
     */
    protected function loadItinerary($itineraryOptions, &$counter)
    {
        $tmpItinerary = new MasterPricer\Itinerary($counter);

        $tmpItinerary->departureLocalization = new MasterPricer\DepartureLocalization(
            $itineraryOptions->departureLocation
        );
        $tmpItinerary->arrivalLocalization = new MasterPricer\ArrivalLocalization(
            $itineraryOptions->arrivalLocation
        );
        $tmpItinerary->timeDetails = new MasterPricer\TimeDetails($itineraryOptions->date);

        $this->itinerary[] = $tmpItinerary;

        $counter++;
    }

    /**
     * @param FareMasterPricerTbSearch|FareMasterPricerCalendarOptions $options
     * @return void
     */
    protected function loadNrOfPaxAndResults(FareMasterPricerTbSearch $options)
    {
        if (is_int($options->nrOfRequestedPassengers) || is_int($options->nrOfRequestedResults)) {
            $this->numberOfUnit = new MasterPricer\NumberOfUnit(
                $options->nrOfRequestedPassengers,
                $options->nrOfRequestedResults
            );
        }
    }
}
