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

use Amadeus\Client\RequestOptions\Fare\MPFareFamily;
use Amadeus\Client\RequestOptions\Fare\MPItinerary;
use Amadeus\Client\RequestOptions\FareMasterPricerCalendarOptions;
use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
use Amadeus\Client\RequestOptions\TicketAtcShopperMpTbSearchOptions;
use Amadeus\Client\Struct\Fare\MasterPricer;

/**
 * Fare_MasterPricerTravelBoardSearch message structure
 *
 * Also used for Fare_MasterPricerCalendar and Ticket_ATCShopperMasterPricerTravelBoardSearch
 *
 * @package Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class MasterPricerTravelBoardSearch extends BaseMasterPricerMessage
{
    /**
     * @var mixed
     */
    public $globalOptions;
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
     * @var MasterPricer\OfficeIdDetails[]
     */
    public $officeIdDetails;

    /**
     * MasterPricerTravelBoardSearch constructor.
     *
     * @param FareMasterPricerTbSearch|FareMasterPricerCalendarOptions|TicketAtcShopperMpTbSearchOptions|null $options
     */
    public function __construct($options = null)
    {
        if ($options instanceof FareMasterPricerTbSearch) {
            $this->loadOptions($options);
        }
    }

    /**
     * @param FareMasterPricerTbSearch|FareMasterPricerCalendarOptions|TicketAtcShopperMpTbSearchOptions $options
     */
    protected function loadOptions($options)
    {
        $this->loadNrOfPaxAndResults($options);

        $this->loadFareOptions($options);

        $passengerCounter = 1;
        $infantCounter = 1;
        foreach ($options->passengers as $passenger) {
            $this->loadPassenger($passenger, $passengerCounter, $infantCounter);
        }

        $segmentCounter = 1;
        foreach ($options->itinerary as $itinerary) {
            $this->loadItinerary($itinerary, $segmentCounter);
        }

        foreach ($options->officeIds as $officeId) {
            $this->loadOfficeId($officeId);
        }

        if ($this->checkAnyNotEmpty(
            $options->cabinClass,
            $options->cabinOption,
            $options->requestedFlightTypes,
            $options->airlineOptions,
            $options->progressiveLegsMin,
            $options->progressiveLegsMax
        )) {
            $this->travelFlightInfo = new MasterPricer\TravelFlightInfo(
                $options->cabinClass,
                $options->cabinOption,
                $options->requestedFlightTypes,
                $options->airlineOptions,
                $options->progressiveLegsMin,
                $options->progressiveLegsMax
            );
        }

        if (!empty($options->priceToBeat)) {
            $this->priceToBeat = new MasterPricer\PriceToBeat(
                $options->priceToBeat,
                $options->priceToBeatCurrency
            );
        }

        $this->loadFareFamilies($options->fareFamilies);
    }

    /**
     * @param string $officeId
     */
    protected function loadOfficeId($officeId)
    {
        $this->officeIdDetails[] = new MasterPricer\OfficeIdDetails($officeId);
    }

    /**
     * @param MPItinerary $itineraryOptions
     * @param int $counter BYREF
     */
    protected function loadItinerary($itineraryOptions, &$counter)
    {
        $segmentRef = $counter;

        if (!empty($itineraryOptions->segmentReference)) {
            $segmentRef = $itineraryOptions->segmentReference;
        }

        $tmpItinerary = new MasterPricer\Itinerary($segmentRef);

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
     * @param MPFareFamily[] $fareFamilies
     */
    protected function loadFareFamilies($fareFamilies)
    {
        foreach ($fareFamilies as $fareFamily) {
            $this->fareFamilies[] = new MasterPricer\FareFamilies($fareFamily);
        }
    }
}
