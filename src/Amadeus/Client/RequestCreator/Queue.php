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

namespace Amadeus\Client\RequestCreator;

use Amadeus\Client\RequestOptions\QueueListOptions;
use Amadeus\Client\RequestOptions\QueueMoveItemOptions;
use Amadeus\Client\RequestOptions\QueuePlacePnrOptions;
use Amadeus\Client\RequestOptions\QueueRemoveItemOptions;
use Amadeus\Client\Struct;

/**
 * Queue Request Creator
 *
 * Responsible for creating all "Queue_" messages
 *
 * methods for creation must have the correct name
 * 'create'<message name without underscores>
 *
 * @package Amadeus\Client\RequestCreator
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Queue
{
    /**
     * Queue_List
     *
     * @param QueueListOptions $params
     * @return Struct\Queue\QueueList
     */
    public function createQueueList(QueueListOptions $params)
    {
        $queueListRequest = new Struct\Queue\QueueList(
            $params->queue->queue,
            $params->queue->category
        );

        return $queueListRequest;
    }

    /**
     * Queue_PlacePNR
     *
     * @param QueuePlacePnrOptions $params
     * @return Struct\Queue\PlacePnr
     */
    public function createQueuePlacePnr(QueuePlacePnrOptions $params)
    {
        $req = new Struct\Queue\PlacePnr(
            $params->recordLocator,
            $params->sourceOfficeId,
            $params->targetQueue
        );

        return $req;
    }

    /**
     * Queue_RemoveItem
     *
     * @param QueueRemoveItemOptions $params
     * @return Struct\Queue\RemoveItem
     */
    public function createQueueRemoveItem(QueueRemoveItemOptions $params)
    {
        $req = new Struct\Queue\RemoveItem(
            $params->queue,
            $params->recordLocator,
            $params->originatorOfficeId
        );

        return $req;
    }

    /**
     * Queue_MoveItem
     *
     * @param QueueMoveItemOptions $params
     * @return Struct\Queue\MoveItem
     */
    public function createQueueMoveItem(QueueMoveItemOptions $params)
    {
        $req = new Struct\Queue\MoveItem(
            $params->recordLocator,
            $params->officeId,
            $params->sourceQueue,
            $params->destinationQueue
        );

        return $req;
    }
}
