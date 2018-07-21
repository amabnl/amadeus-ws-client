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
 * CabinId
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 */
class CabinId
{
    const CABIN_ECONOMY = "Y";
    const CABIN_ECONOMY_STANDARD = "M";
    const CABIN_ECONOMY_PREMIUM = "W";
    const CABIN_BUSINESS = "C";
    const CABIN_FIRST_SUPERSONIC = "F";

    /**
     * Major cabin
     */
    const CABINOPT_MAJOR = "MC";
    /**
     * Mandatory cabin for all segments
     */
    const CABINOPT_MANDATORY = "MD";
    /**
     * Recommended cabin to be used at least one segment
     */
    const CABINOPT_RECOMMENDED = "RC";

    /**
     * MC Major cabin
     * MD Mandatory cabin for all segments
     * RC Recommended cabin to be used at least one segment
     *
     * @var string
     */
    public $cabinQualifier;

    /**
     * self::CABIN_*
     *
     * @var string
     */
    public $cabin;

    /**
     * CabinId constructor.
     *
     * @param string $cabinCode self::CABIN_*
     * @param string|null $cabinOption self::CABINOPT_*
     */
    public function __construct($cabinCode, $cabinOption = null)
    {
        $this->cabin = $cabinCode;
        $this->cabinQualifier = $cabinOption;
    }
}
