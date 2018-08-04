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
 * Special
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Special
{
    const TYPE_AISLE_SEAT = "A";
    const TYPE_WINDOW_SEAT = "W";
    const TYPE_CHARGEABLE_SEAT = "CH";
    const TYPE_EXIT_ROW_SEAT = "E";
    const TYPE_FACILITIES_FOR_HANDICAPPED = "H";
    const TYPE_SEAT_SUITABLE_FOR_ADULT_WITH_INFANT = "I";
    const TYPE_BULKHEAD_SEAT = "K";
    const TYPE_MEDICALLY_OK_FOR_TRAVEL = "MA";
    const TYPE_NON_SMOKING_SEAT = "N";
    const TYPE_SMOKING_SEAT = "S";
    const TYPE_SEAT_SUITABLE_FOR_UNACCOMPANIED_MINOR = "U";

    /**
     * @var string
     */
    public $data;

    /**
     * self::TYPE_*
     *
     * @var string
     */
    public $seatType;

    /**
     * Special constructor.
     *
     * @param string $data
     * @param string|null $seatType
     */
    public function __construct($data, $seatType = null)
    {
        $this->data = $data;
        $this->seatType = $seatType;
    }
}
