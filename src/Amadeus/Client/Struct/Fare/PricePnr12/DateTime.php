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

namespace Amadeus\Client\Struct\Fare\PricePnr12;

/**
 * DateTime
 *
 * @package Amadeus\Client\Struct\Fare\PricePnr12
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DateTime
{
    /**
     * @var string
     */
    public $year;

    /**
     * @var string
     */
    public $month;

    /**
     * @var string
     */
    public $day;

    /**
     * PricePnr12 DateTime constructor.
     *
     * @param \DateTime|null $date
     */
    public function __construct($date)
    {
        if ($date instanceof \DateTime) {
            $this->year = $date->format('y');
            $this->month = $date->format('m');
            $this->day = $date->format('d');
        }
    }
}
