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

use Amadeus\Client\RequestOptions\Fare\MasterPricer\MultiTicketWeights;

/**
 * NumberOfUnit
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class NumberOfUnit
{
    /**
     * @var UnitNumberDetail[]
     */
    public $unitNumberDetail = [];

    /**
     * @param int|null $requestedPax
     * @param int|null $requestedResults
     * @param MultiTicketWeights|null $multiTicketWeights
     */
    public function __construct($requestedPax, $requestedResults, $multiTicketWeights)
    {
        if (is_int($requestedPax)) {
            $this->unitNumberDetail[] = new UnitNumberDetail(
                $requestedPax,
                UnitNumberDetail::TYPE_PASS
            );
        }
        if (is_int($requestedResults)) {
            $this->unitNumberDetail[] = new UnitNumberDetail(
                $requestedResults,
                UnitNumberDetail::TYPE_RESULTS
            );
        }

        if ($multiTicketWeights && $multiTicketWeights instanceof MultiTicketWeights) {
            $this->unitNumberDetail[] = new UnitNumberDetail(
                $multiTicketWeights->oneWayOutbound,
                UnitNumberDetail::TYPE_OUTBOUND_RECOMMENDATION
            );
            $this->unitNumberDetail[] = new UnitNumberDetail(
                $multiTicketWeights->oneWayInbound,
                UnitNumberDetail::TYPE_INBOUND_RECOMMENDATION
            );
            $this->unitNumberDetail[] = new UnitNumberDetail(
                $multiTicketWeights->returnTrip,
                UnitNumberDetail::TYPE_COMPLETE_RECOMMENDATION
            );
        }
    }
}
