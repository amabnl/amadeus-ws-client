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

use Amadeus\Client\RequestOptions\Fare\MPPassenger;
use Amadeus\Client\RequestOptions\TicketCheckEligibilityOptions;
use Amadeus\Client\Struct\Fare\MasterPricer\PricingTicketing;
use Amadeus\Client\Struct\Fare\MasterPricer\UnitNumberDetail;
use Amadeus\Client\Struct\Ticket\CheckEligibility;
use Test\Amadeus\BaseTestCase;

/**
 * CheckEligibilityTest
 *
 * @package Test\Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CheckEligibilityTest extends BaseTestCase
{
    /**
     * 5.1 Operation: 1 - Basic case
     *
     * This example shows a ticket eligibility request for one Adult passenger with ticket number 172-23000000004.
     * The ticket was originally priced with Public Fare.
     */
    public function testCanMakeMessageBasic()
    {
        $opt = new TicketCheckEligibilityOptions([
            'nrOfRequestedPassengers' => 1,
            'passengers' => [
                new MPPassenger([
                    'type' => MPPassenger::TYPE_ADULT,
                    'count' => 1
                ])
            ],
            'flightOptions' => [
                TicketCheckEligibilityOptions::FLIGHTOPT_PUBLISHED,
            ],
            'ticketNumbers' => [
                '1722300000004'
            ]
        ]);

        $msg = new CheckEligibility($opt);

        $this->assertCount(1, $msg->numberOfUnit->unitNumberDetail);
        $this->assertEquals(1, $msg->numberOfUnit->unitNumberDetail[0]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_PASS, $msg->numberOfUnit->unitNumberDetail[0]->typeOfUnit);

        $this->assertCount(1, $msg->paxReference);
        $this->assertCount(1, $msg->paxReference[0]->ptc);
        $this->assertEquals('ADT', $msg->paxReference[0]->ptc[0]);
        $this->assertCount(1, $msg->paxReference[0]->traveller);
        $this->assertEquals(1, $msg->paxReference[0]->traveller[0]->ref);
        $this->assertNull($msg->paxReference[0]->traveller[0]->infantIndicator);

        $this->assertCount(1, $msg->fareOptions->pricingTickInfo->pricingTicketing->priceType);
        $this->assertEquals(
            [
                PricingTicketing::PRICETYPE_PUBLISHEDFARES
            ],
            $msg->fareOptions->pricingTickInfo->pricingTicketing->priceType
        );

        $this->assertCount(1, $msg->ticketChangeInfo->ticketNumberDetails->documentDetails);
        $this->assertEquals('1722300000004', $msg->ticketChangeInfo->ticketNumberDetails->documentDetails[0]->number);
        $this->assertEmpty($msg->ticketChangeInfo->ticketRequestedSegments);

        $this->assertEmpty($msg->combinationFareFamilies);
        $this->assertNull($msg->customerRef);
        $this->assertEmpty($msg->fareFamilies);
        $this->assertEmpty($msg->feeOption);
        $this->assertEmpty($msg->formOfPaymentByPassenger);
        $this->assertNull($msg->globalOptions);
        $this->assertEmpty($msg->itinerary);
        $this->assertEmpty($msg->officeIdDetails);
        $this->assertEmpty($msg->passengerInfoGrp);
        $this->assertNull($msg->priceToBeat);
        $this->assertEmpty($msg->solutionFamily);
        $this->assertEmpty($msg->taxInfo);
        $this->assertNull($msg->travelFlightInfo);
    }
}

