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

namespace Amadeus\Client\Struct\Air\RetrieveSeatMap;

/**
 * Reservation
 *
 * @package Amadeus\Client\Struct\Air\RetrieveSeatMap
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Reservation
{
    /**
     * @var string
     */
    public $companyId;

    /**
     * @var string
     */
    public $controlNumber;

    /**
     * @var string
     */
    public $controlType;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $time;

    /**
     * Reservation constructor.
     *
     * @param string $controlNumber
     * @param string $companyId
     * @param \DateTime|null $date
     */
    public function __construct($controlNumber, $companyId, $date = null)
    {
        $this->controlNumber = $controlNumber;
        $this->companyId = $companyId;

        if ($date instanceof \DateTime) {
            $this->date = $date->format('dmy');
            $timeString = $date->format('Hi');

            if ($timeString !== '0000') {
                $this->time = $timeString;
            }
        }
    }
}
