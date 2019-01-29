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

namespace Amadeus\Client\Struct\Hotel\Sell;

/**
 * PassengerReference
 *
 * @package Amadeus\Client\Struct\Hotel\Sell
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class PassengerReference
{
    const TYPE_BOOKING_PAYER_AND_HOLDER_NON_OCCUPANT = "BHN";
    const TYPE_BOOKING_PAYER_AND_HOLDER_OCCUPANT = "BHO";
    const TYPE_BOOKING_PAYER_NON_OCCUPANT = "BPN";
    const TYPE_BOOKING_PAYER_OCCUPANT = "BPO";

    /**
     * self::TYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * @var int
     */
    public $value;

    /**
     * @param int $tattoo
     * @param string $type self::TYPE_*
     */
    public function __construct($tattoo, $type)
    {
        $this->value = $tattoo;
        $this->type = $type;
    }
}
