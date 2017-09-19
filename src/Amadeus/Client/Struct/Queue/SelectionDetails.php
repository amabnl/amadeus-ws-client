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
 * Structure class for the SelectionDetails message part for Queue_* messages
 *
 * @package Amadeus\Client\Struct\Queue
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SelectionDetails
{
    const PLACEPNR_OPTION_QUEUE = "QEQ";
    const PLACEPNR_OPTION_DELAYQUEUE = "QED";
    const MOVE_OPTION_COPY_QUEUE_KEEP = "QBB";
    const MOVE_OPTION_COPY_QUEUE_REMOVE = "QBD";
    const LIST_OPTION_SORT_CREATION = "CD";
    const LIST_OPTION_SORT_DEPARTURE = "DD";
    const LIST_OPTION_SORT_TICKETING = "TD";
    const REMOVE_OPTION_SPECIFIC_PNR = "QRP";
    const REMOVE_OPTION_ALL = "QR";

    /* Queue_* options:
     * QBA  copy PNR/msg to specified delay queue and keep existing
     * QBB  copy PNR/msg to specified queue and keep existing
     * QBC  copy PNR/msg to specified delay queue and remove existing
     * QBD  copy PNR/msg to specified queue and remove existing
     * QWD  copy PNR from delay planner and keep existing
     * QWO  copy PNR from option planner and keep existing
     * QWR  copy PNR from planner and remove existing
     * QWS  copy PNR from planner and keep existing
     * QWT  copy PNR from ticketing planner and keep existing
     */

    /**
     * @var string
     */
    public $option;

    /**
     * @param string $optionCode
     */
    public function __construct($optionCode)
    {
        $this->option = $optionCode;
    }
}
