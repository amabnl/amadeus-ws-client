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

use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Fare\PricePnr12\AttributeDetails;
use Amadeus\Client\Struct\Fare\PricePnr12\OverrideInformation;
use Amadeus\Client\Struct\Fare\PricePnr12\PricingFareBase;
use Amadeus\Client\Struct\Fare\PricePnr12\ValidatingCarrier;

/**
 * Fare_PricePNRWithBookingClass v 12 and lower structure
 *
 * @package Amadeus\Client\Struct\Fare
 * @author dieter <dieter.devlieghere@benelux.amadeus.com>
 */
class PricePNRWithBookingClass12 extends BaseWsMessage
{
    public $pnrLocatorData;

    public $paxSegReference;
    /**
     * @var PricePnr12\OverrideInformation
     */
    public $overrideInformation;

    public $dateOverride;
    /**
     * @var PricePnr12\ValidatingCarrier
     */
    public $validatingCarrier;

    public $cityOverride;

    public $currencyOverride;

    public $taxDetails;

    public $discountInformation;
    /**
     * @var PricePnr12\PricingFareBase[]
     */
    public $pricingFareBase = [];

    public $flightInformation;

    public $openSegmentsInformation;

    public $bookingClassSelection;

    public $fopInformation;

    public $carrierAgreements;

    public $frequentFlyerInformation;

    public $instantPricingOption;

    /**
     * PricePNRWithBookingClass12 constructor.
     *
     * @param FarePricePnrWithBookingClassOptions $options
     */
    public function __construct(FarePricePnrWithBookingClassOptions $options)
    {
        if (count($options->overrideOptions) === 0) {
            $this->overrideInformation = new OverrideInformation(AttributeDetails::OVERRIDE_NO_OPTION);
        } else {
            $this->overrideInformation = new OverrideInformation();

            foreach ($options->overrideOptions as $overrideOption) {
                $this->overrideInformation->attributeDetails[] = new AttributeDetails($overrideOption);
            }
        }

        if (is_string($options->validatingCarrier)) {
            $this->validatingCarrier = new ValidatingCarrier($options->validatingCarrier);
        }

        if (in_array(AttributeDetails::OVERRIDE_FAREBASIS, $options->overrideOptions)) {
            foreach ($options->pricingsFareBasis as $pricingFareBasis) {
                $this->pricingFareBase[] = new PricingFareBase($pricingFareBasis);
            }
        }
    }
}
