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

namespace Amadeus\Client\Struct\Service;

use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;
use Amadeus\Client\RequestOptions\Service\FormOfPayment;
use Amadeus\Client\RequestOptions\Service\FrequentFlyer;
use Amadeus\Client\RequestOptions\ServiceIntegratedCatalogueOptions;
use Amadeus\Client\RequestOptions\ServiceIntegratedPricingOptions;
use Amadeus\Client\Struct\Fare\BasePricingMessage;
use Amadeus\Client\Struct\Fare\PricePnr13\CarrierInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\Currency;
use Amadeus\Client\Struct\Fare\PricePnr13\DateInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\FormOfPaymentInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\FrequentFlyerInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\LocationInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\OptionDetail;
use Amadeus\Client\Struct\Fare\PricePnr13\PaxSegTstReference;
use Amadeus\Client\Struct\Service\IntegratedPricing\PricingOptionKey;
use Amadeus\Client\Struct\Service\IntegratedPricing\PricingOption;

/**
 * Service_IntegratedPricing request structure
 *
 * @package Amadeus\Client\Struct\Service
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class IntegratedPricing extends BasePricingMessage
{
    /**
     * @var PricingOption[]
     */
    public $pricingOption = [];

    /**
     * IntegratedPricing constructor.
     *
     * @param ServiceIntegratedPricingOptions|ServiceIntegratedCatalogueOptions|null $options
     */
    public function __construct($options = null)
    {
        if (!is_null($options)) {
            $this->pricingOption = $this->loadPricingOptions($options);
        }
    }

    /**
     * @param ServiceIntegratedPricingOptions|ServiceIntegratedCatalogueOptions $options
     * @return PricingOption[]
     */
    protected function loadPricingOptions($options)
    {
        $priceOptions = [];

        $priceOptions = self::mergeOptions(
            $priceOptions,
            self::makePricingOptionForValidatingCarrier($options->validatingCarrier)
        );

        $priceOptions = self::mergeOptions(
            $priceOptions,
            self::makePricingOptionForCurrencyOverride($options->currencyOverride)
        );

        $priceOptions = self::mergeOptions(
            $priceOptions,
            self::makePricingOptionWithOptionDetailAndRefs(
                PricingOptionKey::OVERRIDE_ACCOUNT_CODE,
                $options->accountCode,
                $options->accountCodeRefs
            )
        );

        $priceOptions = self::mergeOptions(
            $priceOptions,
            self::makePricingOptionWithOptionDetailAndRefs(
                PricingOptionKey::OVERRIDE_AWARD,
                $options->awardPricing,
                []
            )
        );

        $priceOptions = self::mergeOptions(
            $priceOptions,
            self::makePricingOptionWithOptionDetailAndRefs(
                PricingOptionKey::OVERRIDE_CORPORATION_NUMBER,
                $options->corporationNumber,
                []
            )
        );

        $priceOptions = self::mergeOptions(
            $priceOptions,
            self::loadDateOverride($options->overrideDate)
        );

        $priceOptions = self::mergeOptions(
            $priceOptions,
            self::makePricingOptionWithOptionDetailAndRefs(
                PricingOptionKey::OVERRIDE_TICKET_DESIGNATOR,
                $options->ticketDesignator,
                []
            )
        );

        $priceOptions = self::mergeOptions(
            $priceOptions,
            self::loadPointOverrides($options->pointOfSaleOverride)
        );

        $priceOptions = self::mergeOptions(
            $priceOptions,
            self::loadFormOfPaymentOverride($options->formOfPayment)
        );

        $priceOptions = self::mergeOptions(
            $priceOptions,
            self::loadFrequentFlyerOverride($options->frequentFlyers)
        );

        $priceOptions = self::mergeOptions(
            $priceOptions,
            self::loadReferences($options->references)
        );

        $priceOptions = self::mergeOptions(
            $priceOptions,
            self::makeOverrideOptions($options->overrideOptions, $priceOptions)
        );

        // All options processed, no options found:
        if (empty($priceOptions)) {
            $priceOptions[] = new PricingOption(PricingOptionKey::OVERRIDE_NO_OPTION);
        }

        return $priceOptions;
    }

    /**
     * @param string|null $validatingCarrier
     * @return PricingOption[]
     */
    protected static function makePricingOptionForValidatingCarrier($validatingCarrier)
    {
        $opt = [];

        if ($validatingCarrier !== null) {
            $po = new PricingOption(PricingOptionKey::OVERRIDE_VALIDATING_CARRIER);

            $po->carrierInformation = new CarrierInformation($validatingCarrier);

            $opt[] = $po;
        }

        return $opt;
    }

    /**
     * @param string|null $currency
     * @return PricingOption[]
     */
    protected static function makePricingOptionForCurrencyOverride($currency)
    {
        $opt = [];

        if ($currency !== null) {
            $po = new PricingOption(PricingOptionKey::OVERRIDE_CURRENCY);

            $po->currency = new Currency($currency);

            $opt[] = $po;
        }

        return $opt;
    }

    /**
     * @param string $overrideCode
     * @param string|array|null $options
     * @param PaxSegRef[] $references
     * @return PricingOption[]
     */
    protected function makePricingOptionWithOptionDetailAndRefs($overrideCode, $options, $references)
    {
        $opt = [];

        if ($options !== null) {
            $po = new PricingOption($overrideCode);

            $po->optionDetail = new OptionDetail($options);

            if (!empty($references)) {
                $po->paxSegTstReference = new PaxSegTstReference($references);
            }

            $opt[] = $po;
        }

        return $opt;
    }

    /**
     * @param \DateTime|null $dateOverride
     * @return PricingOption[]
     */
    protected static function loadDateOverride($dateOverride)
    {
        $opt = [];

        if ($dateOverride instanceof \DateTime) {
            $po = new PricingOption(PricingOptionKey::OVERRIDE_PRICING_DATE);

            $po->dateInformation = new DateInformation(
                DateInformation::OPT_DATE_OVERRIDE,
                $dateOverride
            );

            $opt[] = $po;
        }

        return $opt;
    }

    /**
     * @param string|null $posOverride
     * @return PricingOption[]
     */
    protected static function loadPointOverrides($posOverride)
    {
        $opt = [];

        if (!empty($posOverride)) {
            $po = new PricingOption(PricingOptionKey::OVERRIDE_POINT_OF_SALE);

            $po->locationInformation = new LocationInformation(
                LocationInformation::TYPE_POINT_OF_SALE,
                $posOverride
            );

            $opt[] = $po;
        }

        return $opt;
    }

    /**
     * @param FormOfPayment[] $formOfPayment
     * @return PricingOption[]
     */
    protected static function loadFormOfPaymentOverride($formOfPayment)
    {
        $opt = [];

        if (!empty($formOfPayment)) {
            $po = new PricingOption(PricingOptionKey::OVERRIDE_FORM_OF_PAYMENT);

            $po->formOfPaymentInformation = new FormOfPaymentInformation($formOfPayment);

            $opt[] = $po;
        }

        return $opt;
    }

    /**
     * @param FrequentFlyer[] $frequentFlyers
     * @return PricingOption[]
     */
    protected static function loadFrequentFlyerOverride($frequentFlyers)
    {
        $opt = [];

        if (!empty($frequentFlyers)) {
            $po = new PricingOption(PricingOptionKey::OVERRIDE_FREQUENT_FLYER_INFORMATION);

            $po->frequentFlyerInformation = new FrequentFlyerInformation($frequentFlyers);

            $opt[] = $po;
        }

        return $opt;
    }

    /**
     * @param PaxSegRef[] $references
     * @return PricingOption[]
     */
    protected static function loadReferences($references)
    {
        $opt = [];

        if (!empty($references)) {
            $po = new PricingOption(PricingOptionKey::OVERRIDE_PAX_SEG_ELEMENT_SELECTION);

            $po->paxSegTstReference = new PaxSegTstReference($references);

            $opt[] = $po;
        }

        return $opt;
    }

    /**
     * @param string[] $overrideOptions
     * @param PricingOption[] $priceOptions
     * @return PricingOption[]
     */
    protected static function makeOverrideOptions($overrideOptions, $priceOptions)
    {
        $opt = [];

        foreach ($overrideOptions as $overrideOption) {
            if (!self::hasPricingGroup($overrideOption, $priceOptions)) {
                $opt[] = new PricingOption($overrideOption);
            }
        }

        return $opt;
    }

    /**
     * Merges Pricing options
     *
     * @param PricingOption[] $existingOptions
     * @param PricingOption[] $newOptions
     * @return PricingOption[] merged array
     */
    protected static function mergeOptions($existingOptions, $newOptions)
    {
        if (!empty($newOptions)) {
            $existingOptions = array_merge(
                $existingOptions,
                $newOptions
            );
        }

        return $existingOptions;
    }
}
