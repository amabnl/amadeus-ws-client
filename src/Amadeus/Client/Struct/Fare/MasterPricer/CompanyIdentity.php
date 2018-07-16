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
 * CompanyIdentity
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CompanyIdentity
{
    const QUAL_PREFERRED = 'F';
    const QUAL_MANDATORY = 'M';
    const QUAL_NIGHT_CLASS = 'N';
    const QUAL_FORCE_FULLAIRLINE_RECOMMENDATION = 'O';
    /**
     * @deprecated
     */
    const QUAL_POLLED = 'P';
    /**
     * @deprecated
     */
    const QUAL_FARE_FAMILY_REPARTITION = 'R';
    const QUAL_CARRIERS_LIST_BYPASS_BSP_CHECKS = 'T';
    const QUAL_MANDATORY_VALIDATING_CARRIER = 'V';
    const QUAL_EXCLUDED_VALIDATING_CARRIER = 'W';
    const QUAL_EXCLUDED = 'X';

    const QUAL_ITINERARY_MAJOR_CABIN = 'MC';
    const QUAL_ITINERARY_MANDATORY_ALL_SEGMENTS = 'MD';
    const QUAL_ITINERARY_RECOMMENDED_AT_LEAST_ONE_SEGMENT = 'RC';

    /**
     * self::QUAL_*
     *
     * @var string
     */
    public $carrierQualifier;

    /**
     * @var string[]
     */
    public $carrierId = [];

    /**
     * CompanyIdentity constructor.
     *
     * @param string $carrierQualifier self::QUAL_*
     * @param string[] $carrierId
     */
    public function __construct($carrierQualifier, $carrierId)
    {
        $this->carrierQualifier = $carrierQualifier;
        $this->carrierId = $carrierId;
    }
}
