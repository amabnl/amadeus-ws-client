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

use Amadeus\Client\RequestOptions\Fare\PricePnr\AwardPricing;
use Amadeus\Client\RequestOptions\Fare\PricePnr\ExemptTax;
use Amadeus\Client\RequestOptions\Fare\PricePnr\FareBasis;
use Amadeus\Client\RequestOptions\Fare\PricePnr\Tax;
use Amadeus\Client\RequestOptions\Ticket\ExchangeInfoOptions;
use Amadeus\Client\RequestOptions\Ticket\MultiRefOpt;
use Amadeus\Client\RequestOptions\Ticket\PaxSegRef;

/**
 * Ticket_RepricePNRWithBookingClass request options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TicketRepricePnrWithBookingClassOptions extends Base
{
    const OVERRIDE_ADD_TAX = "AT";
    const OVERRIDE_AWARD = "AWD";
    const OVERRIDE_ATC_BEST_PRICING = "BST";
    const OVERRIDE_CHECKED_IN_COUPON_SELECTION = "CK";
    const OVERRIDE_CORPORATION_NUMBER = "CRP";
    const OVERRIDE_PAST_DATE_PRICING = "DAT";
    const OVERRIDE_DOWNGRADE_OPTION = "DNG";
    const OVERRIDE_EXEMPT_TAXES = "ET";
    const OVERRIDE_FARE_BASIS_SIMPLE_OVERRIDE = "FBA";
    const OVERRIDE_FARE_BREAK_POINT = "FBP";
    const OVERRIDE_FARE_CURRENCY_OVERRIDE = "FCO";
    const OVERRIDE_FORCE_FEE_BREAK_POINT = "FFB";
    const OVERRIDE_FREQUENT_FLYER_INFORMATION = "FTI";
    const OVERRIDE_IGNORE_CORPORATE_IN_BLB = "IGC";
    const OVERRIDE_TIER_LEVEL = "LVL";
    const OVERRIDE_MILEAGE_ACCRUAL = "MA";
    const OVERRIDE_MILES_AND_CASH = "MC";
    const OVERRIDE_NO_BREAKPOINT = "NBP";
    const OVERRIDE_NO_JOURNEY_TURNAROUND_POINT = "NJT";
    const OVERRIDE_NO_OPTION = "NOP";
    const OVERRIDE_OVERRIDE_CONTROLLING_CARRIER = "OCC";
    const OVERRIDE_OVERRIDE_REUSABLE_AMOUNT = "ORA";
    const OVERRIDE_PASSENGER_DISCOUNT_PTC = "PAX";
    const OVERRIDE_PRICING_BY_FARE_FAMILY = "PFF";
    const OVERRIDE_POINT_OF_SALE_OVERRIDE = "POS";
    const OVERRIDE_POINT_OF_TICKETING_OVERRIDE = "POT";
    const OVERRIDE_EXPANDED_PARAMETERS = "PRM";
    const OVERRIDE_PROMO_CERTIFICATE = "PRO";
    const OVERRIDE_POINT_OF_TURNAROUND_FLIGHT_INDICATOR = "PTA";
    const OVERRIDE_PTC_ONLY = "PTC";
    const OVERRIDE_NEGOTIATED_FARE = "RN";
    const OVERRIDE_PUBLISHED_FARES = "RP";
    const OVERRIDE_HOLD_FOR_FUTURE_USE = "RTF";
    const OVERRIDE_UNIFARES = "RU";
    const OVERRIDE_RESIDUAL_VALUE_IN_FO_LINE = "RVD";
    const OVERRIDE_CORPORATE_UNIFARES = "RW";
    const OVERRIDE_PAX_SEG_LINE_TST_SELECTION = "SEL";
    const OVERRIDE_TRANSITIONAL_CERTIFICATE = "TRS";
    const OVERRIDE_UPGRADE = "UPG";
    const OVERRIDE_VALIDATING_CARRIER = "VC";
    const OVERRIDE_WITHOLD_COUNTRY_TAXES = "WC";
    const OVERRIDE_WITHOLD_Q_SURCHARGES = "WQ";
    const OVERRIDE_WITHOLD_TAX = "WT";
    const OVERRIDE_WAIVER_OPTION = "WV";
    const OVERRIDE_ZAP_OFF = 'ZAP';
    
    /**
     * @var ExchangeInfoOptions[]
     */
    public $exchangeInfo = [];

    /**
     * List of override options. self::OVERRIDE_*
     *
     * If there are no override options specified, an option NOP
     * (no option) element will be added to the call.
     *
     * @var string[]
     */
    public $overrideOptions = [];


    /**
     * Specify the validating carrier
     *
     * @var string
     */
    public $validatingCarrier;


    /**
     * Override the controlling carrier
     *
     * @var string
     */
    public $controllingCarrier;


    /**
     * Price with corporate unifares.
     *
     * @var string[]
     */
    public $corporateUniFares = [];


    /**
     * Provide a fare basis to price with
     *
     * @var FareBasis[]
     */
    public $pricingsFareBasis = [];

    /**
     * This option is used to price an itinerary applying an award program for a given carrier.
     *
     * must be combined with $this->corporateUniFares!
     *
     * @var AwardPricing
     */
    public $awardPricing;

    /**
     * Add Taxes
     *
     * @var Tax[]
     */
    public $taxes = [];

    /**
     * This option is used to exempt the passenger from one, several or all taxes.
     *
     * @var ExemptTax[]
     */
    public $exemptTaxes = [];


    /**
         * This option allows you to override the currency of the office.
     *
     * @var string
     */
    public $currencyOverride;

    /**
     * Passenger, Segment or TST references to partially price the PNR
     *
     * @var MultiRefOpt[]
     */
    public $multiReferences = [];

    /**
     * Force breakpoint
     *
     * Used to force a breakpoint after a given segment.
     *
     * @var PaxSegRef[]
     */
    public $forceBreakPointRefs = [];

    /**
     * Breakpoint Prohibited
     *
     * Used to prohibit breakpoint after a given segment.
     *
     * @var PaxSegRef[]
     */
    public $noBreakPointRefs = [];

    /**
     * Specify passenger PTC or discount codes
     *
     * @var string[]
     */
    public $paxDiscountCodes = [];

    /**
     * @var PaxSegRef[]
     */
    public $paxDiscountCodeRefs = [];

    /**
     * Override the Point of Sale
     *
     * e.g. 'LON' for point of sale London
     *
     * @var string
     */
    public $pointOfSaleOverride;

    /**
     * Override the Point of Ticketing
     *
     * e.g. 'LON' for point of ticketing London
     *
     * @var string
     */
    public $pointOfTicketingOverride;

    /**
     * A waiver option to be applied to the repricing
     *
     * @var string
     */
    public $waiverCode;


    /**
     * Override Reusable Amount
     *
     * Set the whole fare (which can be distributed as reusable and refundable depending on the form of payment)
     * as refundable overriding their previous conditions.
     *
     * Provide the tickets for which ORA should be applied
     *
     * @var PaxSegRef[]
     */
    public $overrideReusableAmountRefs = [];
}
