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

/**
 * BookingClassDetails
 *
 * @package Amadeus\Client\Struct\Fare\InformativePricing13
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class BookingClassDetails
{
    /**
     * 1 Request all non-displayable RBD's
     * 2 Request all RBD's including non-displayable RBD's.
     * 3 Request all Frequent Flyer Program Award Classes
     * 4 Total number of seats in the allotment
     * 5 Number of seats sold in the allotment
     * 6 Number of seats unsold in the allotment
     * 700 Request is expanded to include nonmatching connections
     *
     * @var string
     */
    public $designator;

    /**
     * 70A Suppress from display
     * 70B Class cancelled
     * 70C Class closed on limit sales level
     * A Quota sell limit as agreed
     * AVL Available
     * C Closed
     * L Waitlist only
     * N Near to sell
     * R Request only
     * X Closed to arrival
     * Z All status
     *
     * @var string|int
     */
    public $availabilityStatus;

    /**
     * A Luxury or premium meal
     * B Breakfast
     * BR Brunch
     * C Alcoholic beverages - complimentary
     * D Dinner
     * E Entertainment
     * F Food for purchase
     * G Lite lunch
     * K Cold buffet
     * L Lunch
     * M Meal (to be used as a generalization)
     * P Alcoholic beverages for purchase
     * R Refreshment
     * S Snack or light meal
     * V Continental breakfast
     *
     * @var string
     */
    public $specialService;

    /**
     * BookingClassDetails constructor.
     *
     * @param string $bookingClass
     * @param string|int $availabilityAmount
     */
    public function __construct($bookingClass, $availabilityAmount)
    {
        $this->designator = $bookingClass;
        $this->availabilityStatus = $availabilityAmount;
    }
}
