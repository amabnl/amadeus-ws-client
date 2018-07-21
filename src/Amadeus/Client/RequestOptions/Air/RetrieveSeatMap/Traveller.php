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

namespace Amadeus\Client\RequestOptions\Air\RetrieveSeatMap;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Traveller
 *
 * @package Amadeus\Client\RequestOptions\Air\RetrieveSeatMap
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Traveller extends LoadParamsFromArray
{
    const TYPE_ADULT = "ADT";
    const TYPE_CHILD = "CHD";
    const TYPE_INFANT_NO_SEAT = "INF";
    const TYPE_INFANT_WITH_SEAT = "INS";

    /**
     * @var int
     */
    public $uniqueId;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * self::TYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * @var \DateTime
     */
    public $dateOfBirth;

    /**
     * @var string
     */
    public $passengerTypeCode;

    /**
     * @var string
     */
    public $ticketDesignator;

    /**
     * @var string
     */
    public $ticketNumber;

    /**
     * @var string
     */
    public $fareBasisOverride;

    /**
     * @var FrequentFlyer
     */
    public $frequentTravellerInfo;
}
