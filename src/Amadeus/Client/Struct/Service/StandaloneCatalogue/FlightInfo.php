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

namespace Amadeus\Client\Struct\Service\StandaloneCatalogue;

use Amadeus\Client\RequestOptions\Fare\InformativePricing\Segment;
use Amadeus\Client\Struct\Fare\InformativePricing13\SegmentGroup;
use Amadeus\Client\Struct\Fare\InformativePricing13\SegmentInformation;
use Amadeus\Client\Struct\Air\FlightTypeDetails;

/**
 * FlightInfo
 *
 * @package Amadeus\Client\Struct\Service\StandaloneCatalogue
 * @author Arvind Pandey <arvindpandey87@gmail.com>
 */
class FlightInfo extends SegmentGroup
{
    /**
     * @var SegmentInformation
     */
    public $flightDetails;

    /**
     * FlightInfo constructor.
     *
     * @param Segment $options
     */
    public function __construct($options)
    {
        $this->flightDetails = new SegmentInformation(
            $options->segmentTattoo,
            $options->departureDate,
            $options->from,
            $options->to,
            $options->marketingCompany,
            $options->flightNumber,
            $options->bookingClass
        );

        $this->loadOptionalSegmentInformation($options);

        SegmentGroup::loadInventory($options->inventory);
    }

    /**
     * Load non-required options if available
     *
     * @param FlightInfo $options
     */
    protected function loadOptionalSegmentInformation($options)
    {
        if (! empty($options->operatingCompany)) {
            $this->flightDetails->companyDetails->operatingCompany = $options->operatingCompany;
        }
        
        if ($options->arrivalDate instanceof \DateTime) {
            $this->flightDetails->flightDate->setArrivalDate($options->arrivalDate);
        }
        
        if (! empty($options->groupNumber)) {
            $this->flightDetails->flightTypeDetails = new FlightTypeDetails($options->groupNumber);
        }
        
        SegmentGroup::loadAdditionalSegmentDetails($options->airplaneCode, $options->nrOfStops);
    }
}
