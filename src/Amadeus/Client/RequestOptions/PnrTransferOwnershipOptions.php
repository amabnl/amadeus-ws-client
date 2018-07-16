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
 * Pnr_TransferOwnership Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PnrTransferOwnershipOptions extends Base
{
    /**
     * Record Locator of the PNR whose the Ownership is transferred.
     *
     * @var string
     */
    public $recordLocator;

    /**
     * Set to True to indicate that the OwnershipTransfer function only applies to the current PNR,
     * not to the PNRs linked with Associated Cross Reference Record (AXR) to the current PNR.
     *
     * @var bool
     */
    public $inhibitPropagation = false;

    /**
     * Amadeus Office ID or third party identification to transfer ownership to.
     *
     * @var string
     */
    public $newOffice;

    /**
     * Change the owner User Security Entity.
     *
     * @var string
     */
    public $newUserSecurityEntity;

    /**
     * Transfer ownership to a third party identification on a retrieved PNR.
     *
     * @var string
     */
    public $newThirdParty;

    /**
     * Change the Ticketing office.
     *
     * Only works in combination with $this->newOffice
     *
     * @var bool
     */
    public $changeTicketingOffice = false;

    /**
     * Change the Queueing office
     *
     * Only works in combination with $this->newOffice
     *
     * @var bool
     */
    public $changeQueueingOffice = false;

    /**
     * Change the office specified in the option queue element.
     *
     * Only works in combination with $this->newOffice
     *
     * @var bool
     */
    public $changeOptionQueueElement = false;
}
