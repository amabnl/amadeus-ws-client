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

use Amadeus\Client\RequestOptions\Fare\MPLocation;

/**
 * DepartureLocalization
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DepartureLocalization
{
    /**
     * Details on localization of the departure point
     *
     * @var LocationDetails
     */
    public $departurePoint;
    /**
     * Departure multi city option
     *
     * @var MultiCity[]
     */
    public $depMultiCity = [];
    /**
     * To specify a series or a range of PNR segments
     *
     * @var PNRSegmentReference
     */
    public $firstPnrSegmentRef;
    /**
     * Attribute details
     *
     * @var AttributeDetails[]
     */
    public $attributeDetails = [];

    /**
     * DepartureLocalization constructor.
     *
     * @param MPLocation $location
     */
    public function __construct(MPLocation $location)
    {
        if (empty($location->multiCity)) {
            $this->departurePoint = new LocationDetails($location);
        } else {
            foreach ($location->multiCity as $city) {
                $this->depMultiCity[] = new MultiCity($city);
            }
        }
    }
}
