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

namespace Amadeus\Client\RequestOptions\Pnr;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Traveller in a PNR
 *
 * @package Amadeus\Client\RequestOptions\Pnr
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Traveller extends LoadParamsFromArray
{
    const TRAV_TYPE_ADULT = "ADT";

    const TRAV_TYPE_CHILD = "CHD";

    const TRAV_TYPE_INFANT = "INF";

    const TRAV_TYPE_INFANT_WITH_SEAT = "INS";

    const TRAV_TYPE_STUDENT = "STU";

    /**
     * Unique sequence number for traveller
     *
     * @var int
     */
    public $number;

    /**
     * Travels with infant?
     *
     * Only required to set to "true" when there is no further information available
     * about said infant. If a first name, last name and/or date of birth is known
     * of the infant, you can just add this information in the $this->infant property.
     *
     * @var bool
     */
    public $withInfant = false;

    /**
     * Type of traveller
     *
     * @var string
     */
    public $travellerType = self::TRAV_TYPE_ADULT;

    /**
     * First name
     *
     * @var string
     */
    public $firstName;

    /**
     * Last name
     *
     * @var string
     */
    public $lastName;

    /**
     * Date of Birth
     *
     * @var \DateTime
     */
    public $dateOfBirth;

    /**
     * An optional infant travelling with an adult traveller.
     *
     * @var Traveller
     */
    public $infant;
}
