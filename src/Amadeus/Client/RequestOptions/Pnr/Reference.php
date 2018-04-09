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

namespace Amadeus\Client\RequestOptions\Pnr;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Reference - For making passenger & segment association to a PNR segment or PNR element
 *
 * @package Amadeus\Client\RequestOptions\Pnr
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Reference extends LoadParamsFromArray
{
    const TYPE_PASSENGER_TATTOO = "PT";

    const TYPE_PASSENGER_REQUEST = "PR";

    const TYPE_SEGMENT_TATTOO = "ST";

    const TYPE_SEGMENT_REQUEST = "SR";

    /**
     * Reference type
     *
     * self::TYPE_*
     *
     * Possible values:
     * 001 Customer identification number
     * 002 Corporate identification number
     * D Dominant segment in a marriage
     * N Non dominant segment in a marriage
     * OT Other element tattoo reference number
     * PR Passenger Client-request-message-defined ref. nbr
     * PT Passenger tattoo reference number
     * SR Segment Client-request-message-defined ref. nbr
     * SS Segment Tattoo+SubTattoo reference number
     * ST Segment Tattoo reference number
     *
     * @var string
     */
    public $type;

    /**
     * Tattoo or reference number
     *
     * @var string
     */
    public $id;
}
