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

namespace Amadeus\Client\Struct\DocRefund\UpdateRefund;

use Amadeus\Client\RequestOptions\DocRefund\TaxData;

/**
 * TaxDetailsInformation
 *
 * @package Amadeus\Client\Struct\DocRefund\UpdateRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TaxDetailsInformation
{
    const CATEGORY_ADDITIONAL_COLLECTION = "700";
    const CATEGORY_PAID = "701";
    const CATEGORY_CURRENT = "702";
    const CATEGORY_TOTAL_AMOUNT_OF_ALL_PASSENGER_FACILITY_CHARGES = "703";
    const CATEGORY_TOTAL_TAXES = "704";
    const CATEGORY_INCLUDE_DEPARTURE_TAXES_ONLY = "D";
    const CATEGORY_TAX_EXEMPT = "E";
    const CATEGORY_TAXES_INCLUDED = "I";
    const CATEGORY_TAXES_NOT_APPLICABLE = "N";
    const CATEGORY_EXEMPT_SECURITY_SURCHARGE = "Q";
    const CATEGORY_DOMESTIC_TAX_NOT_APPLICABLE = "T";

    /**
     * self::CATEGORY_*
     *
     * @var string
     */
    public $taxCategory;

    /**
     * @var TaxDetails[]
     */
    public $taxDetails = [];

    /**
     * TaxDetailsInformation constructor.
     *
     * @param TaxData $taxData
     */
    public function __construct(TaxData $taxData)
    {
        $this->taxCategory = $taxData->category;

        $this->taxDetails[] = new TaxDetails(
            $taxData->rate,
            $taxData->countryCode,
            $taxData->currencyCode,
            $taxData->type
        );
    }
}
