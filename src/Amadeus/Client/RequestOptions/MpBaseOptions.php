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

use Amadeus\Client\RequestOptions\Fare\MPTicketingPriceScheme;

/**
 * MasterPricer Base Options
 *
 * For MasterPricer options shared with ATC messages (such as Ticket_CheckEligibility)
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MpBaseOptions extends Base
{
    const FLIGHTOPT_PUBLISHED = 'RP';
    const FLIGHTOPT_UNIFARES = 'RU';
    const FLIGHTOPT_CORPORATE_UNIFARES = 'RW';
    const FLIGHTOPT_NO_RESTRICTION = 'NR';
    const FLIGHTOPT_REFUNDABLE = 'RF';
    const FLIGHTOPT_NO_ADVANCE_PURCHASE = 'NAP';
    const FLIGHTOPT_NO_PENALTIES = 'NPE';
    const FLIGHTOPT_NO_LOWCOST = 'XLC';
    const FLIGHTOPT_ELECTRONIC_TICKET = 'ET';
    const FLIGHTOPT_PAPER_TICKET = 'PT';
    const FLIGHTOPT_ELECTRONIC_PAPER_TICKET = 'EP';
    const FLIGHTOPT_FORCE_NEUTRAL_FARE_SEARCH = 'NPF';
    const FLIGHTOPT_NO_SLICE_AND_DICE = 'NSD';
    const FLIGHTOPT_DISPLAY_MIN_MAX_STAY = 'MST';
    const FLIGHTOPT_TICKET_AVAILABILITY_CHECK = 'TAC';
    const FLIGHTOPT_IN_FLIGHT_SERVICES = 'IFS';
    const FLIGHTOPT_ONLY_PTC = 'PTC';
    const FLIGHTOPT_MINIRULES = 'MNR';
    const FLIGHTOPT_ONLY_BUNDLED_FARES = 'BD';
    const FLIGHTOPT_ONLY_UNBUNDLED_FARES = 'UBD';

    const CORPORATE_QUALIFIER_AMADEUS_NEGO = 'RC';
    const CORPORATE_QUALIFIER_UNIFARE = 'RW';

    /**
     * The total number of passengers.
     *
     * Must match the data present in $this->passengers
     *
     * @var int
     */
    public $nrOfRequestedPassengers;

    /**
     * Maximum number of recommendations requested
     *
     * @var int
     */
    public $nrOfRequestedResults;

    /**
     * Passenger info
     *
     * @var Fare\MPPassenger[]
     */
    public $passengers = [];

    /**
     * Provide extra fare & flight options
     *
     * Choose from self::FLIGHTOPT_*
     *
     * @var string[]
     */
    public $flightOptions = [];

    /**
     * Corporate numbers for returning Corporate Unifares
     *
     * In combination with fareType self::FLIGHTOPT::CORPORATE_UNIFARES
     *
     * @var string[]
     */
    public $corporateCodesUnifares = [];

    /**
     * Corporate qualifier for returning Corporate Unifares
     *
     * In combination with fareType self::FLIGHTOPT::CORPORATE_UNIFARES
     *
     * Choose from self::CORPORATE_QUALIFIER_*
     *
     * @var string
     */
    public $corporateQualifier;

    /**
     * Whether to perform a ticketability pre-check
     *
     * @var bool
     */
    public $doTicketabilityPreCheck = false;

    /**
     * The currency to convert to.
     *
     * All price amounts for recommendations will be converted in the requested Currency.
     *
     * (The provided currency must be a valid 3-character ISO 4217 Currency Code)
     *
     * for example: EUR, USD, JPY,...
     *
     * @var string
     */
    public $currencyOverride;

    /**
     * FeeIds you want to pass on fare options level.
     *
     * @var Fare\MPFeeId[]
     */
    public $feeIds;

    /**
     * Whether to perform a multi ticket search
     *
     * @var bool
     */
    public $multiTicket = false;

    /**
     * @var MPTicketingPriceScheme
     */
    public $ticketingPriceScheme;

    /**
     * Optional. Weights for Multi Ticket functionality.
     *
     * @var Fare\MasterPricer\MultiTicketWeights
     */
    public $multiTicketWeights;

    /**
     * A maximum of 3 forms of payment may be specified.
     *
     * @var Fare\MasterPricer\FormOfPaymentDetails[]|array
     */
    public $formOfPayment;
}
