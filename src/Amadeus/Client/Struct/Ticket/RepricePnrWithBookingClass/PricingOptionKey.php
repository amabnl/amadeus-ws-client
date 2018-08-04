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

namespace Amadeus\Client\Struct\Ticket\RepricePnrWithBookingClass;

/**
 * PricingOptionKey
 *
 * @package Amadeus\Client\Struct\Ticket\RepricePnrWithBookingClass
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PricingOptionKey
{
    const OPTION_ADD_TAX = "AT";
    const OPTION_AWARD = "AWD";
    const OPTION_ATC_BEST_PRICING = "BST";
    const OPTION_CHECKED_IN_COUPON_SELECTION = "CK";
    const OPTION_CORPORATION_NUMBER = "CRP";
    const OPTION_PAST_DATE_PRICING = "DAT";
    const OPTION_DOWNGRADE_OPTION = "DNG";
    const OPTION_EXEMPT_TAXES = "ET";
    const OPTION_FARE_BASIS_SIMPLE_OVERRIDE = "FBA";
    const OPTION_FARE_BREAK_POINT = "FBP";
    const OPTION_FARE_CURRENCY_OVERRIDE = "FCO";
    const OPTION_FORCE_FEE_BREAK_POINT = "FFB";
    const OPTION_FREQUENT_FLYER_INFORMATION = "FTI";
    const OPTION_IGNORE_CORPORATE_IN_BLB = "IGC";
    const OPTION_TIER_LEVEL = "LVL";
    const OPTION_MILEAGE_ACCRUAL = "MA";
    const OPTION_MILES_AND_CASH = "MC";
    const OPTION_NO_BREAKPOINT = "NBP";
    const OPTION_NO_JOURNEY_TURNAROUND_POINT = "NJT";
    const OPTION_NO_OPTION = "NOP";
    const OPTION_OVERRIDE_CONTROLLING_CARRIER = "OCC";
    const OPTION_OVERRIDE_REUSABLE_AMOUNT = "ORA";
    const OPTION_PASSENGER_DISCOUNT_PTC = "PAX";
    const OPTION_PRICING_BY_FARE_FAMILY = "PFF";
    const OPTION_POINT_OF_SALE_OVERRIDE = "POS";
    const OPTION_POINT_OF_TICKETING_OVERRIDE = "POT";
    const OPTION_EXPANDED_PARAMETERS = "PRM";
    const OPTION_PROMO_CERTIFICATE = "PRO";
    const OPTION_POINT_OF_TURNAROUND_FLIGHT_INDICATOR = "PTA";
    const OPTION_PTC_ONLY = "PTC";
    const OPTION_NEGOTIATED_FARE = "RN";
    const OPTION_PUBLISHED_FARES = "RP";
    const OPTION_HOLD_FOR_FUTURE_USE = "RTF";
    const OPTION_UNIFARES = "RU";
    const OPTION_RESIDUAL_VALUE_IN_FO_LINE = "RVD";
    const OPTION_CORPORATE_UNIFARES = "RW";
    const OPTION_PAX_SEG_LINE_TST_SELECTION = "SEL";
    const OPTION_TRANSITIONAL_CERTIFICATE = "TRS";
    const OPTION_UPGRADE = "UPG";
    const OPTION_VALIDATING_CARRIER = "VC";
    const OPTION_WITHOLD_COUNTRY_TAXES = "WC";
    const OPTION_WITHOLD_Q_SURCHARGES = "WQ";
    const OPTION_WITHOLD_TAX = "WT";
    const OPTION_WAIVER_OPTION = "WV";
    const OPTION_ZAP_OFF = "ZAP";

    /**
     * self::OPTION_*
     *
     * @var string
     */
    public $pricingOptionKey;

    /**
     * PricingOptionKey constructor.
     *
     * @param string $key self::OPTION_*
     */
    public function __construct($key)
    {
        $this->pricingOptionKey = $key;
    }
}
