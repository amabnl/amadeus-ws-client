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

use Amadeus\Client\RequestOptions\Fop\MopInfo;
use Amadeus\Client\Struct\Fop\CreateFormOfPayment\GroupUsage14;
use Amadeus\Client\Struct\Fop\CreateFormOfPayment\PaymentModule14;
use Amadeus\Client\Struct\WsMessageUtility;

/**
 * PaymentModule
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PaymentModule extends WsMessageUtility
{
    /**
     * @var GroupUsage
     */
    public $groupUsage;

    /**
     * @var PaymentData
     */
    public $paymentData;

    /**
     * @var PaymentStatus
     */
    public $paymentStatus;

    /**
     * @var PaymentSupplementaryData[]
     */
    public $paymentSupplementaryData = [];

    /**
     * @var MopInformation
     */
    public $mopInformation;

    /**
     * @var string
     */
    public $dummy;

    /**
     * @var MopDetailedData
     */
    public $mopDetailedData;

    /**
     * PaymentModule constructor.
     *
     * @param string $fopType
     */
    public function __construct($fopType)
    {
        if ($this instanceof PaymentModule14) {
            $this->groupUsage = new GroupUsage14($fopType);
        } else {
            $this->groupUsage = new GroupUsage($fopType);
        }
    }

    /**
     * Load all paymentData
     *
     * @param MopInfo $options
     */
    public function loadPaymentData(MopInfo $options)
    {
        if ($this->checkAnyNotEmpty(
            $options->payMerchant,
            $options->transactionDate,
            $options->payments,
            $options->installmentsInfo,
            $options->fraudScreening,
            $options->payIds
        )) {
            $this->paymentData = new PaymentData(
                $options->payMerchant,
                $options->transactionDate,
                $options->payments,
                $options->installmentsInfo,
                $options->fraudScreening,
                $options->payIds
            );
        }
    }
}
