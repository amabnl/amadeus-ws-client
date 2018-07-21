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

namespace Amadeus\Client\RequestOptions\Fop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Mean Of Payment Information
 *
 * @package Amadeus\Client\RequestOptions\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MopInfo extends LoadParamsFromArray
{
    const STATUS_NEW = "N";
    const STATUS_OLD = "O";

    const ENCODING_MUTUAL = "ZZZ";

    const FOPTYPE_AUTHORIZATION_ONLY = "AO";
    const FOPTYPE_FC_ELEMENT = "FC";
    const FOPTYPE_FP_ELEMENT = "FP";
    const FOPTYPE_PAY_ELEMENT = "PAY";


    const MOP_PAY_TYPE_ACCOUNT_PAYMENT = "ACC";
    const MOP_PAY_TYPE_PREVIOUSLY_ISSUED_BY_SALES_AGENT = "AGT";
    const MOP_PAY_TYPE_AMOP_PAYMENT = "AMP";
    const MOP_PAY_TYPE_CASH = "CA";
    const MOP_PAY_TYPE_CREDIT_CARD = "CC";
    const MOP_PAY_TYPE_CHECK = "CK";
    const MOP_PAY_TYPE_DIRECT_DEBIT_FOP_TYPE = "ELV";
    const MOP_PAY_TYPE_GOVERNMENT_TRANSPORTATION_REQUEST = "GR";
    const MOP_PAY_TYPE_INVOICE = "INV";
    const MOP_PAY_TYPE_MISCELLANEOUS = "MS";
    const MOP_PAY_TYPE_NONREFUNDABLE = "NR";
    const MOP_PAY_TYPE_PREPAID_TICKET_ADVICE = "PT";
    const MOP_PAY_TYPE_SINGLE_GOVERNMENT_TRANSPORTATION_REQUEST = "SGR";
    const MOP_PAY_TYPE_UNITED_NATIONS_TRANSPORTATION_REQUEST = "UN";
    const MOP_PAY_TYPE_WEB_REDIRECTION_ACCOUNT_PAYMENT = "WA";
    const MOP_PAY_TYPE_WEB_REDIRECTION_FUND_TRANSFER_PAYMENT = "WF";


    /**
     * FOP position in the FP line.
     *
     * Must be set to 1 if there is only 1 FOP in the FP line.
     * Must be set to 0 if this is an old FOP
     *
     * @var int
     */
    public $sequenceNr;

    /**
     * FOP Status (New or Old)
     *
     * self::STATUS_*
     *
     * @var string
     */
    public $fopStatus;

    /**
     * Form Of Payment Code
     *
     * @var string
     */
    public $fopCode;

    /**
     * To create a Form Of Payment in an unstructured way using old Free Flow text
     *
     * @var string
     */
    public $freeFlowText;

    /**
     * If unstructured free flow is used: which encoding?
     *
     * @var string
     */
    public $freeFlowEncoding = self::ENCODING_MUTUAL;

    /**
     * Structured freeflow Data
     *
     * @var DataOrSwitch[]
     */
    public $supplementaryData = [];
    /**
     * Structured freeflow Switch
     *
     * @var DataOrSwitch[]
     */
    public $supplementarySwitches = [];

    /**
     * self::FOPTYPE_*
     *
     * @var string
     */
    public $fopType;

    /**
     * The Merchant company
     *
     * @var string
     */
    public $payMerchant;

    /**
     * @var \DateTime
     */
    public $transactionDate;

    /**
     * @var Payment[]
     */
    public $payments = [];

    /**
     * @var InstallmentsInfo
     */
    public $installmentsInfo;

    /**
     * self::MOP_PAY_TYPE_*
     *
     * @var string
     */
    public $mopPaymentType;

    /**
     * @var CreditCardInfo
     */
    public $creditCardInfo;

    /**
     * @var InvoiceInfo
     */
    public $invoiceInfo;

    /**
     * @var FraudScreeningOptions
     */
    public $fraudScreening;

    /**
     * @var PayId[]
     */
    public $payIds = [];

    /**
     * PaymentSupplementaryData
     *
     * @var PaySupData[]
     */
    public $paySupData = [];
}
