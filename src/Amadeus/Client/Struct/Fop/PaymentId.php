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
 * PaymentId
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PaymentId
{
    const TYPE_APPLICATION_CORRELATOR_ID = "APP";
    const TYPE_CAPTURE_REFERENCE_ID = "CRI";
    const TYPE_DCC_CURRENCY_CHOSEN = "DCC";
    const TYPE_THIRD_PARTY_RECORD_ID = "EXT";
    const TYPE_FRAUD_RECORD_ID = "FRI";
    const TYPE_MERCHANT_REFERENCE = "MRF";
    const TYPE_PAYMENT_PAGE_TOKEN = "PPT";
    const TYPE_PAYMENT_RECORD_ID = "PRI";
    const TYPE_PSP_RECONCILATION_REFERENCE = "PRR";
    const TYPE_REFUND_REFERENCE_ID = "RRI";

    /**
     * self::TYPE_*
     *
     * @var string
     */
    public $referenceType;

    /**
     * @var string
     */
    public $uniqueReference;

    /**
     * PaymentId constructor.
     *
     * @param string $uniqueReference
     * @param string $referenceType
     */
    public function __construct($uniqueReference, $referenceType)
    {
        $this->referenceType = $referenceType;
        $this->uniqueReference = $uniqueReference;
    }
}
