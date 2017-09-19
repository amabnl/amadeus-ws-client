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

namespace Amadeus\Client\Struct\PriceXplorer;

/**
 * Structure class for the LocationIdentificationType message part for PriceXplorer_* messages
 *
 * @package Amadeus\Client\Struct\PriceXplorer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class LocationIdentificationType
{
    const QUAL_DESTINATION = "D";

    const QUAL_ORIGIN = "O";

    /**
     * Identification of the name of place/location, other than 3164 City name.
     *
     * IATA Code or:
     * ARNK     ARNK (for RTG use only)
     * ZZZ     ZZZ (used to designate all cities)
     *
     * @var string
     */
    public $code;

    /**
     *  Identification of a code list.
     *
     * @var string self::QUAL_*
     */
    public $qualifier;

    /**
     * Name of place/location, other than 3164 city name.
     *
     * @var string
     */
    public $name;
}
