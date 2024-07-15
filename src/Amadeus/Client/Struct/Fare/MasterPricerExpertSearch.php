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
use Amadeus\Client\RequestOptions\FareMasterPricerExSearchOptions;
use Amadeus\Client\RequestOptions\TicketAtcShopperMpTbSearchOptions;
use Amadeus\Client\Struct\Fare\MasterPricer;

/**
 * Fare_MasterPricerExpertSearch message structure
 *
 * @package Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MasterPricerExpertSearch extends BaseMasterPricerMessage
{

    /**
     * @var mixed
     */
    public $globalOptions;
    /**
     * @var MasterPricer\CustomerRef
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
     * @var MasterPricer\FeeOption[]
     */
    public $feeOption;
    /**
     * @var MasterPricer\OfficeIdDetails[]
     */
    public $officeIdDetails;

    /**
     * MasterPricerExpertSearch constructor.
     *
     * @param FareMasterPricerExSearch|FareMasterPricerCalendarOptions|TicketAtcShopperMpExSearchOptions|null $options
     */
    public function __construct($options = null)
    {
        if ($options instanceof FareMasterPricerExSearchOptions) {
            $this->loadOptions($options);
        }
    }

    /**
     * @param FareMasterPricerExSearch|FareMasterPricerCalendarOptions|TicketAtcShopperMpExSearchOptions $options
     */
    protected function loadOptions($options)
    {
        $this->loadNumberOfUnits($options);

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
            $options->progressiveLegsMax,
            $options->maxLayoverPerConnectionHours,
            $options->maxLayoverPerConnectionMinutes
        )) {
            $this->travelFlightInfo = new MasterPricer\TravelFlightInfo(
                $options->cabinClass,
                $options->cabinOption,
                $options->requestedFlightTypes,
                $options->airlineOptions,
                $options->progressiveLegsMin,
                $options->progressiveLegsMax,
                $options->maxLayoverPerConnectionHours,
                $options->maxLayoverPerConnectionMinutes
            );
        }

        if (!empty($options->priceToBeat)) {
            $this->priceToBeat = new MasterPricer\PriceToBeat(
                $options->priceToBeat,
                $options->priceToBeatCurrency
            );
        }

        $this->loadFareFamilies($options->fareFamilies);

        $this->loadCustomerRefs($options->dkNumber);

        $this->loadFeeOptions($options->feeOption);
    }

    /**
     * @param string $officeId
     */
    protected function loadOfficeId($officeId)
    {
        $this->officeIdDetails[] = new MasterPricer\OfficeIdDetails($officeId);
    }

    /**
     * @param MPItinerary $opt
     * @param int $counter BYREF
     */
    protected function loadItinerary($opt, &$counter)
    {
        $segmentRef = $counter;

        if (!empty($opt->segmentReference)) {
            $segmentRef = $opt->segmentReference;
        }

        $tmpItinerary = new MasterPricer\Itinerary($segmentRef);

        $tmpItinerary->departureLocalization = new MasterPricer\DepartureLocalization(
            $opt->departureLocation
        );
        $tmpItinerary->arrivalLocalization = new MasterPricer\ArrivalLocalization(
            $opt->arrivalLocation
        );
        $tmpItinerary->timeDetails = new MasterPricer\TimeDetails($opt->date);

        if ($this->checkAnyNotEmpty(
            $opt->airlineOptions,
            $opt->requestedFlightTypes,
            $opt->includedConnections,
            $opt->excludedConnections,
            $opt->nrOfConnections,
            $opt->cabinClass,
            $opt->cabinOption
        )) {
            $tmpItinerary->flightInfo = new MasterPricer\FlightInfo(
                $opt->airlineOptions,
                $opt->requestedFlightTypes,
                $opt->includedConnections,
                $opt->excludedConnections,
                $opt->nrOfConnections,
                null,
                $opt->cabinClass,
                $opt->cabinOption
            );
        }

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

    /**
     * Load Customer references
     *
     * @param string $dkNumber
     */
    protected function loadCustomerRefs($dkNumber)
    {
        if (!is_null($dkNumber)) {
            $this->customerRef = new MasterPricer\CustomerRef();
            $this->customerRef->customerReferences[] = new MasterPricer\CustomerReferences(
                $dkNumber,
                MasterPricer\CustomerReferences::QUAL_AGENCY_GROUPING_ID
            );
        }
    }

    private function loadFeeOptions($feeOptions)
    {
        if (!is_null($feeOptions)) {
            foreach ($feeOptions as $feeOption) {
                $this->feeOption[] = new MasterPricer\FeeOption($feeOption);
            }
        }
    }
}
