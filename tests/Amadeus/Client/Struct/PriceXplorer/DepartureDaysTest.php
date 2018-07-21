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

namespace Test\Amadeus\Client\Struct\PriceXplorer;

use Amadeus\Client\Struct\PriceXplorer\DepartureDays;
use Amadeus\Client\Struct\PriceXplorer\SelectionDetails;
use Test\Amadeus\BaseTestCase;

/**
 * DepartureDaysTest
 *
 * @package Test\Amadeus\Client\Struct\PriceXplorer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DepartureDaysTest extends BaseTestCase
{
    public function testCanConstructWithNoOption()
    {
        $obj = new DepartureDays(
            [1,2,3]
        );

        $this->assertEquals('123', $obj->daySelection->dayOfWeek);
        $this->assertEquals(SelectionDetails::OPT_OUTBOUND_DEP_DAYS, $obj->selectionInfo->selectionDetails[0]->option);
        $this->assertNull($obj->selectionInfo->selectionDetails[0]->optionInformation);
    }
}
