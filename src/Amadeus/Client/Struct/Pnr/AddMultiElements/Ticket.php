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

namespace Amadeus\Client\Struct\Pnr\AddMultiElements;

use Amadeus\Client\RequestOptions\Queue;

/**
 * Ticket
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Ticket
{
    /**
     * Ticketing indicator for TK TL element
     *
     * @var string
     */
    const TICK_IND_TL = "TL";
    /**
     * Ticketing indicator for TK OK element
     *
     * @var string
     */
    const TICK_IND_OK = "OK";
    /**
     * Ticketing indicator for TK XL element
     *
     * @var string
     */
    const TICK_IND_XL = "XL";

    /**
     * @var string
     */
    public $indicator;
    /**
     * @var string
     */
    public $date;
    /**
     * @var string
     */
    public $time;
    /**
     * @var string
     */
    public $officeId;
    /**
     * @var string
     */
    public $freetext;
    /**
     * @var string
     */
    public $airlineCode;
    /**
     * @var string
     */
    public $queueNumber;
    /**
     * @var string
     */
    public $queueCategory;

    /**
     * @var string[]
     */
    public $sitaAddress = [];

    /**
     * @param string $indicator
     * @param \DateTime $date
     * @param Queue $queue
     */
    public function __construct($indicator, $date = null, $queue = null)
    {
        $this->indicator = $indicator;

        if ($indicator !== self::TICK_IND_OK && $date instanceof \DateTime) {
            //Set Ticket Datetime
            $this->date = $date->format("dmy");

            $tickTime = $date->format('Gis');
            if ($tickTime !== "000000") {

            }
        }

        if ($indicator === self::TICK_IND_TL && $queue instanceof Queue) {
            //Set Ticket Queue
        }
    }
}
