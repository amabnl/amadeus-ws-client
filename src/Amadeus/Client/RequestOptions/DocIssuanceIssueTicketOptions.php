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

use Amadeus\Client\RequestOptions\DocIssuance\Option;
use Amadeus\Client\RequestOptions\DocIssuance\CompoundOption;

/**
 * DocIssuanceIssueTicketOptions
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DocIssuanceIssueTicketOptions extends Base
{
    const PAX_TYPE_ADULT = 'A';
    const PAX_TYPE_INFANT = 'IN';

    const OPTION_ETICKET = 'ET';
    const OPTION_RETRIEVE_PNR = 'RT';
    const OPTION_ITINERARY_RECEIPT = 'ITR';
    const OPTION_PRE_ISSUE_VALIDATION = 'TKT';
    const OPTION_PRINT_ITIN = 'IBP';
    const OPTION_PRINT_JOINT_ITIN = 'IBJ';
    const OPTION_PRINT_EXTENDED_ITIN = 'IEP';
    const OPTION_PRINT_EXTENDED_JOINT_ITIN = 'IEJ';
    const OPTION_TICKET_ONLY = 'TKA';
    const OPTION_ETICKET_REVALIDATION = 'ETR';

    /**
     * The Ticket Issuance options to be triggered
     *
     * self::OPTION::* or an array of Option objects
     *
     * CCC Concealed Credit Card
     * CCO Credit Card Override
     * ET  Electronic Override
     * ETR ET Revalidation
     * EXC Exchange ticketing
     * GRP Group Identifier
     * HT  Hotel Terms
     * IBJ Print Basic Joint Itinerary
     * IBP Print Basic Itinerary
     * ICP Inhibit Credit Card Charge Form Coupon Print
     * IEJ Print Extended Joint Itinerary
     * IEP Print Extended Itinerary
     * IMP Mini-Itinerary generation
     * INE Individual Extended Invoice for each passenger
     * INF Infant select
     * INJ Print a single Extended Invoice for all passengers
     * INV Print individual basic Invoice for each passenger
     * IPE PNR Invoice
     * IPG GAC Invoice
     * IRI Involuntary Rerouting Indicator
     * ISH Inhibit shift
     * ITR Itinerary Receipt
     * IVJ Print a single basic invoice for all passengers
     * IWP Itinerary Wallet print generation
     * NCC NO CREDIT
     * OET Force electronic
     * ONC Override Name Check
     * OPT Force Paper
     * OVR Revalidation Override
     * PCC Present Credit Card
     * POD Payment On Demand
     * PT  Paper override
     * RT  Retrieve PNR
     * TKA Ticket Only
     * TKP Electronic ticketing via TCH
     * TKT Pre-issue validation
     * TMD Template display
     * TMO Template Override
     * ZAA Send Accounting AIR
     *
     * TTP/<OPTION>
     *
     * @var string[]|Option[]
     */
    public $options = [];

    /**
     * Agent Code to be printed
     *
     * TTP/A23G7
     *
     * @var string
     */
    public $agentCode;

    /**
     * Alternate date validation
     *
     * TTP/OD23NOV09
     *
     * @var \DateTime
     */
    public $alternateDateValidation;

    /**
     * Override Past Date TST?
     *
     * TTP/O
     *
     * @var bool
     */
    public $overridePastDateTst = false;

    /**
     * The list of TST's numbers to be ticketed
     *
     * TTP/T1
     *
     * @var int[]
     */
    public $tsts = [];

    /**
     * Tattoos of segments to be ticketed
     *
     * TTP/S1
     *
     * @var int[]
     */
    public $segmentTattoos = [];

    /**
     * Tattoos of passengers to be ticketed
     *
     * TTP/P1
     *
     * @var int[]
     */
    public $passengerTattoos = [];

    /**
     * Line numbers (for FOP line number in case of revalidation)
     *
     * @var int[]
     */
    public $lineNumbers = [];

    /**
     * Coupon numbers (for coupon number in case of revalidation)
     *
     * @var int[]
     */
    public $couponNumbers = [];
    
    /**
     * PAX Passenger Type Selection
     *
     * self::PAX_TYPE_*
     *
     * @var string
     */
    public $passengerType;

    /**
     * Compound Options
     *
     * @var CompoundOption[]
     */
    public $compoundOptions = [];
}
