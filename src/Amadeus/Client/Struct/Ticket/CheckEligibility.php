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

namespace Amadeus\Client\Struct\Ticket;

use Amadeus\Client\RequestOptions\TicketCheckEligibilityOptions;
use Amadeus\Client\Struct\Fare\BaseMasterPricerMessage;
use Amadeus\Client\Struct\Fare\MasterPricer\FareFamilies;
use Amadeus\Client\Struct\Fare\MasterPricer\Itinerary;
use Amadeus\Client\Struct\Fare\MasterPricer\PriceToBeat;
use Amadeus\Client\Struct\Fare\MasterPricer\TravelFlightInfo;
use Amadeus\Client\Struct\Ticket\CheckEligibility\CombinationFareFamilies;
use Amadeus\Client\Struct\Ticket\CheckEligibility\CustomerRef;
use Amadeus\Client\Struct\Ticket\CheckEligibility\FeeOption;
use Amadeus\Client\Struct\Ticket\CheckEligibility\FormOfPaymentByPassenger;
use Amadeus\Client\Struct\Ticket\CheckEligibility\GlobalOptions;
use Amadeus\Client\Struct\Ticket\CheckEligibility\OfficeIdDetails;
use Amadeus\Client\Struct\Ticket\CheckEligibility\PassengerInfoGrp;
use Amadeus\Client\Struct\Ticket\CheckEligibility\SolutionFamily;
use Amadeus\Client\Struct\Ticket\CheckEligibility\TaxInfo;
use Amadeus\Client\Struct\Ticket\CheckEligibility\TicketChangeInfo;

/**
 * Ticket_CheckEligibility request structure
 *
 * @package Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CheckEligibility extends BaseMasterPricerMessage
{
    /**
     * @var GlobalOptions
     */
    public $globalOptions;

    /**
     * @var CustomerRef
     */
    public $customerRef;

    /**
     * @var FormOfPaymentByPassenger[]
     */
    public $formOfPaymentByPassenger = [];

    /**
     * @var SolutionFamily[]
     */
    public $solutionFamily = [];

    /**
     * @var PassengerInfoGrp[]
     */
    public $passengerInfoGrp = [];

    /**
     * @var FareFamilies[]
     */
    public $fareFamilies = [];

    /**
     * @var PriceToBeat
     */
    public $priceToBeat;

    /**
     * @var TaxInfo[]
     */
    public $taxInfo = [];

    /**
     * @var TravelFlightInfo
     */
    public $travelFlightInfo;

    /**
     * @var Itinerary[]
     */
    public $itinerary = [];

    /**
     * @var TicketChangeInfo
     */
    public $ticketChangeInfo;

    /**
     * @var CombinationFareFamilies[]
     */
    public $combinationFareFamilies = [];

    /**
     * @var FeeOption[]
     */
    public $feeOption = [];

    /**
     * @var OfficeIdDetails[]
     */
    public $officeIdDetails = [];

    /**
     * CheckEligibility constructor.
     *
     * @param TicketCheckEligibilityOptions $options
     */
    public function __construct(TicketCheckEligibilityOptions $options)
    {
        $this->loadNumberOfUnits($options);

        $this->loadFareOptions($options);

        $passengerCounter = 1;
        $infantCounter = 1;
        foreach ($options->passengers as $passenger) {
            $this->loadPassenger($passenger, $passengerCounter, $infantCounter);
        }

        $this->loadTicketNumbers($options->ticketNumbers);
    }

    /**
     * @param string[] $ticketNumbers
     */
    protected function loadTicketNumbers($ticketNumbers)
    {
        if (!empty($ticketNumbers)) {
            $this->ticketChangeInfo = new TicketChangeInfo($ticketNumbers);
        }
    }
}
