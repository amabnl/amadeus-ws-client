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

namespace Amadeus\Client\Struct\Hotel\Sell;

/**
 * DeliveringSystem
 *
 * @package Amadeus\Client\Struct\Hotel\Sell
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class DeliveringSystem
{
    const COMPANY_ERETAIL = "AERE";
    const COMPANY_ETRAVEL_MANAGEMENT = "AETM";
    const COMPANY_COMMAND_PAGE = "COMM";
    const COMPANY_SELL2_SELL_CONNECT = "SECO";
    const COMPANY_SELLING_PLATFORM_CLASSIC = "SELL";
    const COMPANY_NON_SPECIFIC_PRODUCT_FROM_SEL = "SEP";
    const COMPANY_WEBSERVICES = "WEBS";

    /**
     * self::COMPANY_*
     *
     * @var string
     */
    public $companyId;

    /**
     * DeliveringSystem constructor.
     *
     * @param string $companyId
     */
    public function __construct($companyId = self::COMPANY_WEBSERVICES)
    {
        $this->companyId = $companyId;
    }
}
