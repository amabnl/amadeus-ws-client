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

namespace Amadeus\Client\Struct\PointOfRef\Search;

/**
 * GeoCode
 *
 * @package Amadeus\Client\Struct\PointOfRef\Search
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class GeoCode
{
    /**
     * Number of 1/100000 degrees east of Greenwich (positive values) or west of Greenwich (negative values).
     *
     * @var double
     */
    public $longitude;

    /**
     * Number of 1/100000 degrees north of Equator (positive values) or south of Equator (negative values).
     *
     * @var double
     */
    public $latitude;

    /**
     * GeoCode constructor.
     *
     * @param double|null $longitude
     * @param double|null $latitude
     */
    public function __construct($longitude = null, $latitude = null)
    {
        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }
}
