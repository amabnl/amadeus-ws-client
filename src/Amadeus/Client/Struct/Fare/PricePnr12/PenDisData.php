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

namespace Amadeus\Client\Struct\Fare\PricePnr12;

/**
 * PenDisData
 *
 * @package Amadeus\Client\Struct\Fare\PricePnr12
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PenDisData
{
    const TYPE_BASE_FARE = 700;
    const TYPE_TOTAL_FARE = 701;
    const TYPE_TICKETS_NON_REFUNDABLE = 702;
    const TYPE_TICKETS_NON_REFUNDABLE_AFTER_DEPARTURE = 703;
    const TYPE_TICKETS_NON_REFUNDABLE_BEFORE_DEPARTURE = 706;
    const TYPE_PENALTIES_APPLY = 704;
    const TYPE_SUBJECT_TO_CANCELLATION_CHANGE_PENALTY = 705;
    const TYPE_EXEMPT_FEES = "EXF";
    const TYPE_INCLUDE_FEES = "INF";

    const QUALIFIER_FIXED_AMOUNT = 707;
    const QUALIFIER_PERCENTAGE = 708;

    /**
     * self::TYPE_*
     *
     * @var string
     */
    public $penaltyType;

    /**
     * self::QUALIFIER_*
     *
     * @var string
     */
    public $penaltyQualifier;

    /**
     * @var int
     */
    public $penaltyAmount;

    /**
     * @var string
     */
    public $discountCode;

    /**
     * @var string
     */
    public $penaltyCurrency;

    /**
     * PenDisData constructor.
     *
     * @param string $discountCode
     */
    public function __construct($discountCode)
    {
        $this->discountCode = $discountCode;
    }
}
