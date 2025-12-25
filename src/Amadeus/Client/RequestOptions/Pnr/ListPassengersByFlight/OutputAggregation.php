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

namespace Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Output Aggregation
 *
 * @package Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight
 * @author Evan Chuang <ycchuang1999@gmail.com>
 */
class OutputAggregation extends LoadParamsFromArray
{
    const AGGREGATION_KEY_CABIN = 'CAB';
    const AGGREGATION_KEY_CLASS_OF_SERVICE = 'CLA';
    const AGGREGATION_KEY_INBOUND_CONNECTION = 'INB';
    const AGGREGATION_KEY_FLIGHT_LEG = 'LEG';
    const AGGREGATION_KEY_ALLIANCE_TIER_DESCRIPTION = 'LTD';
    const AGGREGATION_KEY_MEAL_TYPE = 'MEA';
    const AGGREGATION_KEY_OUTBOUND_CONNECTION = 'OUT';
    const AGGREGATION_KEY_AIRLINE_PRIORITY_CODE = 'RPC';
    const AGGREGATION_KEY_AIRLINE_TIER_LEVEL = 'RTL';
    const AGGREGATION_KEY_FLIGHT_SEGMENT = 'SEG';
    const AGGREGATION_KEY_STAFF_TYPE = 'STF';

    /**
     * Position of the aggregation key in the aggregation hierarchy
     *
     * @var int
     */
    public $aggregationLevel;

    /**
     * Aggregation key (e.g. aggregate by class)
     *
     * self::AGGREGATION_KEY_*
     *
     * @var string
     */
    public $aggregationKey;
}
