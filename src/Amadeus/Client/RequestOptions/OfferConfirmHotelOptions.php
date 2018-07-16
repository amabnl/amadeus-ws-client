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

namespace Amadeus\Client\RequestOptions;

/**
 * Offer_ConfirmHotel Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class OfferConfirmHotelOptions extends Base
{
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
    const FOP_ADVANCE_DEPOSIT = 'ADV';
    const FOP_CRQCHECK_GUARANTEE = 'HI';
    const FOP_HOTEL_GUEST_IDENT = 'ID';

    /**
     * PNR record locator
     *
     * @var string
     */
    public $recordLocator;

    /**
     * The offer reference number of the offer to confirm
     *
     * @var int
     */
    public $offerReference;

    /**
     * List of passengers who are associated with this hotel offer
     *
     * @var int[]
     */
    public $passengers = [];

    /**
     * The Originator Identifier
     *
     * @var string
     */
    public $originatorId;

    /**
     * self::PAYMENT_*
     *
     * @var int
     */
    public $paymentType;

    /**
     * self::FOP_*
     *
     * @var string
     */
    public $formOfPayment;

    /**
     * @var Offer\PaymentDetails
     */
    public $paymentDetails;
}
