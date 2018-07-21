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

use Amadeus\Client\RequestCreator\MessageVersionUnsupportedException;
use Amadeus\Client\RequestOptions\OfferConfirmCarOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Offer\ConfirmCar\AddressUsageDetails;
use Amadeus\Client\Struct\Offer\ConfirmCar\DeliveryAndCollection;
use Amadeus\Client\Struct\Offer\ConfirmCar\PaxTattooNbr;
use Amadeus\Client\Struct\Offer\ConfirmCar\PnrInfo;
use Amadeus\Client\Struct\Offer\ConfirmCar\TattooReference;
use Amadeus\Client\Struct\Pnr\Retrieve\ReservationOrProfileIdentifier;

/**
 * Offer_ConfirmCarOffer
 *
 * @package Amadeus\Client\Struct\Offer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ConfirmCar extends BaseWsMessage
{
    /**
     * @var ConfirmCar\PnrInfo
     */
    public $pnrInfo;

    public $bookingType;

    /**
     * @var ConfirmCar\DeliveryAndCollection[]
     */
    public $deliveryAndCollection = [];

    public $vehicleInformation;

    public $arrivalInfo;

    public $fFlyerNbr;

    public $rateInfo;

    public $payment;

    public $attributeData;

    public $billingData;

    public $supleInfo;

    public $estimatedDistance;

    public $agentInformation;

    public $trackingOpt;

    public $customerEmail;

    public $retryProcess;

    public $ruleReference;

    public $action;

    /**
     * ConfirmCar constructor.
     *
     * @param OfferConfirmCarOptions $params
     * @throws MessageVersionUnsupportedException
     */
    public function __construct($params)
    {
        $this->pnrInfo = new PnrInfo();

        if (!empty($params->passengerTattoo)) {
            $this->pnrInfo->paxTattooNbr = new PaxTattooNbr($params->passengerTattoo);
        }

        if (!empty($params->offerTattoo)) {
            $this->pnrInfo->tattooReference = new TattooReference($params->offerTattoo);
        }

        if (!empty($params->recordLocator)) {
            $this->pnrInfo->pnrRecLoc = new ReservationOrProfileIdentifier($params->recordLocator);
        }

        if (!empty($params->pickUpInfo)) {
            $this->deliveryAndCollection[] = new DeliveryAndCollection(
                AddressUsageDetails::PURPOSE_COLLECTION,
                $params->pickUpInfo
            );
        }
        if (!empty($params->dropOffInfo)) {
            $this->deliveryAndCollection[] = new DeliveryAndCollection(
                AddressUsageDetails::PURPOSE_DELIVERY,
                $params->dropOffInfo
            );
        }
    }
}
