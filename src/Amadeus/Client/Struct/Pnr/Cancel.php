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

namespace Amadeus\Client\Struct\Pnr;

use Amadeus\Client\RequestOptions\PnrCancelOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Pnr\AddMultiElements\PnrActions;
use Amadeus\Client\Struct\Pnr\Cancel\Element;
use Amadeus\Client\Struct\Pnr\Cancel\Elements;

/**
 * PNR_Cancel message structure
 *
 * @package Amadeus\Client\Struct\Pnr
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Cancel extends BaseWsMessage
{
    /**
     * @var ReservationInfo
     */
    public $reservationInfo;

    /**
     * @var PnrActions
     */
    public $pnrActions;

    /**
     * @var Cancel\Elements[]
     */
    public $cancelElements = [];

    /**
     * Cancel constructor.
     *
     * @param PnrCancelOptions $params
     */
    public function __construct(PnrCancelOptions $params)
    {
        if (is_string($params->recordLocator) && strlen($params->recordLocator) >= 6) {
            $this->reservationInfo = new ReservationInfo($params->recordLocator);
        }

        $this->pnrActions = new PnrActions($params->actionCode);

        if ($params->cancelItinerary === true) {
            $this->cancelElements[] = new Cancel\Elements(Elements::ENTRY_ITINERARY);
        }

        $this->loadElements($params);

        $this->loadSegments($params);

        $this->loadGroupPassengers($params);

        $this->loadPassengers($params);

        $this->loadOffers($params);
    }

    /**
     * @param PnrCancelOptions $params
     * @return void
     */
    protected function loadElements(PnrCancelOptions $params)
    {
        if (!empty($params->elementsByTattoo)) {
            $tmp = new Cancel\Elements(Elements::ENTRY_ELEMENT);

            foreach ($params->elementsByTattoo as $tattoo) {
                $tmp->element[] = new Element($tattoo, Element::IDENT_OTHER_TATTOO);
            }

            $this->cancelElements[] = $tmp;
        }
    }

    /**
     * @param PnrCancelOptions $params
     * @return void
     */
    protected function loadSegments(PnrCancelOptions $params)
    {
        if (!empty($params->segments)) {
            $tmp = new Cancel\Elements(Elements::ENTRY_ELEMENT);

            foreach ($params->segments as $tatoo) {
                $tmp->element[] = new Element($tatoo, Element::IDENT_SEGMENT_TATTOO);
            }

            $this->cancelElements[] = $tmp;
        }
    }

    /**
     * @param PnrCancelOptions $params
     * @return void
     */
    protected function loadGroupPassengers(PnrCancelOptions $params)
    {
        if (!empty($params->groupPassengers)) {
            $tmp = new Cancel\Elements(Elements::ENTRY_NAME_INTEGRATION);

            foreach ($params->groupPassengers as $offerRef) {
                $tmp->element[] = new Element($offerRef, Element::IDENT_PASSENGER_TATTOO);
            }

            $this->cancelElements[] = $tmp;
        }
    }

    /**
     * @param PnrCancelOptions $params
     * @return void
     */
    protected function loadPassengers(PnrCancelOptions $params)
    {
        if (!empty($params->passengers)) {
            $tmp = new Cancel\Elements(Elements::ENTRY_ELEMENT);

            foreach ($params->passengers as $offerRef) {
                $tmp->element[] = new Element($offerRef, Element::IDENT_PASSENGER_TATTOO);
            }

            $this->cancelElements[] = $tmp;
        }
    }

    /**
     * @param PnrCancelOptions $params
     * @return void
     *
     */
    protected function loadOffers(PnrCancelOptions $params)
    {
        if (!empty($params->offers)) {
            $tmp = new Cancel\Elements(Elements::ENTRY_ELEMENT);

            foreach ($params->offers as $offerRef) {
                $tmp->element[] = new Element($offerRef, Element::IDENT_OFFER_TATTOO);
            }

            $this->cancelElements[] = $tmp;
        }
    }
}
