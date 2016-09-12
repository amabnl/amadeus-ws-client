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

use Amadeus\Client\RequestOptions\TicketCreateTstFromPricingOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Ticket_CreateTSTFromPricing
 *
 * @package Amadeus\Client\Struct\Ticket
 * @author dieter <dieter.devlieghere@benelux.amadeus.com>
 */
class CreateTSTFromPricing extends BaseWsMessage
{
    /**
     * PNR record locator information for this transaction.
     *
     * This PNR record locator is used for tracing purpose, no internal retrieve.
     *
     * @var PnrLocatorData
     */
    public $pnrLocatorData;

    /**
     * List of fares to take into account for TST creation.
     *
     * A fare has been calculated for several .
     * As we can have 10 TST per Pax, 99 passenger per PNR, and a TST split with the Infant,
     * the max number of TST is 1980.
     *
     * @var PsaList[]
     */
    public $psaList = [];

    /**
     * CreateTSTFromPricing constructor.
     *
     * @param TicketCreateTstFromPricingOptions $params
     */
    public function __construct(TicketCreateTstFromPricingOptions $params)
    {
        foreach ($params->pricings as $pricing) {
            $tmp = new PsaList(
                $pricing->tstNumber,
                ItemReference::REFTYPE_TST
            );

            if (!empty($pricing->passengerReferences)) {
                $tmp->paxReference = new PaxReference();

                foreach ($pricing->passengerReferences as $passengerReference) {
                    $tmp->paxReference->refDetails[] = new RefDetails(
                        $passengerReference->id,
                        $passengerReference->type
                    );
                }
            }

            $this->psaList[] = $tmp;
        }

        if (!is_null($params->informationalRecordLocator)) {
            $this->pnrLocatorData = new PnrLocatorData($params->informationalRecordLocator);
        }
    }
}
