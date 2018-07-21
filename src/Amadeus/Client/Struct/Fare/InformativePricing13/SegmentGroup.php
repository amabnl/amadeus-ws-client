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

use Amadeus\Client\RequestOptions\Fare\InformativePricing\Segment;
use Amadeus\Client\Struct\Air\FlightTypeDetails;

/**
 * SegmentGroup
 *
 * @package Amadeus\Client\Struct\Fare\InformativePricing13
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SegmentGroup
{
    /**
     * @var SegmentInformation
     */
    public $segmentInformation;

    /**
     * @var AdditionalSegmentDetails
     */
    public $additionnalSegmentDetails;

    /**
     * @var Inventory
     */
    public $inventory;

    /**
     * SegmentGroup constructor.
     *
     * @param Segment $options
     */
    public function __construct($options)
    {
        $this->segmentInformation = new SegmentInformation(
            $options->segmentTattoo,
            $options->departureDate,
            $options->from,
            $options->to,
            $options->marketingCompany,
            $options->flightNumber,
            $options->bookingClass
        );

        $this->loadOptionalSegmentInformation($options);

        $this->loadInventory($options->inventory);
    }

    /**
     * Load non-required options if available
     *
     * @param Segment $options
     */
    protected function loadOptionalSegmentInformation($options)
    {
        if (!empty($options->operatingCompany)) {
            $this->segmentInformation->companyDetails->operatingCompany = $options->operatingCompany;
        }

        if ($options->arrivalDate instanceof \DateTime) {
            $this->segmentInformation->flightDate->setArrivalDate($options->arrivalDate);
        }

        if (!empty($options->groupNumber)) {
            $this->segmentInformation->flightTypeDetails = new FlightTypeDetails($options->groupNumber);
        }

        $this->loadAdditionalSegmentDetails($options->airplaneCode, $options->nrOfStops);
    }

    /**
     * @param string|null $airplaneCode
     * @param int|null $nrOfStops
     */
    protected function loadAdditionalSegmentDetails($airplaneCode, $nrOfStops)
    {
        if (!empty($airplaneCode) || !empty($nrOfStops)) {
            $this->additionnalSegmentDetails = new AdditionalSegmentDetails($airplaneCode, $nrOfStops);
        }
    }

    /**
     * Load inventory information
     *
     * @param array $inventory
     */
    protected function loadInventory($inventory)
    {
        if (is_array($inventory) && count($inventory) > 0) {
            $this->inventory = new Inventory();

            foreach ($inventory as $bookingClass => $availabilityAmount) {
                $this->inventory->bookingClassDetails[] = new BookingClassDetails(
                    $bookingClass,
                    $availabilityAmount
                );
            }
        }
    }
}
