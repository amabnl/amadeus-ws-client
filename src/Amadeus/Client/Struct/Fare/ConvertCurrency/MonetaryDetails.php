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
 * MonetaryDetails
 *
 * @package Amadeus\Client\Struct\Fare\ConvertCurrency
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MonetaryDetails
{
    /**
     * Type qualifier Base Fare
     *
     * See documentation of Fare_ConvertCurrency
     * (Monetary amount type qualifier codesets (Ref: 5025 1A 07.1.4))
     */
    const BASE_FARE = 'B';

    /**
     * @var string
     */
    public $typeQualifier = self::BASE_FARE;

    /**
     * @var int|float
     */
    public $amount;

    /**
     * @var string
     */
    public $currency;

    /**
     * Construct MonetaryDetails
     *
     * @param int|float $amount
     */
    public function __construct($amount)
    {
        $this->amount = $amount;
    }
}
