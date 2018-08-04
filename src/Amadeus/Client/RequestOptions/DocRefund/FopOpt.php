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
 * Form of Payment options
 *
 * @package Amadeus\Client\RequestOptions\DocRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FopOpt extends LoadParamsFromArray
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
     * Form of Payment Type
     *
     * self::TYPE_*
     *
     * @var string
     */
    public $fopType;

    /**
     * Amount
     *
     * @var int|float
     */
    public $fopAmount;

    /**
     * Source of Approval
     *
     * self::SOURCE_*
     *
     * @var string
     */
    public $fopSourceOfApproval;

    /**
     * Authorized amount
     *
     * @var int|float
     */
    public $fopAuthorizedAmount;

    /**
     * Interactive Free Text
     *
     * @var FreeTextOpt[]
     */
    public $freeText = [];
}
