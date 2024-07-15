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
use Amadeus\Client\RequestOptions\Fare\PricePnr\Cabin;
use Amadeus\Client\RequestOptions\Fare\PricePnr\ExemptTax;
use Amadeus\Client\RequestOptions\Fare\PricePnr\FareBasis;
use Amadeus\Client\RequestOptions\Fare\PricePnr\FareFamily;
use Amadeus\Client\RequestOptions\Fare\PricePnr\FormOfPayment;
use Amadeus\Client\RequestOptions\Fare\PricePnr\ObFee;
use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;
use Amadeus\Client\RequestOptions\Fare\PricePnr\Tax;
use Amadeus\Client\RequestOptions\Fare\PricePnr\ZapOff;

/**
 * Fare_PricePnrWithBookingClass Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FarePricePnrWithBookingClassOptions extends Base
{
    const OVERRIDE_NO_OPTION = 'NOP';
    const OVERRIDE_FAREBASIS = 'FBA';
    const OVERRIDE_FARETYPE_PUB = 'RP';
    const OVERRIDE_FARETYPE_NEG = 'RN';
    const OVERRIDE_FARETYPE_UNI = 'RU';
    const OVERRIDE_FARETYPE_CORPNR = 'RC';
    const OVERRIDE_FARETYPE_CORPUNI = 'RW';
    const OVERRIDE_RETURN_LOWEST = 'RLO';
    const OVERRIDE_RETURN_LOWEST_AVAILABLE = 'RLA';
    const OVERRIDE_RETURN_ALL = 'RLI';
    const OVERRIDE_PTC_ONLY = 'PTC';
    const OVERRIDE_FORM_OF_PAYMENT = 'FOP';

    const PRICING_LOGIC_IATA = 'IATA';
    const PRICING_LOGIC_ATAF = 'ATAF';

    const TICKET_TYPE_ELECTRONIC = 'ET';
    const TICKET_TYPE_PAPER = 'PT';
    const TICKET_TYPE_BOTH = 'EP';


    /**
     * List of override options. self::OVERRIDE_*
     *
     * If there are no override options specified, an option NOP
     * (no option) element will be added to the call.
     *
     * AC  Add Country taxes
     * AT  Add Tax
     * AWD AWarD
     * BK  Booking class override
     * BND Bound Input
     * CC  Controlling Carrier Override
     * CMP Companions
     * CON Connection
     * DAT past DATe pricing
     * DIA Diagnostic Tool
     * DO  booking Date Override
     * ET  Exempt Taxes
     * FBA Fare BAsis simple override
     * FBL Fare Basis force override
     * FBP Force Break Point
     * FCO Fare Currency Override
     * FCS Fare Currency Selection
     * FOP Form Of Payment
     * GRI Global Route Indicator
     * IP  Instant Pricing
     * MA  Mileage Accrual
     * MBT Fare amount override with M/BT
     * MC  Miles and Cash (Pricing by Points)
     * MIT Fare amount override with M/IT
     * NBP No BreakPoint
     * NF  No ticketing Fee
     * NOP No Option
     * OBF OB Fees (include and/or exclude)
     * PAX Passenger discount/PTC
     * PFF Pricing by Fare Family
     * PL  Pricing Logic
     * POS Point Of Sale
     * POT Point Of Ticketing override
     * PRM expanded PaRaMeters
     * PRO Promo Certificate
     * PTA Point of Turnaround
     * PTC PTC only
     * RC  Corporate negociated fares
     * RLI Return LIst of fare
     * RLO Return LOwest possible fare
     * RN  Negotiated fare
     * RP  Published Fares
     * RU  Unifares
     * RW  Corporate Unifares
     * SEL Passenger/Segment/Line/TST selection
     * STO Stopover
     * TKT TicKet Type
     * TRS Transitional Certificate
     * VC  Validating Carrier
     * WC  Withold Country taxes
     * WQ  Withold Q surcharges
     * WT  Withold Tax
     * ZAP ZAP-off
     *
     * @var string[]
     */
    public $overrideOptions = [];

    /**
     * @var array
     */
    public $overrideOptionsWithCriteria = [];

    /**
     * Specify the validating carrier
     *
     * @var string
     */
    public $validatingCarrier;

    /**
     * Price with a corporate negotiated fare.
     *
     * @var string
     */
    public $corporateNegoFare;

    /**
     * Price with corporate unifares.
     *
     * @var string[]
     */
    public $corporateUniFares = [];

    /**
     * This option is used to price an itinerary applying an award program for a given carrier.
     *
     * must be combined with $this->corporateUniFares!
     *
     * @var AwardPricing
     */
    public $awardPricing;

    /**
     * This option allows you to override the currency of the office.
     *
     * Corresponding cryptic: FXX/R,FC-USD
     *
     * @var string
     */
    public $currencyOverride;

    /**
     * Provide a fare basis to price with
     *
     * @var FareBasis[]
     */
    public $pricingsFareBasis = [];

    /**
     * Add up to 3 OBFees and/or to exempt up to 3 OBFees
     *
     * @var ObFee[]
     */
    public $obFees = [];

    /**
     * OB Fees Passenger & Segment references
     *
     * @var PaxSegRef[]
     */
    public $obFeeRefs = [];

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
     * Pricing Logic IATA or ATAF
     *
     * self::PRICING_LOGIC_*
     *
     * @var string
     */
    public $pricingLogic;

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
     * This option is used to select the type of ticket fare: paper, electronic or both.
     *
     * self::TICKET_TYPE_*
     *
     * @var string
     */
    public $ticketType;

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
     * Target fares that were applicable on a given date.
     *
     * @var \DateTime
     */
    public $pastDatePricing;

    /**
     * Form of Payment override (max. 3)
     *
     * @var FormOfPayment[]
     */
    public $formOfPayment = [];

    /**
     * Passenger, Segment or TST references to partially price the PNR
     *
     * @var PaxSegRef[]
     */
    public $references = [];

    /**
     * Fare family to be used in pricing (e.g. "CLASSIC").
     *
     * @var string|FareFamily[]
     */
    public $fareFamily;

    /**
     * Zap-Off to be applied
     *
     * @var ZapOff[]
     */
    public $zapOff;

    /**
     * Cabins to be applied
     *
     * @var Cabin[]
     */
    public $cabins;
}
