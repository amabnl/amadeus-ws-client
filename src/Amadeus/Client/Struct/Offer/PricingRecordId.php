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

namespace Amadeus\Client\Struct\Offer;

/**
 * PricingRecordId
 *
 * @package Amadeus\Client\Struct\Offer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PricingRecordId
{
    /**
     * PQR Product Quotation Record Reference
     *
     * @var string
     */
    const REFTYPE_PRODQUOTREC = "PQR";

    /**
     * Code giving specific meaning to a reference segment or a reference number.
     *
     * @var string
     */
    public $referenceType = self::REFTYPE_PRODQUOTREC;

    /**
     * Identification number.
     *
     * @var string
     */
    public $uniqueReference;

    /**
     * @param string $referenceNr
     */
    public function __construct($referenceNr)
    {
        $this->uniqueReference = $referenceNr;
    }
}
