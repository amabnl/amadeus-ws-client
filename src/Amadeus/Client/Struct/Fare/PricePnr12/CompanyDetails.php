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

namespace Amadeus\Client\Struct\Fare\PricePnr12;

/**
 * CompanyDetails
 *
 * @package Amadeus\Client\Struct\Fare\PricePnr12
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CompanyDetails
{
    const QUAL_AWARD_PUBLISHING = "M";
    const QUAL_CONTROLLING_CARRIER_OVERRIDE = "CC";

    /**
     * @var string
     */
    public $qualifier;

    /**
     * @var string
     */
    public $company;

    /**
     * CompanyDetails constructor.
     *
     * @param string $company
     * @param string $qualifier self::QUAL_*
     */
    public function __construct($company, $qualifier = self::QUAL_AWARD_PUBLISHING)
    {
        $this->company = $company;
        $this->qualifier = $qualifier;
    }
}
