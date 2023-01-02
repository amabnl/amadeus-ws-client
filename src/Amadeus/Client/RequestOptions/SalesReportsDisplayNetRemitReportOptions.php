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

/**
 * SalesReports_DisplayNetRemitReportOptions Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class SalesReportsDisplayNetRemitReportOptions extends Base
{
    /**
     * All agents of the office
     */
    const SELECT_OFFICE_ALL_AGENTS = 'SOF';
    /**
     * All offices sharing the same IATA number
     */
    const SELECT_ALL_OFFICES_SHARING_IATA_NR = 'SAN';
    /**
     * Satellite ticket office
     */
    const SELECT_SATELLITE_TICKET_OFFICE = 'STP';
    /**
     * Ticket delivery office
     */
    const SELECT_TICKET_DELIVERY_OFFICE = 'TDO';
    /**
     * by net remit qualifier
     */
    const SELECT_BY_NET_REMIT = 'BNR';

    /**
     * Reporting Office
     */
    const AGENCY_SRC_REPORTING_OFFICE = 'REP';
    /**
     * STP office
     */
    const AGENCY_SRC_STP_OFFICE = 'STP';
    /**
     * TDO office
     */
    const AGENCY_SRC_TDO_OFFICE = 'TDO';

    const DATE_TYPE_CURRENT = 'C';
    const DATE_TYPE_SALES_REPORT_CLOSURE = 'D';
    const DATE_TYPE_ISSUANCE = 'I';
    const DATE_TYPE_REFUNDING = 'R';
    const DATE_TYPE_SPECIFIC = 'S';

    const CURRENCY_TARGET = 3;
    const CURRENCY_REFERENCE = 2;

    /**
     * On behalf of/in exchange for a document previously issued by a Sales Agent
     */
    const FOP_ISSUED_BY_AGENT = 'AGT';
    /**
     * Cash
     */
    const FOP_CASH = 'CA';
    /**
     * Credit Card
     */
    const FOP_CREDIT_CARD = 'CC';
    /**
     * Check
     */
    const FOP_CHECK = 'CK';
    /**
     * Government transportation request
     */
    const FOP_GOVERNMENT_TRANSPORTATION_REQ = 'GR';
    /**
     * Miscellaneous
     */
    const FOP_MISCELLANEOUS = 'MS';
    /**
     * Non-refundable (refund restricted)
     */
    const FOP_NON_REFUNDABLE = 'NR';
    /**
     * Prepaid Ticket Advice (PTA)
     */
    const FOP_PREPAID_TICKET_ADVICE = 'PT';
    /**
     * Single government transportation request
     */
    const FOP_SINGLE_GOVERNMENT_TRANSPORTATION_REQ = 'SGR';
    /**
     * United Nations Transportation Request
     */
    const FOP_UNITED_NATIONS_TRANSPORTATION_REQ = 'UN';

    /**
     * Domestic sale
     */
    const SALESIND_DOMESTIC = 'DOM';
    /**
     * International sale
     */
    const SALESIND_INTERNATIONAL = 'INT';
    /**
     * Voided document
     */
    const SALESIND_VOIDED_DOCUMENT = 'V';

    /**
     * Request options
     *
     * self::SELECT_*
     *
     * @var string[]
     */
    public $requestOptions = [];

    /**
     * Agency Source type
     *
     * self::AGENCY_SRC_*
     *
     * @var string
     */
    public $agencySourceType;
    /**
     * The IATA number for the agency you want reporting on
     *
     * @var string
     */
    public $agencyIataNumber;
    /**
     * The Amadeus Office ID for the agency you want reporting on
     *
     * @var string
     */
    public $agencyOfficeId;
    /**
     * The specific Agent sign for which you want reporting
     *
     * @var string
     */
    public $agentCode;
    /**
     * The Transaction code for which you want reporting
     *
     * Possible values:
     * ACMA Agency credit memo
     * ACMR Refund credit memo
     * ADMA Agency debit memo
     * BPAS Boarding-Pass-Only
     * CANN Cancelled computer generated ticket number
     * CANR Cancellation with reversal
     * CANX Cancelled document
     * EM50 Miscelaneous charges order - Electronic
     * MCOA Automated MCO
     * MCOM Miscelaneous Charges order - Manual
     * MD50 Miscelaneous charges order - Automated
     * PSCN Printed stock control number
     * PTAM Prepaid ticket advice - Manual
     * RCSM Commission recall statement
     * RENA Automated Refund and Exchange Notice
     * RENM Manual Refund and Exchange Notice
     * RFND Refund
     * RSCN Regenarated stock control number
     * SSAC Sales summary adjustment notice - due to agent
     * SSAD sales summary adjustment notice - due to airline
     * TAAD Travel agent automated deduction
     * TASF Travel Agency service fee
     * TKTA OPTAT sale - Automated
     * TKTB OPATB sale - Automated
     * TKTM Manual sale
     * TKTT Electronic ticketing sale - Automated
     * TORM Tour order - Manual
     * VSCN Voided stock control number
     * XSBA Excess baggage Ticket - Automated
     * XSBM Excess baggage Ticket - Manual
     *
     * @var string
     */
    public $transactionCode;
    /**
     * Transaction type
     *
     * Possible values:
     * ADJA Adustment for Agent
     * ADJP Adjustment for provider
     * AUTS Automated sale document
     * CCAS Cancelled cash sale document
     * CCCS Cancelled credit card sale document
     * INVT Inventory Document
     * MANS Manual sale document
     * REFD Refund document
     * SALE Sale document
     * VOID Voided document
     *
     * @var string
     */
    public $transactionType;

    /**
     * Transaction Issue Indicator
     *
     * Possible values:
     * C Current transaction code
     * O Original transaction code
     *
     * @var string
     */
    public $transactionIssueIndicator;
    /**
     * IATA Airline code for validating carier
     *
     * @var string
     */
    public $validatingCarrier;
    /**
     * Start date of reporting
     *
     * @var \DateTime
     */
    public $startDate;
    /**
     * End date of reporting
     *
     * @var \DateTime
     */
    public $endDate;
    /**
     * Get Reporting from a specific date
     *
     * @var \DateTime
     */
    public $specificDate;
    /**
     * How to interpret the specificDate parameter
     *
     * self::DATE_TYPE_*
     *
     * @var string
     */
    public $specificDateType;
    /**
     * self::CURRENCY_*
     *
     * @var string
     */
    public $currencyType;
    /**
     * The currency to report on
     * @var string
     */
    public $currency;
    /**
     * Form of Payment Type
     *
     * self::FOP_*
     *
     * @var string
     */
    public $fopType;
    /**
     * Form of Payment Vendor code
     *
     * @var string
     */
    public $fopVendor;
    /**
     * Document Info
     *
     * self::SALESIND_*
     *
     * @var string
     */
    public $documentInfo;
}
