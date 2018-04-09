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

namespace Amadeus\Client\Struct\Fare\MasterPricer;

/**
 * PricingTicketing
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PricingTicketing
{
    const PRICETYPE_TICKETABILITY_PRECHECK = 'TAC';
    const PRICETYPE_UNIFARES = 'RU';
    const PRICETYPE_PUBLISHEDFARES = 'RP';
    const PRICETYPE_CORPORATE_UNIFARES = 'RW';
    const PRICETYPE_NO_RESTRICTION = "NR";
    const PRICETYPE_REFUNDABLE = "RF";
    const PRICETYPE_NO_ADVANCE_PURCHASE = "NAP";
    const PRICETYPE_NO_PENALTIES = "NPE";
    const PRICETYPE_NO_LOWCOST = "XLC";
    const PRICETYPE_ELECTRONIC_TICKET = "ET";
    const PRICETYPE_PAPER_TICKET = "PT";
    const PRICETYPE_ELECTRONIC_PAPER_TICKET = "EP";
    const PRICETYPE_FORCE_NEUTRAL_FARE_SEARCH = "NPF";
    const PRICETYPE_NO_SLICE_AND_DICE = "NSD";
    const PRICETYPE_DISPLAY_MIN_MAX_STAY = "MST";
    const PRICETYPE_OVERRIDE_CURRENCY_CONVERSION = "CUC";
    const PRICETYPE_MULTI_TICKET = "MTK";
    const PRICETYPE_IN_FLIGHT_SERVICES = "IFS";

    /**
     * self::PRICETYPE_*
     *
     * ACS Anchored Search
     * ADC Airline distribution flavor 2
     * ADI Airline distribution flavor 1
     * AID Office ID Override for Flight Search purposes
     * BAG Additional baggages allowance
     * CFD Commercial fare family distribution
     * CFF Combine Fare Families
     * CFS Activate Selection of Fare Currency option
     * CUC Override of the currency conversion
     * DDI Direct Distribution
     * DFA Display Fare Component Amounts
     * DNP Distribution Non-stop Preferred
     * ECC Empty Cell Clarification
     * ECT Show sold-out flights
     * EP Either paper or electronic ticket is requested
     * ERT Easy Round Trip
     * ET ELECTRONIC TICKET ONLY
     * EVD Even distribution
     * FCN Minimum fee amount for credit card
     * FCX Maximum fee amount for credit card
     * FDN Minimum fee amount for debit card
     * FDX Maximum fee amount for debit card
     * FEE FEE
     * FFS Distribution Ratio of recommendations per FF at Requested Segment Level
     * FLF Flight facts
     * FON Minimum fee amount for any card
     * FOX Maximum fee amount for any card
     * FQC Flight Quality Control
     * IAV Ignore real availability
     * IFS In-flight services
     * MA Mileage accrual
     * MC Miles and cash
     * MCD Mono-Main Carrier Distribution
     * MFA Marginal Fare Amount
     * MNR Display Minirules
     * MST Display Max Stay Information
     * MTH Aggregation by month
     * MTK Multiple Ticket
     * NAD No Airline Distribution
     * NAP No advance purchase fares
     * NPE No penalties fares
     * NPF Force neutral fare search
     * NR No restriction fares
     * NSD Disable slice and dice process
     * OPD Open Return
     * OWD One Way Display
     * OWP One Way Pricing
     * PFP Partner Flight Process
     * PPB Price Per Bound
     * PSB Passenger Same Booking Code
     * PT EXCLUDE ELECTRONIC TICKET FARE
     * PTC Passenger type code specified in query
     * PTP Price to reach per proposed segment
     * PTR Price To Reach
     * RCP Reference City Pair
     * RDG To keep solutions with more ground time at last connecting point.
     * RDM To keep the best solutions per major flight
     * RF Refundable fares
     * RJS Rank in journey Server
     * RP Published fares
     * RTC Half round trip combination
     * RU Unifares
     * RW Corporate Unifares
     * SFR Local search failure information option
     * SLM Service Level minimum
     * TAC Ticket ability check
     * TAN No ticket ability check
     * TFO Through Fare Only option
     * UPS Upsell mode
     * URC Useless Recommendation Check
     * WF Web Fares
     * WP Waive Penalty Option
     * XLC No LCC fares
     * XMX No mix LCC/FSC fares
     * YQR YQ/YR option
     *
     * @var string[]
     */
    public $priceType = [];

    /**
     * PricingTicketing constructor.
     *
     * @param string|null $mainPricingType
     */
    public function __construct($mainPricingType = null)
    {
        if (!is_null($mainPricingType)) {
            $this->priceType[] = $mainPricingType;
        }
    }
}
