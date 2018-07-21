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

namespace Amadeus\Client\Struct\Fare\ConvertCurrency;

/**
 * ConversionRateDetails
 *
 * @package Amadeus\Client\Struct\Fare\ConvertCurrency
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ConversionRateDetails
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
     * self::RATE_TYPE_*
     *
     * @var string
     */
    public $rateType;

    /**
     * Amount to convert
     *
     * @var double
     */
    public $rate;

    /**
     * ConversionRateDetails constructor.
     *
     * @param string $conversionType
     * @param double|null $amount
     */
    public function __construct($conversionType, $amount = null)
    {
        $this->rateType = $conversionType;
        $this->rate = $amount;
    }
}
