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

namespace Amadeus\Client\Struct\Offer\Create;

/**
 * MonetaryDetails
 *
 * @package Amadeus\Client\Struct\Offer\Create
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MonetaryDetails
{
    const QUAL_TAX_EXCLUDED = "EXC";
    const QUAL_GRAND_TOTAL = "GT";
    const QUAL_TAX_INCLUDED = "INC";
    const QUAL_MARKUP_AMOUNT = "MK";
    const QUAL_TAX_INCLUSION_UNKNOWN = "UNK";

    /**
     * self::QUAL_*
     *
     * @var string
     */
    public $typeQualifier;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var string
     */
    public $currency;

    /**
     * MonetaryDetails constructor.
     *
     * @param int $amount
     * @param string $currency
     * @param string $typeQualifier self::QUAL_*
     */
    public function __construct($amount, $currency, $typeQualifier = self::QUAL_MARKUP_AMOUNT)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->typeQualifier = $typeQualifier;
    }
}
