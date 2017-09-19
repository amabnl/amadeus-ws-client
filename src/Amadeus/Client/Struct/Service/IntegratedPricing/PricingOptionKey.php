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

namespace Amadeus\Client\Struct\Service\IntegratedPricing;

/**
 * PricingOptionKey
 *
 * @package Amadeus\Client\Struct\Service\IntegratedPricing
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PricingOptionKey
{
    const OVERRIDE_ACCOUNT_CODE = "ACC";
    const OVERRIDE_PRICING_ALL_SERVICES = "ALL";
    const OVERRIDE_AWARD = "AWD";
    const OVERRIDE_CODE_SELECTION = "COD";
    const OVERRIDE_CORPORATION_NUMBER = "CRP";
    const OVERRIDE_PRICING_DATE = "DAT";
    const OVERRIDE_CURRENCY = "FCO";
    const OVERRIDE_INFANT_PROCESSING = "INF";
    const OVERRIDE_JOURNEY_TURNAROUND_POINT = "JTP";
    const OVERRIDE_NO_BREAK_POINT = "NBP";
    const OVERRIDE_NO_JOURNEY_TURNAROUND_POINT = "NJT";
    const OVERRIDE_NO_OPTION = "NOP";
    const OVERRIDE_NON_TRUSTED_REQUEST = "NTR";
    const OVERRIDE_ORIGIN_DESTINATION = "OD";
    const OVERRIDE_PASSENGER_DISCOUNT_PTC = "PAX";
    const OVERRIDE_POINT_OF_SALE = "POS";
    const OVERRIDE_PTCONLY = "PTC";
    const OVERRIDE_SHOW_COMMERCIAL_DESCRIPTION = "SCD";
    const OVERRIDE_PAX_SEG_ELEMENT_SELECTION = "SEL";
    const OVERRIDE_SHOW_PRICING_DESCRIPTION = "SPD";
    const OVERRIDE_TICKET_DESIGNATOR = "TKD";
    const OVERRIDE_VALIDATING_CARRIER = "VC ";
    const OVERRIDE_FORM_OF_PAYMENT = "FOP";
    const OVERRIDE_FREQUENT_FLYER_INFORMATION = "FTI";

    /**
     * self::OVERRIDE_*
     *
     * AC    Add Country taxes
     * ACC   Account Code
     * ALL   Pricing All services
     * AT    Add Tax
     * AWD   AWarD
     * BGA   Baggage Allowance
     * BK    Booking class override
     * BKT   Booking or PNR Type
     * CAB   CABin option
     * CMP   Companions
     * COD   Code selection
     * CRP   CoRPoration number
     * DAT   past DATe pricing
     * DO    booking Date Override
     * ET    Exempt Taxes
     * EXC   Exclude
     * FBA   Fare BAsis simple override
     * FBL   Fare Basis force override
     * FBP   Fare Break Point
     * FCO   Fare Currency Override
     * FCS   Fare Currency Selection
     * FFB   Force Fee Break point
     * FLI   FLight Indicator
     * FNB   Force No fee Break point
     * FOP   Form Of Payment
     * FTI   Frequent Flyer Information
     * GRI   Global Route Indicator
     * GRP   Service Group filtering
     * INC   Include only
     * INF   Infant processing
     * IP    Instant Pricing
     * JTP   Journey Turnaround Point
     * LBC   List Booking Code
     * LVL   Tier Level
     * MA    Mileage Accrual
     * MBT   Fare amount override with M/BT
     * MC    Miles and Cash (Pricing by Points)
     * MIT   Fare amount override with M/IT
     * NBP   No BreakPoint
     * NF    No ticketing Fee
     * NJT   No Journey Turnaround Point
     * NOP   No Option
     * NS    No Split
     * NSD   No Slice and Dice
     * NTR   Non Trusted Request
     * NVO   No Validation on Original class
     * OBF   OB Fees (include and/or exclude)
     * OCO   Occurrence override
     * OD    Origin and Destination option
     * OIS   Show Only Issuable recommendation
     * PAX   Passenger discount/PTC
     * PFF   Pricing by Fare Family
     * PL    Pricing Logic
     * POS   Point Of Sale
     * POT   Point Of Ticketing override
     * PRM   expanded PaRaMeters
     * PRO   Promo Certificate
     * PSR   PSR
     * PTC   PTC only
     * RC    Corporate negotiated fares
     * RLA   Return Lowest Available fare
     * RLI   Return LIst of fare
     * RLO   Return LOwest possible fare
     * RN    Negotiated fare
     * RP    Published Fares
     * RU    Unifares
     * RW    Corporate Unifares
     * SCD   Show Commercial Description
     * SEL   Passenger/Segment/Line/TST selection
     * SPD   Show Pricing Description
     * STP   Service Type override
     * SUB   Service Sub-Group filtering
     * TKD   Ticket Designator
     * TKN   Ticket Number
     * TKT   TicKet Type
     * TRS   Transitional Certificate
     * UU    corporate Unifares only
     * VC    Validating Carrier
     * WC    Withhold Country taxes
     * WQ    Withhold Q surcharges
     * WT    Withhold Tax
     * ZAP   ZAP-off
     *
     * @var string
     */
    public $pricingOptionKey;

    /**
     * PricingOptionKey constructor.
     *
     * @param string $key self::OVERRIDE_*
     */
    public function __construct($key)
    {
        $this->pricingOptionKey = $key;
    }
}
