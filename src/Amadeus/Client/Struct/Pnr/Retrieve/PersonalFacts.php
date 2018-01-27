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

namespace Amadeus\Client\Struct\Pnr\Retrieve;

use Amadeus\Client\RequestOptions\Pnr\Retrieve\Ticket as TicketOptions;
use Amadeus\Client\Struct\WsMessageUtility;

/**
 * PersonalFacts
 *
 * @package Amadeus\Client\Struct\Pnr\Retrieve
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PersonalFacts extends WsMessageUtility
{
    /**
     * @var TravellerInformation
     */
    public $travellerInformation;

    /**
     * @var ProductInformation
     */
    public $productInformation;

    /**
     * @var Ticket
     */
    public $ticket;

    /**
     * PersonalFacts constructor.
     *
     * @param string|null $surName
     * @param \DateTime|null $departureDate
     * @param TicketOptions|null $ticket
     * @param string|null $company
     * @param string|null $flightNumber
     */
    public function __construct($surName, $departureDate, $ticket, $company, $flightNumber)
    {
        if (!empty($surName)) {
            $this->travellerInformation = new TravellerInformation($surName);
        }

        if ($departureDate instanceof \DateTime || $this->checkAnyNotEmpty($company, $flightNumber)) {
            $this->productInformation = new ProductInformation(
                $departureDate,
                $company,
                $flightNumber
            );
        }

        if ($ticket instanceof TicketOptions) {
            $this->ticket = new Ticket($ticket->airline, $ticket->number);
        }
    }
}
