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

namespace Amadeus\Client\Struct\Fop;

/**
 * FopDetails
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FopDetails
{
    const BILLING_CASH = "CA";
    const BILLING_CREDIT = "CC";

    const STATUS_NEW = "N";
    const STATUS_OLD = "O";


    /**
     * @var string
     */
    public $fopCode;

    /**
     * @var string
     */
    public $fopMapTable;

    /**
     * self::BILLING_*
     *
     * @var string
     */
    public $fopBillingCode;

    /**
     * self::STATUS_*
     *
     * @var string
     */
    public $fopStatus;

    /**
     * AA Qantas FOP A
     * AC Qantas FOP ACT
     * BA German Market sub-element Bank Account form of payment for Insurance Application
     * CA Cash
     * CC Credit Card (plus 2 digits vendor code)
     * CK Check
     * FF Qantas FOP FFR
     * IN Qantas FOP INV
     * MC Qantas FOP MCO
     * MS Miscellaneous
     * NB Qantas Non Bankable Credit Card (plus 2 digits vendor code)
     * PP Qantas P FOP
     * PT Prepaid Ticket Advice (PTA)
     * QT Qantas QTA FOP
     * QU Qantas QU FOP
     * RE Qantas REC FOP
     * RN Qantas RND FOP
     * SA Iberia/Savia SF fop for cash
     * TD Qantas TD FOP
     *
     * @var string
     */
    public $fopEdiCode;

    /**
     * BR Barter (AY ATO/CTO specific)
     * CA Cash
     * CC Credit
     * GA Global Accounting
     * HO Head Office credit (BA ATO/CTO specific)
     * LC Local credit (BA ATO/CTO specific)
     * MS Miscellaneous
     * NR Net remit
     *
     * @var string
     */
    public $fopReportingCode;

    /**
     * @var string
     */
    public $fopPrintedCode;

    /**
     * AGT On behalf of/in exchange for a document previously issued by a Sales Agent
     * CA  Cash
     * CC  Credit Card
     * CK  Check
     * DP  Direct Deposit
     * GR  Government transportation request
     * MS  Miscellaneous
     * NR  Non-refundable (refund restricted)
     * PT  Prepaid Ticket Advice (PTA)
     * SGR Single government transportation request
     * UN  United Nations Transportation Request
     *
     * @var string
     */
    public $fopElecTicketingCode;

    /**
     * FopDetails constructor.
     *
     * @param string $fopCode
     * @param string|null status
     */
    public function __construct($fopCode, $status)
    {
        $this->fopCode = $fopCode;
        $this->fopStatus = $status;
    }
}
