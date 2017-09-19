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
 * Class MoveItem
 *
 * @package Amadeus\Client\Struct\Queue
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MoveItem extends BaseWsMessage
{
    /**
     * @var PlacementOption
     */
    public $placementOption;
    /**
     * @var TargetDetails[]
     */
    public $targetDetails = [];
    /**
     * @var MessageText
     */
    public $messageText;
    /**
     * @var RecordLocator
     */
    public $recordLocator;
    /**
     * @var NumberOfPnrs
     */
    public $numberOfPNRs;

    /**
     * @param string $recordLocator
     * @param string $sourceOffice
     * @param Queue $sourceQueue
     * @param Queue $destinationQueue
     */
    public function __construct($recordLocator, $sourceOffice, $sourceQueue, $destinationQueue)
    {
        $this->placementOption = new PlacementOption(SelectionDetails::MOVE_OPTION_COPY_QUEUE_REMOVE);

        $this->targetDetails[] = new TargetDetails($sourceQueue, [], $sourceOffice);
        $this->targetDetails[] = new TargetDetails($destinationQueue, [], $sourceOffice);

        $this->recordLocator = new RecordLocator($recordLocator);
    }
}
