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

namespace Amadeus\Client\Struct\Ticket\RepricePnrWithBookingClass;

use Amadeus\Client\RequestOptions\Ticket\ExchangeInfoOptions;

/**
 * ExchangeInformationGroup
 *
 * @package Amadeus\Client\Struct\Ticket\RepricePnrWithBookingClass
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ExchangeInformationGroup
{
    /**
     * @var TransactionIdentifier
     */
    public $transactionIdentifier;

    /**
     * @var DocumentInfoGroup[]
     */
    public $documentInfoGroup = [];

    /**
     * ExchangeInformationGroup constructor.
     *
     * @param ExchangeInfoOptions $options
     */
    public function __construct(ExchangeInfoOptions $options)
    {
        $this->transactionIdentifier = new TransactionIdentifier($options->number);

        foreach ($options->eTickets as $eTicket) {
            $this->documentInfoGroup[] = new DocumentInfoGroup(
                $eTicket,
                DocumentDetails::TYPE_ELECTRONIC_TICKET
            );
        }

        foreach ($options->paperTickets as $paperTicket) {
            $this->documentInfoGroup[] = new DocumentInfoGroup(
                $paperTicket,
                DocumentDetails::TYPE_PAPER_TICKET
            );
        }
    }
}
