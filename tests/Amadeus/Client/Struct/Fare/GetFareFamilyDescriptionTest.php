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

namespace Test\Amadeus\Client\Struct\Fare;

use Amadeus\Client\RequestOptions\FareGetFareFamilyDescriptionOptions;
use Amadeus\Client\RequestOptions\Reference;
use Amadeus\Client\RequestOptions\ReferenceGroup;
use Amadeus\Client\Struct\Fare\GetFareFamilyDescription;
use Test\Amadeus\BaseTestCase;
use Amadeus\Client\Struct\Fare\GetFareFamilyDescription\ReferenceDetails;
use Amadeus\Client\Struct\Fare\GetFareFamilyDescription\ReferenceInformation;

/**
 * GetFareFamilyDescriptionTest
 *
 * @package Test\Amadeus\Client\Struct\Fare
 */
class GetFareFamilyDescriptionTest extends BaseTestCase
{
    public function testCanMakeMessage()
    {
        $options = new FareGetFareFamilyDescriptionOptions([
            'referenceGroups' => [
                new ReferenceGroup([
                    new Reference(Reference::TYPE_RECOMMENDATION, 1),
                ]),
                new ReferenceGroup([
                    new Reference(Reference::TYPE_FARE_COMPONENT, 1),
                    new Reference(Reference::TYPE_FARE_COMPONENT, 2),
                ])
            ]
        ]);

        $message = new GetFareFamilyDescription($options);

        self::assertCount(2, $message->referenceInformation);
        self::assertEquals([
            new ReferenceInformation([
                new ReferenceDetails(
                    Reference::TYPE_RECOMMENDATION,
                    1
                )
            ]),
            new ReferenceInformation([
                new ReferenceDetails(
                    Reference::TYPE_FARE_COMPONENT,
                    1
                ),
                new ReferenceDetails(
                    Reference::TYPE_FARE_COMPONENT,
                    2
                )
            ]),
        ], $message->referenceInformation);
    }
}
