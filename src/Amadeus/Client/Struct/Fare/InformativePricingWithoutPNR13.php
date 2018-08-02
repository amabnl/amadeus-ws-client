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

namespace Amadeus\Client\Struct\Fare;

use Amadeus\Client\RequestOptions\Fare\InformativePricing\PricingOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\Passenger;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\Segment;
use Amadeus\Client\RequestOptions\FareInformativePricingWithoutPnrOptions;
use Amadeus\Client\Struct\Fare\InformativePricing13\OriginatorGroup;
use Amadeus\Client\Struct\Fare\InformativePricing13\PassengersGroup;
use Amadeus\Client\Struct\Fare\InformativePricing13\SegmentGroup;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionGroup;

/**
 * InformativePricingWithoutPNR
 *
 * @package Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class InformativePricingWithoutPNR13 extends BaseWsMessage
{
    /**
     * @var OriginatorGroup
     */
    public $originatorGroup;

    /**
     * @var PassengersGroup[]
     */
    public $passengersGroup = [];

    /**
     * @var SegmentGroup[]
     */
    public $segmentGroup = [];

    /**
     * @var PricingOptionGroup[]
     */
    public $pricingOptionGroup = [];

    /**
     * InformativePricingWithoutPNR13 constructor.
     *
     * @param FareInformativePricingWithoutPnrOptions|null $options
     */
    public function __construct($options)
    {
        if (!is_null($options)) {
            $this->loadPassengers($options->passengers);

            $this->loadSegments($options->segments);

            $this->loadPricingOptions($options->pricingOptions);
        }
    }

    /**
     * @param Passenger[] $passengers
     */
    protected function loadPassengers($passengers)
    {
        $counter = 1;

        foreach ($passengers as $passenger) {
            $this->passengersGroup[] = new PassengersGroup($passenger, $counter);
            $counter++;
        }
    }

    /**
     * @param Segment[] $segments
     */
    protected function loadSegments($segments)
    {
        foreach ($segments as $segment) {
            $this->segmentGroup[] = new SegmentGroup($segment);
        }
    }

    /**
     * @param PricingOptions|null $pricingOptions
     */
    protected function loadPricingOptions($pricingOptions)
    {
        if (!($pricingOptions instanceof PricingOptions)) {
            $pricingOptions = new PricingOptions();
        }
        $this->pricingOptionGroup = PricePNRWithBookingClass13::loadPricingOptionsFromRequestOptions($pricingOptions);
    }
}
