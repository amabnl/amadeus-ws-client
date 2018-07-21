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
 * Basic PNR Segment
 *
 * @package Amadeus\Client\RequestOptions\Pnr
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Segment extends LoadParamsFromArray
{
    const STATUS_CONFIRMED = "HK";

    const STATUS_WAITLIST_CLOSED = "HL";

    const STATUS_NEED_SEGMENT = "NN";

    const STATUS_GHOST_HOLD_CONFIRMED = "GK";

    const STATUS_GHOST_HOLD_WAITLISTED = "GK";

    const STATUS_GHOST_NEED = "GN";

    const STATUS_PASSIVE_CONFIRMED = "PK";

    const STATUS_PASSIVE_WAITLISTED = "PL";

    /**
     * Segment status
     *
     * self::STATUS_*
     *
     * @var string
     */
    public $status = self::STATUS_CONFIRMED;

    /**
     * How many travellers?
     *
     * @var int
     */
    public $quantity = 1;

    /**
     * Company code (airline)
     *
     * @var string
     */
    public $company;

    /**
     * Passenger associations
     *
     * @var Reference[]
     */
    public $references = [];
}
