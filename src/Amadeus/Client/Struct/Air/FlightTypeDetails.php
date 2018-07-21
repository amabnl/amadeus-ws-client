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
 * @author dieter <dermikagh@gmail.com>
 */
class FlightTypeDetails
{
    const INDICATOR_CODE_SHARE_SERVICE = 'A';
    const INDICATOR_ARRIVAL = 'AR';
    const INDICATOR_AVAILABILITY_FROM_THIRD_PARTY_PROVIDER = 'AT';
    const INDICATOR_BUS = 'B';
    const INDICATOR_FARE_MUST_BREAK = 'BM';
    const INDICATOR_FARE_MUST_NOT_BREAK = 'BN';
    const INDICATOR_FARE_MUST_ONLY_BREAK = 'BO';
    const INDICATOR_FARE_SPECIFIED_BREAK_POINT = 'BP';
    const INDICATOR_BOOKED_RESERVATIONS = 'BR';
    const INDICATOR_BLOCKED_SPACE = 'BS';
    const INDICATOR_CONNECTION_PORTION_OF_JOURNEY = 'C';
    const INDICATOR_CODE_SHARE_BLOCK_SPACE = 'CB';
    const INDICATOR_CODE_SHARE_FREE_SELL = 'CF';
    const INDICATOR_CAR_RENTAL = 'CR';
    const INDICATOR_CRUISE_SHIP = 'CS';
    const INDICATOR_DIRECT_SERVICE = 'D';
    const INDICATOR_DIRECT_ACCESS_PARTICIPANT = 'DA';
    const INDICATOR_MOST_DISTANT_CITY = 'DC';
    const INDICATOR_DEPARTURE = 'DP';
    const INDICATOR_DIRECT_SELL_PARTICIPANT = 'DS';
    const INDICATOR_END_OF_JOURNEY = 'E';
    const INDICATOR_NOT_ETICKET_CANDIDATE = 'EN';
    const INDICATOR_ETICKET_CANDIDATE = 'ET';
    const INDICATOR_CHARTER_FLIGHT = 'F';
    const INDICATOR_FLOWN_FLIGHT = 'FF';
    const INDICATOR_MASTER_FLIGHT = 'FM';
    const INDICATOR_MASTER_FLIGHT_REFERENCE = 'FR';
    const INDICATOR_UNFLOWN_FLIGHT = 'FU';
    const INDICATOR_GROUND_MOVEMENT = 'GR';
    const INDICATOR_HOVERCRAFT = 'H';
    const INDICATOR_HELICOPTER = 'HE';
    const INDICATOR_HOTEL = 'HT';
    const INDICATOR_INBOUND_FLIGHT = 'I';
    const INDICATOR_IATA = 'IA';
    const INDICATOR_STOPOVER_PERMITTED = 'J';
    const INDICATOR_MANDATORY_STOPOVER_POINT = 'JA';
    const INDICATOR_STOPOVER_NOT_PERMITTED = 'K';
    const INDICATOR_INCLUSIVE = 'I';
    const INDICATOR_LOCAL_AVAILABILITY = 'LA';
    const INDICATOR_EXCLUSIVE = 'LX';
    const INDICATOR_MARKETING_FLIGHT_GROUPING = 'M';
    const INDICATOR_CONDITIONAL_MARKETING_GROUPING = 'MC';
    const INDICATOR_MARRIED_PREVIOUS_SEGMENT = 'MP';
    const INDICATOR_MARRIED_NEXT_SEGMENT = 'MX';
    const INDICATOR_NON_STOP_SERVICE = 'N';
    const INDICATOR_OPERATING_FLIGHT = 'O';
    const INDICATOR_ONLINE_CONNECTION = 'OC';
    const INDICATOR_EXCLUSIVE_OPEN_JAW = 'OE';
    const INDICATOR_MANDATORY_OPEN_JAW = 'OM';
    const INDICATOR_PASSENGERS_DISPOSITION = 'P';
    const INDICATOR_POINT_OF_TURNAROUND = 'PT';
    const INDICATOR_LAST_PART = 'Q';
    const INDICATOR_ADDITIONAL_BREAK_POINT = 'RA';
    const INDICATOR_POINT_REPLACEMENT_BREAK_POINT = 'RB';
    const INDICATOR_EXCLUSIVE_FARE_BREAK_POINT = 'RN';
    const INDICATOR_MANDATORY_FARE_BREAK_POINT = 'RO';
    const INDICATOR_MANDATORY_ROUTE_POINT = 'RP';
    const INDICATOR_RECOMMENDED_BOOKING_DESIGNATOR = 'RR';
    const INDICATOR_START_OF_JOURNEY = 'S';
    const INDICATOR_AVAILABILITY_SUB_OD1 = 'S1';
    const INDICATOR_AVAILABILITY_SUB_OD2 = 'S2';
    const INDICATOR_SLAVE_FLIGHT = 'SL';
    const INDICATOR_SMOKING = 'SM';
    const INDICATOR_SEAMLESS_PARTICIPANT = 'SP';
    const INDICATOR_SLAVE_FLIGHT_FOR_REFERENCE = 'SR';
    const INDICATOR_SIDE_TRIP = 'ST';
    const INDICATOR_TRANSFER = 'T';
    const INDICATOR_BY_ARRIVAL_TIME = 'TA';
    const INDICATOR_BY_DEPARTURE_TIME = 'TD';
    const INDICATOR_BY_ELAPSED_TIME = 'TE';
    const INDICATOR_IN_NEUTRAL_ORDER = 'TN';
    const INDICATOR_TRAIN = 'TR';
    const INDICATOR_THREE_SEGMENT_CONNECTION = 'TS';
    const INDICATOR_STOPOVER_OR_CONNECTION_UNKNOWN = 'U';
    const INDICATOR_STOPOVER_POINT = 'V';
    const INDICATOR_VISIT_LINK_FLIGHT = 'VL';
    const INDICATOR_INTERACTIVE_CANCEL_CANDIDATE = 'W';
    const INDICATOR_CONNECT_POINT = 'X';
    const INDICATOR_CAPACITY_OVERRIDDEN = 'Y';
    const INDICATOR_LIMIT_SALES_IGNORED = 'Z';

    /**
     * self::INDICATOR_*
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
