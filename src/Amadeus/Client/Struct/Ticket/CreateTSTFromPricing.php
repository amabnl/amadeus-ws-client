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

use Amadeus\Client\RequestOptions\Ticket\PassengerReference;
use Amadeus\Client\RequestOptions\Ticket\Pricing;
use Amadeus\Client\RequestOptions\TicketCreateTsmFromPricingOptions;
use Amadeus\Client\RequestOptions\TicketCreateTstFromPricingOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Ticket_CreateTSTFromPricing
 *
 * @package Amadeus\Client\Struct\Ticket
 * @author dieter <dermikagh@gmail.com>
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
     * @param TicketCreateTstFromPricingOptions|TicketCreateTsmFromPricingOptions $params
     */
    public function __construct($params)
    {
        foreach ($params->pricings as $pricing) {
            $this->psaList[] = $this->makePsaList($pricing);
        }

        if (!is_null($params->informationalRecordLocator)) {
            $this->pnrLocatorData = new PnrLocatorData($params->informationalRecordLocator);
        }
    }

    /**
     * @param Pricing $pricing
     * @return PsaList
     */
    protected function makePsaList($pricing)
    {
        $refType = (!empty($pricing->tstNumber)) ? ItemReference::REFTYPE_TST : ItemReference::REFTYPE_TSM;
        $ref = (!empty($pricing->tstNumber)) ? $pricing->tstNumber : $pricing->tsmNumber;

        $list = new PsaList(
            $ref,
            $refType
        );

        if (!empty($pricing->passengerReferences)) {
            $list->paxReference = $this->makePaxRef($pricing->passengerReferences);
        }

        return $list;
    }

    /**
     * @param PassengerReference[] $passengerReferences
     * @return PaxReference
     */
    protected function makePaxRef($passengerReferences)
    {
        $paxRef = new PaxReference();

        foreach ($passengerReferences as $passengerReference) {
            $paxRef->refDetails[] = new RefDetails(
                $passengerReference->id,
                $passengerReference->type
            );
        }

        return $paxRef;
    }
}
