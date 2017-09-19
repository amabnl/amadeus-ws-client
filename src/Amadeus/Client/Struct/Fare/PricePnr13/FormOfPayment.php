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

use Amadeus\Client\RequestOptions\Fare\PricePnr\FormOfPayment as FormOfPaymentOptions;

/**
 * FormOfPayment
 *
 * @package Amadeus\Client\Struct\Fare\PricePnr13
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FormOfPayment
{
    const TYPE_PREVIOUSLY_ISSUED_BY_SALES_AGENT = "AGT";
    const TYPE_CASH = "CA";
    const TYPE_CREDIT_CARD = "CC";
    const TYPE_CHECK = "CK";
    const TYPE_GOVERNMENT_TRANSPORTATION_REQUEST = "GR";
    const TYPE_MISCELLANEOUS = "MS";
    const TYPE_NON_REFUNDABLE = "NR";
    const TYPE_PREPAID_TICKET_ADVICE = "PT";
    const TYPE_SINGLE_GOVERNMENT_TRANSPORTATION_REQUEST = "SGR";
    const TYPE_UNITED_NATIONS_TRANSPORTATION_REQUEST = "UN";

    /**
     * self::TYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $indicator;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var string
     */
    public $vendorCode;

    /**
     * @var string
     */
    public $creditCardNumber;

    /**
     * @var string
     */
    public $expiryDate;

    /**
     * @var string
     */
    public $approvalCode;

    /**
     * @var string
     */
    public $sourceOfApproval;

    /**
     * @var int
     */
    public $authorisedAmount;

    /**
     * @var string
     */
    public $addressVerification;

    /**
     * @var string
     */
    public $customerAccount;

    /**
     * @var string
     */
    public $extendedPayment;

    /**
     * @var string
     */
    public $fopFreeText;

    /**
     * @var string
     */
    public $membershipStatus;

    /**
     * @var string
     */
    public $transactionInfo;

    /**
     * @var string
     */
    public $pinCode;

    /**
     * @var string
     */
    public $pinCodeType;

    /**
     * FormOfPayment constructor.
     *
     * @param string|FormOfPaymentOptions $options self::TYPE_* or object with FOP options
     */
    public function __construct($options)
    {
        if ($options instanceof FormOfPaymentOptions) {
            $this->type = $options->type;
            $this->amount = $options->amount;
            $this->creditCardNumber = $options->creditCardNumber;
            $this->vendorCode = $options->vendorCode;
        } else {
            $this->type = $options;
        }
    }
}
