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

use Amadeus\Client\RequestOptions\PnrTransferOwnershipOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Pnr\TransferOwnership\OaIdentificator;
use Amadeus\Client\Struct\Pnr\TransferOwnership\OfficeIdentification;
use Amadeus\Client\Struct\Pnr\TransferOwnership\PropagationAction;
use Amadeus\Client\Struct\Queue\RecordLocator;

/**
 * Structure class for representing the PNR_TransferOwnership request message
 *
 * @package Amadeus\Client\Struct\Pnr
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TransferOwnership extends BaseWsMessage
{
    /**
     * @var RecordLocator
     */
    public $recordLocator;

    /**
     * @var TransferOwnership\PropagationAction
     */
    public $propagatioAction;

    /**
     * @var TransferOwnership\OfficeIdentification
     */
    public $officeIdentification;

    /**
     * @var TransferOwnership\OaIdentificator
     */
    public $oaIdentificator;

    /**
     * TransferOwnership constructor.
     *
     * @param PnrTransferOwnershipOptions $options
     */
    public function __construct($options)
    {
        if (!empty($options->recordLocator)) {
            $this->recordLocator = new RecordLocator($options->recordLocator);
        }

        if ($options->inhibitPropagation === true) {
            $this->propagatioAction = new PropagationAction(
                PropagationAction::PROPAGATION_INHIBIT
            );
        }

        $this->loadNewOffice($options);

        if (!empty($options->newThirdParty)) {
            $this->oaIdentificator = new OaIdentificator($options->newThirdParty);
        }
    }

    /**
     * @param PnrTransferOwnershipOptions $options
     */
    protected function loadNewOffice($options)
    {
        if (!empty($options->newOffice) || !empty($options->newUserSecurityEntity)) {
            $this->officeIdentification = new OfficeIdentification(
                $options->newOffice,
                $options->newUserSecurityEntity
            );

            if ($this->checkAnyTrue(
                $options->changeQueueingOffice,
                $options->changeTicketingOffice,
                $options->changeOptionQueueElement
            )) {
                $this->officeIdentification->loadSpecificChanges(
                    $options->changeTicketingOffice,
                    $options->changeQueueingOffice,
                    $options->changeOptionQueueElement
                );
            }
        }
    }
}
