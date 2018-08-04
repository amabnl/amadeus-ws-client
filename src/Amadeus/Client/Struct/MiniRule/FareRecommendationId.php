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

namespace Amadeus\Client\Struct\MiniRule;

/**
 * FareRecommendationId
 *
 * @package Amadeus\Client\Struct\MiniRule
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FareRecommendationId
{
    /**
     * Fare Recommendation Number
     */
    const REFERENCE_TYPE_FARE_RECOMMENDATION_NUMBER = "FRN";

    /**
     * To retrieve minirules for ALL pricings.
     */
    const PRICING_ID_ALL = "ALL";

    /**
     * self::REFERENCE_TYPE_*
     *
     * @var string
     */
    public $referenceType = self::REFERENCE_TYPE_FARE_RECOMMENDATION_NUMBER;

    /**
     * Identification number or self::PRICING_ID_ALL for all
     *
     * @var int|string
     */
    public $uniqueReference;

    /**
     * FareRecommendationId constructor.
     *
     * @param int|string $pricingId a reference or self::PRICING_ID_ALL
     */
    public function __construct($pricingId)
    {
        $this->uniqueReference = $pricingId;
    }
}
