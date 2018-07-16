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
 * TicketProcessETicketOptions Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Mike Hernas <m@hern.as>
 */
class TicketProcessETicketOptions extends Base
{
    const ACTION_ETICKET_DISPLAY = 131;
    const ACTION_ETICKET_PRINT = 132;
    const ACTION_ETICKET_VOID = 133;
    const ACTION_ETICKET_HISTORY = 137;
    const ACTION_ETICKET_HYBRID_PRINT = 153;
    const ACTION_ETICKET_HYBRID_CANCEL = 79;
    

    /**
     * the ticket number returned from amadeus
     *
     * @var string
     */
    public $ticketNumber;


    /**
     * The code to define the action the query should perform
     *
     * @var int
     */
    public $action;
}
