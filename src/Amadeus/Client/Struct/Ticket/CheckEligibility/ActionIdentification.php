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

namespace Amadeus\Client\Struct\Ticket\CheckEligibility;

/**
 * ActionIdentification
 *
 * @package Amadeus\Client\Struct\Ticket\CheckEligibility
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ActionIdentification
{
    const REQ_ADD = "A";
    const REQ_CHANGE_REQUESTED_SEGMENT = "C";
    const REQ_IGNORE_ONEWAYCOMBINEABLE = "I";
    const REQ_KEEP_FLIGHTS = "K";
    const REQ_KEEP_FLIGHTS_AND_FARES = "KF";
    const REQ_IGNORE_OTHER = "O";
    const REQ_REMOVE = "R";

    /** DocRefund_UpdateRefund */
    const REQ_UPDATE_STAFF_PROFILE_FLAG = "STF";

    /**
     * self::REQ_*
     *
     * @var string
     */
    public $actionRequestCode;

    /**
     * @var ProductDetails
     */
    public $productDetails;

    /**
     * ActionIdentification constructor.
     *
     * @param string $actionRequestCode self::REQ_*
     */
    public function __construct($actionRequestCode)
    {
        $this->actionRequestCode = $actionRequestCode;
    }
}
