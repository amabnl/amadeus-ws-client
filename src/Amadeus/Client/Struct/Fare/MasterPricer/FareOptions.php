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

namespace Amadeus\Client\Struct\Fare\MasterPricer;

use Amadeus\Client\RequestOptions\Fare\MPTicketingPriceScheme;
use Amadeus\Client\RequestOptions\Fare\MPFeeId;

/**
 * FareOptions
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FareOptions
{
    /**
     * @var PricingTickInfo
     */
    public $pricingTickInfo;
    /**
     * @var Corporate
     */
    public $corporate;
    /**
     * @var TicketingPriceScheme
     */
    public $ticketingPriceScheme;
    /**
     * @var FeeIdDescription
     */
    public $feeIdDescription;
    /**
     * @var ConversionRate
     */
    public $conversionRate;
    /**
     * @var FormOfPaymentDetails[]|array
     */
    public $formOfPayment;

    public $frequentTravellerInfo;

    public $monetaryCabinInfo;

    public $multiTicket;

    /**
     * FareOptions constructor.
     *
     * @param array $flightOptions List of flight / fare options
     * @param array $corpCodesUniFares list of Corporate codes for Corporate Unifares
     * @param bool $tickPreCheck Do Ticketability pre-check?
     * @param string|null $currency Override Currency conversion
     * @param MPFeeId[]|null $feeIds List of FeeIds
     * @param string|null Corporate qualifier for Corporate Unifares
     * @param $multiTicket
     * @param MPTicketingPriceScheme|null $ticketingPriceScheme
     * @param array|null $formOfPayment
     */
    public function __construct(
        array $flightOptions,
        array $corpCodesUniFares,
        $tickPreCheck,
        $currency,
        $feeIds,
        $corporateQualifier,
        $multiTicket,
        $ticketingPriceScheme,
        $formOfPayment
    ) {
        if ($tickPreCheck === true) {
            $this->addPriceType(PricingTicketing::PRICETYPE_TICKETABILITY_PRECHECK);
        }

        foreach ($flightOptions as $flightOption) {
            $this->addPriceType($flightOption);

            if ($flightOption === PricingTicketing::PRICETYPE_CORPORATE_UNIFARES) {
                $this->corporate = new Corporate();
                $this->corporate->corporateId[] = new CorporateId($corpCodesUniFares, $corporateQualifier);
            }
        }

        $this->loadCurrencyOverride($currency);
        $this->loadMultiTicket($multiTicket);
        if (!is_null($feeIds)) {
            $this->loadFeeIds($feeIds);
        }
        if (!is_null($ticketingPriceScheme)) {
            $this->loadTicketingPriceScheme($ticketingPriceScheme);
        }
        if (!is_null($formOfPayment) && count($formOfPayment)) {
            $this->loadFormOfPayment($formOfPayment);
        }
    }

    /**
     * Set fee ids if needed
     *
     * @param MPFeeId[] $feeIds
     */
    protected function loadFeeIds($feeIds)
    {
        if (is_null($this->feeIdDescription)) {
            $this->feeIdDescription = new FeeIdDescription();
        }
        foreach ($feeIds as $feeId) {
            $this->feeIdDescription->feeId[] = new FeeId($feeId->type, $feeId->number);
        }
    }

    /**
     * Set currency override code if needed
     *
     * @param string|null $currency
     */
    protected function loadCurrencyOverride($currency)
    {
        if (is_string($currency) && strlen($currency) === 3) {
            $this->addPriceType(PricingTicketing::PRICETYPE_OVERRIDE_CURRENCY_CONVERSION);

            $this->conversionRate = new ConversionRate($currency);
        }
    }

    /**
     * Set multi ticket on if needed
     *
     * @param string|null $multiTicket
     */
    protected function loadMultiTicket($multiTicket)
    {
        if ($multiTicket) {
            $this->addPriceType(PricingTicketing::PRICETYPE_MULTI_TICKET);
        }
    }

    /**
     * Add PriceType
     *
     * @param string $type
     */
    protected function addPriceType($type)
    {
        if (is_null($this->pricingTickInfo)) {
            $this->pricingTickInfo = new PricingTickInfo();
        }
        if (is_null($this->pricingTickInfo->pricingTicketing)) {
            $this->pricingTickInfo->pricingTicketing = new PricingTicketing();
        }

        $this->pricingTickInfo->pricingTicketing->priceType[] = $type;
    }

    protected function loadTicketingPriceScheme($ticketingPriceScheme)
    {
        $priceScheme = new TicketingPriceScheme();
        $priceScheme->referenceNumber = $ticketingPriceScheme->referenceNumber;
        $priceScheme->name = $ticketingPriceScheme->name;
        $priceScheme->status = $ticketingPriceScheme->status;
        $priceScheme->description = $ticketingPriceScheme->description;
        $this->ticketingPriceScheme = $priceScheme;
    }

    protected function loadFormOfPayment(array $formOfPayment)
    {
        /** @var \Amadeus\Client\RequestOptions\Fare\MasterPricer\FormOfPaymentDetails $fop */
        foreach ($formOfPayment as $fop) {
            $this->formOfPayment[] = new FormOfPaymentDetails(
                $fop->type,
                $fop->chargedAmount,
                $fop->creditCardNumber
            );
        }
    }
}
