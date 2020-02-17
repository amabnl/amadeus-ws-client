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
use Amadeus\Client\RequestOptions\QueueListOptions;
use \Amadeus\Client\Struct\Queue\QueueList;
use Amadeus\Client\Struct\Queue\ScanRange;
use \Amadeus\Client\Struct\Queue\SelectionDetails;
use Amadeus\Client\Struct\Queue\SourceType;
use \Amadeus\Client\Struct\Queue\SubQueueInfoDetails;
use Test\Amadeus\BaseTestCase;

/**
 * ListTest
 *
 * @package Amadeus\Client\Struct\Queue
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ListTest extends BaseTestCase
{
    public function testCanConstructQueueListMessage()
    {

        $struct = new QueueList(new QueueListOptions([
            'queue' => new Queue([
                'queue' => 50,
                'category' => 3
            ])
        ]));

        $this->assertCount(1, $struct->sortCriteria->sortOption);
        $this->assertEquals(SelectionDetails::LIST_OPTION_SORT_CREATION, $struct->sortCriteria->sortOption[0]->selectionDetails->option);
        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\Dumbo', $struct->sortCriteria->dumbo);
        $this->assertEquals(50, $struct->queueNumber->queueDetails->number);
        $this->assertEquals(SubQueueInfoDetails::IDTYPE_CATEGORY, $struct->categoryDetails->subQueueInfoDetails->identificationType);
        $this->assertEquals(3, $struct->categoryDetails->subQueueInfoDetails->itemNumber);
        $this->assertNull($struct->categoryDetails->subQueueInfoDetails->itemDescription);

        $this->assertNull($struct->targetOffice);
        $this->assertNull($struct->date);
        $this->assertNull($struct->scanRange);
        $this->assertNull($struct->scroll);
        $this->assertEmpty($struct->searchCriteria);
    }
    
    public function testCanConstructQueueListMessageWithQueueDate()
    {
        
        $struct = new QueueList(new QueueListOptions([
            'queue' => new Queue([
                'queue' => 50,
                'category' => 3,
                'timeMode' => 2
            ])
        ]));
        
        $this->assertCount(1, $struct->sortCriteria->sortOption);
        $this->assertEquals(SelectionDetails::LIST_OPTION_SORT_CREATION, $struct->sortCriteria->sortOption[0]->selectionDetails->option);
        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\Dumbo', $struct->sortCriteria->dumbo);
        $this->assertEquals(50, $struct->queueNumber->queueDetails->number);
        $this->assertEquals(SubQueueInfoDetails::IDTYPE_CATEGORY, $struct->categoryDetails->subQueueInfoDetails->identificationType);
        $this->assertEquals(3, $struct->categoryDetails->subQueueInfoDetails->itemNumber);
        $this->assertNull($struct->categoryDetails->subQueueInfoDetails->itemDescription);
        
        $this->assertEquals(2, $struct->date->timeMode);
        $this->assertNotNull($struct->date);
        
        $this->assertNull($struct->targetOffice);
        $this->assertNull($struct->scanRange);
        $this->assertNull($struct->scroll);
        $this->assertEmpty($struct->searchCriteria);
    }

    public function testCanConstructQueueListMessageWithOffice()
    {

        $struct = new QueueList(new QueueListOptions([
            'queue' => new Queue([
                'queue' => 50,
                'category' => 3,
                'officeId' => 'NCE1A0950'
            ])
        ]));

        $this->assertCount(1, $struct->sortCriteria->sortOption);
        $this->assertEquals(SelectionDetails::LIST_OPTION_SORT_CREATION, $struct->sortCriteria->sortOption[0]->selectionDetails->option);
        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\Dumbo', $struct->sortCriteria->dumbo);
        $this->assertEquals(50, $struct->queueNumber->queueDetails->number);
        $this->assertEquals(SubQueueInfoDetails::IDTYPE_CATEGORY, $struct->categoryDetails->subQueueInfoDetails->identificationType);
        $this->assertEquals(3, $struct->categoryDetails->subQueueInfoDetails->itemNumber);
        $this->assertNull($struct->categoryDetails->subQueueInfoDetails->itemDescription);

        $this->assertEquals('NCE1A0950', $struct->targetOffice->originatorDetails->inHouseIdentification1);
        $this->assertEquals(SourceType::SOURCETYPE_OFFICE_SPECIFIED, $struct->targetOffice->sourceType->sourceQualifier1);

        $this->assertNull($struct->date);
        $this->assertNull($struct->scanRange);
        $this->assertNull($struct->scroll);
        $this->assertEmpty($struct->searchCriteria);
    }

    /**
     * 5.1 Operation: Display Queue By Office And Date
     *
     */
    public function testCanMakeMessageDisplayQueueByOfficeAndDate()
    {
        $struct = new QueueList(new QueueListOptions([
            'queue' => new Queue([
                'queue' => 75,
                'category' => 0,
                'officeId' => 'NCE1A0950'
            ]),
            'searchCriteria' => [
                new Queue\SearchCriteriaOpt([
                    'type' => Queue\SearchCriteriaOpt::TYPE_TICKETING_DATE,
                    'start' => \DateTime::createFromFormat('Ymd', '20090420', new \DateTimeZone('UTC')),
                    'end' => \DateTime::createFromFormat('Ymd', '20090421', new \DateTimeZone('UTC'))
                ]),
                new Queue\SearchCriteriaOpt([
                    'type' => Queue\SearchCriteriaOpt::TYPE_DEPARTURE_DATE,
                    'start' => \DateTime::createFromFormat('Ymd', '20090503', new \DateTimeZone('UTC')),
                    'end' => \DateTime::createFromFormat('Ymd', '20090504', new \DateTimeZone('UTC'))
                ]),
            ]
        ]));

        $this->assertCount(1, $struct->sortCriteria->sortOption);
        $this->assertEquals(SelectionDetails::LIST_OPTION_SORT_CREATION, $struct->sortCriteria->sortOption[0]->selectionDetails->option);
        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\Dumbo', $struct->sortCriteria->dumbo);
        $this->assertEquals(75, $struct->queueNumber->queueDetails->number);
        $this->assertEquals(SubQueueInfoDetails::IDTYPE_CATEGORY, $struct->categoryDetails->subQueueInfoDetails->identificationType);
        $this->assertEquals(0, $struct->categoryDetails->subQueueInfoDetails->itemNumber);
        $this->assertNull($struct->categoryDetails->subQueueInfoDetails->itemDescription);

        $this->assertEquals('NCE1A0950', $struct->targetOffice->originatorDetails->inHouseIdentification1);
        $this->assertEquals(SourceType::SOURCETYPE_OFFICE_SPECIFIED, $struct->targetOffice->sourceType->sourceQualifier1);

        $this->assertCount(2, $struct->searchCriteria);
        $this->assertEquals(SelectionDetails::LIST_OPTION_SORT_TICKETING, $struct->searchCriteria[0]->searchOption->selectionDetails->option);
        $this->assertCount(1, $struct->searchCriteria[0]->dates);
        $this->assertEquals(20, $struct->searchCriteria[0]->dates[0]->beginDateTime->day);
        $this->assertEquals(4, $struct->searchCriteria[0]->dates[0]->beginDateTime->month);
        $this->assertEquals(2009, $struct->searchCriteria[0]->dates[0]->beginDateTime->year);
        $this->assertEquals(21, $struct->searchCriteria[0]->dates[0]->endDateTime->day);
        $this->assertEquals(4, $struct->searchCriteria[0]->dates[0]->endDateTime->month);
        $this->assertEquals(2009, $struct->searchCriteria[0]->dates[0]->endDateTime->year);

        $this->assertEquals(SelectionDetails::LIST_OPTION_SORT_DEPARTURE, $struct->searchCriteria[1]->searchOption->selectionDetails->option);
        $this->assertCount(1, $struct->searchCriteria[1]->dates);
        $this->assertEquals(3, $struct->searchCriteria[1]->dates[0]->beginDateTime->day);
        $this->assertEquals(5, $struct->searchCriteria[1]->dates[0]->beginDateTime->month);
        $this->assertEquals(2009, $struct->searchCriteria[1]->dates[0]->beginDateTime->year);
        $this->assertEquals(4, $struct->searchCriteria[1]->dates[0]->endDateTime->day);
        $this->assertEquals(5, $struct->searchCriteria[1]->dates[0]->endDateTime->month);
        $this->assertEquals(2009, $struct->searchCriteria[1]->dates[0]->endDateTime->year);

        $this->assertNull($struct->date);
        $this->assertNull($struct->scanRange);
        $this->assertNull($struct->scroll);
    }

    public function testCanMakeMessageByTicketingDate()
    {
        $struct = new QueueList(new QueueListOptions([
            'sortType' => QueueListOptions::SORT_TICKETING_DATE,
            'queue' => new Queue([
                'queue' => 50,
                'category' => 3
            ])
        ]));

        $this->assertCount(1, $struct->sortCriteria->sortOption);
        $this->assertEquals(SelectionDetails::LIST_OPTION_SORT_TICKETING, $struct->sortCriteria->sortOption[0]->selectionDetails->option);
        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\Dumbo', $struct->sortCriteria->dumbo);
        $this->assertEquals(50, $struct->queueNumber->queueDetails->number);
        $this->assertEquals(SubQueueInfoDetails::IDTYPE_CATEGORY, $struct->categoryDetails->subQueueInfoDetails->identificationType);
        $this->assertEquals(3, $struct->categoryDetails->subQueueInfoDetails->itemNumber);
        $this->assertNull($struct->categoryDetails->subQueueInfoDetails->itemDescription);

        $this->assertNull($struct->targetOffice);
        $this->assertNull($struct->date);
        $this->assertNull($struct->scanRange);
        $this->assertNull($struct->scroll);
        $this->assertEmpty($struct->searchCriteria);
    }

    public function testCanMakeMessageFirstTenPnrs()
    {
        $struct = new QueueList(new QueueListOptions([
            'queue' => new Queue([
                'queue' => 50,
                'category' => 3
            ]),
            'firstItemNr' => 0,
            'lastItemNr' => 10
        ]));

        $this->assertCount(1, $struct->sortCriteria->sortOption);
        $this->assertEquals(SelectionDetails::LIST_OPTION_SORT_CREATION, $struct->sortCriteria->sortOption[0]->selectionDetails->option);
        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\Dumbo', $struct->sortCriteria->dumbo);
        $this->assertEquals(50, $struct->queueNumber->queueDetails->number);
        $this->assertEquals(SubQueueInfoDetails::IDTYPE_CATEGORY, $struct->categoryDetails->subQueueInfoDetails->identificationType);
        $this->assertEquals(3, $struct->categoryDetails->subQueueInfoDetails->itemNumber);
        $this->assertNull($struct->categoryDetails->subQueueInfoDetails->itemDescription);

        $this->assertEquals(ScanRange::RANGE_OF_NUMBERS, $struct->scanRange->rangeQualifier);
        $this->assertEquals(0, $struct->scanRange->rangeDetails[0]->min);
        $this->assertEquals(10, $struct->scanRange->rangeDetails[0]->max);

        $this->assertNull($struct->targetOffice);
        $this->assertNull($struct->date);
        $this->assertNull($struct->scroll);
        $this->assertEmpty($struct->searchCriteria);
    }

    /**
     * Display Queue Using AccountNumber
     *
     */
    public function testCanConstructQueueListMessageWithAccountNumber()
    {

        $struct = new QueueList(new QueueListOptions([
            'queue' => new Queue([
                'queue' => 50,
                'category' => 3,
                'accountNumber' => 'USAEUR'
            ])
        ]));

        $this->assertCount(1, $struct->sortCriteria->sortOption);
        $this->assertEquals(SelectionDetails::LIST_OPTION_SORT_CREATION, $struct->sortCriteria->sortOption[0]->selectionDetails->option);
        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\Dumbo', $struct->sortCriteria->dumbo);
        $this->assertEquals(50, $struct->queueNumber->queueDetails->number);
        $this->assertEquals(SubQueueInfoDetails::IDTYPE_CATEGORY, $struct->categoryDetails->subQueueInfoDetails->identificationType);
        $this->assertEquals(3, $struct->categoryDetails->subQueueInfoDetails->itemNumber);
        $this->assertNull($struct->categoryDetails->subQueueInfoDetails->itemDescription);

        $this->assertEquals('USAEUR', $struct->accountNumber->account->number);

        $this->assertNull($struct->date);
        $this->assertNull($struct->scanRange);
        $this->assertNull($struct->scroll);
        $this->assertEmpty($struct->searchCriteria);
    }
}
