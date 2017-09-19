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
 * MasterPricer Location request options
 *
 * @package Amadeus\Client\RequestOptions\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MPLocation extends LoadParamsFromArray
{
    const RADIUSUNIT_KILOMETERS = 'K';

    /**
     * Airport code
     *
     * Use ATA/IATA defined 3 letter city code
     *
     * @var string
     */
    public $airport;

    /**
     * City code
     *
     * Use ATA/IATA defined 3 letter city code
     *
     * @var string
     */
    public $city;

    /**
     * List of one or more cities
     *
     * @var string[]
     */
    public $multiCity = [];

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
     * Radius around airport or city requested
     *
     * @var int
     */
    public $radiusDistance;

    /**
     * self::RADIUSUNIT_*
     *
     * @var string
     */
    public $radiusUnit;
}
