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

namespace Amadeus\Client\Struct\Offer\Create;

/**
 * FareRecommendationReference
 *
 * @package Amadeus\Client\Struct\Offer\Create
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FareRecommendationReference
{
    const TYPE_FARE_RECOMMENDATION_NR = "FRN";
    const TYPE_MASTERPRICER_EXPERT_RECOMMENDATION_NR = "MPE";
    const TYPE_OTHER_ELEMENT_TATTOO = "OT";

    /**
     * self::TYPE_*
     *
     * @var string
     */
    public $referenceType;

    /**
     * @var int|string
     */
    public $uniqueReference;

    /**
     * FareRecommendationReference constructor.
     *
     * @param string $referenceType
     * @param int|string $uniqueReference
     */
    public function __construct($referenceType, $uniqueReference)
    {
        $this->referenceType = $referenceType;
        $this->uniqueReference = $uniqueReference;
    }
}
