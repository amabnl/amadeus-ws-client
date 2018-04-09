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

namespace Amadeus\Client\Struct\Fare\GetFareRules;

use Amadeus\Client\Struct\WsMessageUtility;

/**
 * TripDescription
 *
 * @package Amadeus\Client\Struct\Fare\GetFareRules
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TripDescription extends WsMessageUtility
{
    /**
     * @var OrigDest
     */
    public $origDest;

    /**
     * @var DateFlightMovement
     */
    public $dateFlightMovement;

    /**
     * @var Routing[]
     */
    public $routing = [];

    /**
     * TripDescription constructor.
     *
     * @param string|null $origin
     * @param string|null $destination
     * @param \DateTime|null $travelDate
     */
    public function __construct($origin, $destination, $travelDate)
    {
        if ($this->checkAnyNotEmpty($origin, $destination)) {
            $this->origDest = new OrigDest($origin, $destination);
        }

        if ($travelDate instanceof \DateTime) {
            $this->dateFlightMovement = new DateFlightMovement($travelDate);
        }
    }
}
