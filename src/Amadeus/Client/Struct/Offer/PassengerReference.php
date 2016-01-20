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

namespace Amadeus\Client\Struct\Offer;

/**
 * PassengerReference
 *
 * @package Amadeus\Client\Struct\Offer
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class PassengerReference
{
    /**
     * P	Passenger/traveller reference number
     */
    const TYPE_PAXREF = "P";
    /**
     * PA	Adult Passenger
     */
    const TYPE_ADULT = "PA";
    /**
     * PI	Infant Passenger
     */
    const TYPE_INFANT = "PI";

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
     * @param int $tatoo
     * @param string $type
     */
    public function __construct($tatoo, $type)
    {
        $this->value = $tatoo;
        $this->type = $type;
    }
}
