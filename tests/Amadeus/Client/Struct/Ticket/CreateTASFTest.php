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

use Amadeus\Client\RequestOptions\Ticket\MonetaryInformation;
use Amadeus\Client\RequestOptions\Ticket\PassengerTattoo;
use Amadeus\Client\RequestOptions\TicketCreateTasfOptions;
use Amadeus\Client\Struct\Ticket\CreateTASF;
use Test\Amadeus\BaseTestCase;

/**
 * CreateTASFTest
 *
 * @package Test\Amadeus\Client\Struct\Ticket
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class CreateTASFTest extends BaseTestCase
{
    public function testCanMakeGenericTASFOptions()
    {
        $opt = new TicketCreateTasfOptions([
            'passengerTattoo' => new PassengerTattoo([
                'type' => PassengerTattoo::TYPE_ADULT,
                'value' => 1,
            ]),
            'monetaryInformation' => new MonetaryInformation([
                'amount' => 30,
                'currency' => 'EUR',
            ]),
        ]);

        $message = new CreateTASF($opt);

        $this->assertEquals(PassengerTattoo::TYPE_ADULT, $message->passengerTattoo->passengerReference->type);
        $this->assertEquals(1, $message->passengerTattoo->passengerReference->value);
        $this->assertEquals(30, $message->monetaryInformation->monetaryDetails->amount);
        $this->assertEquals('EUR', $message->monetaryInformation->monetaryDetails->currency);
    }

    public function testCanMakeTASFOptionsWithRFIC()
    {
        $opt = new TicketCreateTasfOptions([
            'passengerTattoo' => new PassengerTattoo([
                'type' => PassengerTattoo::TYPE_ADULT,
                'value' => 1,
            ]),
            'monetaryInformation' => new MonetaryInformation([
                'amount' => 30,
                'currency' => 'EUR',
            ]),
            'reasonForIssuanceCode' => 'T',
        ]);

        $message = new CreateTASF($opt);

        $this->assertEquals(PassengerTattoo::TYPE_ADULT, $message->passengerTattoo->passengerReference->type);
        $this->assertEquals(1, $message->passengerTattoo->passengerReference->value);
        $this->assertEquals(30, $message->monetaryInformation->monetaryDetails->amount);
        $this->assertEquals('EUR', $message->monetaryInformation->monetaryDetails->currency);

        $this->assertEquals('T', $message->reasonForIssuanceCode->criteriaDetails->attributeType);
    }
}
