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

namespace Amadeus\Client\Struct\Fare\InformativePricing13;

use Amadeus\Client\RequestOptions\Fare\InformativePricing\Passenger;

/**
 * PassengersGroup
 *
 * @package Amadeus\Client\Struct\Fare\InformativePricing13
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PassengersGroup
{
    /**
     * @var SegmentRepetitionControl
     */
    public $segmentRepetitionControl;

    /**
     * @var TravellersId
     */
    public $travellersID;

    /**
     * @var DiscountPtc
     */
    public $discountPtc;

    /**
     * PassengersGroup constructor.
     *
     * @param Passenger $passenger
     * @param int $group
     */
    public function __construct($passenger, $group)
    {
        $this->segmentRepetitionControl = new SegmentRepetitionControl(
            $group,
            count($passenger->tattoos)
        );

        $this->travellersID = new TravellersId();

        foreach ($passenger->tattoos as $tattoo) {
            $this->travellersID->travellerDetails[] = new TravellerDetails($tattoo);
        }

        if (!empty($passenger->type)) {
            $this->discountPtc = new DiscountPtc($passenger->type);
        }
    }
}
