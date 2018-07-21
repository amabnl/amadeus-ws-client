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
 * FareProductDetail
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FareProductDetail
{
    const TYPE_ATPCO_NEGO_FARES_CAT35 = "RA";
    const TYPE_DDF_BASED_ON_PUBLIC_FARES = "RD";
    const TYPE_DDF_BASED_ON_PRIVATE_FARES = "RDV";
    const TYPE_AMADEUS_NEGO_FARES = "RN";
    const TYPE_PUBLISHED_FARES = "RP";
    const TYPE_UNIFARES = "RU";
    const TYPE_ATPCO_PRIVATE_FARES_CAT15 = "RV";

    /**
     * @var string
     */
    public $fareBasis;

    /**
     * self::TYPE_*
     *
     * @var string[]
     */
    public $fareType = [];

    /**
     * FareProductDetail constructor.
     *
     * @param string|null $fareBasis
     * @param string|null $fareType
     */
    public function __construct($fareBasis = null, $fareType = null)
    {
        $this->fareBasis = $fareBasis;
        if (!is_null($fareType)) {
            $this->fareType[] = $fareType;
        }
    }
}
