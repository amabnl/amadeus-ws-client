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

use Amadeus\Client\RequestOptions\Ticket\ReqSegOptions;
use Amadeus\Client\RequestOptions\TicketAtcShopperMpTbSearchOptions;
use Amadeus\Client\Struct\Fare\MasterPricerTravelBoardSearch;
use Amadeus\Client\Struct\Ticket\CheckEligibility\TicketChangeInfo;

/**
 * Ticket_ATCShopperMasterPricerTravelBoardSearch message structure
 *
 * @package Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AtcShopperMasterPricerTravelBoardSearch extends MasterPricerTravelBoardSearch
{
    /**
     * @var TicketChangeInfo
     */
    public $ticketChangeInfo;

    /**
     * AtcShopperMasterPricerTravelBoardSearch constructor.
     * @param TicketAtcShopperMpTbSearchOptions|null $options
     */
    public function __construct($options)
    {
        parent::__construct($options);

        $this->loadTicketChangeInfo($options->ticketNumbers, $options->requestedSegments);
    }

    /**
     * @param string[] $ticketNumbers
     * @param ReqSegOptions[] $requestedSegments
     */
    protected function loadTicketChangeInfo($ticketNumbers, $requestedSegments)
    {
        if (!empty($ticketNumbers)) {
            $this->ticketChangeInfo = new TicketChangeInfo($ticketNumbers, $requestedSegments);
        }
    }
}
