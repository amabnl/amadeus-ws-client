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

use Amadeus\Client\RequestOptions\Pay\AmountRange;
use Amadeus\Client\RequestOptions\Pay\Period;
use Amadeus\Client\RequestOptions\Pay\Reservation;

/**
 * PayListVirtualCardsOptions Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Konstantin Bogomolov <bog.konstantin@gmail.com>
 */
class PayListVirtualCardsOptions extends Base
{
    const SUBTYPE_DEBIT = 'Debit';
    const SUBTYPE_CREDIT = 'Credit';
    const SUBTYPE_PREPAID = 'Prepaid';

    const CARD_STATUS_ACTIVE = 'Active';
    const CARD_STATUS_INACTIVE = 'Inactive';
    const CARD_STATUS_DELETED = 'Deleted';

    /**
     * self::SUBTYPE_*
     *
     * @var string
     */
    public $SubType;

    /**
     * 2 characters of the vendor code of the card
     *
     * @var string
     */
    public $VendorCode;

    /**
     * @var AmountRange
     */
    public $AmountRange;

    /**
     * ISO 4217 currency code. Mandatory when AmountRange is present
     *
     * @var string
     */
    public $CurrencyCode;

    /**
     * Search cards created within a period of time
     *
     * @var Period
     */
    public $Period;

    /**
     * self::CARD_STATUS_*
     *
     * @var string
     */
    public $CardStatus;

    /**
     * @var Reservation
     */
    public $Reservation;
}
