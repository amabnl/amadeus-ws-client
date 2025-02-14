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

use Amadeus\Client\RequestOptions\PayDeleteVirtualCardOptions;
use Amadeus\Client\Struct\InvalidArgumentException;
use Test\Amadeus\BaseTestCase;

/**
 * ListVirtualCardsTest
 *
 * @package Amadeus\Client\Struct\Pay
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class DeleteVirtualCardTest extends BaseTestCase
{
    public function testCanMakeListVirtualCards(): void
    {
        $options = new PayDeleteVirtualCardOptions([
            'amadeusReference' => '222245PE',
            'externalReference' => '0RAAbaOZgJ2ePy4eo0K5g1Hfa',
        ]);

        $struct = new DeleteVirtualCard($options);

        self::assertEquals('222245PE', $struct->References[0]->_);
        self::assertEquals('Amadeus', $struct->References[0]->Type);

        self::assertEquals('0RAAbaOZgJ2ePy4eo0K5g1Hfa', $struct->References[1]->_);
        self::assertEquals('External', $struct->References[1]->Type);
    }

    public function testAllOptionsAreRequired(): void
    {
        $options = new PayDeleteVirtualCardOptions([
            'amadeusReference' => '222245PE',
            'externalReference' => '', // one of references is not provided
        ]);

        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('All DeleteVirtualCard options are mandatory');

        new DeleteVirtualCard($options);
    }
}
