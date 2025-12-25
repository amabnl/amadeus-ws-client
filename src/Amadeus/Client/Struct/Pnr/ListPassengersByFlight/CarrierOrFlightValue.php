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

namespace Amadeus\Client\Struct\Pnr\ListPassengersByFlight;

use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\CarrierOrFlight as CarrierOrFlightOption;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\DateIdentification as DateIdentificationOption;

/**
 * CarrierOrFlight
 *
 * @package Amadeus\Client\Struct\Pnr\ListPassengersByFlight
 * @author Evan Chuang <ycchuang1999@gmail.com>
 */
class CarrierOrFlightValue
{
    /**
     * @var CarrierDetails
     */
    public $carrierDetails;

    /**
     * @var FlightDetails
     */
    public $flightDetails;

    /**
     * @var string
     */
    public $departureDate;

    /**
     * @var string
     */
    public $boardPoint;

    /**
     * @var string
     */
    public $offPoint;

    /**
     * CarrierOrFlight constructor.
     *
     * @param CarrierOrFlightOption $options
     */
    public function __construct(CarrierOrFlightOption $options)
    {
        if ($options->marketingCarrier) {
            $this->carrierDetails = new CarrierDetails($options->marketingCarrier);
        }

        if ($options->flightNumber) {
            $this->flightDetails = new FlightDetails(
                $options->flightNumber,
                $options->operationSuffix
            );
        }

        if ($options->departureDate instanceof \DateTime) {
            $this->departureDate = $options->departureDate->format('dmy');
        }

        if ($options->boardPoint) {
            $this->boardPoint = $options->boardPoint;
        }

        if ($options->offPoint) {
            $this->offPoint = $options->offPoint;
        }
    }
}
