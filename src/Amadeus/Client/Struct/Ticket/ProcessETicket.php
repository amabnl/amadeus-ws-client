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

namespace Amadeus\Client\Struct\Ticket;

use Amadeus\Client\RequestOptions\TicketProcessETicketOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Ticket\ProcessETicket\TicketInfoGroup;
use Amadeus\Client\Struct\Ticket\ProcessEDoc\MsgActionDetails;

/**
 * TicketProcessETicket
 *
 * Basic Request structure for the TicketProcessETicket messages
 *
 * @package Amadeus\Client\Struct\Ticket\EDoc
 * @author Mike Hernas <m@hern.as>
 */
class ProcessETicket extends BaseWsMessage
{
    /**
     * @var MsgActionDetails
     */
    public $msgActionDetails;

    /**
     * @var TicketInfoGroup[]
     */
    public $ticketInfoGroup = [];


    /**
     * ProcessETicket constructor.
     *
     * @param TicketProcessETicketOptions $options
     */
    public function __construct(TicketProcessETicketOptions $options)
    {
        $this->loadOptions($options->ticketNumber);
        $this->loadMsgAction($options->action);
    }

    /**
     * @param string $ticketNumber
     */
    protected function loadOptions($ticketNumber)
    {
        if (!empty($ticketNumber)) {
            $this->ticketInfoGroup[] = new TicketInfoGroup($ticketNumber);
        }
    }

    /**
     * @param string $action
     */
    protected function loadMsgAction($action)
    {
        if ($this->checkAnyNotEmpty($action)) {
            $this->msgActionDetails = new MsgActionDetails($action, null);
        }
    }
}
