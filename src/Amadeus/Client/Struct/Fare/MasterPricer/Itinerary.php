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

namespace Amadeus\Client\Struct\Fare\MasterPricer;

/**
 * Itinerary
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Itinerary
{
    /**
     * Indicates reference of the requested segment
     *
     * @var RequestedSegmentRef
     */
    public $requestedSegmentRef;
    /**
     * Specification of the requested departure point
     *
     * @var DepartureLocalization
     */
    public $departureLocalization;
    /**
     * Specification of the requested arrival point
     *
     * @var ArrivalLocalization
     */
    public $arrivalLocalization;
    /**
     * Details on requested date and time plus range of date trip duration
     *
     * @var TimeDetails
     */
    public $timeDetails;
    /**
     * Specify Flight options.
     *
     * @var FlightInfo
     */
    public $flightInfo;
    /**
     * Info concerning the flights booked in the PNR
     *
     * @var mixed
     */
    public $flightInfoPNR;
    /**
     * Action identification for the requested segment
     *
     * @var mixed
     */
    public $requestedSegmentAction;
    /**
     * Coded attributes
     *
     * @var mixed
     */
    public $attributes;

    /**
     * @param int|null $reqSegRef
     */
    public function __construct($reqSegRef = null)
    {
        $this->requestedSegmentRef = new RequestedSegmentRef($reqSegRef);
    }
}
