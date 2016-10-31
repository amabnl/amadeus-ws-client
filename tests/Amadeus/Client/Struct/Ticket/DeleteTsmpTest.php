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

namespace Test\Amadeus\Client\Struct\Ticket;

use Amadeus\Client\RequestOptions\TicketDeleteTsmpOptions;
use Amadeus\Client\Struct\Ticket\DeleteTSMP;
use Amadeus\Client\Struct\Ticket\ReferenceDetails;
use Test\Amadeus\BaseTestCase;

/**
 * DeleteTsmpTest
 *
 * @package Test\Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class DeleteTsmpTest extends BaseTestCase
{
    public function testCanBuildMessageWithPaxTattoos()
    {
        $opt = new TicketDeleteTsmpOptions([
            'paxTattoos' => [1, 2]
        ]);

        $message = new DeleteTSMP($opt);

        $this->assertCount(2, $message->criteriaTattoo);
        $this->assertEquals(1, $message->criteriaTattoo[0]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_PASSENGER_TATTOO, $message->criteriaTattoo[0]->referenceDetails->type);
        $this->assertEquals(2, $message->criteriaTattoo[1]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_PASSENGER_TATTOO, $message->criteriaTattoo[1]->referenceDetails->type);
    }

    public function testCanBuildMessageWithInfantTattoo()
    {
        $opt = new TicketDeleteTsmpOptions([
            'infantTattoos' => [1]
        ]);

        $message = new DeleteTSMP($opt);

        $this->assertCount(1, $message->criteriaTattoo);
        $this->assertEquals(1, $message->criteriaTattoo[0]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_INFANT_PARENT_TATTOO, $message->criteriaTattoo[0]->referenceDetails->type);
    }

    public function testCanBuildMessageWithTsmTattoos()
    {
        $opt = new TicketDeleteTsmpOptions([
            'tsmTattoos' => [14, 16, 17]
        ]);

        $message = new DeleteTSMP($opt);

        $this->assertCount(3, $message->criteriaTattoo);
        $this->assertEquals(14, $message->criteriaTattoo[0]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_TSM_TATTOO, $message->criteriaTattoo[0]->referenceDetails->type);
        $this->assertEquals(16, $message->criteriaTattoo[1]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_TSM_TATTOO, $message->criteriaTattoo[1]->referenceDetails->type);
        $this->assertEquals(17, $message->criteriaTattoo[2]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_TSM_TATTOO, $message->criteriaTattoo[2]->referenceDetails->type);
    }

    /**
     * I don't even know if this would actually work...
     */
    public function testCanBuildMessageWithMixedTattoos()
    {
        $opt = new TicketDeleteTsmpOptions([
            'tsmTattoos' => [14],
            'infantTattoos' => [1],
            'paxTattoos' => [3]
        ]);

        $message = new DeleteTSMP($opt);

        $this->assertCount(3, $message->criteriaTattoo);
        $this->assertEquals(1, $message->criteriaTattoo[0]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_INFANT_PARENT_TATTOO, $message->criteriaTattoo[0]->referenceDetails->type);
        $this->assertEquals(3, $message->criteriaTattoo[1]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_PASSENGER_TATTOO, $message->criteriaTattoo[1]->referenceDetails->type);
        $this->assertEquals(14, $message->criteriaTattoo[2]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_TSM_TATTOO, $message->criteriaTattoo[2]->referenceDetails->type);
    }
}
