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

/**
 * Ticket_DisplayTSMFareElement request options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TicketDisplayTsmFareElOptions extends Base
{
    const TYPE_FARE_DISCOUNT = "FD";
    const TYPE_ENDORSEMENT = "FE";
    const TYPE_COMMISSION = "FM";
    const TYPE_ORIGINAL_EXCHANGE_DOCUMENT = "FO";
    const TYPE_FORM_OF_PAYMENT = "FP";
    const TYPE_TOUR_CODE = "FT";
    const TYPE_MISCELLANEOUS_INFORMATION_1 = "FZ1";
    const TYPE_MISCELLANEOUS_INFORMATION_2 = "FZ2";
    const TYPE_ALL_FARE_ELEMENTS = "ALL";

    /**
     * The tattoo of the associated TSM.
     *
     * @var int
     */
    public $tattoo;

    /**
     * Type of the Fare element of the associated TSM.
     *
     * self::TYPE_*
     *
     * @var string
     */
    public $type = self::TYPE_ALL_FARE_ELEMENTS;
}
