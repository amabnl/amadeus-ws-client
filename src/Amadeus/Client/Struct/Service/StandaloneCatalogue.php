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
namespace Amadeus\Client\Struct\Service;

use Amadeus\Client\RequestOptions\Fare\InformativePricing\Passenger;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\PricingOptions;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\Segment;
use Amadeus\Client\RequestOptions\ServiceStandaloneCatalogueOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Service\StandaloneCatalogue\PassengerInfoGroup;
use Amadeus\Client\Struct\Service\StandaloneCatalogue\FlightInfo;
use Amadeus\Client\Struct\Fare\PricePNRWithBookingClass13;

/**
 * StandaloneCatalogue
 *
 * @package Amadeus\Client\Struct\Fare
 * @author Arvind Pandey <arvindpandey87@gmail.com>
 */
class StandaloneCatalogue extends BaseWsMessage
{

    /**
     *
     * @var PassengerInfoGroup[]
     */
    public $passengerInfoGroup = [];

    /**
     *
     * @var FlightInfo[]
     */
    public $flightInfo = [];

    /**
     *
     * @var PricingOptions[]
     */
    public $pricingOption = [];

    /**
     * StandaloneCatalogue constructor.
     *
     * @param ServiceStandaloneCatalogueOptions|null $options
     */
    public function __construct($options)
    {
        if (! is_null($options)) {
            $this->loadPassengers($options->passengers);
            
            $this->loadFlightDetails($options->segments);
            
            $this->loadPricingOptions($options->pricingOptions);
        }
    }

    /**
     *
     * @param Passenger[] $passengers
     */
    protected function loadPassengers($passengers)
    {
        $counter = 1;
        foreach ($passengers as $passenger) {
            $this->passengerInfoGroup[] = new PassengerInfoGroup($passenger, $counter);
            $counter ++;
        }
    }

    /**
     *
     * @param Segment[] $segments
     */
    protected function loadFlightDetails($segments)
    {
        foreach ($segments as $segment) {
            $this->flightInfo[] = new FlightInfo($segment);
        }
    }

    /**
     *
     * @param PricingOptions|null $pricingOptions
     */
    protected function loadPricingOptions($pricingOptions)
    {
        $this->pricingOption = PricePNRWithBookingClass13::loadPricingOptionsFromRequestOptions($pricingOptions);
    }
}
