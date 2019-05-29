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
use Amadeus\Client\RequestOptions\QueueRemoveItemOptions;
use Amadeus\Client\Struct\Queue\RemoveItem;
use Amadeus\Client\Struct\Queue\SourceType;
use Amadeus\Client\Struct\Queue\SubQueueInfoDetails;
use Test\Amadeus\BaseTestCase;

/**
 * RemoveItemTest
 *
 * @package Test\Amadeus\Client\Struct\Queue
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class RemoveItemTest extends BaseTestCase
{
    public function testCanMakeRemoveItemMessage()
    {
        $opt = new QueueRemoveItemOptions([
            'recordLocator' => 'ABC123',
            'originatorOfficeId' => 'BRUXX0000',
            'queue' => new Queue(['queue' => 50, 'category' => 0])
        ]);

        $msg = new RemoveItem(
            $opt->queue,
            $opt->recordLocator,
            $opt->originatorOfficeId
        );

        $this->assertEquals(SourceType::SOURCETYPE_SAME_AS_ORIGINATOR, $msg->targetDetails[0]->targetOffice->sourceType->sourceQualifier1);
        $this->assertEquals('ABC123', $msg->targetDetails[0]->recordLocator[0]->reservation->controlNumber);
        $this->assertEquals(1, count($msg->targetDetails));
        $this->assertEquals('BRUXX0000', $msg->targetDetails[0]->targetOffice->originatorDetails->inHouseIdentification1);
        $this->assertNull($msg->targetDetails[0]->placementDate);
        $this->assertEquals(50, $msg->targetDetails[0]->queueNumber->queueDetails->number);
        $this->assertEquals(SubQueueInfoDetails::IDTYPE_CATEGORY, $msg->targetDetails[0]->categoryDetails->subQueueInfoDetails->identificationType);
        $this->assertEquals(0, $msg->targetDetails[0]->categoryDetails->subQueueInfoDetails->itemNumber);
    }
    
    
    public function testCanMakeRemoveItemMessageWithTimeMode()
    {
        $opt = new QueueRemoveItemOptions([
            'recordLocator' => 'ABC123',
            'originatorOfficeId' => 'BRUXX0000',
            'queue' => new Queue(['queue' => 50, 'category' => 0, 'timeMode' => 1])
        ]);
        
        $msg = new RemoveItem(
            $opt->queue,
            $opt->recordLocator,
            $opt->originatorOfficeId
        );
        
        $this->assertEquals(SourceType::SOURCETYPE_SAME_AS_ORIGINATOR, $msg->targetDetails[0]->targetOffice->sourceType->sourceQualifier1);
        $this->assertEquals('ABC123', $msg->targetDetails[0]->recordLocator[0]->reservation->controlNumber);
        $this->assertEquals(1, count($msg->targetDetails));
        $this->assertEquals('BRUXX0000', $msg->targetDetails[0]->targetOffice->originatorDetails->inHouseIdentification1);
        $this->assertNotNull($msg->targetDetails[0]->placementDate);
        $this->assertEquals(1, $msg->targetDetails[0]->placementDate->timeMode);
        $this->assertEquals(50, $msg->targetDetails[0]->queueNumber->queueDetails->number);
        $this->assertEquals(SubQueueInfoDetails::IDTYPE_CATEGORY, $msg->targetDetails[0]->categoryDetails->subQueueInfoDetails->identificationType);
        $this->assertEquals(0, $msg->targetDetails[0]->categoryDetails->subQueueInfoDetails->itemNumber);
    }

}
