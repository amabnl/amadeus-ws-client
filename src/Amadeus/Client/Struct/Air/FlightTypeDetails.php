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
 * FlightTypeDetails
 *
 * @package Amadeus\Client\Struct\Air
 * @author dieter <dieter.devlieghere@benelux.amadeus.com>
 */
class FlightTypeDetails
{
    /**
     * A Codeshare service
     * AR Arrival
     * AT Availability directly from a third party travel provider
     * B Bus
     * BM Fare must break
     * BN Fare must not break
     * BO Fare must only break
     * BP Specified fare break point
     * BR Booked reservations booking designator (RBD)
     * BS Blocked space for other than codeshare purposes
     * C Connection portion of journey
     * CB Code share -- block space
     * CF Code share -- free sell
     * CR Car Rental
     * CS Cruise Ship
     * D Direct service
     * DA Direct access participant
     * DC Most Distant City
     * DP Departure
     * DS Direct sell participant
     * E End of journey
     * EN Not electronic ticket candidate
     * ET Electronic ticket candidate
     * F Charter Flight
     * FF Flown flight segment
     * FM Master Flight
     * FR Master Flight for reference
     * FU Unflown flight segment
     * GR Ground Movement
     * H Hovercraft
     * HE Helicopter
     * HT Hotel
     * I Inbound flight
     * IA IATA (International Air Transport Association)
     * J Stopover permitted
     * JA Mandatory Stopover Point
     * K Stopover not permitted
     * L Inclusive
     * LA Local availability
     * LX Exclusive
     * M Marketing flight grouping indicator
     * MC Conditional marketing flight grouping indicator
     * MP Married to previous segment
     * MX Married to next segment
     * N Non-stop service
     * O Operating flight
     * OC Online connection
     * OE Exclusive open jaw (other fare break points not allowed)
     * OM Mandatory open jaw (other fare break points may exist)
     * P Passenger's disposition, unknown
     * PT Point of turnaround
     * Q Last part
     * RA Specified fare break point - additional break point
     * RB Specified fare break point-replacement break point
     * RN Exclusive fare break at destination of surface break (other fare break point not allowed)
     * RO Mandatory fare break at destination of surface break (other fare break points may exist)
     * RP Mandatory route Point
     * RR Recommended reservations booking designator (RBD) to be booked
     * S Start of journey
     * S1 Availability of Sub O&D 1
     * S2 Availability of Sub O&D 2
     * SL Slave Flight
     * SM Smoking
     * SP Seamless participant
     * SR Slave flight for reference
     * ST Side trip
     * T Transfer
     * TA By arrival time
     * TD By departure time
     * TE By elapsed time
     * TN In neutral order
     * TR Train
     * TS Three segment connection
     * U Stopover or connection unknown
     * V Stopover point
     * VL Visit-link flight
     * W Interactive cancel candidate
     * X Connect point
     * Y Capacity was overridden
     * Z Limit sales ignored
     *
     * @var string[]
     */
    public $flightIndicator = [];

    /**
     * FlightTypeDetails constructor.
     *
     * @param string $indicator
     */
    public function __construct($indicator)
    {
        $this->flightIndicator[] = $indicator;
    }
}
