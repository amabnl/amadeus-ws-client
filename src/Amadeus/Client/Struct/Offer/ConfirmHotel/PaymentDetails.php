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

namespace Amadeus\Client\Struct\Offer\ConfirmHotel;

/**
 * PaymentDetails
 *
 * @package Amadeus\Client\Struct\Offer\ConfirmHotel
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PaymentDetails
{
    const SERVICE_HOTEL = 3;

    const PAYMENT_GUARANTEED = 1;
    const PAYMENT_DEPOSIT = 2;

    const FOP_CREDIT_CARD = 1;
    const FOP_TRAVEL_AGENT_IDENT = 10;
    const FOP_CORPORATE_IDENT = 12;
    const FOP_ADDRESS = 14;
    const FOP_WIRE_PAYMENT = 28;
    const FOP_MISC_CHARGE_ORDER = 4;
    const FOP_CHECK = 6;
    const FOP_BUSINESS_ACCOUNT = 9;
    const FOP_ADVANCE_DEPOSIT = "ADV";
    const FOP_CRQCHECK_GUARANTEE = "HI";
    const FOP_HOTEL_GUEST_IDENT = "ID";

    /**
     * self::FOP_*
     *
     * @var string
     */
    public $formOfPaymentCode;

    /**
     * self::PAYMENT_*
     *
     * @var string
     */
    public $paymentType;

    /**
     * self::SERVICE_*
     *
     * @var string
     */
    public $serviceToPay = self::SERVICE_HOTEL;

    /**
     * @var string
     */
    public $referenceNumber;

    /**
     * PaymentDetails constructor.
     *
     * @param string $paymentType
     * @param string $formOfPayment
     */
    public function __construct($paymentType, $formOfPayment)
    {
        $this->paymentType = $paymentType;
        $this->formOfPaymentCode = $formOfPayment;
    }
}
