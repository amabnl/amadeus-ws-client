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

namespace Amadeus\Client\RequestOptions\DocRefund;

use Amadeus\Client\LoadParamsFromArray;

/**
 * TaxData
 *
 * @package Amadeus\Client\RequestOptions\DocRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TaxData extends LoadParamsFromArray
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

    const TYPE_EXTENDED_TAXES = "XT";

    /**
     * Tax Category
     *
     * self::CATEGORY_*
     *
     * @var string
     */
    public $category;

    /**
     * Tax rate
     *
     * @var float
     */
    public $rate;

    /**
     * 2-character ISO country code
     *
     * @var string
     */
    public $countryCode;

    /**
     * 3-character ISO currency string
     *
     * @var string
     */
    public $currencyCode;

    /**
     * Tax Type
     *
     * self::TYPE_*
     *
     * @var string
     */
    public $type;
}
