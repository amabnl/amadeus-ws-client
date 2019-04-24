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

namespace Amadeus\Client\RequestOptions\Fare;

use Amadeus\Client\LoadParamsFromArray;

/**
 * MasterPricer Itinerary request settings
 *
 * @package Amadeus\Client\RequestOptions\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MPItinerary extends LoadParamsFromArray
{
    const FLIGHTTYPE_DIRECT = 'D';
    const FLIGHTTYPE_NONSTOP = 'N';
    const FLIGHTTYPE_CONNECTING = 'C';
    const FLIGHTTYPE_CHEAPEST_ONLINE = 'OL';
    const FLIGHTTYPE_OVERNIGHT_NOT_ALLOWED = 'OV';

    const AIRLINEOPT_PREFERRED = 'F';
    const AIRLINEOPT_MANDATORY = 'M';
    const AIRLINEOPT_NIGHT_CLASS = 'N';
    const AIRLINEOPT_EXCLUDED = 'X';

    /**
     * Major cabin
     */
    const CABINOPT_MAJOR = 'MC';
    /**
     * Mandatory cabin for all segments
     */
    const CABINOPT_MANDATORY = 'MD';
    /**
     * Recommended cabin to be used at least one segment
     */
    const CABINOPT_RECOMMENDED = 'RC';

    /**
     * Segment Reference (optional)
     *
     * @var int
     */
    public $segmentReference;

    /**
     * Departure location
     *
     * @var MPLocation
     */
    public $departureLocation;

    /**
     * Arrival location
     *
     * @var MPLocation
     */
    public $arrivalLocation;

    /**
     * @var MPDate
     */
    public $date;

    /**
     * List of airline options.
     *
     * Keys are the option to be used (self::AIRLINEOPT_*), values are the airline codes:
     *
     * e.g.
     * 'airlineOptions' => [
     *     self::AIRLINEOPT_PREFERRED => [
     *         'LH',
     *         'BA'
     *     ]
     * ]
     *
     * @var array
     */
    public $airlineOptions = [];

    /**
     * Flight options for this itinerary
     *
     * Choose from self::FLIGHTTYPE_*
     *
     * @var string[]
     */
    public $requestedFlightTypes = [];

    /**
     * List of IATA Airport/City locations which should be used as connection point.
     *
     * If you provide multiple connection points, only recommendations will be returned having the same connection
     * points as the ones specified, in the same order as specified.
     *
     * @var string[]
     */
    public $includedConnections = [];

    /**
     * List of IATA Airport/City locations which should be excluded as connection point.
     *
     * @var string[]
     */
    public $excludedConnections = [];

    /**
     * A number of connections can be requested for connecting flights.
     *
     * If you specify a value here, results will only show connecting flights with exactly the specified number of connections.
     *
     * @var int
     */
    public $nrOfConnections;

    /**
     * Set to true to disallow connecting flight to change airports within a city.
     *
     * @var bool
     */
    public $noAirportChange = false;



    /**
     * Cabin class requested for the entire itinerary
     *
     * self::CABIN_*
     *
     * @var string
     */
    public $cabinClass;

    /**
     * Cabin option - how to interpret the cabin class
     *
     * self::CABINOPT_*
     *
     * @var string
     */
    public $cabinOption;

    public $travelDetails;

    /**
     * Anchored Search segment (MPAnchoredSegment)
     *
     * @var array[MPAnchoredSegment]
     */
    public $anchoredSegments;
}
