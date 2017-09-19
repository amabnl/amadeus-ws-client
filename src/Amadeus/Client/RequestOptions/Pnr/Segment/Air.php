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

namespace Amadeus\Client\RequestOptions\Pnr\Segment;

use Amadeus\Client\RequestOptions\Pnr\Segment;

/**
 * PNR Air Segment
 *
 * @package Amadeus\Client\RequestOptions\Pnr\Segment
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Air extends Segment
{
    const SELLTYPE_LONG_SELL = 0;
    const SELLTYPE_BASIC_BOOKING = "P10";

    /**
     * Segment status
     *
     * self::STATUS_*
     *
     * @var string
     */
    public $status = self::STATUS_NEED_SEGMENT;

    /**
     * Flight Number
     *
     * @var string
     */
    public $flightNumber;

    /**
     * Departure date
     *
     * @var \DateTime
     */
    public $date;

    /**
     * Origin (=Departure) IATA location code
     *
     * @var string
     */
    public $origin;

    /**
     * Destination (=Arrival) IATA location code
     *
     * @var string
     */
    public $destination;

    /**
     * Class of Service
     *
     * @var string
     */
    public $bookingClass;

    /**
     * Sell type
     *
     * @var string|int
     */
    public $sellType = self::SELLTYPE_LONG_SELL;
}
