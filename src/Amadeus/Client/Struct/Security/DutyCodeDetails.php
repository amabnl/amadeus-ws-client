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

namespace Amadeus\Client\Struct\Security;

/**
 * DutyCodeDetails
 *
 * @package Amadeus\Client\Struct\Security
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DutyCodeDetails
{
    /**
     * Definition of Dutycode Reference qualifier "Duty Code"
     *
     * See Amadeus Core Webservices documentation
     * [Reference qualifier codesets (Ref: 1153 IA 01.2.57)]
     * @var string
     */
    const RQ_DUTYCODE = "DUT";

    /**
     * @var string
     */
    public $referenceQualifier;
    /**
     * @var string
     */
    public $referenceIdentifier;

    /**
     * DutyCodeDetails constructor.
     *
     * @param string $dutyCode
     * @param string $qual
     */
    public function __construct($dutyCode, $qual = self::RQ_DUTYCODE)
    {
        $this->referenceIdentifier = $dutyCode;
        $this->referenceQualifier = $qual;
    }
}
