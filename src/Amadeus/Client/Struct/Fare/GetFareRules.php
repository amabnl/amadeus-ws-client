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

use Amadeus\Client\RequestOptions\FareGetFareRulesOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Fare\CheckRules\ItemNumber;
use Amadeus\Client\Struct\Fare\ConvertCurrency\ConversionRate;
use Amadeus\Client\Struct\Fare\GetFareRules\FlightQualification;
use Amadeus\Client\Struct\Fare\GetFareRules\MultiCorporate;
use Amadeus\Client\Struct\Fare\GetFareRules\PricingTickInfo;
use Amadeus\Client\Struct\Fare\GetFareRules\TransportInformation;
use Amadeus\Client\Struct\Fare\GetFareRules\TripDescription;

/**
 * Fare_GetFareRules request structure
 *
 * @package Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class GetFareRules extends BaseWsMessage
{
    /**
     * @var MsgType
     */
    public $msgType;

    /**
     * @var GetFareRules\AvailCabinConf
     */
    public $availCabinConf;

    /**
     * @var ConversionRate
     */
    public $conversionRate;

    /**
     * @var GetFareRules\PricingTickInfo
     */
    public $pricingTickInfo;

    /**
     * @var GetFareRules\MultiCorporate
     */
    public $multiCorporate;

    /**
     * @var GetFareRules\CorporateInfo
     */
    public $corporateInfo;

    /**
     * @var GetFareRules\MonetaryInfo
     */
    public $monetaryInfo;

    /**
     * @var ItemNumber
     */
    public $itemNumber;

    /**
     * @var GetFareRules\DateOfFlight
     */
    public $dateOfFlight;

    /**
     * @var GetFareRules\FlightQualification[]
     */
    public $flightQualification = [];

    /**
     * @var GetFareRules\TransportInformation[]
     */
    public $transportInformation = [];

    /**
     * @var GetFareRules\TripDescription[]
     */
    public $tripDescription = [];

    /**
     * @var GetFareRules\PricingInfo[]
     */
    public $pricingInfo = [];

    /**
     * @var GetFareRules\FareRule
     */
    public $fareRule;

    /**
     * @var GetFareRules\LocationInfo
     */
    public $locationInfo;

    /**
     * GetFareRules constructor.
     *
     * @param FareGetFareRulesOptions $options
     */
    public function __construct(FareGetFareRulesOptions $options)
    {
        $this->msgType = new MsgType(MessageFunctionDetails::FARE_GET_FARE_RULES);

        $this->loadTicketingDate($options->ticketingDate);
        $this->loadFlightQualification($options->fareBasis, $options->ticketDesignator, $options->directionality);
        $this->loadMultiCorporate($options->uniFares, $options->negoFares);
        $this->loadTransportInformation($options->airline);
        $this->loadTripDescription($options->origin, $options->destination, $options->travelDate);
    }

    /**
     * @param \DateTime|null $ticketingDate
     */
    protected function loadTicketingDate($ticketingDate)
    {
        if ($ticketingDate instanceof \DateTime) {
            $this->pricingTickInfo = new PricingTickInfo($ticketingDate);
        }
    }

    /**
     * @param string|null $fareBasis
     * @param string|null $ticketDesignator
     * @param string|null $directionality
     */
    protected function loadFlightQualification($fareBasis, $ticketDesignator, $directionality)
    {
        if ($this->checkAnyNotEmpty($fareBasis, $ticketDesignator, $directionality)) {
            $this->flightQualification[] = new FlightQualification(
                $fareBasis,
                $ticketDesignator,
                $directionality
            );
        }
    }

    /**
     * @param string[] $uniFares
     * @param string[] $negoFares
     */
    protected function loadMultiCorporate($uniFares, $negoFares)
    {
        if ($this->checkAnyNotEmpty($uniFares, $negoFares)) {
            $this->multiCorporate = new MultiCorporate($uniFares, $negoFares);
        }
    }

    /**
     * @param string|null $airline
     */
    protected function loadTransportInformation($airline)
    {
        if (!empty($airline)) {
            $this->transportInformation[] = new TransportInformation($airline);
        }
    }

    /**
     * @param string|null $origin
     * @param string|null $destination
     * @param \DateTime|null $travelDate
     */
    protected function loadTripDescription($origin, $destination, $travelDate)
    {
        if ($this->checkAnyNotEmpty($origin, $destination, $travelDate)) {
            $this->tripDescription[] = new TripDescription($origin, $destination, $travelDate);
        }
    }
}
