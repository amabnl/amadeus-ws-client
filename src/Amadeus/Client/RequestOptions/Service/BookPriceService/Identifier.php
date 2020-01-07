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

namespace Amadeus\Client\RequestOptions\Service\BookPriceService;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Identifier
 *
 * @package Amadeus\Client\RequestOptions\Service\BookPriceService
 * @author Mike Hernas <mike@ahoy.io>
 */
class Identifier extends LoadParamsFromArray
{
    const BOOKING_METHOD_SSR = "01";
    const BOOKING_METHOD_SVC = "02";

    /**
     * self::BOOKING_METHOD_*
     *
     * 01   SSR type of booking method
     * 02   SVC type of booking method
     *
     * @var string
     */
    public $bookingMethod;

    /**
     *
     * @var string
     */
    public $RFIC;

    /**
     *
     * @var string
     */
    public $RFISC;

    /**
     *
     * @var string
     */
    public $code;
}
