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
 * PayGenerateVirtualCardOptions Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Konstantin Bogomolov <bog.konstantin@gmail.com>
 */
class PayGenerateVirtualCardOptions extends Base
{
    const SUBTYPE_DEBIT = 'DEBIT';
    const SUBTYPE_CREDIT = 'CREDIT';
    const SUBTYPE_PREPAID = 'PREPAID';
    const SUBTYPE_FALLBACK = 'FALLBACK';

    const VENDOR_VISA = 'VI';
    const VENDOR_MASTERCARD = 'CA';

    /**
     * Friendly name of the card. Should be unique. Can be used for reporting purpose to uniquely identify a card.
     * @var string
     */
    public $CardName;

    /**
     * self::SUBTYPE_*
     * @var string
     */
    public $SubType;

    /**
     * @var string
     */
    public $VendorCode;

    /**
     * @var boolean
     */
    public $ReturnCVV;

    /**
     * @var integer
     */
    public $Amount;

    /**
     * @var integer
     */
    public $DecimalPlaces;

    /**
     * @var string
     */
    public $CurrencyCode;

    /**
     * @var integer
     */
    public $maxAlowedTransactions;

    /**
     * Date YYYY-MM-DD
     * @var string
     */
    public $endValidityPeriod;
}
