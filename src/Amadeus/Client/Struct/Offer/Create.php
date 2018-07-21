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

namespace Amadeus\Client\Struct\Offer;

use Amadeus\Client\RequestOptions\Offer\AirRecommendation;
use Amadeus\Client\RequestOptions\OfferCreateOptions;
use Amadeus\Client\RequestOptions\Offer\ProductReference as ProdRefOpt;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Offer\Create\AirPricingRecommendation;
use Amadeus\Client\Struct\Offer\Create\ProductReference;
use Amadeus\Client\Struct\Offer\Create\TotalPrice;

/**
 * Offer_CreateOffer request structure
 *
 * @package Amadeus\Client\Struct\Offer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Create extends BaseWsMessage
{
    /**
     * @var Create\AirPricingRecommendation[]
     */
    public $airPricingRecommendation = [];

    /**
     * @var Create\TotalPrice
     */
    public $totalPrice;

    /**
     * @var Create\ProductReference
     */
    public $productReference;

    /**
     * Offer_CreateOffer constructor.
     *
     * @param OfferCreateOptions $options
     */
    public function __construct(OfferCreateOptions $options)
    {
        if (!empty($options->airRecommendations)) {
            $this->loadAirRecommendations($options->airRecommendations);
        } else {
            $this->loadProdRef($options->productReferences);
        }

        $this->loadMarkup($options->markupAmount, $options->markupCurrency);
    }

    /**
     * @param AirRecommendation[] $airRecommendations
     */
    protected function loadAirRecommendations($airRecommendations)
    {
        foreach ($airRecommendations as $airRecommendation) {
            $this->airPricingRecommendation[] = new AirPricingRecommendation($airRecommendation);
        }
    }

    /**
     * @param ProdRefOpt[] $productReferences
     */
    protected function loadProdRef($productReferences)
    {
        $this->productReference = new ProductReference($productReferences);
    }

    /**
     * @param int|null $amount
     * @param string|null $currency
     */
    protected function loadMarkup($amount, $currency)
    {
        if (!is_null($amount) && !is_null($currency)) {
            $this->totalPrice = new TotalPrice($amount, $currency);
        }
    }
}
