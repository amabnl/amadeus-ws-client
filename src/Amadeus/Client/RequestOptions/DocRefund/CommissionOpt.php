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

namespace Amadeus\Client\RequestOptions\DocRefund;

use Amadeus\Client\LoadParamsFromArray;

/**
 * CommissionOpt
 *
 * @package Amadeus\Client\RequestOptions\DocRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CommissionOpt extends LoadParamsFromArray
{
    const TYPE_CANCELLATION_PENALTY = "CP";
    const TYPE_CANCELLATION_PENALTY_IN_MILES = "CPM";
    const TYPE_AIRLINE_COMMISSION_A = "FMA";
    const TYPE_AIRLINE_COMMISSION_B = "FMB";
    const TYPE_NEW_COMMISSION = "NEW";
    const TYPE_OLD_COMMISSION = "OLD";
    const TYPE_COMMISSION_ON_CANCELLATION_PENALTY = "XLP";

    /**
     * Commission Type
     *
     * self::TYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * Commission Amount
     *
     * @var float|int
     */
    public $amount;

    /**
     * Free text
     *
     * @var string
     */
    public $freeText;

    /**
     * Commission Rate
     *
     * @var float|int
     */
    public $rate;
}
