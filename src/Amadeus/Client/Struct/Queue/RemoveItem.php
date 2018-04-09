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

namespace Amadeus\Client\Struct\Queue;

use Amadeus\Client\RequestOptions\Queue;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Structure class for representing the Queue_RemoveItem request message
 *
 * @package Amadeus\Client\Struct\Queue
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class RemoveItem extends BaseWsMessage
{
    /**
     * @var RemovalOption
     */
    public $removalOption;
    /**
     * @var TargetDetails[]
     */
    public $targetDetails = [];

    /**
     * @param Queue $targetQueue
     * @param string|string[] $recordLocator
     * @param string $originatorOffice
     */
    public function __construct($targetQueue, $recordLocator, $originatorOffice)
    {
        $this->removalOption = new RemovalOption(SelectionDetails::REMOVE_OPTION_SPECIFIC_PNR);

        if (is_string($recordLocator)) {
            $recordLocator = [$recordLocator];
        }

        $this->targetDetails[] = new TargetDetails($targetQueue, $recordLocator, $originatorOffice);
    }
}
