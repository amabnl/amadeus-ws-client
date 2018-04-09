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

namespace Test\Amadeus\Client\Struct\Fare\MasterPricer;

use Amadeus\Client\Struct\Fare\MasterPricer\FareFamilySegment;
use Amadeus\Client\Struct\Fare\MasterPricer\ReferenceInfo;
use Amadeus\Client\Struct\Fare\MasterPricer\ReferencingDetail;
use Test\Amadeus\BaseTestCase;

/**
 * FareFamilySegmentTest
 *
 * @package Test\Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FareFamilySegmentTest extends BaseTestCase
{
    public function testCanCreateWithRef()
    {
        $obj = new FareFamilySegment();
        $obj->referenceInfo = new ReferenceInfo();
        $obj->referenceInfo->referencingDetail[] = new ReferencingDetail(3);

        $this->assertCount(1, $obj->referenceInfo->referencingDetail);
        $this->assertEquals(3, $obj->referenceInfo->referencingDetail[0]->refNumber);
        $this->assertEquals(ReferencingDetail::QUAL_SEGMENT_REFERENCE, $obj->referenceInfo->referencingDetail[0]->refQualifier);
    }
}
