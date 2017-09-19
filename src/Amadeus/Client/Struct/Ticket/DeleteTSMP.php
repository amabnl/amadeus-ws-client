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

use Amadeus\Client\RequestOptions\TicketDeleteTsmpOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Ticket\DeleteTSMP\CriteriaTattoo;
use Amadeus\Client\Struct\Ticket\DeleteTSMP\ReferenceDetails;

/**
 * DeleteTSMP
 *
 * @package Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DeleteTSMP extends BaseWsMessage
{
    /**
     * @var CriteriaTattoo[]
     */
    public $criteriaTattoo = [];

    /**
     * DeleteTSMP constructor.
     *
     * @param TicketDeleteTsmpOptions $params
     */
    public function __construct(TicketDeleteTsmpOptions $params)
    {
        foreach ($params->infantTattoos as $infantTattoo) {
            $this->addCriteriaTattoo($infantTattoo, ReferenceDetails::TYPE_INFANT_PARENT_TATTOO);
        }

        foreach ($params->paxTattoos as $paxTattoo) {
            $this->addCriteriaTattoo($paxTattoo, ReferenceDetails::TYPE_PASSENGER_TATTOO);
        }

        foreach ($params->tsmTattoos as $tsmTattoo) {
            $this->addCriteriaTattoo($tsmTattoo, ReferenceDetails::TYPE_TSM_TATTOO);
        }
    }

    /**
     * Add a tattoo ref to the message
     *
     * @param string|int $tattoo
     * @param string $type
     */
    protected function addCriteriaTattoo($tattoo, $type)
    {
        $this->criteriaTattoo[] = new CriteriaTattoo($type, $tattoo);
    }
}
