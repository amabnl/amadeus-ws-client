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

namespace Amadeus\Client\Struct\Info;

/**
 * LocationInformation
 *
 * @package Amadeus\Client\Struct\Info
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class LocationInformation
{
    const TYPE_LOCATION = "L";

    const TYPE_ALL = "ALL";

    /**
     * A Airport
     * B Bus station
     * C City
     * G Ground transport
     * H Heliport
     * L Location
     * O Offpoint
     * R Railway station
     * S Associated location
     *
     * From the Amadeus Web Services docs:
     *     The category of the input location is ignored,
     *     i.e. the search is performed among all types of location: city, airport, etc.
     *     However, the category of the returned locations may be specified (option 'LTY').
     *
     * @var string
     */
    public $locationType;

    /**
     * @var LocationDescription
     */
    public $locationDescription;

    /**
     * LocationInformation constructor.
     *
     * @param string $type
     * @param string|null $code
     * @param string|null $name
     */
    public function __construct($type = self::TYPE_LOCATION, $code = null, $name = null)
    {
        $this->locationType = $type;
        $this->locationDescription = new LocationDescription($code, $name);
    }
}
