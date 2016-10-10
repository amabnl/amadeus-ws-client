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

use Amadeus\Client\RequestOptions\Fare\PricePnr\AwardPricing;
use Amadeus\Client\RequestOptions\Fare\PricePnr\ExemptTax;
use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;
use Amadeus\Client\RequestOptions\Fare\PricePnr\Tax;
use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
use Amadeus\Client\RequestOptions\FarePricePnrWithLowerFaresOptions as LowerFareOpt;
use Amadeus\Client\RequestOptions\FarePricePnrWithLowestFareOptions as LowestFareOpt;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Fare\PricePnr12\AttributeDetails;
use Amadeus\Client\Struct\Fare\PricePnr12\CarrierAgreements;
use Amadeus\Client\Struct\Fare\PricePnr12\CityDetail;
use Amadeus\Client\Struct\Fare\PricePnr12\CurrencyOverride;
use Amadeus\Client\Struct\Fare\PricePnr12\DateOverride;
use Amadeus\Client\Struct\Fare\PricePnr12\DiscountInformation;
use Amadeus\Client\Struct\Fare\PricePnr12\FrequentFlyerInformation;
use Amadeus\Client\Struct\Fare\PricePnr12\OverrideInformation;
use Amadeus\Client\Struct\Fare\PricePnr12\PenDisData;
use Amadeus\Client\Struct\Fare\PricePnr12\PenDisInformation;
use Amadeus\Client\Struct\Fare\PricePnr12\PricingFareBase;
use Amadeus\Client\Struct\Fare\PricePnr12\RefDetails;
use Amadeus\Client\Struct\Fare\PricePnr12\ReferenceQualifier;
use Amadeus\Client\Struct\Fare\PricePnr12\TaxDetails;
use Amadeus\Client\Struct\Fare\PricePnr12\ValidatingCarrier;
use Amadeus\Client\Struct\OptionNotSupportedException;

/**
 * Fare_PricePNRWithBookingClass v 12 and lower structure
 *
 * @package Amadeus\Client\Struct\Fare
 * @author dieter <dieter.devlieghere@benelux.amadeus.com>
 */
class PricePNRWithBookingClass12 extends BaseWsMessage
{
    public $pnrLocatorData;

    /**
     * @var PricePnr12\PaxSegReference
     */
    public $paxSegReference;

    /**
     * @var PricePnr12\OverrideInformation
     */
    public $overrideInformation;

    /**
     * @var PricePnr12\DateOverride
     */
    public $dateOverride;

    /**
     * @var PricePnr12\ValidatingCarrier
     */
    public $validatingCarrier;

    /**
     * @var PricePnr12\CityOverride
     */
    public $cityOverride;

    /**
     * @var PricePnr12\CurrencyOverride
     */
    public $currencyOverride;

    /**
     * @var PricePnr12\TaxDetails[]
     */
    public $taxDetails = [];

    /**
     * @var PricePnr12\DiscountInformation[]
     */
    public $discountInformation = [];
    /**
     * @var PricePnr12\PricingFareBase[]
     */
    public $pricingFareBase = [];

    public $flightInformation;

    public $openSegmentsInformation;

    public $bookingClassSelection;

    public $fopInformation;

    /**
     * @var CarrierAgreements
     */
    public $carrierAgreements;

    /**
     * @var FrequentFlyerInformation
     */
    public $frequentFlyerInformation;

    public $instantPricingOption;

    /**
     * PricePNRWithBookingClass12 constructor.
     *
     * @param FarePricePnrWithBookingClassOptions|LowerFareOpt|LowestFareOpt $options
     */
    public function __construct($options)
    {
        $this->checkUnsupportedOptions($options);

        $this->overrideInformation = new OverrideInformation();

        $this->loadOverrideOptions($options);

        $this->loadValidatingCarrier($options);

        $this->loadFareBasis($options);

        $this->loadCorporateFares($options);

        $this->loadPaxDiscount($options->paxDiscountCodes, $options->paxDiscountCodeRefs);

        $this->loadOverrideLocations($options);

        $this->loadTaxOptions($options);

        $this->loadReferences($options->references);

        $this->loadPastDate($options->pastDatePricing);

        //No Options?
        if (empty($this->overrideInformation->attributeDetails)) {
            $this->overrideInformation->attributeDetails[] = new AttributeDetails(
                AttributeDetails::OVERRIDE_NO_OPTION
            );
        }
    }

    /**
     * @param FarePricePnrWithBookingClassOptions|LowerFareOpt|LowestFareOpt $options
     * @throws OptionNotSupportedException
     */
    protected function checkUnsupportedOptions($options)
    {
        if (!empty($options->obFees)) {
            throw new OptionNotSupportedException('OB Fees option not supported in version 12 or lower');
        }

        if (!empty($options->pricingLogic)) {
            throw new OptionNotSupportedException('Pricing Logic option not supported in version 12 or lower');
        }
    }

    /**
     * @param FarePricePnrWithBookingClassOptions|LowerFareOpt|LowestFareOpt $options
     */
    protected function loadOverrideOptions($options)
    {
        if (count($options->overrideOptions) !== 0) {
            foreach ($options->overrideOptions as $overrideOption) {
                $this->overrideInformation->attributeDetails[] = new AttributeDetails($overrideOption);
            }
        }

        if (!empty($options->currencyOverride)) {
            $this->currencyOverride = new CurrencyOverride($options->currencyOverride);
        }

        if (!empty($options->ticketType)) {
            $this->overrideInformation->attributeDetails[] = new AttributeDetails(
                $this->convertTicketType($options->ticketType)
            );
        }
    }

    /**
     * Convert new codes (Price PNR version 13+) to old format
     *
     * @param string $optionsTicketType
     * @return string|null
     */
    protected function convertTicketType($optionsTicketType)
    {
        $converted = null;

        $map = [
            FarePricePnrWithBookingClassOptions::TICKET_TYPE_ELECTRONIC => AttributeDetails::OVERRIDE_ELECTRONIC_TICKET,
            FarePricePnrWithBookingClassOptions::TICKET_TYPE_PAPER => AttributeDetails::OVERRIDE_PAPER_TICKET,
            FarePricePnrWithBookingClassOptions::TICKET_TYPE_BOTH => AttributeDetails::OVERRIDE_BOTH_TICKET,
        ];

        if (array_key_exists($optionsTicketType, $map)) {
            $converted = $map[$optionsTicketType];
        }

        return $converted;
    }

    /**
     * @param FarePricePnrWithBookingClassOptions|LowerFareOpt|LowestFareOpt $options
     */
    protected function loadCorporateFares($options)
    {
        if ($options->corporateNegoFare !== null) {
            $this->loadCorporateNegoFare($options->corporateNegoFare);
        }

        if (!empty($options->corporateUniFares)) {
            $this->loadCorporateUniFares($options->corporateUniFares);

            if (!empty($options->awardPricing)) {
                $this->loadAwardPricing($options->awardPricing);
            }
        }
    }

    /**
     * @param string $corporateNegoFare
     */
    protected function loadCorporateNegoFare($corporateNegoFare)
    {
        $this->overrideInformation->attributeDetails[] = new AttributeDetails(
            AttributeDetails::OVERRIDE_FARETYPE_CORPNR,
            $corporateNegoFare
        );
    }

    /**
     * @param string[] $corporateUniFares
     */
    protected function loadCorporateUniFares($corporateUniFares)
    {
        foreach ($corporateUniFares as $corporateUniFare) {
            $this->overrideInformation->attributeDetails[] = new AttributeDetails(
                AttributeDetails::OVERRIDE_FARETYPE_CORPUNI,
                $corporateUniFare
            );
        }
    }

    /**
     * @param AwardPricing $awardPricing
     */
    protected function loadAwardPricing($awardPricing)
    {
        $this->carrierAgreements = new CarrierAgreements($awardPricing->carrier);
        $this->frequentFlyerInformation = new FrequentFlyerInformation($awardPricing->tierLevel);
    }

    /**
     * @param FarePricePnrWithBookingClassOptions|LowerFareOpt|LowestFareOpt $options
     */
    protected function loadOverrideLocations($options)
    {
        if (!empty($options->pointOfSaleOverride)) {
            $this->cityOverride = new PricePnr12\CityOverride(
                $options->pointOfSaleOverride,
                CityDetail::QUAL_POINT_OF_SALE
            );
        }

        if (!empty($options->pointOfTicketingOverride)) {
            if (empty($this->cityOverride)) {
                $this->cityOverride = new PricePnr12\CityOverride();
            }
            $this->cityOverride->cityDetail[] = new PricePnr12\CityDetail(
                $options->pointOfTicketingOverride,
                CityDetail::QUAL_POINT_OF_TICKETING
            );
        }
    }

    /**
     * @param FarePricePnrWithBookingClassOptions|LowerFareOpt|LowestFareOpt $options
     */
    protected function loadValidatingCarrier($options)
    {
        if (is_string($options->validatingCarrier)) {
            $this->validatingCarrier = new ValidatingCarrier($options->validatingCarrier);
        }
    }

    /**
     * @param FarePricePnrWithBookingClassOptions|LowerFareOpt|LowestFareOpt $options
     *
     */
    protected function loadFareBasis($options)
    {
        $short = AttributeDetails::OVERRIDE_FAREBASIS; //Short var name because I get complaints from phpcs. No judging.
        if (in_array($short, $options->overrideOptions) && !empty($options->pricingsFareBasis)) {
            foreach ($options->pricingsFareBasis as $pricingFareBasis) {
                $this->pricingFareBase[] = new PricingFareBase($pricingFareBasis);
            }
        }
    }

    /**
     * @param FarePricePnrWithBookingClassOptions|LowerFareOpt|LowestFareOpt $options
     */
    protected function loadTaxOptions($options)
    {
        if (!empty($options->taxes)) {
            $this->loadTaxes($options->taxes);
        }

        if (!empty($options->exemptTaxes)) {
            $this->loadExemptTaxes($options->exemptTaxes);
        }
    }

    /**
     * @param Tax[] $taxes
     */
    protected function loadTaxes($taxes)
    {
        foreach ($taxes as $tax) {
            $this->taxDetails[] = new TaxDetails($tax);
        }
    }

    /**
     * @param ExemptTax[] $exemptTaxes
     */
    protected function loadExemptTaxes($exemptTaxes)
    {
        foreach ($exemptTaxes as $exemptTax) {
            $this->taxDetails[] = new TaxDetails($exemptTax);
        }
    }

    /**
     * @param string[] $paxDiscountCodes
     * @param PaxSegRef[] $refs
     */
    protected function loadPaxDiscount($paxDiscountCodes, $refs)
    {
        if (!empty($paxDiscountCodes)) {
            $tmp = new DiscountInformation();
            $tmp->penDisInformation = new PenDisInformation();
            $tmp->penDisInformation->infoQualifier = PenDisInformation::QUAL_DISCOUNT;

            foreach ($paxDiscountCodes as $discountCode) {
                $tmp->penDisInformation->penDisData[] = new PenDisData($discountCode);
            }

            if (!empty($refs)) {
                $tmp->referenceQualifier = new ReferenceQualifier();

                foreach ($refs as $ref) {
                    $tmp->referenceQualifier->refDetails[] = new RefDetails($ref->reference, $ref->type);
                }
            }

            $this->discountInformation[] = $tmp;
        }
    }

    /**
     * @param PaxSegRef[] $references
     */
    protected function loadReferences($references)
    {
        if (!empty($references)) {
            $this->paxSegReference = new PricePnr12\PaxSegReference();

            foreach ($references as $ref) {
                $this->paxSegReference->refDetails[] = new RefDetails(
                    $ref->reference,
                    $ref->type
                );
            }
        }
    }

    /**
     * @param \DateTime|null $pastDate
     */
    protected function loadPastDate($pastDate)
    {
        if ($pastDate instanceof \DateTime) {
            $this->dateOverride = new DateOverride(DateOverride::OPT_DATE_OVERRIDE, $pastDate);
        }
    }
}
