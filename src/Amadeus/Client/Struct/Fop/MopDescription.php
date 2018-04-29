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
use Amadeus\Client\Struct\Fop\CreateFormOfPayment\MopDescription14;
use Amadeus\Client\Struct\Fop\CreateFormOfPayment\PaymentModule14;
use Amadeus\Client\Struct\WsMessageUtility;

/**
 * MopDescription
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
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
        if (!is_null($options->sequenceNr)) {
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
            $this->mopDetails->pnrSupplementaryData[] = new PnrSupplementaryData(
                DataAndSwitchMap::TYPE_DATA_INFORMATION,
                $options->supplementaryData
            );
        }
        if (!empty($options->supplementarySwitches)) {
            $this->mopDetails->pnrSupplementaryData[] = new PnrSupplementaryData(
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
        if ($this->checkAnyNotEmpty(
            $options->fopType,
            $options->payMerchant,
            $options->payments,
            $options->installmentsInfo,
            $options->mopPaymentType,
            $options->creditCardInfo,
            $options->fraudScreening,
            $options->payIds,
            $options->paySupData
        )) {
            if ($this instanceof MopDescription14) {
                $this->paymentModule = new PaymentModule14($options->fopType);
            } else {
                $this->paymentModule = new PaymentModule($options->fopType);
            }
            $this->paymentModule->loadPaymentData($options);

            $this->loadMopInformation($options);

            $this->loadPaymentSupplementaryData($options);
        }
    }

    /**
     * Load Supplementary data
     *
     * @param MopInfo $options
     */
    protected function loadPaymentSupplementaryData(MopInfo $options)
    {
        foreach ($options->paySupData as $paySupData) {
            $this->paymentModule->paymentSupplementaryData[] = new PaymentSupplementaryData(
                $paySupData->function,
                $paySupData->data
            );
        }
    }

    /**
     * Load MopInformation
     *
     * @param MopInfo $options
     */
    protected function loadMopInformation(MopInfo $options)
    {
        if (!empty($options->mopPaymentType)) {
            $this->checkAndCreateMopInformation();
            $this->paymentModule->mopInformation->fopInformation = new FopInformation($options->mopPaymentType);
        }

        if (!empty($options->creditCardInfo)) {
            $this->checkAndCreateMopInformation();
            $this->paymentModule->mopInformation->creditCardData = new CreditCardData($options->creditCardInfo);
            if ($this->checkAnyNotEmpty(
                $options->creditCardInfo->approvalCode,
                $options->creditCardInfo->threeDSecure
            )
            ) {
                $this->checkAndCreateMopDetailedData($options->fopType);
                $this->paymentModule->mopDetailedData->creditCardDetailedData = new CreditCardDetailedData(
                    $options->creditCardInfo->approvalCode,
                    $options->creditCardInfo->sourceOfApproval,
                    $options->creditCardInfo->threeDSecure
                );
            }
        }

        if (!empty($options->invoiceInfo)) {
            $this->checkAndCreateMopInformation();
            $this->paymentModule->mopInformation->invoiceDataGroup = new InvoiceDataGroup($options->invoiceInfo);
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

    /**
     * Create MopDetailedData node if needed
     *
     * @param string $fopType
     */
    private function checkAndCreateMopDetailedData($fopType)
    {
        if (is_null($this->paymentModule->mopDetailedData)) {
            $this->paymentModule->mopDetailedData = new MopDetailedData($fopType);
        }
    }
}
