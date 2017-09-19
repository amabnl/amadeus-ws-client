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
 * InvoiceFormOfPayment
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class InvoiceFormOfPayment extends FormOfPayment
{
    const INDICATOR_COLLECTIVE_INVOICE = "C";

    /**
     * self::INDICATOR_*
     *
     * @var string
     */
    public $indicator;

    /**
     * @var string
     */
    public $merchantCode;

    /**
     * @var string
     */
    public $expiryDate;

    /**
     * @var string
     */
    public $customerAccount;

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
     * InvoiceFormOfPayment constructor.
     *
     * @param string $type
     * @param string $customerAccount
     * @param string $membershipStatus
     * @param string|null $merchantCode
     */
    public function __construct($type, $customerAccount, $membershipStatus, $merchantCode = null)
    {
        parent::__construct($type);
        $this->customerAccount = $customerAccount;
        $this->membershipStatus = $membershipStatus;
        $this->merchantCode = $merchantCode;
    }
}
