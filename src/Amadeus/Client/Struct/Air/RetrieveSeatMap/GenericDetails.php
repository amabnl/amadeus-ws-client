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

namespace Amadeus\Client\Struct\Air\RetrieveSeatMap;

/**
 * GenericDetails
 *
 * @package Amadeus\Client\Struct\Air\RetrieveSeatMap
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class GenericDetails
{
    /**
     * First class, Highest class of service (First Class Category)
     */
    const CABIN_FIRST = 1;
    /**
     * Second class, Medium class of service (Business Class Category)
     */
    const CABIN_SECOND = 2;
    /**
     * Third class, Lowest class of service (Economy Class Category)
     */
    const CABIN_THIRD = 3;

    const SMOKING_NO = "N";
    const SMOKING_YES = "S";

    const SEAT_AISLE = "A";
    const SEAT_HANDICAPPED_INCAPACITATED_PASSENGER = "H";
    const SEAT_ADULT_WITH_INFANT = "I";
    const SEAT_BULKHEAD = "K";
    const SEAT_MEDICALLY_OK_TO_TRAVEL = "MA";
    const SEAT_NO_SMOKING = "N";
    const SEAT_SMOKING_SEAT = "S";
    const SEAT_UNACCOMPANIED_MINOR = "U";
    const SEAT_WINDOW = "W";

    /**
     * @var string
     */
    public $cabinClassDesignator;

    /**
     * self::SMOKING_*
     *
     * @var string
     */
    public $noSmokingIndicator;

    /**
     * self::CABIN_*
     *
     * @var int
     */
    public $cabinClass;

    /**
     * @var string
     */
    public $compartmentDesignator;

    /**
     * self::SEAT_*
     *
     * @var string
     */
    public $seatCharacteristic;

    /**
     * GenericDetails constructor.
     *
     * @param string $cabinClassDesignator
     */
    public function __construct($cabinClassDesignator)
    {
        $this->cabinClassDesignator = $cabinClassDesignator;
    }
}
