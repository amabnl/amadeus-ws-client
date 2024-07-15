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

use Amadeus\Client\RequestOptions\TicketRetrieveListOfTSMOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Ticket\RetrieveListOfTSM\DeadIndicator;
use Amadeus\Client\Struct\Ticket\RetrieveListOfTSM\StatusDetails;

/**
 * Ticket_RetrieveListOfTSM request structure
 *
 * @package Amadeus\Client\Struct\Ticket
 * @author Mike Hernas <m@hern.as>
 */
class RetrieveListOfTSM extends BaseWsMessage
{
    /**
     * @var DeadIndicator
     */
    public $deadIndicator;

    /**
     * RetrieveListOfTSM constructor.
     *
     * @param TicketRetrieveListOfTSMOptions $params
     */
    public function __construct(TicketRetrieveListOfTSMOptions $params)
    {
        $this->deadIndicator = new DeadIndicator(
            new StatusDetails(StatusDetails::IND_DEAD, 0)
        );
    }
}
