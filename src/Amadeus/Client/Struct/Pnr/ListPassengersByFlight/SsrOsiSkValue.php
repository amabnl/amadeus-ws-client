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

use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\SsrOsiSk;

class SsrOsiSkValue
{
    /**
     * @var SpecialRequirementsInfo
     */
    public $specialRequirementsInfo;

    /**
     * @var SeatDetails
     */
    public $seatDetails;

    /**
     * @param SsrOsiSk $options
     */
    public function __construct(SsrOsiSk $options)
    {
        if ($options->ssrCode || $options->serviceType) {
            $this->specialRequirementsInfo = new SpecialRequirementsInfo($options->ssrCode, $options->serviceType);
        }
        if ($options->seatNumber) {
            $this->seatDetails = new SeatDetails($options->seatNumber);
        }
    }
}
