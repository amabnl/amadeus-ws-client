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

namespace Test\Amadeus\Client\Struct\Pnr;

use Test\Amadeus\BaseTestCase;
use Amadeus\Client\Struct\Pnr\Split;
use Amadeus\Client\RequestOptions\PnrSplitOptions;

/**
 * SplitTest
 *
 * @package Test\Amadeus\Client\Struct\Pnr
 */
class SplitTest extends BaseTestCase
{
    public function testCanSplitPnrWithPassengerTattoos()
    {
        $message = new Split(
            new PnrSplitOptions([
                'recordLocator' => 'ABCDEF',
                'passengerTattoos' => [1,2,3]
            ])
        );
        $this->assertEquals('ABCDEF', $message->reservationInfo->reservation->controlNumber);
        $this->assertEquals('PT', $message->splitDetails->passenger->type);
        $this->assertCount(3, $message->splitDetails->passenger->tattoo);
        $this->assertEquals(1, $message->splitDetails->passenger->tattoo[0]);
        $this->assertEquals(2, $message->splitDetails->passenger->tattoo[1]);
        $this->assertEquals(3, $message->splitDetails->passenger->tattoo[2]);
    }
}
