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

namespace Amadeus\Client\RequestOptions\Fop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * CreditCardInfo
 *
 * @package Amadeus\Client\RequestOptions\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CreditCardInfo extends LoadParamsFromArray
{
    const APPROVAL_SOURCE_AUTOMATIC = "A";
    const APPROVAL_SOURCE_MANUAL_SETTLEMENT = "B";
    const APPROVAL_SOURCE_AUTOMATIC_SETTLEMENT = "F";
    const APPROVAL_SOURCE_AUTOMATIC_NON_AMADEUS_PAYMENT = "G";
    const APPROVAL_SOURCE_MANUAL = "M";

    /**
     * Card Vendor code
     *
     * @var string
     */
    public $vendorCode;

    /**
     * Card number
     *
     * @var string
     */
    public $cardNumber;

    /**
     * Expiry date on card (format: MMYY)
     *
     * @var string
     */
    public $expiryDate;

    /**
     * Security ID/ Security Code
     *
     * @var string
     */
    public $securityId;

    /**
     * Card holder name
     *
     * @var string
     */
    public $name;

    /**
     * Issue number
     *
     * @var int|string
     */
    public $issueNumber;

    /**
     * Date when the credit card has been issued
     *
     * @var string
     */
    public $startDate;

    /**
     * CC transaction authorization approval code
     *
     * @var string
     */
    public $approvalCode;

    /**
     * Authorization approval source
     *
     * self::APPROVAL_SOURCE_*
     *
     * @var string
     */
    public $sourceOfApproval;

    /**
     * @var ThreeDSecureInfo
     */
    public $threeDSecure;
}
