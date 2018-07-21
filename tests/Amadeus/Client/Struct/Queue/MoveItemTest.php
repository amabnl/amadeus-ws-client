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
use Amadeus\Client\Struct\Queue\MoveItem;
use Amadeus\Client\Struct\Queue\SelectionDetails;
use Amadeus\Client\Struct\Queue\SourceType;
use Amadeus\Client\Struct\Queue\SubQueueInfoDetails;
use Test\Amadeus\BaseTestCase;

/**
 * MoveItemTest
 *
 * @package Amadeus\Client\Struct\Queue
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MoveItemTest extends BaseTestCase
{

    public function testCanConstructQueueMoveItemMessage()
    {
        $struct = new MoveItem(
            'ABC123',
            'BRUXX000',
            new Queue(['queue' => 30, 'category' => 0]),
            new Queue(['queue' => 65, 'category' => 5, 'officeId' => 'AMSXX001'])
        );

        $this->assertEquals(SelectionDetails::MOVE_OPTION_COPY_QUEUE_REMOVE, $struct->placementOption->selectionDetails->option);
        $this->assertEquals('ABC123', $struct->recordLocator->reservation->controlNumber);
        $this->assertInternalType('array', $struct->targetDetails);
        $this->assertEquals(2, count($struct->targetDetails));
        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\TargetDetails', $struct->targetDetails[0]);
        $this->assertEquals('BRUXX000', $struct->targetDetails[0]->targetOffice->originatorDetails->inHouseIdentification1);
        $this->assertEquals(SourceType::SOURCETYPE_SAME_AS_ORIGINATOR, $struct->targetDetails[0]->targetOffice->sourceType->sourceQualifier1);
        $this->assertEquals(30, $struct->targetDetails[0]->queueNumber->queueDetails->number);
        $this->assertEquals(SubQueueInfoDetails::IDTYPE_CATEGORY, $struct->targetDetails[0]->categoryDetails->subQueueInfoDetails->identificationType);
        $this->assertEquals(0, $struct->targetDetails[0]->categoryDetails->subQueueInfoDetails->itemNumber);
        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\TargetDetails', $struct->targetDetails[1]);
        $this->assertEquals('AMSXX001', $struct->targetDetails[1]->targetOffice->originatorDetails->inHouseIdentification1);
        $this->assertEquals(SourceType::SOURCETYPE_OFFICE_SPECIFIED, $struct->targetDetails[1]->targetOffice->sourceType->sourceQualifier1);
        $this->assertEquals(65, $struct->targetDetails[1]->queueNumber->queueDetails->number);
        $this->assertEquals(SubQueueInfoDetails::IDTYPE_CATEGORY, $struct->targetDetails[1]->categoryDetails->subQueueInfoDetails->identificationType);
        $this->assertEquals(5, $struct->targetDetails[1]->categoryDetails->subQueueInfoDetails->itemNumber);
    }

}
