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

namespace Amadeus\Client\Struct\Air;

/**
 * RelatedproductInformation
 *
 * @package Amadeus\Client\Struct\Air
 * @author dieter <dermikagh@gmail.com>
 */
class RelatedproductInformation
{
    const STATUS_SELL_SEGMENT = "NN";
    const STATUS_CONFIRMED = "HK";
    const STATUS_WAITLISTED = "HL";
    const STATUS_CANCEL_REFUSED = "HX";
    const STATUS_CANCEL_SEGMENT = "OX";
    const STATUS_LINK_DOWN = "SS";
    const STATUS_SELL_REFUSED_UC = "UC";
    const STATUS_SELL_REFUSED_UN = "UN";
    const STATUS_CANCEL_ACCEPTED = "XX";

    //Constants for RebookAirSegment:
    const STATUS_FORCE_BOOKING = "FB";
    const STATUS_FORCE_GROUP = "FG";

    /**
     * @var int
     */
    public $quantity;

    /**
     * HK Confirmed
     * HL Waitlisted
     * HX Cancel refused
     * NN Sell Segment
     * OX Cancel segment
     * SS Link down
     * UC Sell refused
     * UN Sell refused
     * XX Cancel accepted
     * @var string
     */
    public $statusCode;

    /**
     * RelatedproductInformation constructor.
     *
     * @param int $quantity
     * @param string $statusCode
     */
    public function __construct($quantity, $statusCode)
    {
        $this->quantity = $quantity;
        $this->statusCode = $statusCode;
    }
}
