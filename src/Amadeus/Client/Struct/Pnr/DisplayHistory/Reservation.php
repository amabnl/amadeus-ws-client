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
 * Reservation
 *
 * @package Amadeus\Client\Struct\Pnr\DisplayHistory
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Reservation
{
    /**
     * @var string
     */
    public $controlNumber;

    /**
     * YYYYMMDD
     *
     * @var string
     */
    public $date;

    /**
     * HHMM
     *
     * @var string
     */
    public $time;

    /**
     * Reservation constructor.
     *
     * @param string $recordLocator
     * @param \DateTime|null $creationDateTime
     */
    public function __construct($recordLocator, $creationDateTime = null)
    {
        $this->controlNumber = $recordLocator;

        if ($creationDateTime instanceof \DateTime) {
            $this->loadCreationDate($creationDateTime);
        }
    }

    /**
     * @param \DateTime $creation
     */
    protected function loadCreationDate(\DateTime $creation)
    {
        $this->date = $creation->format('Ymd');

        $time = $creation->format('Hi');
        if ($time !== '0000') {
            $this->time = $time;
        }
    }
}
