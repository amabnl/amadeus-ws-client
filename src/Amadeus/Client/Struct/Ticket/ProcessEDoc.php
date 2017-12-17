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

use Amadeus\Client\RequestOptions\TicketProcessEDocOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Ticket\ProcessEDoc\CustomerReference;
use Amadeus\Client\Struct\Ticket\ProcessEDoc\DocGroup;
use Amadeus\Client\Struct\Ticket\ProcessEDoc\FrequentTravellerInfo;
use Amadeus\Client\Struct\Ticket\ProcessEDoc\InfoGroup;
use Amadeus\Client\Struct\Ticket\ProcessEDoc\MsgActionDetails;
use Amadeus\Client\Struct\Ticket\ProcessEDoc\PricingInfo;
use Amadeus\Client\Struct\Ticket\ProcessEDoc\TextInfo;

/**
 * TicketProcessEDoc
 *
 * Basic Request structure for the TicketProcessEDoc messages
 *
 * @package Amadeus\Client\Struct\Ticket\EDoc
 * @author Farah Hourani <farahhourani94@gmail.com>
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ProcessEDoc extends BaseWsMessage
{
    /**
     * @var MsgActionDetails
     */
    public $msgActionDetails;

    /**
     * @var FrequentTravellerInfo
     */
    public $frequentTravellerInfo;

    /**
     * @var TextInfo[]
     */
    public $textInfo = [];

    /**
     * @var CustomerReference
     */
    public $customerReference;

    /**
     * @var PricingInfo
     */
    public $pricingInfo;

    /**
     * @var InfoGroup[]
     */
    public $infoGroup = [];

    /**
     * @var DocGroup[]
     */
    public $docGroup = [];


    /**
     * ProcessEDoc constructor.
     *
     * @param TicketProcessEDocOptions $options
     */
    public function __construct(TicketProcessEDocOptions $options)
    {
        $this->loadOptions($options->ticketNumber);
        $this->loadMsgAction($options->action, $options->additionalActions);
        $this->loadFrequentTravellers($options->frequentTravellers);
    }

    /**
     * @param string $ticketNumber
     */
    protected function loadOptions($ticketNumber)
    {
        if (!empty($ticketNumber)) {
            $this->infoGroup[] = new InfoGroup($ticketNumber);
        }
    }

    /**
     * @param string $action
     * @param string[] $additionalActions
     */
    protected function loadMsgAction($action, $additionalActions)
    {
        if ($this->checkAnyNotEmpty($action, $additionalActions)) {
            $this->msgActionDetails = new MsgActionDetails($action, $additionalActions);
        }
    }

    /**
     * @param $frequentTravellers
     */
    protected function loadFrequentTravellers($frequentTravellers)
    {
        if (!empty($frequentTravellers)) {
            $this->frequentTravellerInfo = new FrequentTravellerInfo($frequentTravellers);
        }
    }
}
