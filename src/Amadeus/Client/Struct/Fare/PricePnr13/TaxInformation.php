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

namespace Amadeus\Client\Struct\Fare\PricePnr13;

/**
 * TaxInformation
 *
 * @package Amadeus\Client\Struct\Fare\PricePnr13
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TaxInformation
{
    const QUALIFIER_TAX = 7;

    /**
     * @var string
     */
    public $taxQualifier = self::QUALIFIER_TAX;

    /**
     * @var TaxType
     */
    public $taxType;

    /**
     * @var string
     */
    public $taxNature;

    /**
     * @var TaxData
     */
    public $taxData;

    /**
     * TaxInformation constructor.
     *
     * @param string $countryCode
     * @param string $taxNature
     * @param string|null $amountPercentage TaxData::QUALIFIER_*
     * @param int|null $rate
     */
    public function __construct($countryCode, $taxNature, $amountPercentage = null, $rate = null)
    {
        $this->taxType = new TaxType($countryCode);
        $this->taxNature = $taxNature;
        if (!is_null($amountPercentage) && !is_null($rate)) {
            $this->taxData = new TaxData($rate, $amountPercentage);
        }
    }
}
