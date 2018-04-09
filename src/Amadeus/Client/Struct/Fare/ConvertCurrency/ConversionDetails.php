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
 * ConversionDetails
 *
 * @package Amadeus\Client\Struct\Fare\ConvertCurrency
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ConversionDetails
{
    /**
     * @var ConversionDirection
     */
    public $conversionDirection;
    /**
     * @var CurrencyInfo
     */
    public $currencyInfo;
    /**
     * @var AmountInfo
     */
    public $amountInfo;
    /**
     * @var mixed
     */
    public $locationInfo;

    /**
     * Create new ConversionDetails
     *
     * @param string|null $convertOption (OPTIONAL) SelectionDetails::OPTION_CONVERT_*
     * @param string|null $currency
     * @param int|null $amount
     */
    public function __construct($convertOption = null, $currency = null, $amount = null)
    {
        $this->conversionDirection = new ConversionDirection($convertOption);

        $this->currencyInfo = new CurrencyInfo($currency);

        if (!is_null($amount)) {
            $this->amountInfo = new AmountInfo($amount);
        }
    }
}
