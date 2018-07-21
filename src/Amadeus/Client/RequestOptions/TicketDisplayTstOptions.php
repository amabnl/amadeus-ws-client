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
 * Ticket_DisplayTST Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TicketDisplayTstOptions extends Base
{
    const MODE_ALL = 'ALL';
    const MODE_SELECTIVE = 'SEL';

    /**
     * Display all TST's or display selectively?
     *
     * self::MODE_*
     *
     * @var string
     */
    public $displayMode;

    /**
     * TST numbers to retrieve
     *
     * @var int[]
     */
    public $tstNumbers = [];

    /**
     * Segment Tattoo numbers for which to retrieve TST's
     *
     * @var int[]
     */
    public $segments = [];

    /**
     * Passenger Tattoo numbers for who to retrieve TST's
     *
     * @var int[]
     */
    public $passengers = [];

    /**
     * Scrolling view - display TST's starting from this number
     *
     * @var int
     */
    public $scrollingStart;

    /**
     * Scrolling view - how many TST's to display
     *
     * @var int
     */
    public $scrollingCount;
}
