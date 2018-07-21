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

use Amadeus\Client\RequestOptions\Fop\FraudScreeningOptions;
use Amadeus\Client\RequestOptions\Fop\InstallmentsInfo;
use Amadeus\Client\RequestOptions\Fop\PayId;
use Amadeus\Client\RequestOptions\Fop\Payment;

/**
 * PaymentData
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PaymentData
{
    /**
     * @var MerchantInformation
     */
    public $merchantInformation;

    /**
     * @var MonetaryInformation[]
     */
    public $monetaryInformation = [];

    /**
     * @var CurrenciesRatesGroup[]
     */
    public $currenciesRatesGroup = [];

    /**
     * @var SliderConversion
     */
    public $sliderConversion;

    /**
     * @var PaymentId[]
     */
    public $paymentId = [];

    /**
     * @var ExtendedPaymentInfo
     */
    public $extendedPaymentInfo;

    /**
     * @var TransactionDateTime
     */
    public $transactionDateTime;

    /**
     * @var ExpirationPeriod
     */
    public $expirationPeriod;

    /**
     * @var DistributionChannelInformation
     */
    public $distributionChannelInformation;

    /**
     * @var PurchaseDescription
     */
    public $purchaseDescription;

    /**
     * @var Association[]
     */
    public $association = [];

    /**
     * @var FraudScreeningData
     */
    public $fraudScreeningData;

    /**
     * @var PaymentDataMap[]
     */
    public $paymentDataMap = [];

    /**
     * PaymentData constructor.
     *
     * @param string|null $payMerchant
     * @param \DateTime|null $transactionDate
     * @param Payment[] $payments
     * @param InstallmentsInfo|null $installmentsInfo
     * @param FraudScreeningOptions|null $fraudScreening
     * @param PayId[] $payIds
     */
    public function __construct($payMerchant, $transactionDate, $payments, $installmentsInfo, $fraudScreening, $payIds)
    {
        if (!empty($payMerchant)) {
            $this->merchantInformation = new MerchantInformation($payMerchant);
        }

        if (!empty($transactionDate)) {
            $this->transactionDateTime = new TransactionDateTime(null, $transactionDate);
        }

        if (!empty($payments)) {
            $this->monetaryInformation[] = new MonetaryInformation($payments);
        }

        if (!empty($installmentsInfo)) {
            $this->extendedPaymentInfo = new ExtendedPaymentInfo($installmentsInfo);
        }

        if (!empty($fraudScreening)) {
            $this->fraudScreeningData = new FraudScreeningData($fraudScreening);
        }

        foreach ($payIds as $payId) {
            $this->paymentId[] = new PaymentId(
                $payId->id,
                $payId->type
            );
        }
    }
}
