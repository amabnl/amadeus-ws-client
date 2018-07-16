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
 * Pnr_AddMultiElements Base options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PnrAddMultiElementsBase extends PnrBase
{
    /**
     * One or more action codes to be performed on the PNR
     *
     * self::ACTION_* How to handle the PNR after creating
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
     * Received From (RF) string to be added to the transaction.
     *
     * @var string
     */
    public $receivedFrom;

    /**
     * Whether to automatically add the default Received From string if none is provided.
     *
     * Defaults to true for backwards compatibility.
     *
     * See https://github.com/amabnl/amadeus-ws-client/issues/68
     *
     * @var bool
     */
    public $autoAddReceivedFrom = true;

    /**
     * Holds the default Received From string if no explicit RF was provided.
     *
     * ** !! Do not use !! **
     *
     * @deprecated This is a workaround until we can decide on what happens with RF elements for the next major version.
     * @var string
     */
    public $defaultReceivedFrom;
}
