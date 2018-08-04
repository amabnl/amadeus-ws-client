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

namespace Amadeus\Client\Struct\Fare\MasterPricer;

use Amadeus\Client\RequestOptions\Fare\MPLocation;

/**
 * LocationDetails
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class LocationDetails
{
    /**
     * Specifies a reference given to a number of flight segment in the querying system
     *
     * @var int
     */
    public $distance;
    /**
     * Indicates the reference of the number of flight segment
     * K  Kilometers
     * P  Passenger/traveller reference number
     * S  PNR segment reference number
     *
     * @var string
     */
    public $distanceUnit;
    /**
     * Use ATA/IATA defined 3 letter city code
     *
     * @var string
     */
    public $locationId;
    /**
     *
     * A  Airport
     * C  City
     * D  Consider Destination (off point) of the PNR requested segment
     * O  Consider Origin (board point) of the PNR requested segment
     *
     * @var string
     */
    public $airportCityQualifier;
    /**
     * Latitude in degrees
     *
     * @var string
     */
    public $latitude;
    /**
     * Longitude in degrees
     *
     * @var string
     */
    public $longitude;

    /**
     * LocationDetails constructor.
     *
     * @param MPLocation $location
     */
    public function __construct(MPLocation $location)
    {
        if (!empty($location->airport)) {
            $this->locationId = $location->airport;
            $this->airportCityQualifier = "A";
        } elseif (!empty($location->city)) {
            $this->locationId = $location->city;
            $this->airportCityQualifier = "C";
        }

        if (!empty($location->longitude) && !empty($location->latitude)) {
            $this->longitude = $location->longitude;
            $this->latitude = $location->latitude;
        }

        if (!empty($location->radiusDistance) && !empty($location->radiusUnit)) {
            $this->distance = $location->radiusDistance;
            $this->distanceUnit = $location->radiusUnit;
        }
    }
}
