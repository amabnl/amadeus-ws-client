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

namespace Amadeus\Client\Struct\Air;

/**
 * FlightDate
 *
 * @package Amadeus\Client\Struct\Air
 * @author dieter <dieter.devlieghere@benelux.amadeus.com>
 */
class FlightDate
{
    /**
     * DDMMYY
     *
     * @var string
     */
    public $departureDate;
    /**
     * HHMM
     *
     * @var string
     */
    public $departureTime;
    /**
     * DDMMYY
     *
     * @var string
     */
    public $arrivalDate;
    /**
     * HHMM
     *
     * @var string
     */
    public $arrivalTime;
    /**
     * @var string
     */
    public $dateVariation;

    /**
     * FlightDate constructor.
     *
     * @param string|\DateTime $departureDate in format DDMMYY or \DateTime
     */
    public function __construct($departureDate)
    {
        if (!($departureDate instanceof \DateTime)) {
            $this->departureDate = $departureDate;
        } else {
            $this->departureDate = $departureDate->format('dmy');
            $time = $departureDate->format('Hi');
            if ($time !== "0000") {
                $this->departureTime = $time;
            }
        }
    }

    /**
     * Load Arrival date info from \DateTime
     *
     * @param \DateTime $arrivalDate
     */
    public function setArrivalDate(\DateTime $arrivalDate)
    {
        $this->arrivalDate = $arrivalDate->format('dmy');
        $time = $arrivalDate->format('Hi');
        if ($time !== "0000") {
            $this->arrivalTime = $time;
        }
    }
}
