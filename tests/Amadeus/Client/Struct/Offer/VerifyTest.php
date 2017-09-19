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

namespace Test\Amadeus\Client\Struct\Offer;

use Amadeus\Client\RequestOptions\OfferVerifyOptions;
use Amadeus\Client\Struct\Offer\Reference;
use Amadeus\Client\Struct\Offer\Verify;
use Test\Amadeus\BaseTestCase;

/**
 * VerifyTest
 *
 * @package Test\Amadeus\Client\Struct\Offer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class VerifyTest extends BaseTestCase
{
    public function testCanMakeVerifyMessage()
    {
        $opt = new OfferVerifyOptions([
            'offerReference' => 1,
            'segmentName' => 'AIR'
        ]);

        $message = new Verify($opt->offerReference, $opt->segmentName);

        $this->assertEquals(1, $message->offerTatoo->reference->value);
        $this->assertEquals(Reference::TYPE_OFFER_TATTOO, $message->offerTatoo->reference->type);
        $this->assertEquals('AIR', $message->offerTatoo->segmentName);
    }
}
