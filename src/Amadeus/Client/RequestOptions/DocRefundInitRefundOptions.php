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
 * DocRefund_InitRefund Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DocRefundInitRefundOptions extends Base
{
    const ACTION_ATC_REFUND = 'ATC';
    const ACTION_ATC_REFUND_INVOLUNTARY = 'ATI';
    const ACTION_COVER_ADDITIONAL_EXPENDITURE = 'COV';
    const ACTION_EMD_TICKET_NUMBER = 'EMD';
    const ACTION_INVOLUNTARY_NO_REASON = 'I';
    const ACTION_NON_REFUNDABLE_INDICATORS_BYPASS = 'NRF';
    const ACTION_NOT_REPORTED_REFUND = 'NRP';
    const ACTION_NO_SHOW = 'NS';
    const ACTION_ZERO_REFUND = 'NUL';
    const ACTION_HOLD_FOR_FUTURE_USE = 'RTF';
    const ACTION_TAXES = 'TAX';

    const TYPE_FROM_NUMBER = 'FRM';
    const TYPE_TRANSMISSION_CONTROL_NUMBER = 'TCN';
    const TYPE_TO_NUMBER = 'TO';

    /**
     * The number of the ticket or document to be refunded
     *
     * @var string
     */
    public $ticketNumber;

    /**
     * The refund option(s) to be applied
     *
     * see self::ACTION_*
     *
     * @var string|string[]
     */
    public $actionCodes = [];

    /**
     * Item Number
     *
     * @var string|int
     */
    public $itemNumber;

    /**
     * Type of the Item number.
     *
     * self::TYPE_*
     *
     * @var string
     */
    public $itemNumberType;

    /**
     * Coupon Number
     *
     * @var int|string
     */
    public $couponNumber;
    
    /**
     * Stock Type Code
     *
     * @var int|string
     */
    public $stockTypeCode;
    
    /**
     * Stock Provider
     *
     * @var int|string
     */
    public $stockProvider;
}
