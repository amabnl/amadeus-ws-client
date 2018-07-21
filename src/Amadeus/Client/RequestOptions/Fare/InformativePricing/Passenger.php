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

namespace Amadeus\Client\RequestOptions\Fare\InformativePricing;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Passenger
 *
 * @package Amadeus\Client\RequestOptions\Fare\InformativePricing
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Passenger extends LoadParamsFromArray
{
    const TYPE_ADULT = "ADT";

    const TYPE_CHILD = "CH";

    const TYPE_INFANT = "INF";

    const TYPE_INFANT_WITH_SEAT = "INS";

    /**
     * What type of passengers?
     *
     * @see self::TYPE_*
     * @var string
     */
    public $type;

    /**
     * List of _unique_ identifiers for these passengers.
     *
     * If you have 3 passengers of this type, you need to provide 3 unique tattoos.
     *
     * For infants in a seat with an adult, you must provide the ID of the adult as their tattoo.
     *
     * @var int[]
     */
    public $tattoos = [];
}
