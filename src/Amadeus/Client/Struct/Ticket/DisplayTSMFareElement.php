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

use Amadeus\Client\RequestOptions\TicketDisplayTsmFareElOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Ticket\DisplayTSMFareElement\FareElementTattoo;

/**
 * Ticket_DisplayTSMFareElement request structure
 *
 * @package Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DisplayTSMFareElement extends BaseWsMessage
{
    /**
     * @var FareElementTattoo[]
     */
    public $fareElementTattoo = [];

    /**
     * DisplayTSMFareElement constructor.
     *
     * @param TicketDisplayTsmFareElOptions $params
     */
    public function __construct(TicketDisplayTsmFareElOptions $params)
    {
        $this->fareElementTattoo[] = new FareElementTattoo(
            $params->tattoo,
            $params->type
        );
    }
}
