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

namespace Amadeus\Client\Struct\Pay;

use Amadeus\Client\RequestOptions\PayGetVirtualCardDetailsOptions;
use Amadeus\Client\Struct\InvalidArgumentException;
use Test\Amadeus\BaseTestCase;

/**
 * GetVirtualCardDetailsTest
 *
 * @package Amadeus\Client\Struct\Pay
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class GetVirtualCardDetailsTest extends BaseTestCase
{
    public function testCanMakeGetVirtualCardDetails(): void
    {
        $options = new PayGetVirtualCardDetailsOptions([
            'amadeusReference' => '2222483Q',
            'externalReference' => '0RABg9JZ0fdbtH28BiAtcJRd8',
            'displayFilter' => PayGetVirtualCardDetailsOptions::FILTER_FULL,
        ]);

        $struct = new GetVirtualCardDetails($options);

        self::assertEquals('2222483Q', $struct->References[0]->_);
        self::assertEquals('Amadeus', $struct->References[0]->Type);

        self::assertEquals('0RABg9JZ0fdbtH28BiAtcJRd8', $struct->References[1]->_);
        self::assertEquals('External', $struct->References[1]->Type);
    }

    public function testAtLeastOneReferenceMustBePresent(): void
    {
        $options = new PayGetVirtualCardDetailsOptions([
            'displayFilter' => PayGetVirtualCardDetailsOptions::FILTER_FULL,
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one Reference in GetVirtualCardDetails options is mandatory');

        new GetVirtualCardDetails($options);
    }
}
