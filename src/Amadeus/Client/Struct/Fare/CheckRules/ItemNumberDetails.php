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

namespace Amadeus\Client\Struct\Fare\CheckRules;

/**
 * ItemNumberDetails
 *
 * @package Amadeus\Client\Struct\Fare\CheckRules
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class ItemNumberDetails
{
    /**
     * @var string
     */
    public $number;

    /**
     * Possible values:
     *
     * 700 Frequent Traveler account to be decremented
     * 701 Teletype address
     * 702 Queue Identifier
     * 703 Sub-queue category
     * 704 First booked segment
     * 705 Last booked segment
     * A Account number
     * C Customer number
     * D Document number
     * FC Fare Component
     * P Product number
     * T Total of Fare Required
     *
     * @var string
     */
    public $type;

    /**
     * ItemNumberDetails constructor.
     *
     * @param string $itemNumber
     * @param string|null $type
     */
    public function __construct($itemNumber, $type = null)
    {
        $this->number = $itemNumber;
        $this->type = $type;
    }
}
