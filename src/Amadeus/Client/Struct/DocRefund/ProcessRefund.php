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

namespace Amadeus\Client\Struct\DocRefund;

use Amadeus\Client\RequestOptions\DocRefund\RefundItinOpt;
use Amadeus\Client\RequestOptions\DocRefundProcessRefundOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\DocRefund\ProcessRefund\PhoneFaxEmailAddress;
use Amadeus\Client\Struct\DocRefund\ProcessRefund\PrinterReference;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\RefundedItinerary;

/**
 * ProcessRefund
 *
 * @package Amadeus\Client\Struct\DocRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ProcessRefund extends BaseWsMessage
{
    /**
     * @var ActionDetails
     */
    public $actionDetails;
    
    /**
     * @var mixed
     */
    public $dummysegment;
    
    /**
     * @var PrinterReference
     */
    public $printerReference;
    
    /**
     * @var mixed
     */
    public $targetStockProvider;
    
    /**
     * @var RefundedItinerary[]
     */
    public $refundedItinerary;
    
    /**
     * @var PhoneFaxEmailAddress[]
     */
    public $phoneFaxEmailAddress;

    /**
     * ProcessRefund constructor.
     *
     * @param DocRefundProcessRefundOptions $options
     */
    public function __construct(DocRefundProcessRefundOptions $options)
    {
        if (!empty($options->statusIndicators)) {
            $this->actionDetails = new ActionDetails($options->statusIndicators);
        }

        if ($this->checkAnyNotEmpty($options->printer, $options->printerType)) {
            $this->printerReference = new PrinterReference($options->printer, $options->printerType);
        }

        $this->loadRefundedItinerary($options->refundedItinerary);

        $this->loadEmailFax(
            $options->refundNoticesEmailAddresses,
            $options->refundNoticesFaxes,
            $options->sendNotificationToEmailInAPE,
            $options->sendNotificationToFaxInAPF
        );
    }

    /**
     * @param RefundItinOpt[] $refundedItinerary
     */
    protected function loadRefundedItinerary($refundedItinerary)
    {
        foreach ($refundedItinerary as $itin) {
            $this->refundedItinerary[] = new RefundedItinerary($itin);
        }
    }

    /**
     * @param string[] $refundNoticesEmailAddresses
     * @param array $refundNoticesFaxes
     * @param bool $sendNotificationToEmailInAPE
     * @param bool $sendNotificationToFaxInAPF
     */
    protected function loadEmailFax(
        $refundNoticesEmailAddresses,
        $refundNoticesFaxes,
        $sendNotificationToEmailInAPE,
        $sendNotificationToFaxInAPF
    ) {
        if ($sendNotificationToEmailInAPE) {
            $this->phoneFaxEmailAddress[] = new PhoneFaxEmailAddress(
                PhoneFaxEmailAddress::TYPE_E_MAIL_ADDRESS_FROM_APE
            );
        }

        if ($sendNotificationToFaxInAPF) {
            $this->phoneFaxEmailAddress[] = new PhoneFaxEmailAddress(
                PhoneFaxEmailAddress::TYPE_FAX_NUMBER_FROM_APF
            );
        }

        foreach ($refundNoticesEmailAddresses as $emailAddress) {
            $this->phoneFaxEmailAddress[] = new PhoneFaxEmailAddress(
                PhoneFaxEmailAddress::TYPE_E_MAIL_ADDRESS,
                $emailAddress
            );
        }

        foreach ($refundNoticesFaxes as $number => $areaCode) {
            $this->phoneFaxEmailAddress[] = new PhoneFaxEmailAddress(
                PhoneFaxEmailAddress::TYPE_FAX_NUMBER,
                null,
                $number,
                $areaCode
            );
        }
    }
}
