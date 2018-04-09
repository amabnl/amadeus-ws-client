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

/**
 * Pnr_Cancel Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PnrCancelOptions extends PnrBase
{
    /**
     * Only provide the Record Locator if the PNR is not yet in context!!
     *
     * @var string
     */
    public $recordLocator;

    /**
     * How to handle the PNR after doing the Cancel operation
     *
     * 0 No special processing
     * 10 End transact (ET)
     * 11 End transact with retrieve (ER)
     * 12 End transact and change advice codes (ETK)
     * 13 End transact with retrieve and change advice codes (ERK)
     * 14 End transact split PNR (EF)
     * 15 Cancel the itinerary for all PNRs connected by the AXR and end transact (ETX)
     * 16 Cancel the itinerary for all PNRs connected by the AXR and end transact with retrieve (ERX)
     * 20 Ignore (IG)
     * 21 Ignore and retrieve (IR)
     * 267 Stop EOT if segment sell error
     * 30 Show warnings at first EOT
     * 50 Reply with short message
     *
     * @var int|int[]
     */
    public $actionCode = 0;

    /**
     * All Passengers by name element number to be removed
     *
     * @var int[]
     */
    public $passengers = [];

    /**
     * All elements by Tattoo number to be removed
     *
     * @var int[]
     */
    public $elementsByTattoo = [];

    /**
     * Set to true if you want to cancel the entire itinerary of the PNR.
     *
     * This is the equivalent of the XI entry in SEL and will effectively cancel the PNR.
     *
     * @var bool
     */
    public $cancelItinerary = false;

    /**
     * Offers by Offer Reference to be removed
     *
     * @var int[]
     */
    public $offers = [];

    /**
     * All GROUP Passengers by name element number to be removed
     *
     * @var int[]
     */
    public $groupPassengers = [];

    /**
     * All tattoos of PNR Segments to be removed
     *
     * @var int[]
     */
    public $segments = [];
}
