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
 * Structure class for the DepartureDays message part for PriceXplorer_* messages
 *
 * @package Amadeus\Client\Struct\PriceXplorer
 * @author  Dieter Devlieghere <dermikagh@gmail.com>
 */
class DepartureDays
{
    /**
     * @var DaySelection
     */
    public $daySelection;
    /**
     * @var DaySelection
     */
    public $additionalDaySelection;
    /**
     * @var SelectionInfo
     */
    public $selectionInfo;

    /**
     * @param array $weekDays Week days in array format: e.g. array(1, 2)
     *                        for Monday and Tuesday. MUST BE CONSECUTIVE DAYS
     * @param string|null $departureDayOption
     */
    public function __construct($weekDays = [], $departureDayOption = null)
    {
        $this->daySelection = new DaySelection($weekDays);

        if ($departureDayOption === null) {
            $this->selectionInfo = new SelectionInfo();
        } else {
            $this->selectionInfo = new SelectionInfo($departureDayOption);
        }
    }
}
