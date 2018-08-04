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

namespace Amadeus\Client\Struct\Offer\Create;

/**
 * ReferenceDetails
 *
 * @package Amadeus\Client\Struct\Offer\Create
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ReferenceDetails
{
    const PRODREF_BOOKING_CODE = "BC";
    const PRODREF_CAR_PRODUCT = "CPI";
    const PRODREF_CAR_RATE_UNQ = "CUI";
    const PRODREF_HOTEL_PRODUCT = "HPI";
    const PRODREF_HOTEL_PROPERTY_CODE = "PC";
    const PRODREF_SEGMENT_TATOO_REF = "ST";

    /**
     * self::TYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * @var int|string
     */
    public $value;

    /**
     * ReferenceDetails constructor.
     *
     * @param int|string $value
     * @param string $type
     */
    public function __construct($value, $type)
    {
        $this->value = $value;
        $this->type = $type;
    }
}
