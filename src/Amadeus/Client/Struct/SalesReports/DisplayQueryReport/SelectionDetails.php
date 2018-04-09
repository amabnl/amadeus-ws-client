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

namespace Amadeus\Client\Struct\SalesReports\DisplayQueryReport;

/**
 * SelectionDetails
 *
 * @package Amadeus\Client\Struct\SalesReports\DisplayQueryReport
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SelectionDetails
{
    /**
     * All agents of the office
     */
    const SELECT_OFFICE_ALL_AGENTS = "SOF";
    /**
     * All offices sharing the same IATA number
     */
    const SELECT_ALL_OFFICES_SHARING_IATA_NR = "SAN";
    /**
     * Satellite ticket office
     */
    const SELECT_SATELLITE_TICKET_OFFICE = "STP";
    /**
     * Ticket delivery office
     */
    const SELECT_TICKET_DELIVERY_OFFICE = "TDO";
    /**
     * by net remit qualifier
     */
    const SELECT_BY_NET_REMIT = "BNR";

    /**
     * self::SELECT_*
     *
     * @var string
     */
    public $option;

    /**
     * SelectionDetails constructor.
     * @param string $option
     */
    public function __construct($option)
    {
        $this->option = $option;
    }
}
