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

namespace Amadeus\Client\Struct\Queue;

/**
 * Class ScanRange
 *
 * @package Amadeus\Client\Struct\Queue
 */
class ScanRange
{
    const RANGE_INDIVIDUAL_NUMBERS = 700;
    const RANGE_OF_NUMBERS = 701;

    /**
     * self::RANGE_*
     *
     * @var string|int
     */
    public $rangeQualifier;

    /**
     * @var RangeDetails[]
     */
    public $rangeDetails = [];

    /**
     * ScanRange constructor.
     *
     * @param int $start
     * @param int $end
     * @param int $qualifier
     */
    public function __construct($start, $end, $qualifier = self::RANGE_OF_NUMBERS)
    {
        $this->rangeQualifier = $qualifier;
        $this->rangeDetails[] = new RangeDetails($start, $end);
    }
}
