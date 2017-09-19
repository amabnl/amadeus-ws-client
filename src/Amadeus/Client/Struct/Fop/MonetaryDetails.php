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

namespace Amadeus\Client\Struct\Fop;

/**
 * MonetaryDetails
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MonetaryDetails
{
    const TYPE_TOTAL_FARE_AMOUNT = "712";
    const TYPE_ADDITIONAL_COLLECTION_AMOUNT = "A";
    const TYPE_AUTHORIZED_AMOUNT = "AUT";
    const TYPE_BALANCE = "BAL";
    const TYPE_TRANSACTION_TOTAL_AMOUNT = "I";
    const TYPE_TRANSACTION_TOTAL_AMOUNT_IN_PNR_CURRENCY = "IPC";
    const TYPE_FIRST_INSTALMENT_AMOUNT = "ISF";
    const TYPE_INSTALMENT_INTEREST = "ISI";
    const TYPE_FOLLOWING_INSTALMENT_AMOUNT = "ISN";
    const TYPE_INITIAL_TST_TOTAL_AMOUNT = "IT";
    const TYPE_INITIAL_TOTAL_AMOUNT_IN_PNR_CURRENCY = "ITC";
    const TYPE_MILES = "MIL";
    const TYPE_PENALTY = "PEN";
    const TYPE_TOTAL_AMOUNT_REMAINING_AMOUNT = "R";
    const TYPE_REFUNDABLE_AMOUNT = "REF";
    const TYPE_REUSABLE_AMOUNT = "REU";
    const TYPE_INITIAL_TST_INDIVIDUAL_AMOUNT = "T";
    const TYPE_INITIAL_TST_INDIVIDUAL_AMOUNT_TPC = "TPC";

    /**
     * self::QUAL_*
     *
     * @var string
     */
    public $typeQualifier;

    /**
     * @var int|string|double
     */
    public $amount;

    /**
     * @var string
     */
    public $currency;

    /**
     * Construct MonetaryDetails
     *
     * @param int|double $amount
     * @param string $currency 3-character currency code
     * @param string $type self::TYPE_*
     */
    public function __construct($amount, $currency, $type)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->typeQualifier = $type;
    }
}
