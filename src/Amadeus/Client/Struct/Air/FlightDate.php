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

namespace Amadeus\Client\Struct\Air;

/**
 * FlightDate
 *
 * @package Amadeus\Client\Struct\Air
 * @author dieter <dermikagh@gmail.com>
 */
class FlightDate
{
    protected $dateFormat = 'dmy';

    /**
     * DDMMYY
     *
     * @var string
     */
    public $departureDate;
    /**
     * HHMM
     *
     * @var string
     */
    public $departureTime;
    /**
     * DDMMYY
     *
     * @var string
     */
    public $arrivalDate;
    /**
     * HHMM
     *
     * @var string
     */
    public $arrivalTime;
    /**
     * @var string|int
     */
    public $dateVariation;

    /**
     * FlightDate constructor.
     *
     * @param string|\DateTime|null $departureDate in format DDMMYY or \DateTime
     * @param \DateTime|null $arrivalDate
     * @param string|\DateTime|null $arrivalTime
     * @param int|null $dateVariation
     */
    public function __construct($departureDate, $arrivalDate = null, $arrivalTime = null, $dateVariation = null)
    {
        $this->loadDepartureDate($departureDate);

        $this->loadArrivalDate($arrivalDate, $arrivalTime);

        if (!is_null($dateVariation)) {
            $this->dateVariation = $dateVariation;
        }
    }

    /**
     * @param \DateTime|string|null $departureDate
     */
    protected function loadDepartureDate($departureDate)
    {
        if ($departureDate instanceof \DateTime) {
            $this->departureDate = ($departureDate->format('dmy') !== '000000') ? $departureDate->format($this->dateFormat) : null;
            $time = $departureDate->format('Hi');
            if ($time !== '0000') {
                $this->departureTime = $time;
            }
        } elseif (!empty($departureDate)) {
            $this->departureDate = $departureDate;
        }
    }

    /**
     * @param \DateTime|null $arrivalDate
     * @param string|\DateTime|null $arrivalTime
     */
    protected function loadArrivalDate($arrivalDate, $arrivalTime)
    {
        if ($arrivalDate instanceof \DateTime) {
            $this->setArrivalDate($arrivalDate);
        } elseif ($arrivalTime instanceof \DateTime) {
            $time = $arrivalTime->format('Hi');
            if ($time !== '0000') {
                $this->arrivalTime = $time;
            }
        } elseif (is_string($arrivalTime) && !empty($arrivalTime)) {
            $this->arrivalTime = $arrivalTime;
        }
    }

    /**
     * Load Arrival date info from \DateTime
     *
     * @param \DateTime $arrivalDate
     */
    public function setArrivalDate(\DateTime $arrivalDate)
    {
        $this->arrivalDate = ($arrivalDate->format('dmy') !== '000000') ? $arrivalDate->format($this->dateFormat) : null;
        $time = $arrivalDate->format('Hi');
        if ($time !== '0000') {
            $this->arrivalTime = $time;
        }
    }
}
