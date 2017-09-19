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

namespace Amadeus\Client\Struct\Pnr\AddMultiElements;

/**
 * Seat
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Seat
{
    /**
     * @var string
     */
    public $qualifier;

    /**
     *
     * NSST No smoking seat
     * NSSB No smoking bulkhead seat
     * NSSA No smoking aisle seat
     * NSSW No smoking window seat
     * SMSW Smoking window seat
     * SMST Smoking seat
     * SMSB Smoking bulkhead seat
     * SMSA Smoking aisle seat
     * SEAT Pre-reserved seat with boarding pass issued or to be issued
     * RQST Seat request - include seat number preference
     *
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $boardpoint;

    /**
     * @var string
     */
    public $offpoint;

    /**
     * Seat constructor.
     *
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }
}
