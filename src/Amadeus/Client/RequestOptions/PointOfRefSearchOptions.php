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

namespace Amadeus\Client\RequestOptions;

/**
 * PointOfRef_Search Request options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PointOfRefSearchOptions extends Base
{
    const FILT_NONE = 0;
    const FILT_COUNTRY = 1;
    const FILT_STATE = 2;
    const FILT_IATA = 3;

    const TARGET_ALL = 'ALL';
    const TARGET_HOTEL = 'HTL';
    const TARGET_AIRPORT = 'APT';
    const TARGET_TRAIN = 'TRA';

    const LIST_TYPE_SHORT = 'S';

    /**
     * Resulting list type
     *
     * self::LIST_TYPE_*
     *
     * @var string
     */
    public $listType;

    /**
     * How many results do we want?
     *
     * @var int
     */
    public $maxNrOfResults;

    /**
     * What category of results are we looking for?
     *
     * @var string self::TARGET_*
     */
    public $targetCategoryCode = self::TARGET_ALL;

    /**
     * Longitude of the location where you want to find Points of Reference
     *
     * Use either longitute/latitude or location codes country/state/iata
     *
     * @var double
     */
    public $longitude;
    /**
     * Latitude of the location where you want to find Points of Reference
     *
     * @var double
     */
    public $latitude;

    /**
     * Country code of the location where you want to find Points of Reference
     *
     * @var string
     */
    public $country;

    /**
     * State code of the location where you want to find Points of Reference
     *
     * @var string
     */
    public $state;

    /**
     * IATA location code of the location where you want to find Points of Reference
     *
     * @var string
     */
    public $iata;

    /**
     * Business ID category code of the location where you want to find Points of Reference
     *
     * @var string
     */
    public $businessCategory;

    /**
     * Business ID foreign key in the given category of the location where you want to find Points of Reference
     *
     * @var string
     */
    public $businessForeignKey;

    /**
     * Radius (in meters) from the location where you want to find Points of Reference
     *
     * @var double
     */
    public $searchRadius;

    /**
     * A specific name of a Point of Reference
     *
     * @var string
     */
    public $name;
}
