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

namespace Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Flight Identification
 *
 * @package Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight
 * @author Evan Chuang <ycchuang1999@gmail.com>
 */
class FlightIdentification extends LoadParamsFromArray
{
    /**
     * Marketing Carrier
     *
     * @var string
     */
    public $marketingCarrier;

    /**
     * Flight Number
     *
     * @var string
     */
    public $flightNumber;

    /**
     * Operational Alpha Suffix
     *
     * @var string
     */
    public $operationSuffix;

    /**
     * Board Point
     *
     * The port of departure of the flight or
     * flight segment. It is mandatory when FDD date is used.
     *
     * @var string
     */
    public $boardPoint;

    /**
     * Off Point
     *
     * The port of arrival of the flight or
     * flight segment. It is mandatory when FDD date is used.
     *
     * @var string
     */
    public $offPoint;
}
