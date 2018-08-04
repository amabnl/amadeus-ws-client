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

use Amadeus\Client\RequestOptions\TicketCancelDocumentOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\DocRefund\DocumentNumberDetails;
use Amadeus\Client\Struct\Ticket\CancelDocument\SequenceNumberRanges;
use Amadeus\Client\Struct\Ticket\CancelDocument\StockProviderDetails;
use Amadeus\Client\Struct\Ticket\CancelDocument\TargetOfficeDetails;
use Amadeus\Client\Struct\Ticket\CancelDocument\VoidOption;

/**
 * Ticket_CancelDocument request structure
 *
 * @package Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CancelDocument extends BaseWsMessage
{
    /**
     * @var DocumentNumberDetails
     */
    public $documentNumberDetails;

    /**
     * @var SequenceNumberRanges[]
     */
    public $sequenceNumberRanges = [];

    /**
     * @var VoidOption
     */
    public $voidOption;

    /**
     * @var StockProviderDetails
     */
    public $stockProviderDetails;

    /**
     * @var TargetOfficeDetails
     */
    public $targetOfficeDetails;

    /**
     * CancelDocument constructor.
     *
     * @param TicketCancelDocumentOptions $options
     */
    public function __construct($options)
    {
        if (!empty($options->officeId)) {
            $this->targetOfficeDetails = new TargetOfficeDetails($options->officeId);
        }

        if ($this->checkAnyNotEmpty($options->airlineStockProvider, $options->marketStockProvider)) {
            $this->stockProviderDetails = new StockProviderDetails(
                $options->airlineStockProvider,
                $options->marketStockProvider
            );
        }

        if ($options->void === true) {
            $this->voidOption = new VoidOption();
        }

        foreach ($options->sequenceRanges as $sequenceRange) {
            $this->sequenceNumberRanges[] = new SequenceNumberRanges($sequenceRange->from, $sequenceRange->to);
        }

        if (!empty($options->eTicket)) {
            $this->documentNumberDetails = new DocumentNumberDetails($options->eTicket);
        }
    }
}
