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
use Amadeus\Client\Struct\WsMessageUtility;

/**
 * MopDescription
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class MopDescription extends WsMessageUtility
{
    /**
     * @var FopSequenceNumber
     */
    public $fopSequenceNumber;
    /**
     * @var FopMasterElementReference
     */
    public $fopMasterElementReference;
    /**
     * @var StakeholderPayerReference
     */
    public $stakeholderPayerReference;
    /**
     * @var MopDetails
     */
    public $mopDetails;
    /**
     * @var PaymentModule
     */
    public $paymentModule;

    /**
     * MopDescription constructor.
     *
     * @param MopInfo $options
     */
    public function __construct(MopInfo $options)
    {
        if (!empty($options->sequenceNr)) {
            $this->fopSequenceNumber = new FopSequenceNumber($options->sequenceNr);
        }

        $this->loadMopDetails($options);

        $this->loadPaymentModule($options);
    }

    /**
     * load Method of Payment Details
     *
     * @param MopInfo $options
     */
    protected function loadMopDetails(MopInfo $options)
    {
        $this->mopDetails = new MopDetails();

        if ($this->checkAnyNotEmpty($options->fopCode, $options->fopStatus)) {
            $this->mopDetails->fopPNRDetails = new FopPNRDetails(
                $options->fopCode,
                $options->fopStatus
            );
        }

        if (!empty($options->freeFlowText)) {
            $this->mopDetails->oldFopFreeflow = new OldFopFreeflow(
                $options->freeFlowText,
                $options->freeFlowEncoding
            );
        }

        if (!empty($options->supplementaryData)) {
            $this->mopDetails->pnrSupplementaryData = new PnrSupplementaryData(
                DataAndSwitchMap::TYPE_DATA_INFORMATION,
                $options->supplementaryData
            );
        }
        if (!empty($options->supplementarySwitches)) {
            $this->mopDetails->pnrSupplementaryData = new PnrSupplementaryData(
                DataAndSwitchMap::TYPE_SWITCH_INFORMATION,
                $options->supplementarySwitches
            );
        }
    }

    /**
     * Payment Module information
     *
     * @param MopInfo $options
     */
    protected function loadPaymentModule(MopInfo $options)
    {
        if ($this->checkAnyNotEmpty($options->fopType, $options->payMerchant, $options->payments, $options->installmentsInfo, $options->mopPaymentType, $options->creditCardInfo, $options->fraudScreening)) {
            $this->paymentModule = new PaymentModule($options->fopType);

            if (!empty($options->payMerchant)) {
                $this->checkAndCreatePaymentData();
                $this->paymentModule->paymentData->merchantInformation = new MerchantInformation($options->payMerchant);
            }

            if (!empty($options->payments)) {
                $this->checkAndCreatePaymentData();
                $this->paymentModule->paymentData->monetaryInformation[] = new MonetaryInformation($options->payments);
            }

            if (!empty($options->installmentsInfo)) {
                $this->checkAndCreatePaymentData();
                $this->paymentModule->paymentData->extendedPaymentInfo = new ExtendedPaymentInfo(
                    $options->installmentsInfo
                );
            }

            if (!empty($options->fraudScreening)) {
                $this->checkAndCreatePaymentData();
                $this->paymentModule->paymentData->fraudScreeningData = new FraudScreeningData($options->fraudScreening);
            }

            if (!empty($options->mopPaymentType)) {
                $this->checkAndCreateMopInformation();
                $this->paymentModule->mopInformation->fopInformation = new FopInformation($options->mopPaymentType);
            }

            if (!empty($options->creditCardInfo)) {
                $this->checkAndCreateMopInformation();
                $this->paymentModule->mopInformation->creditCardData = new CreditCardData($options->creditCardInfo);
            }
        }
    }

    /**
     * Create Payment Data node if needed
     */
    private function checkAndCreatePaymentData()
    {
        if (is_null($this->paymentModule->paymentData)) {
            $this->paymentModule->paymentData = new PaymentData();
        }
    }

    /**
     * Create MopInformation node if needed
     */
    private function checkAndCreateMopInformation()
    {
        if (is_null($this->paymentModule->mopInformation)) {
            $this->paymentModule->mopInformation = new MopInformation();
        }
    }
}
