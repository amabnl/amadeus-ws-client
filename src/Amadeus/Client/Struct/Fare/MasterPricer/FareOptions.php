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

/**
 * FareOptions
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
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

    public $ticketingPriceScheme;
    /**
     * @var FeeIdDescription
     */
    public $feeIdDescription;
    /**
     * @var ConversionRate
     */
    public $conversionRate;

    public $formOfPayment;

    public $frequentTravellerInfo;

    public $monetaryCabinInfo;

    /**
     * FareOptions constructor.
     *
     * @param array $flightOptions List of flight / fare options
     * @param array $corpCodesUniFares list of Corporate codes for Corporate Unifares
     * @param bool $tickPreCheck Do Ticketability pre-check?
     */
    public function __construct(array $flightOptions, array $corpCodesUniFares, $tickPreCheck)
    {
        $this->pricingTickInfo = new PricingTickInfo();
        $this->pricingTickInfo->pricingTicketing = new PricingTicketing();

        if ($tickPreCheck === true) {
            $this->pricingTickInfo->pricingTicketing->priceType[] = PricingTicketing::PRICETYPE_TICKETABILITY_PRECHECK;
        }

        foreach ($flightOptions as $flightOption) {
            $this->pricingTickInfo->pricingTicketing->priceType[] = $flightOption;

            if ($flightOption === PricingTicketing::PRICETYPE_CORPORATE_UNIFARES) {
                $this->corporate = new Corporate();
                $this->corporate->corporateId[] = new CorporateId($corpCodesUniFares);
            }
        }
    }
}
