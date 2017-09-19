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

namespace Amadeus\Client\Struct\Ticket\DisplayTSMFareElement;

/**
 * Reference
 *
 * @package Amadeus\Client\Struct\Ticket\DisplayTSMFareElement
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Reference
{
    const QUAL_ALL_FARE_ELEMENTS = "ALL";
    const QUAL_FARE_DISCOUNT = "FD";
    const QUAL_ENDORSEMENT = "FE";
    const QUAL_COMMISSION = "FM";
    const QUAL_ORIGINAL_EXCHANGE_DOCUMENT = "FO";
    const QUAL_FORM_OF_PAYMENT = "FP";
    const QUAL_TOUR_CODE = "FT";
    const QUAL_MISCELLANEOUS_INFORMATION_1 = "FZ1";
    const QUAL_MISCELLANEOUS_INFORMATION_2 = "FZ2";

    /**
     * self::QUAL_*
     *
     * @var string|int
     */
    public $qualifier;

    /**
     * @var int
     */
    public $number;

    /**
     * Reference constructor.
     *
     * @param int $number
     * @param int|string $qualifier self::QUAL_*
     */
    public function __construct($number, $qualifier)
    {
        $this->number = $number;
        $this->qualifier = $qualifier;
    }
}
