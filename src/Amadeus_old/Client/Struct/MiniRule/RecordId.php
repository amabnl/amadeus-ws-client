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

namespace Amadeus\Client\Struct\MiniRule;

/**
 * RecordId
 *
 * @package Amadeus\Client\Struct\MiniRule
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class RecordId
{
    /**
     * To retrieve minirules for ALL pricing references of the given type on a PNR.
     */
    const PRICING_ID_ALL = "ALL";
    /**
     * Offer element tattoo
     */
    const REFERENCE_TYPE_OFFER = "OF";
    /**
     * Transitional Stored Ticket
     */
    const REFERENCE_TYPE_TST = "TST";
    /**
     * Product Quotation Record Reference
     */
    const REFERENCE_TYPE_PROD_QUOTATION = "PQR";

    /**
     * self::REFERENCE_TYPE_*
     *
     * @var string
     */
    public $referenceType;

    /**
     * Identification number or self::PRICING_ID_ALL for all
     *
     * @var int|string
     */
    public $uniqueReference;

    /**
     * RecordId constructor.
     *
     * @param int|string $pricingId a reference or self::PRICING_ID_ALL
     * @param string $pricingType
     */
    public function __construct($pricingId, $pricingType)
    {
        $this->referenceType = $pricingType;
        $this->uniqueReference = $pricingId;
    }
}
