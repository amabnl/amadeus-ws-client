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

namespace Amadeus\Client\Struct\Offer;

use Amadeus\Client\RequestOptions\OfferConfirmHotelOptions;
use Amadeus\Client\RequestOptions\Offer\PaymentDetails as PaymentDetailsOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\InvalidArgumentException;
use Amadeus\Client\Struct\Offer\ConfirmHotel\BookingSource;
use Amadeus\Client\Struct\Offer\ConfirmHotel\GlobalBookingInfo;
use Amadeus\Client\Struct\Offer\ConfirmHotel\GroupCreditCardInfo;
use Amadeus\Client\Struct\Offer\ConfirmHotel\GuaranteeOrDeposit;
use Amadeus\Client\Struct\Offer\ConfirmHotel\PaymentDetails;
use Amadeus\Client\Struct\Offer\ConfirmHotel\PaymentInfo;
use Amadeus\Client\Struct\Offer\ConfirmHotel\PnrInfo;
use Amadeus\Client\Struct\Offer\ConfirmHotel\Reservation;
use Amadeus\Client\Struct\Offer\ConfirmHotel\RoomList;
use Amadeus\Client\Struct\Offer\ConfirmHotel\RoomStayData;
use Amadeus\Client\Struct\Offer\ConfirmHotel\TattooReference;

/**
 * Offer_ConfirmHotelOffer
 *
 * @package Amadeus\Client\Struct\Offer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ConfirmHotel extends BaseWsMessage
{
    /**
     * @var ConfirmHotel\PnrInfo
     */
    public $pnrInfo;

    /**
     * @var mixed
     */
    public $groupIndicator;

    /**
     * @var mixed
     */
    public $travelAgentRef;

    /**
     * @var ConfirmHotel\RoomStayData[]
     */
    public $roomStayData = [];

    /**
     * @var mixed
     */
    public $arrivalFlightDetailsGrp;

    /**
     * ConfirmHotel constructor.
     *
     * @param OfferConfirmHotelOptions $params
     */
    public function __construct(OfferConfirmHotelOptions $params)
    {
        if (!empty($params->recordLocator)) {
            $this->pnrInfo = new PnrInfo($params->recordLocator, null, Reservation::CONTROLTYPE_PNR_IDENTIFICATION);
        }

        $this->loadRoomStayData($params);
        $this->loadPaymentInfo($params);
    }

    /**
     * Check for existence of RoomStayData and create if needed.
     *
     * @return void
     */
    protected function makeRoomStayData()
    {
        if (!isset($this->roomStayData[0])) {
            $this->roomStayData[] = new RoomStayData();
        }
    }

    /**
     * @param OfferConfirmHotelOptions $params
     */
    protected function loadPaymentInfo(OfferConfirmHotelOptions $params)
    {
        if ($this->checkAllNotEmpty($params->paymentType, $params->formOfPayment) &&
            $params->paymentDetails instanceof PaymentDetailsOptions
        ) {
            $this->makeRoomStayData();
            $this->roomStayData[0]->roomList[] = new RoomList();

            $this->roomStayData[0]->roomList[0]->guaranteeOrDeposit = new GuaranteeOrDeposit();
            $this->roomStayData[0]->roomList[0]->guaranteeOrDeposit->paymentInfo = new PaymentInfo(
                $params->paymentType,
                $params->formOfPayment
            );

            if ($params->formOfPayment === PaymentDetails::FOP_CREDIT_CARD) {
                $this->roomStayData[0]->roomList[0]->guaranteeOrDeposit->groupCreditCardInfo = new GroupCreditCardInfo(
                    $params->paymentDetails->ccVendor,
                    $params->paymentDetails->ccCardHolder,
                    $params->paymentDetails->ccCardNumber,
                    $params->paymentDetails->ccExpiry
                );
            } else {
                throw new InvalidArgumentException(
                    'Hotel Offer Confirm Form of Payment '.$params->formOfPayment.' is not yet supported'
                );
            }
        }
    }

    /**
     * @param OfferConfirmHotelOptions $params
     */
    protected function loadRoomStayData(OfferConfirmHotelOptions $params)
    {
        if (!empty($params->offerReference)) {
            $this->makeRoomStayData();
            $this->roomStayData[0]->tattooReference = new TattooReference($params->offerReference);
        }

        if (!empty($params->passengers)) {
            $this->makeRoomStayData();
            $this->roomStayData[0]->globalBookingInfo = new GlobalBookingInfo($params->passengers);
        }

        if (!empty($params->originatorId)) {
            $this->makeRoomStayData();
            if (!($this->roomStayData[0]->globalBookingInfo instanceof GlobalBookingInfo)) {
                $this->roomStayData[0]->globalBookingInfo = new GlobalBookingInfo();
            }

            $this->roomStayData[0]->globalBookingInfo->bookingSource = new BookingSource($params->originatorId);
        }
    }
}
