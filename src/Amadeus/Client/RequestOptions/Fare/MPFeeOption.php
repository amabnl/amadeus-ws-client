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

namespace Amadeus\Client\RequestOptions\Fare;

use Amadeus\Client\LoadParamsFromArray;
use Amadeus\Client\RequestOptions\Fare\MasterPricer\FeeDetails;

/**
 * MasterPricer FeeOption request settings
 *
 * @package Amadeus\Client\RequestOptions\Fare
 * @author Friedemann Schmuhl <friedemann@schmuhl.eu>
 */
class MPFeeOption extends LoadParamsFromArray
{
    const TYPE_BOOKING_FEES = "OA";
    const TYPE_TICKETING_FEES = "OB";
    const TYPE_SERVICE_FEES = "OC";

    /**
     * Carrier fee type.
     *
     * @var string
     */
    public $type;

    /**
     * Details for each fee.
     *
     * @var FeeDetails[]
     */
    public $feeDetails;
}
