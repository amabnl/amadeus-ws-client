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

use Amadeus\Client\RequestOptions\Ticket\FrequentFlyer;

/**
 * Ticket_ProcessEDoc Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Farah Hourani <farahhourani94@gmail.com>
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TicketProcessEDocOptions extends Base
{
    const ACTION_ETICKET_DISPLAY = 131;
    const ACTION_ETICKET_HISTORY = 137;
    const ACTION_ETICKET_UPDATE = 17;
    const ACTION_EMD_DISPLAY = 791;
    const ACTION_EMD_HISTORY = 797;
    const ACTION_EMD_UPDATE = 794;

    const ADD_ACTION_CONSOLIDATED_DISPLAY = 'CND';
    const ADD_ACTION_CONSOLIDATED_DISPLAY_ALSO = 'CNS';
    const ADD_ACTION_DISPLAY_FROM_DCS = 'DCS';
    const ADD_ACTION_ENHANCED_LIST_DISPLAY = 'EXT';
    const ADD_ACTION_LIST_DISPLAY_BY_FREQ_TRAV = 'FQT';
    const ADD_ACTION_LIST_DISPLAY_BY_PAX_NAME = 'PAX';
    const ADD_ACTION_LIST_DISPLAY_BY_UNUSED_COUPON = 'RUC';

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

    /**
     * Additional actions to perform
     *
     * @var string[]
     */
    public $additionalActions = [];

    /**
     * Frequent Traveller information
     *
     * @var FrequentFlyer[]
     */
    public $frequentTravellers = [];
}
