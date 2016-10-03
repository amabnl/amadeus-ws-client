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
use Amadeus\Client\RequestOptions\Fare\PricePnr\AwardPricing;
use Amadeus\Client\RequestOptions\Fare\PricePnr\ExemptTax;
use Amadeus\Client\RequestOptions\Fare\PricePnr\FareBasis;
use Amadeus\Client\RequestOptions\Fare\PricePnr\ObFee;
use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxDiscount;
use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;
use Amadeus\Client\RequestOptions\Fare\PricePnr\Tax;
use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Fare\PricePnr13\CarrierInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\CriteriaDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\Currency;
use Amadeus\Client\Struct\Fare\PricePnr13\DateInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\DiscountPenaltyDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\FirstCurrencyDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\FrequentFlyerInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\FrequentTravellerDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\LocationInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\OptionDetail;
use Amadeus\Client\Struct\Fare\PricePnr13\PaxSegTstReference;
use Amadeus\Client\Struct\Fare\PricePnr13\PenDisInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionGroup;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionKey;
use Amadeus\Client\Struct\Fare\PricePnr13\TaxData;
use Amadeus\Client\Struct\Fare\PricePnr13\TaxInformation;

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
        $this->pricingOptionGroup = $this->loadPricingOptionsFromRequestOptions($options);
    }

    /**
     * Load an array of PricingOptionGroup objects from the Pricing request options.
     *
     * Extracted because this method is also used in the InformativePricingWithoutPnr messages.
     *
     * @param FarePricePnrWithBookingClassOptions $options
     * @return PricingOptionGroup[]
     */
    public static function loadPricingOptionsFromRequestOptions(FarePricePnrWithBookingClassOptions $options)
    {
        $priceOptions = [];

        if ($options->validatingCarrier !== null) {
            $priceOptions[] = self::makePricingOptionForValidatingCarrier($options->validatingCarrier);
        }

        if ($options->currencyOverride !== null) {
            $priceOptions[] = self::makePricingOptionForCurrencyOverride($options->currencyOverride);
        }

        if ($options->pricingsFareBasis !== null) {
            foreach ($options->pricingsFareBasis as $pricingFareBasis) {
                $priceOptions[] = self::makePricingOptionFareBasisOverride($pricingFareBasis);
            }
        }

        if (!empty($options->overrideOptions)) {
            foreach ($options->overrideOptions as $overrideOption) {
                if (!self::hasPricingGroup($overrideOption, $priceOptions)) {
                    $priceOptions[] = new PricingOptionGroup($overrideOption);
                }
            }
        }

        if ($options->corporateNegoFare !== null) {
            $priceOptions[] = self::loadCorpNegoFare($options->corporateNegoFare);
        }

        if (!empty($options->corporateUniFares)) {
            $priceOptions[] = self::loadCorpUniFares($options->corporateUniFares);

            if (!empty($options->awardPricing)) {
                $priceOptions[] = self::loadAwardPricing($options->awardPricing);
            }
        }

        if (!empty($options->obFees)) {
            $priceOptions[] = self::loadObFees($options->obFees, $options->obFeeRefs);
        }

        if (!empty($options->paxDiscountCodes)) {
            $priceOptions[] = self::loadPaxDiscount($options->paxDiscountCodes, $options->paxDiscountCodeRefs);
        }

        if (!empty($options->pointOfSaleOverride)) {
            $priceOptions[] = self::loadPointOfSaleOverride($options->pointOfSaleOverride);
        }

        if (!empty($options->pointOfTicketingOverride)) {
            $priceOptions[] = self::loadPointOfTicketingOverride($options->pointOfTicketingOverride);
        }

        if (!empty($options->pricingLogic)) {
            $priceOptions[] = self::loadPricingLogic($options->pricingLogic);
        }

        if (!empty($options->ticketType)) {
            $priceOptions[] =  self::loadTicketType($options->ticketType);
        }

        if (!empty($options->taxes)) {
            $priceOptions[] = self::loadTaxes($options->taxes);
        }

        if (!empty($options->exemptTaxes)) {
            $priceOptions[] = self::loadExemptTaxes($options->exemptTaxes);
        }

        if ($options->pastDatePricing instanceof \DateTime) {
            $priceOptions[] = self::loadPastDate($options->pastDatePricing);
        }

        if (!empty($options->references)) {
            $priceOptions[] = self::loadReferences($options->references);
        }


        // All options processed, no options found:
        if (empty($priceOptions)) {
            $priceOptions[] = new PricingOptionGroup(PricingOptionKey::OPTION_NO_OPTION);
        }

        return $priceOptions;
    }

    /**
     * @param string $validatingCarrier
     * @return PricePnr13\PricingOptionGroup
     */
    protected static function makePricingOptionForValidatingCarrier($validatingCarrier)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_VALIDATING_CARRIER);

        $po->carrierInformation = new CarrierInformation($validatingCarrier);

        return $po;
    }

    /**
     * @param string $currency
     * @return PricePnr13\PricingOptionGroup
     */
    protected static function makePricingOptionForCurrencyOverride($currency)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_CURRENCY_OVERRIDE);

        $po->currency = new Currency($currency, FirstCurrencyDetails::QUAL_CURRENCY_OVERRIDE);

        return $po;
    }

    /**
     * @param FareBasis $pricingFareBasis
     * @return PricePnr13\PricingOptionGroup
     */
    protected static function makePricingOptionFareBasisOverride($pricingFareBasis)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_BASIS_SIMPLE_OVERRIDE);

        $po->optionDetail = new OptionDetail();

        //Support for legacy fareBasisPrimaryCode to be removed when breaking BC:
        $po->optionDetail->criteriaDetails[] = new CriteriaDetails(
            $pricingFareBasis->fareBasisPrimaryCode . $pricingFareBasis->fareBasisCode
        );

        //Support for legacy segmentReference to be removed when breaking BC:
        $po->paxSegTstReference = new PaxSegTstReference(
            $pricingFareBasis->segmentReference,
            $pricingFareBasis->references
        );

        return $po;
    }

    /**
     * Load corporate negofare
     *
     * @param string $corporateNegoFare
     * @return PricingOptionGroup
     */
    protected static function loadCorpNegoFare($corporateNegoFare)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_CORPORATE_NEGOTIATED_FARES);

        $po->optionDetail = new OptionDetail();
        $po->optionDetail->criteriaDetails[] = new CriteriaDetails($corporateNegoFare);

        return $po;
    }

    /**
     * Load corporate unifares
     *
     * @param string[] $corporateUniFares
     * @return PricingOptionGroup
     */
    protected static function loadCorpUniFares($corporateUniFares)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_CORPORATE_UNIFARES);

        $po->optionDetail = new OptionDetail();

        foreach ($corporateUniFares as $corporateUniFare) {
            $po->optionDetail->criteriaDetails[] = new CriteriaDetails($corporateUniFare);
        }

        return $po;
    }

    /**
     * @param AwardPricing $awardPricing
     * @return PricingOptionGroup
     */
    protected static function loadAwardPricing($awardPricing)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_AWARD_PRICING);

        $po->optionDetail = new OptionDetail();

        $po->carrierInformation = new CarrierInformation($awardPricing->carrier);

        $po->frequentFlyerInformation = new FrequentFlyerInformation();
        $po->frequentFlyerInformation->frequentTravellerDetails[] = new FrequentTravellerDetails(
            $awardPricing->tierLevel
        );

        return $po;
    }

    /**
     * Load OB Fees
     *
     * @param ObFee[] $obFees
     * @param PaxSegRef[] $obFeeRefs
     * @return PricingOptionGroup
     */
    protected static function loadObFees($obFees, $obFeeRefs)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_OB_FEES);

        $po->penDisInformation = new PenDisInformation(PenDisInformation::QUAL_OB_FEES);

        foreach ($obFees as $obFee) {
            $amountType = (!empty($obFee->amount)) ?
                DiscountPenaltyDetails::AMOUNTTYPE_FIXED_WHOLE_AMOUNT :
                DiscountPenaltyDetails::AMOUNTTYPE_PERCENTAGE;

            $rate = (!empty($obFee->amount)) ? $obFee->amount : $obFee->percentage;

            $po->penDisInformation->discountPenaltyDetails[] = new DiscountPenaltyDetails(
                $obFee->rate,
                self::makeObFeeFunction($obFee->include),
                $amountType,
                $rate,
                $obFee->currency
            );
        }

        if (!empty($obFeeRefs)) {
            $po->paxSegTstReference = new PaxSegTstReference(null, $obFeeRefs);
        }

        return $po;
    }

    /**
     * @param string[] $paxDiscount
     * @param PaxSegRef[] $paxDiscountCodeRefs
     * @return PricingOptionGroup
     */
    protected static function loadPaxDiscount($paxDiscount, $paxDiscountCodeRefs)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_PASSENGER_DISCOUNT_PTC);

        $po->penDisInformation = new PenDisInformation(PenDisInformation::QUAL_DISCOUNT);

        foreach ($paxDiscount as $discount) {
            $po->penDisInformation->discountPenaltyDetails[] = new DiscountPenaltyDetails($discount);
        }

        if (!empty($paxDiscountCodeRefs)) {
            $po->paxSegTstReference = new PaxSegTstReference(null, $paxDiscountCodeRefs);
        }

        return $po;
    }

    /**
     * @param string $posOverride
     * @return PricingOptionGroup
     */
    protected static function loadPointOfSaleOverride($posOverride)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_POINT_OF_SALE_OVERRIDE);

        $po->locationInformation = new LocationInformation(
            LocationInformation::TYPE_POINT_OF_SALE,
            $posOverride
        );

        return $po;
    }

    /**
     * @param string $potOverride
     * @return PricingOptionGroup
     */
    protected static function loadPointOfTicketingOverride($potOverride)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_POINT_OF_TICKETING_OVERRIDE);

        $po->locationInformation = new LocationInformation(
            LocationInformation::TYPE_POINT_OF_TICKETING,
            $potOverride
        );

        return $po;
    }

    /**
     * @param string $pricingLogic
     * @return PricingOptionGroup
     */
    protected static function loadPricingLogic($pricingLogic)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_PRICING_LOGIC);

        $po->optionDetail = new OptionDetail();
        $po->optionDetail->criteriaDetails[] = new CriteriaDetails($pricingLogic);

        return $po;
    }

    /**
     * @param string $ticketType
     * @return PricingOptionGroup
     */
    protected static function loadTicketType($ticketType)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_TICKET_TYPE);

        $po->optionDetail = new OptionDetail();
        $po->optionDetail->criteriaDetails[] = new CriteriaDetails($ticketType);

        return $po;
    }

    /**
     * @param Tax[] $taxes
     * @return PricingOptionGroup
     */
    protected static function loadTaxes($taxes)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_ADD_TAX);

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

        return $po;
    }

    /**
     * @param ExemptTax[] $exemptTaxes
     * @return PricingOptionGroup
     */
    protected static function loadExemptTaxes($exemptTaxes)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_EXEMPT_FROM_TAX);

        foreach ($exemptTaxes as $tax) {
            $po->taxInformation[] = new TaxInformation(
                $tax->countryCode,
                $tax->taxNature
            );
        }

        return $po;
    }

    /**
     * @param \DateTime $pastDate
     * @return PricingOptionGroup
     */
    protected static function loadPastDate(\DateTime $pastDate)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_PAST_DATE_PRICING);

        $po->dateInformation = new DateInformation(
            DateInformation::OPT_DATE_OVERRIDE,
            $pastDate
        );

        return $po;
    }

    /**
     * @param PaxSegRef[] $references
     * @return PricingOptionGroup
     */
    protected static function loadReferences($references)
    {
        $po = new PricingOptionGroup(PricingOptionKey::OPTION_PAX_SEGMENT_TST_SELECTION);

        $po->paxSegTstReference = new PaxSegTstReference(null, $references);

        return $po;
    }

    /**
     * Make the correct function code
     *
     * @param bool $include
     * @return string
     */
    protected static function makeObFeeFunction($include)
    {
        return ($include === true) ? ObFee::FUNCTION_INCLUDE : ObFee::FUNCTION_EXCLUDE;
    }



    /**
     * Avoid double pricing groups when combining an explicitly provided override option with a specific parameter
     * that uses the same override option.
     *
     * Backwards compatibility with PricePnrWithBookingClass12
     *
     * @param string $optionKey
     * @param PricingOptionGroup[] $priceOptions
     * @return bool
     */
    protected static function hasPricingGroup($optionKey, $priceOptions)
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
