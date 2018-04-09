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

use Amadeus\Client\RequestOptions\Queue\SearchCriteriaOpt;

/**
 * Queue_List Request Options
 *
 * Options available for the Queue_List message.
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class QueueListOptions extends Base
{
    const SORT_CREATION_DATE = 'CD';
    const SORT_DEPARTURE_DATE = 'DD';
    const SORT_TICKETING_DATE = 'TD';

    /**
     * How to sort the result
     *
     * self::SORT_*
     *
     * @var string
     */
    public $sortType = self::SORT_CREATION_DATE;

    /**
     * The queue, category and office to list.
     *
     * Category and Office are optional
     *
     * @var Queue
     */
    public $queue;

    /**
     * Provide extra Search Criteria (filters)
     *
     * @var SearchCriteriaOpt[]
     */
    public $searchCriteria = [];

    /**
     * To retrieve a limited amount of items. Provide first item number here (0-based)
     *
     * @var int
     */
    public $firstItemNr;

    /**
     * To retrieve a limited amount of items. Provide last item number here (0-based)
     *
     * @var int
     */
    public $lastItemNr;
}
