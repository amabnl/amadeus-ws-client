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

namespace Test\Amadeus\Client\RequestCreator;

use Amadeus\Client\Params\RequestCreatorParams;
use Amadeus\Client\RequestCreator\Base;
use Amadeus\Client\RequestOptions\PnrRetrieveOptions;
use Amadeus\Client\Struct\Pnr\Retrieve;
use Test\Amadeus\BaseTestCase;

/**
 * BaseTest
 *
 * @package Amadeus\Client\RequestCreator
 */
class BaseTest extends BaseTestCase
{
    public function testCanCreatePnrRetrieveMessage()
    {
        $par = new RequestCreatorParams([
            'originatorOfficeId' => 'BRUXXXXXX',
            'receivedFrom' => 'some RF string'
        ]);

        $rq = new Base($par);

        $message = $rq->createRequest(
            'pnrRetrieve',
            new PnrRetrieveOptions(['recordLocator' => 'ABC123'])
        );

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve', $message);
        /** @var Retrieve $message */
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\RetrievalFacts', $message->retrievalFacts);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\Retrieve', $message->retrievalFacts->retrieve);
        $this->assertEquals(Retrieve::RETR_TYPE_BY_RECLOC, $message->retrievalFacts->retrieve->type);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\ReservationOrProfileIdentifier', $message->retrievalFacts->reservationOrProfileIdentifier);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\Reservation', $message->retrievalFacts->reservationOrProfileIdentifier->reservation);
        $this->assertEquals('ABC123', $message->retrievalFacts->reservationOrProfileIdentifier->reservation->controlNumber);

        $this->assertNull($message->settings);
    }
}
