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

namespace Amadeus\Client\Struct\Ticket\CheckEligibility;

use Amadeus\Client\RequestOptions\Ticket\ReqSegOptions;

/**
 * TicketChangeInfo
 *
 * @package Amadeus\Client\Struct\Ticket\CheckEligibility
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TicketChangeInfo
{
    /**
     * @var TicketNumberDetails
     */
    public $ticketNumberDetails;

    /**
     * @var TicketRequestedSegments[]
     */
    public $ticketRequestedSegments = [];

    /**
     * TicketChangeInfo constructor.
     *
     * @param string[] $ticketNumbers
     * @param ReqSegOptions[]|null $requestedSegments
     */
    public function __construct(array $ticketNumbers, $requestedSegments = null)
    {
        $this->ticketNumberDetails = new TicketNumberDetails($ticketNumbers);

        if (!empty($requestedSegments)) {
            foreach ($requestedSegments as $requestedSegment) {
                $this->ticketRequestedSegments[] = new TicketRequestedSegments(
                    $requestedSegment->requestCode,
                    $requestedSegment->connectionLocations
                );
            }
        }
    }
}
