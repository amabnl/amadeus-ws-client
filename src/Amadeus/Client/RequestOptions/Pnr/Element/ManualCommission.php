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

namespace Amadeus\Client\RequestOptions\Pnr\Element;

use Amadeus\Client\RequestOptions\Pnr\Element;

/**
 * Manual Commission - FM element
 *
 * @package Amadeus\Client\RequestOptions\Pnr\Element
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ManualCommission extends Element
{
    const PAXTYPE_INFANT_WITHOUT_SEAT = 766;
    const PAXTYPE_INFANT_WITH_SEAT = 767;
    const PAXTYPE_CABIN_BAGGAGE = "C";
    const PAXTYPE_EXTRA_SEAT = "E";
    const PAXTYPE_GROUP = "G";
    const PAXTYPE_INFANT_NOT_OCCUPYING_A_SEAT = "INF";
    const PAXTYPE_MONTH = "MTH";
    const PAXTYPE_PASSENGER = "PAX";
    const PAXTYPE_YEAR = "YRS";

    const INDICATOR_COMMISSION = "FM";

    /**
     * Passenger type
     *
     * Choose from self::PAXTYPE_*
     *
     * @var string|int
     */
    public $passengerType;

    /**
     * Indicator
     *
     * @var string
     */
    public $indicator;

    /**
     * Commission percentage
     *
     * @var int
     */
    public $percentage;

    /**
     * Commission fixed amount
     *
     * @var int
     */
    public $amount;

    /**
     * VAT Indicator
     *
     * @var string
     */
    public $vatIndicator;

    /**
     * Net remit indicator
     *
     * @var string
     */
    public $remitIndicator;
}
