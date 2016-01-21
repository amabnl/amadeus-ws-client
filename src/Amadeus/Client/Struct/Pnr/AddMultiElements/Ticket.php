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
     * @var string
     */
    const TICK_IND_TL = "TL";
    /**
     * Ticketing indicator for TK OK element
     * @var string
     */
    const TICK_IND_OK = "OK";

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
     */
    public function __construct($indicator)
    {
        $this->indicator = $indicator;
    }
}
