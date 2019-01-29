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

namespace Amadeus\Client\RequestOptions;

use Amadeus\Client\RequestOptions\Hotel\Sell\Booker;
use Amadeus\Client\RequestOptions\Hotel\Sell\BookingCompany;

/**
 * Hotel_Sell Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class HotelSellOptions extends Base
{
    const DELIVER_ERETAIL = "AERE";
    const DELIVER_ETRAVEL_MANAGEMENT = "AETM";
    const DELIVER_COMMAND_PAGE = "COMM";
    const DELIVER_SELL2_SELL_CONNECT = "SECO";
    const DELIVER_SELLING_PLATFORM_CLASSIC = "SELL";
    const DELIVER_NON_SPECIFIC_PRODUCT_FROM_SEL = "SEP";
    const DELIVER_WEBSERVICES = "WEBS";

    /**
     * Booking system identifier
     *
     * self::DELIVER_*
     *
     * @var string
     */
    public $deliveringSystem;

    /**
     * @var BookingCompany[]
     */
    public $bookingCompany = [];

    /**
     * @var Booker
     */
    public $booker;
}
