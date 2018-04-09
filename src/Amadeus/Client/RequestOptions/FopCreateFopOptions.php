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

use Amadeus\Client\RequestOptions\Fop\Group;

/**
 * Fop_CreateFormOfPayment Request options.
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FopCreateFopOptions extends Base
{
    /**
     * Perform Authorization on Ticket & MCO/EMD
     */
    const TRANS_AUTH_ON_TICKET_MCO_EMD = 'DEF';
    /**
     * Perform Authorization on MCO/EMD
     */
    const TRANS_AUTH_ON_MCO_EMD = 'DEFM';
    /**
     * Perform Authorization on Ticket
     */
    const TRANS_AUTH_ON_TICKET = 'DEFP';
    /**
     * DEFX Transaction
     */
    const TRANS_DEFX = 'DEFX';
    /**
     * Create form of payment only
     */
    const TRANS_CREATE_FORM_OF_PAYMENT = 'FP';

    /**
     * create FOP(s) in PNR even if Authorization failed
     */
    const BESTEFFORT_IND_CREATE_FOP_IF_AUTH_FAILS = 'CFP';
    /**
     * Split pricing record
     */
    const BESTEFFORT_IND_SPLIT_PRICING_RECORD = 'SPT';
    /**
     * Confirmation
     */
    const BESTEFFORT_ACT_CONFIRM = 'KK';
    /**
     * Refusal
     */
    const BESTEFFORT_ACT_REFUSE = 'UU';


    /**
     * Transaction code
     *
     * self::TRANS_*
     *
     * @var string
     */
    public $transactionCode;

    /**
     * Trigger OB fee calculation with Pricing options?
     *
     * @var bool
     */
    public $obFeeCalculation = false;

    /**
     * If you want to use best effort processing status, provide indicator and action code.
     *
     * self::BESTEFFORT_IND_*
     *
     * @var string
     */
    public $bestEffortIndicator;

    /**
     * Best effort action code.
     *
     * self::BESTEFFORT_ACT_*
     *
     * @var string
     */
    public $bestEffortAction;

    /**
     * Group of up to 127 different FOPs
     *
     * @var Group[]
     */
    public $fopGroup = [];
}
