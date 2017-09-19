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

use Amadeus\Client\RequestOptions\DocRefund\AddressOpt;
use Amadeus\Client\RequestOptions\DocRefund\CommissionOpt;
use Amadeus\Client\RequestOptions\DocRefund\FopOpt;
use Amadeus\Client\RequestOptions\DocRefund\FreeTextOpt;
use Amadeus\Client\RequestOptions\DocRefund\MonetaryData;
use Amadeus\Client\RequestOptions\DocRefund\Reference;
use Amadeus\Client\RequestOptions\DocRefund\RefundItinOpt;
use Amadeus\Client\RequestOptions\DocRefund\TaxData;
use Amadeus\Client\RequestOptions\DocRefund\Ticket as TicketOpt;
use Amadeus\Client\RequestOptions\DocRefundUpdateRefundOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\Commission;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\DateTimeInformation;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\FopGroup;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\InteractiveFreeText;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\MonetaryInformation;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\PricingDetails;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\ReferenceInformation;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\RefundedItinerary;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\RefundedRoute;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\StructuredAddress;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\TaxDetailsInformation;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\Ticket;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\TicketNumber;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\TourInformation;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\TravellerInformation;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\TravellerPriorityInfo;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\UserIdentification;

/**
 * DocRefund_UpdateRefund request structure
 *
 * @package Amadeus\Client\Struct\DocRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class UpdateRefund extends BaseWsMessage
{
    /**
     * @var UpdateRefund\UserIdentification
     */
    public $userIdentification;

    /**
     * @var UpdateRefund\TicketNumber
     */
    public $ticketNumber;

    /**
     * @var UpdateRefund\DateTimeInformation[]
     */
    public $dateTimeInformation = [];

    /**
     * @var UpdateRefund\ReferenceInformation
     */
    public $referenceInformation;

    /**
     * @var UpdateRefund\TravellerInformation
     */
    public $travellerInformation;

    /**
     * @var UpdateRefund\Ticket[]
     */
    public $ticket = [];

    /**
     * @var UpdateRefund\TravellerPriorityInfo
     */
    public $travellerPriorityInfo;

    /**
     * @var UpdateRefund\MonetaryInformation
     */
    public $monetaryInformation;

    /**
     * @var UpdateRefund\PricingDetails
     */
    public $pricingDetails;

    /**
     * @var UpdateRefund\Commission
     */
    public $commission;

    /**
     * @var UpdateRefund\TaxDetailsInformation[]
     */
    public $taxDetailsInformation = [];

    /**
     * @var UpdateRefund\TourInformation
     */
    public $tourInformation;

    /**
     * @var UpdateRefund\InteractiveFreeText[]
     */
    public $interactiveFreeText = [];

    /**
     * @var UpdateRefund\FopGroup[]
     */
    public $fopGroup = [];

    /**
     * @var UpdateRefund\TransactionContext
     */
    public $transactionContext;

    /**
     * @var UpdateRefund\FirstAndLastSegmentDates
     */
    public $firstAndLastSegmentDates;

    /**
     * @var UpdateRefund\OriginAndDestination
     */
    public $originAndDestination;

    /**
     * @var UpdateRefund\RefundedItinerary[]
     */
    public $refundedItinerary = [];

    /**
     * @var UpdateRefund\RefundedRoute
     */
    public $refundedRoute;

    /**
     * @var UpdateRefund\StructuredAddress
     */
    public $structuredAddress;

    /**
     * @var UpdateRefund\FeeGroup
     */
    public $feeGroup;

    /**
     * UpdateRefund constructor.
     *
     * @param DocRefundUpdateRefundOptions $options
     */
    public function __construct(DocRefundUpdateRefundOptions $options)
    {
        if ($this->checkAnyNotEmpty($options->originator, $options->originatorId)) {
            $this->userIdentification = new UserIdentification(
                $options->originatorId,
                $options->originator
            );
        }

        if ($this->checkAnyNotEmpty($options->ticketNumber, $options->ticketType)) {
            $this->ticketNumber = new TicketNumber($options->ticketNumber, $options->ticketType);
        }

        $this->loadDateTimeInfo($options->refundDate, $options->ticketedDate);

        $this->loadReferences($options->references);

        if (!empty($options->passengerSurName)) {
            $this->travellerInformation = new TravellerInformation($options->passengerSurName);
        }

        $this->loadTickets($options->tickets);

        $this->loadPassengerPriority($options);

        $this->loadMonetaryInfo($options->monetaryData, $options->taxData);

        if (!empty($options->pricingTicketIndicator)) {
            $this->pricingDetails = new PricingDetails($options->pricingTicketIndicator);
        }

        $this->loadCommissions($options->commission);

        if (!empty($options->tourCode)) {
            $this->tourInformation = new TourInformation($options->tourCode);
        }

        $this->loadFreeText($options->freeText);

        $this->loadFop($options->formOfPayment);

        $this->loadRefundedItinerary($options->refundedItinerary);

        $this->loadRefundedRoute($options->refundedRouteStations);

        $this->loadAddress($options->address);
    }

    /**
     * @param \DateTime|null $refundDate
     * @param \DateTime|null $ticketedDate
     */
    protected function loadDateTimeInfo($refundDate, $ticketedDate)
    {
        if ($refundDate instanceof \DateTime) {
            $this->dateTimeInformation[] = new DateTimeInformation(
                DateTimeInformation::OPT_DATE_OF_REFUND,
                $refundDate
            );
        }

        if ($ticketedDate instanceof \DateTime) {
            $this->dateTimeInformation[] = new DateTimeInformation(
                DateTimeInformation::OPT_DATE_TICKETED,
                $ticketedDate
            );
        }
    }

    /**
     * @param Reference[] $references
     */
    protected function loadReferences($references)
    {
        if (!empty($references)) {
            $this->referenceInformation = new ReferenceInformation($references);
        }
    }

    /**
     * @param TicketOpt[] $tickets
     */
    protected function loadTickets($tickets)
    {
        foreach ($tickets as $ticket) {
            $this->ticket[] = new Ticket($ticket);
        }
    }

    /**
     * @param DocRefundUpdateRefundOptions $options
     */
    protected function loadPassengerPriority($options)
    {
        if ($this->checkAnyNotEmpty(
            $options->travellerPrioCompany,
            $options->travellerPrioDateOfJoining,
            $options->travellerPrioReference
        )
        ) {
            $this->travellerPriorityInfo = new TravellerPriorityInfo(
                $options->travellerPrioCompany,
                $options->travellerPrioDateOfJoining,
                $options->travellerPrioReference
            );
        }
    }

    /**
     * @param MonetaryData[] $monetaryData
     * @param TaxData[] $taxData
     */
    protected function loadMonetaryInfo($monetaryData, $taxData)
    {
        if (!empty($monetaryData)) {
            $this->monetaryInformation = new MonetaryInformation($monetaryData);
        }

        foreach ($taxData as $tax) {
            $this->taxDetailsInformation[] = new TaxDetailsInformation($tax);
        }
    }

    /**
     * @param CommissionOpt[] $commission
     */
    protected function loadCommissions($commission)
    {
        if (!empty($commission)) {
            $this->commission = new Commission($commission);
        }
    }

    /**
     * @param FreeTextOpt[] $freeText
     */
    protected function loadFreeText($freeText)
    {
        foreach ($freeText as $opt) {
            $this->interactiveFreeText[] = new InteractiveFreeText($opt);
        }
    }

    /**
     * @param FopOpt[] $formOfPayment
     */
    protected function loadFop($formOfPayment)
    {
        foreach ($formOfPayment as $fop) {
            $this->fopGroup[] = new FopGroup($fop);
        }
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
     * @param string[] $refundedRouteStations
     */
    protected function loadRefundedRoute($refundedRouteStations)
    {
        if (!empty($refundedRouteStations)) {
            $this->refundedRoute = new RefundedRoute($refundedRouteStations);
        }
    }

    /**
     * @param AddressOpt|null $address
     */
    protected function loadAddress($address)
    {
        if ($address instanceof AddressOpt) {
            $this->structuredAddress = new StructuredAddress($address);
        }
    }
}
