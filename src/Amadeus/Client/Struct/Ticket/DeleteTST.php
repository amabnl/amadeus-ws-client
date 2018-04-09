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

use Amadeus\Client\RequestOptions\TicketDeleteTstOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Ticket\DeleteTST\DeleteMode;

/**
 * Ticket_DeleteTST
 *
 * @package Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DeleteTST extends BaseWsMessage
{
    /**
     * @var DeleteMode
     */
    public $deleteMode;

    /**
     * @var PnrLocatorData
     */
    public $pnrLocatorData;

    /**
     * @var PsaList[]
     */
    public $psaList;

    /**
     * DeleteTST constructor.
     *
     * @param TicketDeleteTstOptions $params
     */
    public function __construct(TicketDeleteTstOptions $params)
    {
        $this->deleteMode = new DeleteMode($params->deleteMode);

        if ($params->deleteMode === AttributeDetails::MODE_SELECTIVE) {
            if (!empty($params->tstTattooNr)) {
                $this->psaList[] = new PsaList(
                    null,
                    ItemReference::REFTYPE_TST,
                    $params->tstTattooNr
                );
            }

            if (!empty($params->tstNumber)) {
                $this->psaList[] = new PsaList(
                    $params->tstNumber,
                    ItemReference::REFTYPE_TST
                );
            }

            if (!empty($params->passengerNumber)) {
                $tmp = new PsaList(
                    null,
                    ItemReference::REFTYPE_TST
                );

                $tmp->paxReference = new PaxReference(
                    $params->passengerNumber,
                    RefDetails::QUAL_PASSENGER
                );

                $this->psaList[] = $tmp;
            }

            if (!empty($params->segmentNumber)) {
                $tmp = new PsaList(
                    null,
                    ItemReference::REFTYPE_TST
                );

                $tmp->paxReference = new PaxReference(
                    $params->segmentNumber,
                    RefDetails::QUAL_SEGMENT_REFERENCE
                );

                $this->psaList[] = $tmp;
            }
        }
    }
}
