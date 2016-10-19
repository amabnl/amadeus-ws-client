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

namespace Amadeus\Client\Struct\Service\IntegratedPricing;

/**
 * PricingOptionKey
 *
 * @package Amadeus\Client\Struct\Service\IntegratedPricing
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class PricingOptionKey
{
    const OVERRIDE_ACCOUNT_CODE = "ACC";
    const OVERRIDE_PRICING_ALL_SERVICES = "ALL";
    const OVERRIDE_AWARD = "AWD";
    const OVERRIDE_CODE_SELECTION = "COD";
    const OVERRIDE_CORPORATION_NUMBER = "CRP";
    const OVERRIDE_PRICING_DATE = "DAT";
    const OVERRIDE_CURRENCY = "FCO";
    const OVERRIDE_INFANT_PROCESSING = "INF";
    const OVERRIDE_JOURNEY_TURNAROUND_POINT = "JTP";
    const OVERRIDE_NO_BREAK_POINT = "NBP";
    const OVERRIDE_NO_JOURNEY_TURNAROUND_POINT = "NJT";
    const OVERRIDE_NO_OPTION = "NOP";
    const OVERRIDE_NON_TRUSTED_REQUEST = "NTR";
    const OVERRIDE_ORIGIN_DESTINATION = "OD";
    const OVERRIDE_PASSENGER_DISCOUNT_PTC = "PAX";
    const OVERRIDE_POINT_OF_SALE = "POS";
    const OVERRIDE_PTCONLY = "PTC";
    const OVERRIDE_SHOW_COMMERCIAL_DESCRIPTION = "SCD";
    const OVERRIDE_PAX_SEG_ELEMENT_SELECTION = "SEL";
    const OVERRIDE_SHOW_PRICING_DESCRIPTION = "SPD";
    const OVERRIDE_TICKET_DESIGNATOR = "TKD";
    const OVERRIDE_VALIDATING_CARRIER = "VC ";

    /**
     * self::OVERRIDE_*
     *
     * ACC   Account Code
     * ALL   Pricing all services
     * AWD   Award
     * COD   Code selection
     * CRP   Corporation number
     * DAT   Pricing Date
     * FCO   Fare currency override
     * INF   Infant processing
     * JTP   Journey turnaround point
     * NBP   No break point
     * NJT   No journey turnaround point
     * NOP   No option
     * NTR   Non trusted request
     * OD    Origin and destination option
     * PAX   Passenger discount/PTC
     * POS   Point of sale
     * PTC   PTC only
     * SCD   Show commercial description
     * SEL   Passenger/Segment/Element selection
     * SPD   Show pricing description
     * TKD   Ticket designator
     * VC    Validating carrier
     *
     * @var string
     */
    public $pricingOptionKey;

    /**
     * PricingOptionKey constructor.
     *
     * @param string $key self::OVERRIDE_*
     */
    public function __construct($key)
    {
        $this->pricingOptionKey = $key;
    }
}
