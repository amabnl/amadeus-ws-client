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

use Amadeus\Client\RequestOptions\QueueListOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Structure class for representing the Queue_List request message
 *
 * @package Amadeus\Client\Struct\Queue
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class QueueList extends BaseWsMessage
{
    /**
     * @var Scroll
     */
    public $scroll;

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
     * @var QueueDate
     */
    public $date;

    /**
     * @var ScanRange
     */
    public $scanRange;

    /**
     * @var SearchCriteria[]
     */
    public $searchCriteria = [];

    /**
     * @var SortCriteria
     */
    public $sortCriteria;

    /**
     * @var AccountNumber
     */
    public $accountNumber;

    /**
     * @param QueueListOptions $options
     */
    public function __construct(QueueListOptions $options)
    {
        $this->queueNumber = new QueueNumber($options->queue->queue);

        $this->categoryDetails = new CategoryDetails($options->queue->category);

        if (!empty($options->queue->timeMode)) {
            $this->date = new QueueDate($options->queue->timeMode);
        }
        
        if (!empty($options->queue->officeId)) {
            $this->targetOffice = new TargetOffice(
                SourceType::SOURCETYPE_OFFICE_SPECIFIED,
                $options->queue->officeId
            );
        }

        $this->sortCriteria = new SortCriteria($options->sortType);

        foreach ($options->searchCriteria as $opt) {
            $this->searchCriteria[] = new SearchCriteria($opt);
        }

        if (is_int($options->firstItemNr) && is_int($options->lastItemNr)) {
            $this->scanRange = new ScanRange($options->firstItemNr, $options->lastItemNr);
        }

        if (!empty($options->queue->accountNumber)) {
            $this->accountNumber = new AccountNumber($options->queue->accountNumber);
        }
    }
}
