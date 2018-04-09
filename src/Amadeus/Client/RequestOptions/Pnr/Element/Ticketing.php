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

namespace Amadeus\Client\RequestOptions\Pnr\Element;

use Amadeus\Client\RequestOptions\Pnr\Element;
use Amadeus\Client\RequestOptions\Queue;

/**
 * Ticketing element
 *
 * @package Amadeus\Client\RequestOptions\Pnr\Element
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Ticketing extends Element
{
    /**
     * Ticketing indicator for TK TL element
     *
     * @var string
     */
    const TICKETMODE_TIMELIMIT = "TL";
    /**
     * Ticketing indicator for TK OK element
     *
     * @var string
     */
    const TICKETMODE_OK = "OK";
    /**
     * Ticketing indicator for TK XL element
     *
     * @var string
     */
    const TICKETMODE_CANCEL = "XL";

    /**
     * self::TICKETMODE_*
     *
     * @var string
     */
    public $ticketMode;

    /**
     * @var \DateTime
     */
    public $date;

    /**
     * Queue to place the PNR on (for TICKETMODE_TIMELIMIT)
     *
     * @var Queue
     */
    public $ticketQueue;
}
