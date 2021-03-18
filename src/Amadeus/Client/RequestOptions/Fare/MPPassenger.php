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

namespace Amadeus\Client\RequestOptions\Fare;

use Amadeus\Client\LoadParamsFromArray;

/**
 * MasterPricerPassenger
 *
 * @package Amadeus\Client\RequestOptions\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MPPassenger extends LoadParamsFromArray
{
    const TYPE_ADULT = "ADT";

    const TYPE_CHILD = "CH";

    const TYPE_INFANT = "INF";

    const TYPE_INFANT_WITH_SEAT = "INS";

    const TYPE_STUDENT = "ST";

    const TYPE_INDIVIDUAL_INCLUSIVE_TOUR = 'IIT';

    /**
     * What type of passengers? self::TYPE_*
     *
     * @var string|array
     */
    public $type;

    /**
     * How many passengers of this type ?
     *
     * @var int
     */
    public $count;
}
