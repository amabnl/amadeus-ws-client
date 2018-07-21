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

namespace Amadeus\Client\RequestOptions\MiniRule;

use Amadeus\Client\LoadParamsFromArray;

/**
 * MiniRule Pricing
 *
 * @package Amadeus\Client\RequestOptions\MiniRule
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Pricing extends LoadParamsFromArray
{
    /**
     * To return minirules for all pricings of a given type
     */
    const ALL_PRICINGS = "ALL";

    /**
     * Offer element tattoo
     *
     * Only to be used with MiniRule_GetFromPricingRec
     */
    const TYPE_OFFER = "OF";

    /**
     * Transitional Stored Ticket
     *
     * Only to be used with MiniRule_GetFromPricingRec
     */
    const TYPE_TST = "TST";

    /**
     * Product Quotation Record Reference
     *
     * Only to be used with MiniRule_GetFromPricingRec
     */
    const TYPE_PROD_QUOTATION = "PQR";

    /**
     * Fare Recommendation Number
     *
     * Only to be used with MiniRule_GetFromPricing
     */
    const TYPE_FARE_RECOMMENDATION_NUMBER = "FRN";

    /**
     * self::ALL_PRICINGS to indicate ALL or a number indicating a specific pricing
     *
     * @var int|string
     */
    public $id;

    /**
     * self::TYPE_*
     *
     * Only necessary for MiniRule_GetFromPricingRec:
     * For MiniRule_GetFromPricing, "FRN" is assumed.
     *
     * @var string
     */
    public $type;
}
