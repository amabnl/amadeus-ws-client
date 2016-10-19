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

namespace Amadeus\Client\RequestCreator;

use Amadeus\Client\RequestOptions\TicketCreateTsmFromPricingOptions;
use Amadeus\Client\RequestOptions\TicketCreateTstFromPricingOptions;
use Amadeus\Client\RequestOptions\TicketDeleteTstOptions;
use Amadeus\Client\RequestOptions\TicketDisplayTstOptions;
use Amadeus\Client\Struct;

/**
 * Ticket
 *
 * @package Amadeus\Client\RequestCreator
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Ticket
{
    /**
     * Ticket_CreateTstFromPricing
     *
     * @param TicketCreateTstFromPricingOptions $params
     * @return Struct\Ticket\CreateTSTFromPricing
     */
    public function createTicketCreateTSTFromPricing(TicketCreateTstFromPricingOptions $params)
    {
        return new Struct\Ticket\CreateTSTFromPricing($params);
    }

    /**
     * Ticket_CreateTSMFromPricing
     *
     * @param TicketCreateTsmFromPricingOptions $params
     * @return Struct\Ticket\CreateTSMFromPricing
     */
    public function createTicketCreateTSMFromPricing(TicketCreateTsmFromPricingOptions $params)
    {
        return new Struct\Ticket\CreateTSMFromPricing($params);
    }

    /**
     * Ticket_DeleteTST
     *
     * @param TicketDeleteTstOptions $params
     * @return Struct\Ticket\DeleteTST
     */
    public function createTicketDeleteTST(TicketDeleteTstOptions $params)
    {
        return new Struct\Ticket\DeleteTST($params);
    }

    /**
     * Ticket_DisplayTST
     *
     * @param TicketDisplayTstOptions $params
     * @return Struct\Ticket\DisplayTST
     */
    public function createTicketDisplayTST(TicketDisplayTstOptions $params)
    {
        return new Struct\Ticket\DisplayTST($params);
    }
}
