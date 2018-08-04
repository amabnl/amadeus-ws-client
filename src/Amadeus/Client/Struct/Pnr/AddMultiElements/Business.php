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

namespace Amadeus\Client\Struct\Pnr\AddMultiElements;

/**
 * Business
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Business
{
    const FUNC_MISC = 32;

    const FUNC_AIR = 1;

    const FUNC_AIRTAXI = 10;

    const FUNC_TOUR_AIRLINE = 11;

    const FUNC_SURFACE = 12;

    const FUNC_TOUR_TOUROPERATOR = 13;

    const FUNC_CAR = 2;

    const FUNC_HOTEL = 3;

    const FUNC_RAIL = 6;

    const FUNC_TOUR = 7;

    const FUNC_HOTEL_AIRLINE = 8;

    const FUNC_CAR_AIRLINE = 9;

    const FUNC_ARNK = 0;

    /**
     * 1 Air Provider
     * 10 Air taxi (ATX)
     * 11 Tour (TUR), requested through airline rather than
     * 12 Surface (SUR)
     * 13 Tour (TTO), requested from tour operator
     * 2 Car Provider (CCR) Hotel Provider (HHL)
     * 3 Hotel Provider (HHL)
     * 32 Miscellaneous
     * 6 Rail
     * 7 Tour
     * 8 Hotel, requested through airline rather than hotel
     * 9 Car, requested through airline rather than car ope
     *
     * @var int
     */
    public $function;

    /**
     * Business constructor.
     *
     * @param int $businessFunction
     */
    public function __construct($businessFunction)
    {
        $this->function = $businessFunction;
    }
}
