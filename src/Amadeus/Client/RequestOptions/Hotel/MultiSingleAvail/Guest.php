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

namespace Amadeus\Client\RequestOptions\Hotel\MultiSingleAvail;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Guest
 *
 * @package Amadeus\Client\RequestOptions\Hotel\MultiSingleAvail
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Guest extends LoadParamsFromArray
{
    const OCCUPANT_OVER_21 = 1;
    const OCCUPANT_OVER_65 = 2;
    const OCCUPANT_UNDER_2 = 3;
    const OCCUPANT_UNDER_12 = 4;
    const OCCUPANT_UNDER_17 = 5;
    const OCCUPANT_UNDER_21 = 6;
    const OCCUPANT_INFANT = 7;
    const OCCUPANT_CHILD = 8;
    const OCCUPANT_TEENAGER = 9;
    const OCCUPANT_ADULT = 10;
    const OCCUPANT_SENIOR = 11;
    const OCCUPANT_ADDITIONAL_WITH_ADULT = 12;
    const OCCUPANT_ADDITIONAL_WITHOUT_ADULT = 13;
    const OCCUPANT_FREE_CHILD = 14;
    const OCCUPANT_FREE_ADULT = 15;
    const OCCUPANT_YOUNG_DRIVER = 16;
    const OCCUPANT_YOUNGER_DRIVER = 17;
    const OCCUPANT_UNDER_10 = 18;

    /**
     * Age Qualifying code
     *
     * self::OCCUPANT_*
     *
     * @var string
     */
    public $occupantCode;

    /**
     * How many guests?
     *
     * @var int
     */
    public $amount;
}
