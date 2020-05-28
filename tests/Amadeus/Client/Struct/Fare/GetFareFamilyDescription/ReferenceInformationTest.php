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

namespace Test\Amadeus\Client\Struct\Fare\GetFareFamilyDescription;

use Amadeus\Client\Struct\Fare\GetFareFamilyDescription\ReferenceDetails;
use Amadeus\Client\Struct\Fare\GetFareFamilyDescription\ReferenceInformation;
use Test\Amadeus\BaseTestCase;

/**
 * ReferenceInformationTest
 *
 * @package Test\Amadeus\Client\Struct\GetFareFamilyDescription
 */
class ReferenceInformationTest extends BaseTestCase
{
    public function testCreating()
    {
        $details = [
            new ReferenceDetails('TA', 'A'),
            new ReferenceDetails('TB', 'B'),
        ];
        $refInfo = new ReferenceInformation($details);

        self::assertEquals($details, $refInfo->referenceDetails);
    }
}
