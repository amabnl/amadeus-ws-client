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

namespace Amadeus\Client\Struct\Fare;

use Amadeus\Client\RequestCreator\MessageVersionUnsupportedException;
use Amadeus\Client\RequestOptions\Fare\PricePnrBcFareBasis;
use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Fare\PricePnr13\CarrierInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\CriteriaDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\Currency;
use Amadeus\Client\Struct\Fare\PricePnr13\FirstCurrencyDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\OptionDetail;
use Amadeus\Client\Struct\Fare\PricePnr13\PaxSegTstReference;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionGroup;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionKey;

/**
 * Fare_PricePNRWithBookingClass v 13 and higher structure
 *
 * @package Amadeus\Client\Struct\Fare
 * @author dieter <dieter.devlieghere@benelux.amadeus.com>
 */
class PricePNRWithBookingClass13 extends BaseWsMessage
{
    /**
     * @var PricePnr13\PricingOptionGroup[]
     */
    public $pricingOptionGroup = [];

    /**
     * PricePNRWithBookingClass13 constructor.
     *
     * @param FarePricePnrWithBookingClassOptions $options
     * @throws MessageVersionUnsupportedException
     */
    public function __construct(FarePricePnrWithBookingClassOptions $options)
    {

        if ($options->validatingCarrier !== null) {
            $this->pricingOptionGroup[] = $this->makePricingOptionForValidatingCarrier($options->validatingCarrier);
        }

        if ($options->currencyOverride !== null) {
            $this->pricingOptionGroup[] = $this->makePricingOptionForCurrencyOverride($options->currencyOverride);
        }

        if ($options->pricingsFareBasis !== null) {
            foreach ($options->pricingsFareBasis as $pricingFareBasis) {
                $this->pricingOptionGroup[] = $this->makePricingOptionFareBasisOverride($pricingFareBasis);
            }
        }

        if (!empty($options->overrideOptions)) {
            foreach ($options->overrideOptions as $overrideOption) {
                if (!$this->hasPricingGroup($overrideOption)) {
                    $this->pricingOptionGroup[] = new PricingOptionGroup($overrideOption);
                }
            }
        }

        // All options processed, no options found:
        if (empty($this->pricingOptionGroup)) {
            $this->pricingOptionGroup[] = new PricingOptionGroup(PricingOptionKey::OPTION_NO_OPTION);
        }
    }

    /**
     * @param string $validatingCarrier
     * @return PricePnr13\PricingOptionGroup
     */
    protected function makePricingOptionForValidatingCarrier($validatingCarrier)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_VALIDATING_CARRIER);

        $po->carrierInformation = new CarrierInformation($validatingCarrier);

        return $po;
    }

    /**
     * @param string $currency
     * @return PricePnr13\PricingOptionGroup
     */
    protected function makePricingOptionForCurrencyOverride($currency)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_CURRENCY_OVERRIDE);

        $po->currency = new Currency($currency, FirstCurrencyDetails::QUAL_CURRENCY_OVERRIDE);

        return $po;
    }

    /**
     * @param PricePnrBcFareBasis $pricingFareBasis
     * @return PricePnr13\PricingOptionGroup
     */
    protected function makePricingOptionFareBasisOverride($pricingFareBasis)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_BASIS_SIMPLE_OVERRIDE);

        $po->optionDetail = new OptionDetail();
        $po->optionDetail->criteriaDetails[] = new CriteriaDetails(
            $pricingFareBasis->fareBasisPrimaryCode . $pricingFareBasis->fareBasisCode
        );

        $po->paxSegTstReference = new PaxSegTstReference($pricingFareBasis->segmentReference);

        return $po;
    }

    /**
     * Avoid double pricing groups when combining an explicitly provided override option with a specific parameter
     * that uses the same override option.
     *
     * Backwards compatibility with PricePnrWithBookingClass12
     *
     * @param string $optionKey
     * @return bool
     */
    protected function hasPricingGroup($optionKey)
    {
        $found = false;

        foreach ($this->pricingOptionGroup as $pog) {
            if ($pog->pricingOptionKey->pricingOptionKey === $optionKey) {
                $found = true;
            }
        }

        return $found;
    }
}
