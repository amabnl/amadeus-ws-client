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

namespace Amadeus\Client\Struct\Fare\PricePnr13;

/**
 * PricingOptionKey
 *
 * @package Amadeus\Client\Struct\Fare\PricePnr13
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PricingOptionKey
{
    const OPTION_NO_OPTION = "NOP";
    const OPTION_VALIDATING_CARRIER = "VC";
    const OPTION_FARE_CURRENCY_OVERRIDE = "FCO";
    const OPTION_PASSENGER_DISCOUNT = "PAX";
    const OPTION_FARE_BASIS_SIMPLE_OVERRIDE = "FBA";
    const OPTION_NEGOTIATED_FARES = "RN";
    const OPTION_PUBLISHED_FARES = "RP";
    const OPTION_UNIFARES = "RU";
    const OPTION_CORPORATE_NEGOTIATED_FARES = "RC";
    const OPTION_CORPORATE_UNIFARES = "RW";
    const OPTION_OB_FEES = "OBF";
    const OPTION_PASSENGER_DISCOUNT_PTC = "PAX";
    const OPTION_POINT_OF_SALE_OVERRIDE = "POS";
    const OPTION_POINT_OF_TICKETING_OVERRIDE = "POT";
    const OPTION_PRICING_LOGIC = "PL";
    const OPTION_TICKET_TYPE = "TKT";
    const OPTION_ADD_TAX = "AT";
    const OPTION_EXEMPT_FROM_TAX = "ET";
    const OPTION_PAX_SEGMENT_TST_SELECTION = "SEL";
    const OPTION_PAST_DATE_PRICING = "DAT";
    const OPTION_AWARD_PRICING = "AWD";
    const OPTION_RETURN_LOWEST = "RLO";
    const OPTION_RETURN_LOWEST_AVAILABLE = "RLA";
    const OPTION_RETURN_ALL = "RLI";
    const OPTION_PTC_ONLY = "PTC";
    const OPTION_FORM_OF_PAYMENT = "FOP";
    const OPTION_CABIN = "CAB";
    const OPTION_FARE_FAMILY = "PFF";
    const OPTION_ZAP_OFF = "ZAP";

    /**
     * AC Add Country taxes
     * AT Add Tax
     * AWD AWarD
     * BK Booking class override
     * BND Bound Input
     * CAB CABin option
     * CC Controlling Carrier Override
     * CMP Companions
     * CON Connection
     * DAT past DATe pricing
     * DIA Diagnostic Tool
     * DO booking Date Override
     * ET Exempt Taxes
     * FBA Fare BAsis simple override
     * FBL Fare Basis force override
     * FBP Force Break Point
     * FCO Fare Currency Override
     * FCS Fare Currency Selection
     * FOP Form Of Payment
     * GRI Global Route Indicator
     * IP Instant Pricing
     * MA Mileage Accrual
     * MBT Fare amount override with M/BT
     * MC Miles and Cash (Pricing by Points)
     * MIT Fare amount override with M/IT
     * NBP No BreakPoint
     * NF No ticketing Fee
     * NOP No Option
     * OBF OB Fees (include and/or exclude)
     * PAX Passenger discount/PTC
     * PFF Pricing by Fare Family
     * PL Pricing Logic
     * POS Point Of Sale
     * POT Point Of Ticketing override
     * PRM expanded PaRaMeters
     * PRO Promo Certificate
     * PTA Point of Turnaround
     * PTC PTC only
     * RC Corporate negotiated fares
     * RLI Return LIst of fare
     * RLO Return LOwest possible fare
     * RN Negotiated fare
     * RP Published Fares
     * RU Unifares
     * RW Corporate Unifares
     * SEL Passenger/Segment/Line/TST selection
     * STO Stopover
     * TKT TicKet Type
     * TRS Transitional Certificate
     * VC Validating Carrier
     * WC Withhold Country taxes
     * WQ Withhold Q surcharges
     * WT Withhold Tax
     * ZAP ZAP-off
     *
     * @var string
     */
    public $pricingOptionKey;

    /**
     * PricingOptionKey constructor.
     *
     * @param string $key
     */
    public function __construct($key)
    {
        $this->pricingOptionKey = $key;
    }
}
