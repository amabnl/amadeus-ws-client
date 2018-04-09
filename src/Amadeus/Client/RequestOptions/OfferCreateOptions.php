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

namespace Amadeus\Client\RequestOptions;

/**
 * Offer_Create Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class OfferCreateOptions extends Base
{
    /**
     * AIR recommendations to convert to an offer
     *
     * @var Offer\AirRecommendation[]
     */
    public $airRecommendations = [];

    /**
     * Up to 2 product references (Hotel or Car)
     *
     * @var Offer\ProductReference[]
     */
    public $productReferences = [];

    /**
     * Add an extra mark-up cost
     *
     * (Not for Hotel offers)
     *
     * @var int
     */
    public $markupAmount;

    /**
     * Currency in which the markup is expressed
     *
     * @var string
     */
    public $markupCurrency;
}
