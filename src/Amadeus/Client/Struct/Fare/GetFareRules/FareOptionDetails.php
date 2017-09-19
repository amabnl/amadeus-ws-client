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

namespace Amadeus\Client\Struct\Fare\GetFareRules;

/**
 * FareOptionDetails
 *
 * @package Amadeus\Client\Struct\Fare\CheckRules
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FareOptionDetails
{
    const QUAL_ORIGIN_TO_DESTINATION = "OD";
    const QUAL_DESTINATION_TO_ORIGIN = "DO";
    const QUAL_BOTH = "BD";

    /**
     * self::QUAL_*
     *
     * @var string
     */
    public $fareQualifier;

    /**
     * @var string
     */
    public $rateCategory;

    /**
     * @var string
     */
    public $amount;

    /**
     * @var string
     */
    public $percentage;

    /**
     * FareOptionDetails constructor.
     *
     * @param string $fareQualifier
     */
    public function __construct($fareQualifier)
    {
        $this->fareQualifier = $fareQualifier;
    }
}
