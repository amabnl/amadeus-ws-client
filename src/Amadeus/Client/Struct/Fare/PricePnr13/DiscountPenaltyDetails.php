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

namespace Amadeus\Client\Struct\Fare\PricePnr13;

/**
 * DiscountPenaltyDetails
 *
 * @package Amadeus\Client\Struct\Fare\PricePnr13
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DiscountPenaltyDetails
{
    const FUNCTION_BASE_FARE = 700;
    const FUNCTION_TOTAL_FARE = 701;
    const FUNCTION_PENALTIES_APPLY = 704;
    const FUNCTION_EXCLUDE_FEE = "EXF";
    const FUNCTION_INCLUDE_FEE = "INF";

    const AMOUNTTYPE_FIXED_WHOLE_AMOUNT = 707;
    const AMOUNTTYPE_PERCENTAGE = 708;

    /**
     * self::FUNCTION_*
     *
     * @var string
     */
    public $function;

    /**
     * self::AMOUNTTYPE_*
     *
     * @var string
     */
    public $amountType;

    /**
     * @var string
     */
    public $amount;

    /**
     * @var string
     */
    public $rate;

    /**
     * @var string
     */
    public $currency;

    /**
     * DiscountPenaltyDetails constructor.
     *
     * @param string $rate
     * @param string|null $function
     * @param string|null $amountType
     * @param string|null $amount
     * @param string|null $currency
     */
    public function __construct($rate, $function = null, $amountType = null, $amount = null, $currency = null)
    {
        $this->rate = $rate;
        $this->function = $function;
        $this->amountType = $amountType;
        $this->amount = $amount;
        $this->currency = $currency;
    }
}
