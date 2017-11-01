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

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * AvailabilityOptions16
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AvailabilityOptions16
{
    const REQ_TYPE_SPECIFIC_FLIGHT = "SF";
    const REQ_TYPE_BY_ARRIVAL_TIME = "TA";
    const REQ_TYPE_BY_DEPARTURE_TIME = "TD";
    const REQ_TYPE_BY_ELAPSED_TIME = "TE";
    const REQ_TYPE_GROUP_AVAILABILITY = "TG";
    const REQ_TYPE_NEUTRAL_ORDER = "TN";
    const REQ_TYPE_NEGOTIATED_SPACE = "TT";


    /**
     * self::REQ_TYPE_*
     *
     * @var string
     */
    public $typeOfRequest;

    /**
     * @var OptionInfo16[]
     */
    public $optionInfo = [];

    /**
     * @var ProductAvailability[]
     */
    public $productAvailability = [];

    /**
     * @var string
     */
    public $typeOfAircraft;

    /**
     * AvailabilityOptions constructor.
     *
     * @param string $requestType self::::REQ_TYPE_*
     */
    public function __construct($requestType)
    {
        $this->typeOfRequest = $requestType;
    }
}
