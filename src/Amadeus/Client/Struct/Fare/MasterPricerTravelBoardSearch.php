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
     * @var mixed
     */
    public $fareFamilies;
    /**
     * @var MasterPricer\FareOptions
     */
    public $fareOptions;
    /**
     * @var mixed
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

    public $valueSearch = [];
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
     * @param FareMasterPricerTbSearch|null $options
     */
    public function __construct(FareMasterPricerTbSearch $options = null)
    {
        if ($options instanceof FareMasterPricerTbSearch) {
            $this->loadOptions($options);
        }
    }

    /**
     * @param FareMasterPricerTbSearch $options
     */
    protected function loadOptions(FareMasterPricerTbSearch $options)
    {
        $this->loadNrOfPaxAndResults($options);

        if ($options->doTicketabilityPreCheck === true) {
            $this->fareOptions = new MasterPricer\FareOptions();
            $this->fareOptions->pricingTickInfo = new MasterPricer\PricingTickInfo();
            $this->fareOptions->pricingTickInfo->pricingTicketing = new MasterPricer\PricingTicketing(
                MasterPricer\PricingTicketing::PRICETYPE_TICKETABILITY_PRECHECK
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

        if (!empty($options->cabinClass)) {
            $this->travelFlightInfo = new MasterPricer\TravelFlightInfo($options->cabinClass);
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
     * @param MPItinerary $itinerary
     * @param int $counter BYREF
     */
    protected function loadItinerary($itinerary, &$counter)
    {
        $tmpItin = new MasterPricer\Itinerary($counter);

        $tmpItin->departureLocalization = new MasterPricer\DepartureLocalization($itinerary->departureLocation);
        $tmpItin->arrivalLocalization = new MasterPricer\ArrivalLocalization($itinerary->arrivalLocation);
        $tmpItin->timeDetails = new MasterPricer\TimeDetails($itinerary->date);

        $this->itinerary[] = $tmpItin;

        $counter++;
    }

    /**
     * @param FareMasterPricerTbSearch $options
     * @return void
     */
    protected function loadNrOfPaxAndResults(FareMasterPricerTbSearch $options)
    {
        if (is_int($options->nrOfRequestedPassengers) || is_int($options->nrOfRequestedResults)) {
            $this->numberOfUnit = new MasterPricer\NumberOfUnit();
            if (is_int($options->nrOfRequestedPassengers)) {
                $this->numberOfUnit->unitNumberDetail[] = new MasterPricer\UnitNumberDetail(
                    $options->nrOfRequestedPassengers,
                    MasterPricer\UnitNumberDetail::TYPE_PASS
                );
            }
            if (is_int($options->nrOfRequestedResults)) {
                $this->numberOfUnit->unitNumberDetail[] = new MasterPricer\UnitNumberDetail(
                    $options->nrOfRequestedResults,
                    MasterPricer\UnitNumberDetail::TYPE_RESULTS
                );
            }
        }
    }
}
