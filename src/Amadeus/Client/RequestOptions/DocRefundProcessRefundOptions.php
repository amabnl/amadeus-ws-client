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
 * DocRefund_ProcessRefund Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DocRefundProcessRefundOptions extends Base
{
    const STATUS_INHIBIT_REFUND_NOTICE = 'IRN';
    const STATUS_REFUND_REVIEW_OPTION = 'REV';

    const PRINTERTYPE_PRINTER_MNEMONIC = 'MNE';
    const PRINTERTYPE_RULE_ID = 'RID';
    const PRINTERTYPE_SETTLEMENT_AUTHORIZATION_CODE = 'SAC';
    const PRINTERTYPE_PRINTER_STOCK = 'STK';
    const PRINTERTYPE_TERMINAL_ID = 'TRM';

    /**
     * self::STATUS_*
     *
     * @var string[]
     */
    public $statusIndicators = [];

    /**
     * Printer type
     *
     * self::PRINTERTYPE_*
     *
     * @var string
     */
    public $printerType;

    /**
     * Printer reference
     *
     * @var string
     */
    public $printer;

    /**
     * Refunded itinerary information
     *
     * @var DocRefund\RefundItinOpt[]
     */
    public $refundedItinerary = [];

    /**
     * Set to true to send the refund notice at commit or resend time
     * to the e-mail address stored in the PNR element APE
     *
     * @var bool
     */
    public $sendNotificationToEmailInAPE = false;

    /**
     * Set to true to send the refund notice at commit or resend time
     * to the fax number stored in the PNR element APF
     *
     * @var bool
     */
    public $sendNotificationToFaxInAPF = false;

    /**
     * List of up to 3 e-mail addresses to send refund notices to.
     *
     * @var string[]
     */
    public $refundNoticesEmailAddresses = [];

    /**
     * List of up to 3 fax numbers to send refund notices to.
     *
     * Keys are the numbers, values are area codes
     *
     * @var array
     */
    public $refundNoticesFaxes = [];
}
