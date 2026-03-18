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

use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\SecondarySearchCriteria as SecondarySearchCriteriaOption;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\CarrierOrFlightValue;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\FrequentFlyerValue;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\NegoRecordLocator;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\TicketArrangementValue;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\NumericRangeValue;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\ElementTypeValue;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\ExtendedPassengerName;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\SsrOsiSkValue;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\CabinValue;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\StatusCodeValue;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\PassengerNameOrTypeValue;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\OriginValue;

/**
 * SecondarySearchCriteria
 *
 * @package Amadeus\Client\Struct\Pnr\ListPassengersByFlight
 * @author Evan Chuang <ycchuang1999@gmail.com>
 */
class SecondarySearchCriteria
{
    /**
     * @var TriggerMarker
     */
    public $triggerMarker;

    /**
     * @var ClassOfServiceValue
     */
    public $classOfServiceValue;

    /**
     * @var CarrierOrFlightValue
     */
    public $carrierOrFlightValue;

    /**
     * @var SsrOsiSkValue
     */
    public $ssrOsiSkValue;

    /**
     * @var CabinValue
     */
    public $cabinValue;

    /**
     * @var StatusCodeValue
     */
    public $statusCodeValue;

    /**
     * @var PassengerNameOrTypeValue
     */
    public $passengerNameOrTypeValue;

    /**
     * @var OriginValue
     */
    public $originValue;

    /**
     * @var TicketArrangementValue
     */
    public $ticketArrangementValue;

    /**
     * @var NumericRangeValue
     */
    public $numericRangeValue;

    /**
     * @var ElementTypeValue
     */
    public $elementTypeValue;

    /**
     * @var FrequentFlyerValue
     */
    public $frequentFlyerValue;

    /**
     * @var NegoRecordLocator
     */
    public $negoRecordLocator;

    /**
     * @var ExtendedPassengerName
     */
    public $extendedPassengerName;

    /**
     * SecondarySearchCriteria constructor.
     *
     * @param SecondarySearchCriteriaOption $options
     */
    public function __construct(SecondarySearchCriteriaOption $options)
    {
        // triggerMarker is a dummy segment, always present and empty according to PDF
        $this->triggerMarker = new TriggerMarker();

        if ($options->classOfService) {
            $this->classOfServiceValue = new ClassOfServiceValue($options->classOfService);
        }

        if ($options->carrierOrFlight) {
            $this->carrierOrFlightValue = new CarrierOrFlightValue($options->carrierOrFlight);
        }

        if ($options->ssrOsiSk) {
            $this->ssrOsiSkValue = new SsrOsiSkValue($options->ssrOsiSk);
        }

        if ($options->cabinCode) {
            $this->cabinValue = new CabinValue($options->cabinCode);
        }

        if ($options->statusCode) {
            $this->statusCodeValue = new StatusCodeValue($options->statusCode);
        }

        if ($options->passengerNameOrType) {
            $this->passengerNameOrTypeValue = new PassengerNameOrTypeValue($options->passengerNameOrType);
        }

        if ($options->origin) {
            $this->originValue = new OriginValue($options->origin);
        }

        if ($options->ticketArrangement) {
            $this->ticketArrangementValue = new TicketArrangementValue($options->ticketArrangement);
        }

        if ($options->numericRange) {
            $this->numericRangeValue = new NumericRangeValue($options->numericRange);
        }

        if ($options->segmentName) {
            $this->elementTypeValue = new ElementTypeValue($options->segmentName);
        }

        if ($options->frequentFlyer) {
            $this->frequentFlyerValue = new FrequentFlyerValue($options->frequentFlyer);
        }

        if ($options->negoRecordLocator) {
            $this->negoRecordLocator = new NegoRecordLocator($options->negoRecordLocator);
        }

        if ($options->extendedPassengerName) {
            $this->extendedPassengerName = new ExtendedPassengerName($options->extendedPassengerName);
        }
    }
}
