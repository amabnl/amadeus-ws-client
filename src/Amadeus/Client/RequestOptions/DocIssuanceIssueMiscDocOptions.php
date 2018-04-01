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

use Amadeus\Client\RequestOptions\DocIssuance\CompoundOption;

/**
 * DocIssuance_IssueMiscellaneousDocuments Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DocIssuanceIssueMiscDocOptions extends Base
{
    const PAX_TYPE_ADULT = 'A';
    const PAX_TYPE_INFANT = 'IN';

    const OPTION_TEMPLATE_DISPLAY = 'TMD';
    const OPTION_TEMPLATE_OVERRIDE = 'TMO';
    const OPTION_EMD_ISSUANCE = 'ED';
    const OPTION_RETRIEVE_PNR = 'RT';
    const OPTION_BASIC_INVOICE = 'INV';
    const OPTION_BASIC_JOINT_INVOICE_ALL_PAX = 'IVJ';
    const OPTION_EXTENDED_INVOICE = 'INE';
    const OPTION_EXTENDED_INVOICE_ALL_PAX = 'INJ';
    const OPTION_CONCEALED_CREDIT_CARD = 'CCC';
    const OPTION_CREDIT_CARD_OVERRIDE = 'CCO';
    const OPTION_DOCUMENT_RECEIPT = 'TRP';
    const OPTION_ELECTRONIC_TICKETING_TCH = 'TKP';
    const OPTION_GROUP_ISSUANCE = 'GRP';
    const OPTION_PRINT_EXTENDED_ITINERARY = 'IEP';
    const OPTION_ITINERARY_RECEIPT = 'ITR';
    const OPTION_HOTEL_TERMS = 'HT';
    const OPTION_INHIBIT_CCCF_COUPON_PRINT = 'ICP';
    const OPTION_NO_CREDIT = 'NCC';
    const OPTION_OVERRIDE_NON_EXCHANGEABLE_IND = 'ONX';
    const OPTION_OVERRIDE_NAME_CHECK = 'ONC';
    const OPTION_PAPER_OVERRIDE = 'PT';
    const OPTION_FORCE_PASSENGER_RECEIPT = 'CR';
    const OPTION_PAYMENT_ON_DEMAND = 'POD';
    const OPTION_PRE_ISSUE_VALIDATION = 'TKT';
    const OPTION_PRESENT_CREDIT_CARD = 'PCC';

    /**
     * The Ticket Issuance options to be triggered
     *
     * self::OPTION::*
     *
     * CCC  Concealed Credit Card
     * CCO  Credit Card Override
     * CF   Force Credit Card Charge Form print
     * CI   Force Agent coupon print
     * CR   Force Passenger Receipt print
     * CU   Force Audit coupon print
     * ED   EMD issuance
     * GRP  Group Identifier
     * HT   Hotel Terms
     * IBJ  Print Basic Joint Itinerary
     * IBP  Print Basic Itinerary
     * ICP  Inhibit Credit Card Charge Form Coupon Print
     * IEJ  Print Extended Joint Itinerary
     * IEP  Print Extended Itinerary
     * IMP  Mini-Itinerary generation
     * INE  Individual Extended Invoice for each passenger
     * INJ  Print a single Extended Invoice for all passengers
     * INV  Print individual basic Invoice for each passenger
     * IPE  PNR Invoice
     * IPG  GAC Invoice
     * ITR  Itinerary Receipt
     * IVJ  Print a single basic invoice for all passengers
     * IWP  Itinerary Wallet print generation
     * NCC  NO CREDIT
     * ONC  Override Name Check
     * ONX  Override the non-exchangeable indicators
     * PCC  Present Credit Card Indicator
     * POD  Payment On Demand
     * PT   Paper override
     * RT   Retrieve PNR
     * TKT  Pre-issue validation
     * TMD  Template display
     * TMO  Template Override
     * TRP  Document receipt
     *
     * @var string[]
     */
    public $options = [];

    /**
     * The list of TSM numbers to be ticketed
     *
     * @var int[]
     */
    public $tsmNumbers = [];

    /**
     * The list of TSM tattoos to be ticketed
     *
     * @var int[]
     */
    public $tsmTattoos = [];

    /**
     * Line Number Selection
     *
     * @var int[]
     */
    public $lineNumbers = [];

    /**
     * Passenger numbers to be ticketed
     *
     * @var int[]
     */
    public $passengerNumbers = [];

    /**
     * Tattoos of passengers to be ticketed
     *
     * @var int[]
     */
    public $passengerTattoos = [];

    /**
     * PAX Passenger Type Selection
     *
     * self::PAX_TYPE_*
     *
     * @var string
     */
    public $passengerType;

    /**
     * Stock Reference
     *
     * @var string
     */
    public $stockReference;

    /**
     * Compound Options
     *
     * @var CompoundOption[]
     */
    public $compoundOptions = [];
}
