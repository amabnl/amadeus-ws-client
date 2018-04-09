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

namespace Amadeus\Client\RequestOptions\Fare\PricePnr;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Ob Fees
 *
 * @package Amadeus\Client\RequestOptions\Fare\PricePnr
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ObFee extends LoadParamsFromArray
{
    const AMOUNTTYPE_FIXED_WHOLE_AMOUNT = 707;
    const AMOUNTTYPE_PERCENTAGE = 708;

    const FUNCTION_INCLUDE = "INF";
    const FUNCTION_EXCLUDE = "EXF";

    /**
     * Include (true) or Exclude (false)
     *
     * @var bool
     */
    public $include = true;

    /**
     * If fixed amount: The amount of the OB Fee
     *
     * @var int
     */
    public $amount;

    /**
     * If percentage: The percentage of the OB Fee
     *
     * @var int
     */
    public $percentage;

    /**
     * Currency in which $amount is expressed
     *
     * @var string
     */
    public $currency;

    /**
     * Fee rate (FCA, T01,...)
     *
     * @var string
     */
    public $rate;
}
