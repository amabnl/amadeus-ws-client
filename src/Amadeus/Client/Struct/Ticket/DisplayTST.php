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

use Amadeus\Client\RequestOptions\TicketDisplayTstOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Ticket\DisplayTST\DisplayMode;
use Amadeus\Client\Struct\Ticket\DisplayTST\PsaInformation;
use Amadeus\Client\Struct\Ticket\DisplayTST\ScrollingInformation;

/**
 * DisplayTST
 *
 * @package Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DisplayTST extends BaseWsMessage
{
    /**
     * @var DisplayMode
     */
    public $displayMode;

    /**
     * @var PnrLocatorData
     */
    public $pnrLocatorData;

    /**
     * @var ScrollingInformation
     */
    public $scrollingInformation;

    /**
     * @var TstReference[]
     */
    public $tstReference = [];

    /**
     * @var PsaInformation
     */
    public $psaInformation;

    /**
     * DisplayTST constructor.
     *
     * @param TicketDisplayTstOptions $params
     */
    public function __construct(TicketDisplayTstOptions $params)
    {
        $this->displayMode = new DisplayMode($params->displayMode);

        if ($params->displayMode === AttributeDetails::MODE_SELECTIVE) {
            $this->loadSelective($params);
        }
    }

    /**
     * @param TicketDisplayTstOptions $params
     */
    protected function loadSelective($params)
    {
        foreach ($params->tstNumbers as $tstNumber) {
            $this->tstReference[] = new TstReference($tstNumber);
        }

        $this->loadReferences($params);

        if ($this->checkAllNotEmpty($params->scrollingCount, $params->scrollingStart)) {
            $this->scrollingInformation = new ScrollingInformation(
                $params->scrollingCount,
                $params->scrollingStart
            );
        }
    }

    /**
     * Load passenger & segment references
     *
     * @param TicketDisplayTstOptions $params
     */
    protected function loadReferences($params)
    {
        if ($this->checkAnyNotEmpty($params->passengers, $params->segments)) {
            $this->psaInformation = new PsaInformation();

            foreach ($params->passengers as $passenger) {
                $this->psaInformation->refDetails[] = new RefDetails($passenger, RefDetails::QUAL_PASSENGER);
            }

            foreach ($params->segments as $segment) {
                $this->psaInformation->refDetails[] = new RefDetails($segment, RefDetails::QUAL_SEGMENT_REFERENCE);
            }
        }
    }
}
