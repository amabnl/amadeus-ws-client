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

namespace Amadeus\Client\RequestOptions;

/**
 * Ticket_DeleteTst Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TicketDeleteTstOptions extends Base
{
    const DELETE_MODE_ALL = "ALL";

    const DELETE_MODE_SELECTIVE = "SEL";

    /**
     * Delete all TST's or delete selectively?
     *
     * self::DELETE_MODE_*
     *
     * @var string
     */
    public $deleteMode;

    /**
     * Delete TST by Tattoo number
     *
     * @var string
     */
    public $tstTattooNr;

    /**
     * Delete TST by TST number
     *
     * @var int
     */
    public $tstNumber;

    /**
     * Delete TST by passenger number
     *
     * @var int
     */
    public $passengerNumber;

    /**
     * Delete TST by segment number
     *
     * @var int
     */
    public $segmentNumber;
}
