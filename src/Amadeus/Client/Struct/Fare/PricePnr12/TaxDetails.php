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

use Amadeus\Client\RequestOptions\Fare\PricePnr\ExemptTax;
use Amadeus\Client\RequestOptions\Fare\PricePnr\Tax;
use Amadeus\Client\Struct\Fare\PricePnr13\TaxData;
use Amadeus\Client\Struct\Fare\PricePnr13\TaxType;

/**
 * TaxDetails
 *
 * @package Amadeus\Client\Struct\Fare\PricePnr12
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TaxDetails
{
    const QUAL_TAX = 7;

    /**
     * @var int
     */
    public $taxQualifier = self::QUAL_TAX;

    /**
     * @var TaxIdentification
     */
    public $taxIdentification;

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
     * TaxDetails constructor.
     *
     * @param ExemptTax|Tax $tax
     */
    public function __construct($tax)
    {
        if ($tax instanceof Tax) {
            $this->taxIdentification = new TaxIdentification(TaxIdentification::IDENT_ADD_TAX);

            $qualifier = (!empty($tax->amount)) ? TaxData::QUALIFIER_AMOUNT : TaxData::QUALIFIER_PERCENTAGE;
            $rate = (!empty($tax->amount)) ? $tax->amount : $tax->percentage;
            $this->taxData = new TaxData($rate, $qualifier);
        } elseif ($tax instanceof ExemptTax) {
            $this->taxIdentification = new TaxIdentification(TaxIdentification::IDENT_EXEMPT_TAX);
        }

        $this->taxType = new TaxType($tax->countryCode);
        $this->taxNature = $tax->taxNature;
    }
}
