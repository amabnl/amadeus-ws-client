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

namespace Amadeus\Client\Struct\Ticket;

use Amadeus\Client\RequestOptions\Fare\PricePnr\AwardPricing;
use Amadeus\Client\RequestOptions\Fare\PricePnr\ExemptTax;
use Amadeus\Client\RequestOptions\Fare\PricePnr\FareBasis;
use Amadeus\Client\RequestOptions\Fare\PricePnr\Tax;
use Amadeus\Client\RequestOptions\Ticket\ExchangeInfoOptions;
use Amadeus\Client\RequestOptions\Ticket\MultiRefOpt;
use Amadeus\Client\RequestOptions\Ticket\PaxSegRef;
use Amadeus\Client\RequestOptions\TicketRepricePnrWithBookingClassOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Fare\PricePnr13\CarrierInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\Currency;
use Amadeus\Client\Struct\Fare\PricePnr13\FrequentFlyerInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\FrequentTravellerDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\LocationInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\OptionDetail;
use Amadeus\Client\Struct\Fare\PricePnr13\PaxSegTstReference;
use Amadeus\Client\Struct\Fare\PricePnr13\PenDisInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\TaxData;
use Amadeus\Client\Struct\Fare\PricePnr13\TaxInformation;
use Amadeus\Client\Struct\Ticket\RepricePnrWithBookingClass\ExchangeInformationGroup;
use Amadeus\Client\Struct\Ticket\RepricePnrWithBookingClass\PricingOption;
use Amadeus\Client\Struct\Ticket\RepricePnrWithBookingClass\PricingOptionKey;

/**
 * Ticket_RepricePNRWithBookingClass request structure
 *
 * @package Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class RepricePnrWithBookingClass extends BaseWsMessage
{
    /**
     * @var ExchangeInformationGroup[]
     */
    public $exchangeInformationGroup = [];

    /**
     * @var PricingOption[]
     */
    public $pricingOption = [];

    /**
     * RepricePnrWithBookingClass constructor.
     *
     * @param TicketRepricePnrWithBookingClassOptions $options
     */
    public function __construct($options)
    {
        if (!is_null($options)) {
            $this->loadExchangeInfo($options->exchangeInfo);

            $this->pricingOption = $this->loadPricingOptionsFromRequestOptions($options);
        }
    }

    /**
     * @param ExchangeInfoOptions[] $exchangeInfo
     */
    protected function loadExchangeInfo($exchangeInfo)
    {
        foreach ($exchangeInfo as $info) {
            if ($info instanceof ExchangeInfoOptions) {
                $this->exchangeInformationGroup[] = new ExchangeInformationGroup($info);
            }
        }
    }

    /**
     * @param TicketRepricePnrWithBookingClassOptions $options
     * @return PricingOption[]
     */
    protected function loadPricingOptionsFromRequestOptions(TicketRepricePnrWithBookingClassOptions $options)
    {
        $priceOptions = [];

        $priceOptions = $this->mergeOptions(
            $priceOptions,
            $this->makePricingOptionForCarrier(
                $options->validatingCarrier,
                PricingOptionKey::OPTION_VALIDATING_CARRIER
            )
        );

        $priceOptions = $this->mergeOptions(
            $priceOptions,
            $this->makePricingOptionForCarrier(
                $options->controllingCarrier,
                PricingOptionKey::OPTION_OVERRIDE_CONTROLLING_CARRIER
            )
        );

        $priceOptions = $this->mergeOptions(
            $priceOptions,
            $this->loadCorpUniFares($options->corporateUniFares, $options->awardPricing)
        );

        $priceOptions = $this->mergeOptions(
            $priceOptions,
            $this->makePricingOptionFareBasisOverride($options->pricingsFareBasis)
        );

        $priceOptions = $this->mergeOptions(
            $priceOptions,
            $this->loadTaxes($options->taxes)
        );

        $priceOptions = $this->mergeOptions(
            $priceOptions,
            $this->loadExemptTaxes($options->exemptTaxes)
        );

        $priceOptions = $this->mergeOptions(
            $priceOptions,
            $this->makePricingOptionForCurrencyOverride($options->currencyOverride)
        );

        $priceOptions = $this->mergeOptions(
            $priceOptions,
            $this->makePricingOptionForSelection($options->multiReferences)
        );

        $priceOptions = $this->mergeOptions(
            $priceOptions,
            $this->makeBreakpointOptions(
                $options->forceBreakPointRefs,
                $options->noBreakPointRefs
            )
        );

        $priceOptions = $this->mergeOptions(
            $priceOptions,
            $this->loadPaxDiscount($options->paxDiscountCodes, $options->paxDiscountCodeRefs)
        );

        $priceOptions = $this->mergeOptions(
            $priceOptions,
            $this->loadPointOverrides(
                $options->pointOfSaleOverride,
                $options->pointOfTicketingOverride
            )
        );

        $priceOptions = $this->mergeOptions(
            $priceOptions,
            $this->loadWaiverCode($options->waiverCode)
        );

        $priceOptions = $this->mergeOptions(
            $priceOptions,
            $this->loadOverrideReusableAmount($options->overrideReusableAmountRefs)
        );

        $priceOptions = $this->mergeOptions(
            $priceOptions,
            $this->makeOverrideOptions($options->overrideOptions, $priceOptions)
        );

        // All options processed, no options found:
        if (empty($priceOptions)) {
            $priceOptions[] = new PricingOption(PricingOptionKey::OPTION_NO_OPTION);
        }

        return $priceOptions;
    }


    /**
     * Merges Pricing options
     *
     * @param PricingOption[] $existingOptions
     * @param PricingOption[] $newOptions
     * @return PricingOption[] merged array
     */
    protected function mergeOptions($existingOptions, $newOptions)
    {
        if (!empty($newOptions)) {
            $existingOptions = array_merge(
                $existingOptions,
                $newOptions
            );
        }

        return $existingOptions;
    }

    /**
     * @param string|null $carrier
     * @param string $pricingOptionKey PricingOptionKey::OPTION_*
     * @return PricingOption[]
     */
    protected function makePricingOptionForCarrier($carrier, $pricingOptionKey)
    {
        $opt = [];

        if ($carrier !== null) {
            $po = new PricingOption($pricingOptionKey);

            $po->carrierInformation = new CarrierInformation($carrier);

            $opt[] = $po;
        }

        return $opt;
    }

    /**
     * @param MultiRefOpt[] $multiReferences
     * @return PricingOption[]
     */
    protected function makePricingOptionForSelection($multiReferences)
    {
        $opt = [];

        foreach ($multiReferences as $multiReference) {
            $opt[] = $this->makePricingOptionWithPaxSegTstRefs(
                $multiReference->references,
                PricingOptionKey::OPTION_PAX_SEG_LINE_TST_SELECTION
            );
        }

        return $opt;
    }


    /**
     * @param PaxSegRef[] $forceBreakPointRefs
     * @param PaxSegRef[] $noBreakPointRefs
     * @return PricingOption[]
     */
    protected function makeBreakpointOptions($forceBreakPointRefs, $noBreakPointRefs)
    {
        $opt = [];

        if (!empty($forceBreakPointRefs)) {
            $opt[] = $this->makePricingOptionWithPaxSegTstRefs(
                $forceBreakPointRefs,
                PricingOptionKey::OPTION_FORCE_FEE_BREAK_POINT
            );
        }

        if (!empty($noBreakPointRefs)) {
            $opt[] = $this->makePricingOptionWithPaxSegTstRefs(
                $noBreakPointRefs,
                PricingOptionKey::OPTION_NO_BREAKPOINT
            );
        }

        return $opt;
    }

    /**
     * @param PaxSegRef[] $refs
     * @param string $pricingOptionKey PricingOptionKey::OPTION_*
     * @return PricingOption
     */
    protected function makePricingOptionWithPaxSegTstRefs($refs, $pricingOptionKey)
    {
        $po = new PricingOption($pricingOptionKey);

        $po->paxSegTstReference = new PaxSegTstReference($refs);

        return $po;
    }

    /**
     * Load corporate unifares
     *
     * @param string[] $corporateUniFares
     * @param AwardPricing|null $awardPricing
     * @return PricingOption[]
     */
    protected function loadCorpUniFares($corporateUniFares, $awardPricing)
    {
        $opt = [];

        if (!empty($corporateUniFares)) {
            $po = new PricingOption(PricingOptionKey::OPTION_CORPORATE_UNIFARES);
            $po->optionDetail = new OptionDetail($corporateUniFares);
            $opt[] = $po;

            if (!empty($awardPricing)) {
                $opt[] = $this->loadAwardPricing($awardPricing);
            }
        }

        return $opt;
    }

    /**
     * @param AwardPricing $awardPricing
     * @return PricingOption
     */
    protected function loadAwardPricing($awardPricing)
    {
        $po = new PricingOption(PricingOptionKey::OPTION_AWARD);

        $po->carrierInformation = new CarrierInformation($awardPricing->carrier);

        $po->frequentFlyerInformation = new FrequentFlyerInformation();
        $po->frequentFlyerInformation->frequentTravellerDetails[] = new FrequentTravellerDetails(
            $awardPricing->tierLevel
        );

        return $po;
    }

    /**
     * @param FareBasis[] $pricingsFareBasis
     * @return PricingOption[]
     */
    protected function makePricingOptionFareBasisOverride($pricingsFareBasis)
    {
        $opt = [];

        if ($pricingsFareBasis !== null) {
            foreach ($pricingsFareBasis as $pricingFareBasis) {
                $po = new PricingOption(PricingOptionKey::OPTION_FARE_BASIS_SIMPLE_OVERRIDE);

                //Support for legacy fareBasisPrimaryCode to be removed when breaking BC:
                $po->optionDetail = new OptionDetail(
                    $pricingFareBasis->fareBasisPrimaryCode.$pricingFareBasis->fareBasisCode
                );

                //Support for legacy segmentReference to be removed when breaking BC:
                $po->paxSegTstReference = new PaxSegTstReference(
                    $pricingFareBasis->references,
                    $pricingFareBasis->segmentReference
                );

                $opt[] = $po;
            }
        }

        return $opt;
    }

    /**
     * @param Tax[] $taxes
     * @return PricingOption[]
     */
    protected function loadTaxes($taxes)
    {
        $opt = [];

        if (!empty($taxes)) {
            $po = new PricingOption(PricingOptionKey::OPTION_ADD_TAX);

            foreach ($taxes as $tax) {
                $qualifier = (!empty($tax->amount)) ? TaxData::QUALIFIER_AMOUNT : TaxData::QUALIFIER_PERCENTAGE;
                $rate = (!empty($tax->amount)) ? $tax->amount : $tax->percentage;

                $po->taxInformation[] = new TaxInformation(
                    $tax->countryCode,
                    $tax->taxNature,
                    $qualifier,
                    $rate
                );
            }
            $opt[] = $po;
        }

        return $opt;
    }

    /**
     * @param ExemptTax[] $exemptTaxes
     * @return PricingOption[]
     */
    protected function loadExemptTaxes($exemptTaxes)
    {
        $opt = [];

        if (!empty($exemptTaxes)) {
            $po = new PricingOption(PricingOptionKey::OPTION_EXEMPT_TAXES);

            foreach ($exemptTaxes as $tax) {
                $po->taxInformation[] = new TaxInformation(
                    $tax->countryCode,
                    $tax->taxNature
                );
            }

            $opt[] = $po;
        }

        return $opt;
    }

    /**
     * @param string|null $currency
     * @return PricingOption[]
     */
    protected function makePricingOptionForCurrencyOverride($currency)
    {
        $opt = [];

        if ($currency !== null) {
            $po = new PricingOption(PricingOptionKey::OPTION_FARE_CURRENCY_OVERRIDE);

            $po->currency = new Currency($currency);

            $opt[] = $po;
        }

        return $opt;
    }

    /**
     * @param string[] $paxDiscount
     * @param PaxSegRef[] $paxDiscountCodeRefs
     * @return PricingOption[]
     */
    protected function loadPaxDiscount($paxDiscount, $paxDiscountCodeRefs)
    {
        $opt = [];

        if (!empty($paxDiscount)) {
            $po = new PricingOption(PricingOptionKey::OPTION_PASSENGER_DISCOUNT_PTC);

            $po->penDisInformation = new PenDisInformation(
                PenDisInformation::QUAL_DISCOUNT,
                $paxDiscount
            );

            if (!empty($paxDiscountCodeRefs)) {
                $po->paxSegTstReference = new PaxSegTstReference($paxDiscountCodeRefs);
            }

            $opt[] = $po;
        }

        return $opt;
    }

    /**
     * @param string|null $posOverride
     * @param string|null $potOverride
     * @return PricingOption[]
     */
    protected function loadPointOverrides($posOverride, $potOverride)
    {
        $opt = [];

        if (!empty($posOverride)) {
            $po = new PricingOption(PricingOptionKey::OPTION_POINT_OF_SALE_OVERRIDE);

            $po->locationInformation = new LocationInformation(
                LocationInformation::TYPE_POINT_OF_SALE,
                $posOverride
            );

            $opt[] = $po;
        }

        if (!empty($potOverride)) {
            $po2 = new PricingOption(PricingOptionKey::OPTION_POINT_OF_TICKETING_OVERRIDE);

            $po2->locationInformation = new LocationInformation(
                LocationInformation::TYPE_POINT_OF_TICKETING,
                $potOverride
            );

            $opt[] = $po2;
        }

        return $opt;
    }


    /**
     * @param string|null $waiverCode
     * @return PricingOption[]
     */
    protected function loadWaiverCode($waiverCode)
    {
        $opt = [];

        if (!empty($waiverCode)) {
            $po = new PricingOption(PricingOptionKey::OPTION_WAIVER_OPTION);

            $po->optionDetail = new OptionDetail($waiverCode);

            $opt[] = $po;
        }

        return $opt;
    }

    /**
     * @param PaxSegRef[] $overrideReusableAmountRefs
     * @return PricingOption[]
     */
    protected function loadOverrideReusableAmount($overrideReusableAmountRefs)
    {
        $opt = [];

        if (!empty($overrideReusableAmountRefs)) {
            $opt[] = $this->makePricingOptionWithPaxSegTstRefs(
                $overrideReusableAmountRefs,
                PricingOptionKey::OPTION_OVERRIDE_REUSABLE_AMOUNT
            );
        }

        return $opt;
    }

    /**
     * @param string[] $overrideOptions
     * @param PricingOption[] $priceOptions
     * @return PricingOption[]
     */
    protected function makeOverrideOptions($overrideOptions, $priceOptions)
    {
        $opt = [];

        foreach ($overrideOptions as $overrideOption) {
            if (!$this->hasPricingOption($overrideOption, $priceOptions)) {
                $opt[] = new PricingOption($overrideOption);
            }
        }

        return $opt;
    }

    /**
     * Avoid double pricing groups when combining an explicitly provided override option with a specific parameter
     * that uses the same override option.
     *
     * @param string $optionKey
     * @param PricingOption[] $priceOptions
     * @return bool
     */
    protected function hasPricingOption($optionKey, $priceOptions)
    {
        $found = false;

        foreach ($priceOptions as $pog) {
            if ($pog->pricingOptionKey->pricingOptionKey === $optionKey) {
                $found = true;
            }
        }

        return $found;
    }
}
