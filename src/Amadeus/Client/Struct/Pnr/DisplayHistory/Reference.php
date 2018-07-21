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

namespace Amadeus\Client\Struct\Pnr\DisplayHistory;

/**
 * Reference
 *
 * @package Amadeus\Client\Struct\Pnr\DisplayHistory
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Reference
{
    /**
     * Other element tatoo reference number
     */
    const QUAL_OTHER_ELEMENT_TATTOO = "OT";
    /**
     * Passenger tattoo indicator
     */
    const QUAL_SEGMENT_TATTOO = "ST";
    /**
     * Segment tattoo indicator
     */
    const QUAL_PASSENGER_TATTOO = "PT";

    /**
     * self::QUAL_*
     *
     * @var string
     */
    public $qualifier;

    /**
     * The tattoo reference
     *
     * @var int
     */
    public $number;

    /**
     * Reference constructor.
     *
     * @param int $tattoo The tattoo reference
     * @param string $type self::QUAL_*
     */
    public function __construct($tattoo, $type)
    {
        $this->number = $tattoo;
        $this->qualifier = $type;
    }
}
