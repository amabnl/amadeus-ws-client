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
 * Fare_ConvertCurrency Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FareConvertCurrencyOptions extends Base
{
    /**
     * All the rates (ICH, BBR, BSR)
     */
    const RATE_TYPE_ALL = 'ALL';
    /**
     * Bankers seller rate
     */
    const RATE_TYPE_BANKERS_SELLER_RATE = 'BSR';
    /**
     * IATA ROE
     */
    const RATE_TYPE_IATA_ROE = 'ROE';
    /**
     * User specified rate (agent override rate)
     */
    const RATE_TYPE_USER_SPECIFIED = 'USR';
    /**
     * IATA clearinghouse rate (ICH)
     */
    const RATE_TYPE_IATA_CLEARINGHOUSE = '700';

    /**
     * @var double
     */
    public $amount;

    /**
     * Convert from this currency (ISO 4217 currency code)
     *
     * @var string
     */
    public $from;

    /**
     * Convert to this currency (ISO 4217 currency code)
     *
     * @var string
     */
    public $to;

    /**
     * For historical currency conversions
     *
     * MAX 12 MONTHS IN THE PAST
     *
     * @var \DateTime
     */
    public $date;

    /**
     * What type of currency conversion to use
     *
     * Bankers Sellers Rate is most often used.
     *
     * @var string
     */
    public $rateOfConversion = self::RATE_TYPE_BANKERS_SELLER_RATE;
}
