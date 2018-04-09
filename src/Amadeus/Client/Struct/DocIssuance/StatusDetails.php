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

namespace Amadeus\Client\Struct\DocIssuance;

/**
 * StatusDetails
 *
 * @package Amadeus\Client\Struct\DocIssuance
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class StatusDetails
{
    const OPTION_ETICKET = "ET";
    const OPTION_RETRIEVE_PNR = "RT";
    const OPTION_ITINERARY_RECEIPT = "ITR";
    const OPTION_PRE_ISSUE_VALIDATION = "TKT";
    const OPTION_PRINT_ITIN = "IBP";
    const OPTION_PRINT_JOINT_ITIN = "IBJ";
    const OPTION_PRINT_EXTENDED_ITIN = "IEP";
    const OPTION_PRINT_EXTENDED_JOINT_ITIN = "IEJ";
    const OPTION_TICKET_ONLY = "TKA";

    const OPTION_EMD_ISSUANCE = "ED";
    const OPTION_BASIC_INVOICE = "INV";
    const OPTION_BASIC_JOINT_INVOICE_ALL_PAX = "IVJ";
    const OPTION_EXTENDED_INVOICE = "INE";
    const OPTION_EXTENDED_INVOICE_ALL_PAX = "INJ";

    const OPTION_DOCUMENT_RECEIPT = "TRP";

    /**
     * CCC Concealed Credit Card
     * CCO Credit Card Override
     * ET Electronic Override
     * ETR ET Revalidation
     * EXC Exchange ticketing
     * GRP Group Identifier
     * HT Hotel Terms
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
     * PT Paper override
     * RT Retrieve PNR
     * TKA Ticket Only
     * TKP Electronic ticketing via TCH
     * TKT Pre-issue validation
     * TMD Template display
     * TMO Template Override
     * ZAA Send Accounting AIR
     *
     * @var string
     */
    public $indicator;

    /**
     * StatusDetails constructor.
     *
     * @param string $option
     */
    public function __construct($option)
    {
        $this->indicator = $option;
    }
}
