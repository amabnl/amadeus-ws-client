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
 * DocRefund_UpdateRefund Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DocRefundUpdateRefundOptions extends Base
{
    const PRICING_IND_DOMESTIC_ITINERARY = 'D';
    const PRICING_IND_INTERNATIONAL_ITINERARY = 'I';

    /**
     * @var string
     */
    public $originator;

    /**
     * @var string
     */
    public $originatorId;

    /**
     * @var string
     */
    public $ticketNumber;

    /**
     * @var string
     */
    public $ticketType;

    /**
     * @var \DateTime
     */
    public $ticketedDate;

    /**
     * @var \DateTime
     */
    public $refundDate;

    /**
     * @var DocRefund\Reference[]
     */
    public $references = [];

    /**
     * @var string
     */
    public $passengerSurName;

    /**
     * @var DocRefund\Ticket[]
     */
    public $tickets = [];

    /**
     * Traveller Priority Company
     *
     * @var string
     */
    public $travellerPrioCompany;

    /**
     * Traveller Priority Date of Joining
     *
     * @var \DateTime
     */
    public $travellerPrioDateOfJoining;

    /**
     * Traveller Priority: Traveller Reference
     *
     * @var string
     */
    public $travellerPrioReference;

    /**
     * Monetary information
     *
     * @var DocRefund\MonetaryData[]
     */
    public $monetaryData = [];

    /**
     * @var DocRefund\TaxData[]
     */
    public $taxData = [];

    /**
     * Pricing Ticket Indicator
     *
     * self::PRICING_IND_*
     *
     * @var string
     */
    public $pricingTicketIndicator;

    /**
     * Commissions
     *
     * @var DocRefund\CommissionOpt[]
     */
    public $commission = [];

    /**
     * Tour Code
     *
     * @var string
     */
    public $tourCode;

    /**
     * Interactive Free Text
     *
     * @var DocRefund\FreeTextOpt[]
     */
    public $freeText = [];

    /**
     * Form of payment information
     *
     * @var DocRefund\FopOpt[]
     */
    public $formOfPayment = [];

    /**
     * Refunded itinerary information
     *
     * @var DocRefund\RefundItinOpt[]
     */
    public $refundedItinerary = [];

    /**
     * Refunded route stations
     *
     * @var string[]
     */
    public $refundedRouteStations = [];

    /**
     * Billing Address
     *
     * @var DocRefund\AddressOpt
     */
    public $address;
}
