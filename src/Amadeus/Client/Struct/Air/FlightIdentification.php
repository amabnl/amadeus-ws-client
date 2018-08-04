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
 * FlightIdentification
 *
 * @package Amadeus\Client\Struct\Air
 * @author dieter <dermikagh@gmail.com>
 */
class FlightIdentification
{
    /**
     * @var string
     */
    public $flightNumber;

    /**
     * 1 Request all non-displayable RBD's
     * 2 Request all RBD's including non-displayable RBD's.
     * 3 Request all Frequent Flyer Program Award Classes
     * 4 Total number of seats in the allotment
     * 5 Number of seats sold in the allotment
     * 6 Number of seats unsold in the allotment
     * 700 Request is expanded to include nonmatching connections
     *
     * @var string
     */
    public $bookingClass;

    /**
     * @var string
     */
    public $operationalSuffix;

    /**
     * @var string
     */
    public $modifier;

    /**
     * FlightIdentification constructor.
     *
     * @param string $flightNumber
     * @param string|null $bookingClass
     * @param string|null $operationalSuffix
     */
    public function __construct($flightNumber, $bookingClass = null, $operationalSuffix = null)
    {
        $this->flightNumber = $flightNumber;
        $this->bookingClass = $bookingClass;
        $this->operationalSuffix = $operationalSuffix;
    }
}
