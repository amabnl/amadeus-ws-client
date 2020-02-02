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

use Amadeus\Client\RequestOptions\Fare\MPPassenger;
use Amadeus\Client\RequestOptions\FareMasterPricerCalendarOptions;
use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
use Amadeus\Client\RequestOptions\TicketCheckEligibilityOptions;
use Amadeus\Client\RequestOptions\Fare\MasterPricer\MultiTicketWeights;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * BaseMasterPricerMessage
 *
 * @package Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class BaseMasterPricerMessage extends BaseWsMessage
{
    /**
     * Number of seats, recommendations.
     *
     * @var MasterPricer\NumberOfUnit
     */
    public $numberOfUnit;

    /**
     * Traveler Details
     *
     * @var MasterPricer\PaxReference[]
     */
    public $paxReference = [];

    /**
     * @var MasterPricer\FareOptions
     */
    public $fareOptions;

    /**
     * @var array
     */
    public $buckets = [];
 

    /**
     * @param MPPassenger $passenger
     * @param int $counter BYREF
     * @param int $infantCounter BYREF
     */
    protected function loadPassenger($passenger, &$counter, &$infantCounter)
    {
        $isInfant = ($passenger->type === 'INF');

        $paxRef = new MasterPricer\PaxReference(
            $isInfant ? $infantCounter : $counter,
            $isInfant,
            $passenger->type
        );

        if ($isInfant) {
            $infantCounter++;
        } else {
            $counter++;
        }

        if ($passenger->count > 1) {
            for ($i = 2; $i <= $passenger->count; $i++) {
                $tmpCount = ($isInfant) ? $infantCounter : $counter;
                $paxRef->traveller[] = new MasterPricer\Traveller($tmpCount, $isInfant);

                if ($isInfant) {
                    $infantCounter++;
                } else {
                    $counter++;
                }
            }
        }

        $this->paxReference[] = $paxRef;
    }

    /**
     * @param FareMasterPricerTbSearch|FareMasterPricerCalendarOptions|TicketCheckEligibilityOptions $options
     * @return void
     */
    protected function loadNumberOfUnits($options)
    {
        if (is_int($options->nrOfRequestedPassengers) ||
            is_int($options->nrOfRequestedResults) ||
            $options->multiTicketWeights instanceof MultiTicketWeights
        ) {
            $this->numberOfUnit = new MasterPricer\NumberOfUnit(
                $options->nrOfRequestedPassengers,
                $options->nrOfRequestedResults,
                $options->multiTicketWeights
            );
        }
    }

    /**
     * @param FareMasterPricerTbSearch|FareMasterPricerCalendarOptions|TicketCheckEligibilityOptions $options
     */
    protected function loadFareOptions($options)
    {
        if ($options->doTicketabilityPreCheck === true ||
            $this->checkAnyNotEmpty(
                $options->corporateCodesUnifares,
                $options->flightOptions,
                $options->currencyOverride,
                $options->feeIds,
                $options->multiTicket,
                $options->ticketingPriceScheme,
                $options->formOfPayment
            )
        ) {
            $this->fareOptions = new MasterPricer\FareOptions(
                $options->flightOptions,
                $options->corporateCodesUnifares,
                $options->doTicketabilityPreCheck,
                $options->currencyOverride,
                $options->feeIds,
                $options->corporateQualifier,
                $options->multiTicket,
                $options->ticketingPriceScheme,
                $options->formOfPayment
            );
        }
    }
}
