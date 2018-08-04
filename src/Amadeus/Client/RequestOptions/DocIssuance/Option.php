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

namespace Amadeus\Client\RequestOptions\DocIssuance;

use Amadeus\Client\LoadParamsFromArray;

/**
 * DocIssuance Option
 *
 * @package Amadeus\Client\RequestOptions\DocIssuance
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Option extends LoadParamsFromArray
{
    const INDICATOR_CONCEALED_CREDIT_CARD = "CCC";
    const INDICATOR_CREDIT_CARD_OVERRIDE = "CCO";
    const INDICATOR_FORCE_PASSENGER_RECEIPT_PRINT = "CR";
    const INDICATOR_EMD_ISSUANCE = "ED";
    const INDICATOR_ELECTRONIC_OVERRIDE = "ET";
    const INDICATOR_EXCHANGE_TICKETING = "EXC";
    const INDICATOR_GROUP_IDENTIFIER = "GRP";
    const INDICATOR_HOTEL_TERMS = "HT";
    const INDICATOR_PRINT_BASIC_JOINT_ITINERARY = "IBJ";
    const INDICATOR_PRINT_BASIC_ITINERARY = "IBP";
    const INDICATOR_INHIBIT_CC_FORM_COUPON_PRINT = "ICP";
    const INDICATOR_PRINT_EXTENDED_JOINT_ITINERARY = "IEJ";
    const INDICATOR_PRINT_EXTENDED_ITINERARY = "IEP";
    const INDICATOR_MINI_ITINERARY_GENERATION = "IMP";
    const INDICATOR_INDIVIDUAL_EXTENDED_INV_PASSENGER = "INE";
    const INDICATOR_INFANT_SELECT = "INF";
    const INDICATOR_PRINT_SINGLE_EXT_INVOICE_ALL_PAX = "INJ";
    const INDICATOR_PRINT_INDIV_INVOICE_EACH_PAX = "INV";
    const INDICATOR_PNR_INVOICE = "IPE";
    const INDICATOR_GAC_INVOICE = "IPG";
    const INDICATOR_INVOLUNTARY_REROUTING = "IRI";
    const INDICATOR_INHIBIT_SHIFT = "ISH";
    const INDICATOR_ITINERARY_RECEIPT = "ITR";
    const INDICATOR_PRINT_SINGLE_BASIC_INV_PAX = "IVJ";
    const INDICATOR_ITINERARY_WALLET_PRINT_GENERATION = "IWP";
    const INDICATOR_NO_CREDIT = "NCC";
    const INDICATOR_FORCE_ELECTRONIC = "OET";
    const INDICATOR_OVERRIDE_NAME_CHECK = "ONC";
    const INDICATOR_OVERRIDE_NON_EX_INDICATORS = "ONX";
    const INDICATOR_FORCE_PAPER = "OPT";
    const INDICATOR_PRESENT_CREDIT_CARD = "PCC";
    const INDICATOR_PAYMENT_ON_DEMAND = "POD";
    const INDICATOR_PAPER_OVERRIDE = "PT";
    const INDICATOR_RETRIEVE_PNR = "RT";
    const INDICATOR_TICKET_ONLY = "TKA";
    const INDICATOR_PRE_ISSUE_VALIDATION = "TKT";
    const INDICATOR_TEMPLATE_DISPLAY = "TMD";
    const INDICATOR_TEMPLATE_OVERRIDE = "TMO";
    const INDICATOR_DOCUMENT_RECEIPT = "TRP";
    const INDICATOR_SEND_ACCOUNTING_AIR = "ZAA";

    /**
     * self::INDICATOR_*
     *
     * @var string
     */
    public $indicator;

    /**
     * @var string
     */
    public $subCompoundType;

    /**
     * @var string
     */
    public $subCompoundDescription;
}
