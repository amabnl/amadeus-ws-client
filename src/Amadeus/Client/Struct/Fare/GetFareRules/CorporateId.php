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
 * CorporateId
 *
 * @package Amadeus\Client\Struct\Fare\GetFareRules
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CorporateId
{
    const QUAL_AMADEUS_NEGO_FARES = "RN";
    const QUAL_UNIFARES = "RU";

    /**
     * self::QUAL_*
     *
     * @var string
     */
    public $corporateQualifier;

    /**
     * @var string
     */
    public $identity;

    /**
     * CorporateId constructor.
     *
     * @param string $identity
     * @param string $corporateQualifier self::QUAL_*
     */
    public function __construct($identity, $corporateQualifier)
    {
        $this->identity = $identity;
        $this->corporateQualifier = $corporateQualifier;
    }
}
