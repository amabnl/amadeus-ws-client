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

namespace Amadeus\Client\Struct\DocRefund\UpdateRefund;

/**
 * FormOfPayment
 *
 * @package Amadeus\Client\Struct\DocRefund\UpdateRefund
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class FormOfPayment
{
    const TYPE_ON_BEHALF_OF_AGENT = "AGT";
    const TYPE_CASH = "CA";
    const TYPE_CREDIT_CARD = "CC";
    const TYPE_CHECK = "CK";
    const TYPE_GOVERNMENT_TRANSPORTATION_REQUEST = "GR";
    const TYPE_MISCELLANEOUS = "MS";
    const TYPE_NON_REFUNDABLE = "NR";
    const TYPE_PREPAID_TICKET_ADVICE = "PT";
    const TYPE_SINGLE_GOVERNMENT_TRANSPORTATION_REQUEST = "SGR";
    const TYPE_UNITED_NATIONS_TRANSPORTATION_REQUEST = "UN";

    const SOURCE_IRU = "MIL";

    /**
     * self::TYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * @var int
     */
    public $amount;

    /**
     * self::SOURCE_*
     *
     * @var string
     */
    public $sourceOfApproval;

    /**
     * @var int
     */
    public $authorisedAmount;
}
