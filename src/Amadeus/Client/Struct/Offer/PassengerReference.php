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
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PassengerReference
{
    /**
     * P Passenger/traveller reference number
     */
    const TYPE_PAXREF = "P";
    /**
     * PA Adult Passenger
     */
    const TYPE_ADULT = "PA";
    /**
     * PI Infant Passenger
     */
    const TYPE_INFANT = "PI";

    const TYPE_BOOKING_HOLDER_OCCUPANT = "BHO";

    const TYPE_BOOKING_HOLDER_NON_OCCUPANT = "BHN";

    const TYPE_BOOKING_OCCUPANT = "BOP";

    const TYPE_GROUP_NAME = "GRN";

    const TYPE_ROOM_MAIN_NON_OCCUPANT = "RMN";

    const TYPE_ROOM_MAIN_OCCUPANT = "RMO";

    const TYPE_ROOM_OCCUPANT = "ROP";

    /**
     * Hotel Offer types:
     *
     * BHN Booking Holder Non occupant pax tattoo
     * BHO Booking Holder Occupant pax tattoo
     * BOP Booking Occupant Pax tattoo
     * GRN GRoup Name tattoo
     * P Holder Pax tattoo (no information on occupancy)
     * RMN Room Main pax tattoo, Non occupant.
     * RMO Room Main pax tattoo, Occupant.
     * ROP Room Occupant Pax tattoo
     */


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
     * @param string $type
     */
    public function __construct($tattoo, $type)
    {
        $this->value = $tattoo;
        $this->type = $type;
    }
}
