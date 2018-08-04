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
use Amadeus\Client\RequestOptions\QueuePlacePnrOptions;
use Amadeus\Client\Struct\Queue\PlacePnr;
use Amadeus\Client\Struct\Queue\SelectionDetails;
use Amadeus\Client\Struct\Queue\SourceType;
use Amadeus\Client\Struct\Queue\SubQueueInfoDetails;
use Test\Amadeus\BaseTestCase;

/**
 * PlacePnrTest
 *
 * @package Test\Amadeus\Client\Struct\Queue
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PlacePnrTest extends BaseTestCase
{

    public function testCanPlacePnrOnQueueWithNoOffice()
    {
        $opt = new QueuePlacePnrOptions([
            'recordLocator' => 'ABC123',
            'targetQueue' => new Queue([
                'queue' => 30,
                'category' => 5
            ])
        ]);

        $msg = new PlacePnr(
            $opt->recordLocator,
            $opt->sourceOfficeId,
            $opt->targetQueue
        );


        $this->assertEquals('ABC123', $msg->recordLocator->reservation->controlNumber);
        $this->assertEquals(SelectionDetails::PLACEPNR_OPTION_QUEUE, $msg->placementOption->selectionDetails->option);
        $this->assertEquals(1, count($msg->targetDetails));
        $this->assertNull($msg->targetDetails[0]->targetOffice->originatorDetails->inHouseIdentification1);
        $this->assertEquals(SourceType::SOURCETYPE_SAME_AS_ORIGINATOR, $msg->targetDetails[0]->targetOffice->sourceType->sourceQualifier1);
        $this->assertEquals(30, $msg->targetDetails[0]->queueNumber->queueDetails->number);
        $this->assertNull($msg->targetDetails[0]->placementDate);
        $this->assertEquals(5, $msg->targetDetails[0]->categoryDetails->subQueueInfoDetails->itemNumber);
        $this->assertEquals(SubQueueInfoDetails::IDTYPE_CATEGORY, $msg->targetDetails[0]->categoryDetails->subQueueInfoDetails->identificationType);
    }

    public function testCanPlacePnrOnQueueWithOffice()
    {
        $opt = new QueuePlacePnrOptions([
            'recordLocator' => 'ABC123',
            'sourceOfficeId' => 'BRU1AXXXX',
            'targetQueue' => new Queue([
                'queue' => 30,
                'category' => 5
            ])
        ]);

        $msg = new PlacePnr(
            $opt->recordLocator,
            $opt->sourceOfficeId,
            $opt->targetQueue
        );

        $this->assertEquals('BRU1AXXXX', $msg->targetDetails[0]->targetOffice->originatorDetails->inHouseIdentification1);
        $this->assertEquals(SourceType::SOURCETYPE_SAME_AS_ORIGINATOR, $msg->targetDetails[0]->targetOffice->sourceType->sourceQualifier1);
    }
}
