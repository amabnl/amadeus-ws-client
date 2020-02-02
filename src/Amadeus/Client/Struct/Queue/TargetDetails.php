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

/**
 * TargetDetails
 *
 * @package Amadeus\Client\Struct\Queue
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TargetDetails
{
    /**
     * @var TargetOffice
     */
    public $targetOffice;
    /**
     * @var QueueNumber
     */
    public $queueNumber;
    /**
     * @var CategoryDetails
     */
    public $categoryDetails;
    /**
     * @var mixed
     * @todo expand this structure when we need it (this is only used for future placement)
     */
    public $placementDate;

    /**
     * @var RecordLocator[]
     */
    public $recordLocator = [];

    /**
     * @param Queue $targetQueue
     * @param string[] $recordLocators
     * @param string $originatorOffice
     */
    public function __construct($targetQueue, $recordLocators, $originatorOffice)
    {
        $theRealOffice = $originatorOffice;
        $sourceType = SourceType::SOURCETYPE_SAME_AS_ORIGINATOR;

        if (!is_null($targetQueue->officeId) && $targetQueue->officeId != $originatorOffice) {
            $sourceType = SourceType::SOURCETYPE_OFFICE_SPECIFIED;
            $theRealOffice = $targetQueue->officeId;
        }

        $this->targetOffice = new TargetOffice($sourceType, $theRealOffice);

        $this->queueNumber = new QueueNumber($targetQueue->queue);
        $this->categoryDetails = new CategoryDetails($targetQueue->category);
        
        if (!empty($targetQueue->timeMode)) {
            $this->placementDate = new QueueDate($targetQueue->timeMode);
        }

        foreach ($recordLocators as $recLoc) {
            $this->recordLocator[] = new RecordLocator($recLoc);
        }
    }
}
