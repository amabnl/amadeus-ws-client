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
 * ExclusionDetail
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ExclusionDetail
{
    const IDENT_EXCLUDED = 'X';

    const QUAL_AIRPORT = 'A';
    const QUAL_CITY = 'C';

    /**
     * @var string
     */
    public $exclusionIdentifier;

    /**
     * @var string
     */
    public $locationId;

    /**
     * self::QUAL_*
     *
     * @var string
     */
    public $airportCityQualifier;

    /**
     * ExclusionDetail constructor.
     *
     * @param string $locationId
     * @param string $exclusionIdentifier
     */
    public function __construct($locationId, $exclusionIdentifier = self::IDENT_EXCLUDED)
    {
        $this->exclusionIdentifier = $exclusionIdentifier;
        $this->locationId = $locationId;
    }
}
