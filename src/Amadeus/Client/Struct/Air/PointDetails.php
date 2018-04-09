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
 * PointDetails
 *
 * @package Amadeus\Client\Struct\Air
 * @author dieter <dermikagh@gmail.com>
 */
class PointDetails
{
    const ID_ARRIVAL_UNKNOWN = "ARNK";

    const ID_ALL_CITIES = "ZZZ";

    /**
     * self::ID_* or a location code
     *
     * @var string
     */
    public $trueLocationId;

    /**
     * @var string
     */
    public $trueLocation;

    /**
     * PointDetails constructor.
     *
     * @param string $locationId
     */
    public function __construct($locationId)
    {
        $this->trueLocationId = $locationId;
    }
}
