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

namespace Test\Amadeus\Client\Struct\Queue;

use Amadeus\Client\RequestOptions\Queue;
use \Amadeus\Client\Struct\Queue\QueueList;
use \Amadeus\Client\Struct\Queue\SelectionDetails;
use \Amadeus\Client\Struct\Queue\SubQueueInfoDetails;
use \Amadeus\Client\Struct\Queue\MoveItem;
use Test\Amadeus\BaseTestCase;

/**
 * ListTest
 *
 * @package Amadeus\Client\Struct\Queue
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class ListTest extends BaseTestCase
{
    public function testCanConstructQueueListMessage()
    {
        $struct = new QueueList(50, 3);

        $this->assertEquals(SelectionDetails::LIST_OPTION_SORT_CREATION, $struct->sortCriteria->sortOption[0]->selectionDetails->option);
        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\Dumbo', $struct->sortCriteria->dumbo);
        $this->assertEquals(50, $struct->queueNumber->queueDetails->number);
        $this->assertEquals(SubQueueInfoDetails::IDTYPE_CATEGORY, $struct->categoryDetails->subQueueInfoDetails->identificationType);
        $this->assertEquals(3, $struct->categoryDetails->subQueueInfoDetails->itemNumber);
    }

}
