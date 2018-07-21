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

use Amadeus\Client\RequestOptions\Ticket\SequenceRange;

/**
 * Ticket_CancelDocument request options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TicketCancelDocumentOptions extends Base
{
    /**
     * E-Ticket document number.
     *
     * 13-digit document number or 10-digit number (without 3-digit numeric airline code).
     *
     * @var string
     */
    public $eTicket;

    /**
     * @var SequenceRange[]
     */
    public $sequenceRanges = [];

    /**
     * Void option.
     *
     * @var bool
     */
    public $void = false;

    /**
     * The targeted Airline Stock Provider (two-character code).
     *
     * @var string
     */
    public $airlineStockProvider;

    /**
     * The targeted Market Stock Provider (two-character code).
     *
     * @var string
     */
    public $marketStockProvider;

    /**
     * Office ID.
     *
     * @var string
     */
    public $officeId;
}
