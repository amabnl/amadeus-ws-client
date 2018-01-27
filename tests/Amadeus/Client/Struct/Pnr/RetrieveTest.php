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

use Amadeus\Client\RequestOptions\Pnr\Retrieve\FrequentTraveller;
use Amadeus\Client\RequestOptions\Pnr\Retrieve\Ticket;
use Amadeus\Client\RequestOptions\PnrRetrieveOptions;
use Amadeus\Client\Struct\Pnr;
use Test\Amadeus\BaseTestCase;

/**
 * RetrieveTest
 *
 * @package Test\Amadeus\Client\Struct\Pnr
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class RetrieveTest extends BaseTestCase
{
    public function testCanCreatePnrRetrieveMessageRetrieveByRecordLocator()
    {
        $message = new Pnr\Retrieve(new PnrRetrieveOptions(['recordLocator' => 'AAAAAA']));

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\RetrievalFacts', $message->retrievalFacts);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\Retrieve', $message->retrievalFacts->retrieve);
        $this->assertEquals(Pnr\Retrieve::RETR_TYPE_BY_RECLOC, $message->retrievalFacts->retrieve->type);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\ReservationOrProfileIdentifier', $message->retrievalFacts->reservationOrProfileIdentifier);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\Reservation', $message->retrievalFacts->reservationOrProfileIdentifier->reservation);
        $this->assertEquals('AAAAAA', $message->retrievalFacts->reservationOrProfileIdentifier->reservation->controlNumber);

        $this->assertNull($message->retrievalFacts->frequentFlyer);
        $this->assertNull($message->retrievalFacts->accounting);
        $this->assertNull($message->retrievalFacts->personalFacts);
        $this->assertNull($message->retrievalFacts->retrieve->option1);
        $this->assertNull($message->retrievalFacts->retrieve->option2);
        $this->assertNull($message->retrievalFacts->retrieve->office);
        $this->assertNull($message->retrievalFacts->retrieve->service);
        $this->assertNull($message->retrievalFacts->retrieve->targetSystem);

        $this->assertNull($message->settings);
    }
    
    public function testCanCreatePnrRetrieveMessageInContext()
    {
        $message = new Pnr\Retrieve(new PnrRetrieveOptions());

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve', $message);
        /** @var Pnr\Retrieve $message */
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\RetrievalFacts', $message->retrievalFacts);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\Retrieve', $message->retrievalFacts->retrieve);
        $this->assertEquals(Pnr\Retrieve::RETR_TYPE_ACTIVE_PNR, $message->retrievalFacts->retrieve->type);
        $this->assertNull($message->retrievalFacts->reservationOrProfileIdentifier);
        $this->assertNull($message->retrievalFacts->frequentFlyer);
        $this->assertNull($message->retrievalFacts->accounting);
        $this->assertNull($message->retrievalFacts->personalFacts);
        $this->assertNull($message->retrievalFacts->retrieve->option1);
        $this->assertNull($message->retrievalFacts->retrieve->option2);
        $this->assertNull($message->retrievalFacts->retrieve->office);
        $this->assertNull($message->retrievalFacts->retrieve->service);
        $this->assertNull($message->retrievalFacts->retrieve->targetSystem);

        $this->assertNull($message->settings);
    }

    public function testCanCreatePnrRetrieveWithCustomerProfile()
    {
        $message = new Pnr\Retrieve(
            new PnrRetrieveOptions([
                'customerProfile' => 'ABC987'
            ])
        );

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve', $message);
        /** @var Pnr\Retrieve $message */
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\RetrievalFacts', $message->retrievalFacts);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\Retrieve', $message->retrievalFacts->retrieve);
        $this->assertEquals(Pnr\Retrieve::RETR_TYPE_BY_CUSTOMER_PROFILE, $message->retrievalFacts->retrieve->type);
        $this->assertEquals('ABC987', $message->retrievalFacts->reservationOrProfileIdentifier->reservation->controlNumber);
        $this->assertNull($message->retrievalFacts->frequentFlyer);
        $this->assertNull($message->retrievalFacts->accounting);
        $this->assertNull($message->retrievalFacts->personalFacts);
        $this->assertNull($message->retrievalFacts->retrieve->option1);
        $this->assertNull($message->retrievalFacts->retrieve->option2);
        $this->assertNull($message->retrievalFacts->retrieve->office);
        $this->assertNull($message->retrievalFacts->retrieve->service);
        $this->assertNull($message->retrievalFacts->retrieve->targetSystem);

        $this->assertNull($message->settings);
    }

    public function testCanCreatePnrRetrieveWithAccountNumber()
    {
        $message = new Pnr\Retrieve(
            new PnrRetrieveOptions([
                'accountNumber' => '12345'
            ])
        );

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve', $message);
        /** @var Pnr\Retrieve $message */
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\RetrievalFacts', $message->retrievalFacts);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\Retrieve', $message->retrievalFacts->retrieve);
        $this->assertEquals(Pnr\Retrieve::RETR_TYPE_BY_ACCOUNT_NUMBER, $message->retrievalFacts->retrieve->type);
        $this->assertEquals('12345', $message->retrievalFacts->accounting->account->number);
        $this->assertNull($message->retrievalFacts->reservationOrProfileIdentifier);
        $this->assertNull($message->retrievalFacts->frequentFlyer);
        $this->assertNull($message->retrievalFacts->personalFacts);
        $this->assertNull($message->retrievalFacts->retrieve->option1);
        $this->assertNull($message->retrievalFacts->retrieve->option2);
        $this->assertNull($message->retrievalFacts->retrieve->office);
        $this->assertNull($message->retrievalFacts->retrieve->service);
        $this->assertNull($message->retrievalFacts->retrieve->targetSystem);

        $this->assertNull($message->settings);
    }

    public function testCanCreatePnrRetrieveWithOfficeAndName()
    {
        $message = new Pnr\Retrieve(
            new PnrRetrieveOptions([
                'officeId' => 'MIA1S213F',
                'lastName' => 'childs'
            ])
        );

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve', $message);
        /** @var Pnr\Retrieve $message */
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\RetrievalFacts', $message->retrievalFacts);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\Retrieve', $message->retrievalFacts->retrieve);
        $this->assertEquals(Pnr\Retrieve::RETR_TYPE_BY_OFFICE_AND_NAME, $message->retrievalFacts->retrieve->type);
        $this->assertEquals('MIA1S213F', $message->retrievalFacts->retrieve->office);
        $this->assertEquals('childs', $message->retrievalFacts->personalFacts->travellerInformation->traveller->surname);
        $this->assertNull($message->retrievalFacts->personalFacts->travellerInformation->passenger);
        $this->assertNull($message->retrievalFacts->personalFacts->productInformation);
        $this->assertNull($message->retrievalFacts->personalFacts->ticket);

        $this->assertNull($message->retrievalFacts->accounting);
        $this->assertNull($message->retrievalFacts->reservationOrProfileIdentifier);
        $this->assertNull($message->retrievalFacts->frequentFlyer);
        $this->assertNull($message->retrievalFacts->retrieve->option1);
        $this->assertNull($message->retrievalFacts->retrieve->option2);
        $this->assertNull($message->retrievalFacts->retrieve->service);
        $this->assertNull($message->retrievalFacts->retrieve->targetSystem);

        $this->assertNull($message->settings);
    }

    public function testCanCreatePnrRetrieveWithFrequentFlyer()
    {
        $message = new Pnr\Retrieve(
            new PnrRetrieveOptions([
                'frequentTraveller' => new FrequentTraveller([
                    'airline' => 'LH',
                    'number' => '992222899525661'
                ])
            ])
        );

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve', $message);
        /** @var Pnr\Retrieve $message */
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\RetrievalFacts', $message->retrievalFacts);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\Retrieve', $message->retrievalFacts->retrieve);
        $this->assertEquals(Pnr\Retrieve::RETR_TYPE_BY_FREQUENT_TRAVELLER, $message->retrievalFacts->retrieve->type);
        $this->assertEquals('LH', $message->retrievalFacts->frequentFlyer->frequentTraveller->companyId);
        $this->assertEquals('992222899525661', $message->retrievalFacts->frequentFlyer->frequentTraveller->membershipNumber);
        $this->assertNull($message->retrievalFacts->retrieve->office);
        $this->assertNull($message->retrievalFacts->personalFacts);
        $this->assertNull($message->retrievalFacts->accounting);
        $this->assertNull($message->retrievalFacts->reservationOrProfileIdentifier);
        $this->assertNull($message->retrievalFacts->retrieve->option1);
        $this->assertNull($message->retrievalFacts->retrieve->option2);
        $this->assertNull($message->retrievalFacts->retrieve->service);
        $this->assertNull($message->retrievalFacts->retrieve->targetSystem);

        $this->assertNull($message->settings);
    }

    public function testCanCreatPnrRetrieveByNameWithDateAndOnlyActivePnrs()
    {
        $message = new Pnr\Retrieve(
            new PnrRetrieveOptions([
                'options' => [
                    PnrRetrieveOptions::OPTION_ACTIVE_ONLY
                ],
                'lastName' => 'childs',
                'departureDate' => \DateTime::createFromFormat(\DateTime::ISO8601, "2018-01-27T00:00:00+0000", new \DateTimeZone('UTC')),
            ])
        );

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve', $message);
        /** @var Pnr\Retrieve $message */
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\RetrievalFacts', $message->retrievalFacts);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\Retrieve', $message->retrievalFacts->retrieve);
        $this->assertEquals(Pnr\Retrieve::RETR_TYPE_BY_OFFICE_AND_NAME, $message->retrievalFacts->retrieve->type);
        $this->assertEquals('childs', $message->retrievalFacts->personalFacts->travellerInformation->traveller->surname);
        $this->assertNull($message->retrievalFacts->personalFacts->travellerInformation->passenger);
        $this->assertEquals('A', $message->retrievalFacts->retrieve->option1);
        $this->assertNull($message->retrievalFacts->retrieve->office);
        $this->assertNull($message->retrievalFacts->retrieve->option2);
        $this->assertNull($message->retrievalFacts->retrieve->service);
        $this->assertNull($message->retrievalFacts->retrieve->targetSystem);
        $this->assertNull($message->retrievalFacts->retrieve->tattoo);

        $this->assertEquals('270118', $message->retrievalFacts->personalFacts->productInformation->product->depDate);
        $this->assertNull($message->retrievalFacts->personalFacts->productInformation->product->depTime);
        $this->assertNull($message->retrievalFacts->personalFacts->productInformation->product->arrDate);
        $this->assertNull($message->retrievalFacts->personalFacts->productInformation->product->arrTime);
        $this->assertNull($message->retrievalFacts->personalFacts->productInformation->boardpointDetail);
        $this->assertNull($message->retrievalFacts->personalFacts->productInformation->offpointDetail);
        $this->assertNull($message->retrievalFacts->personalFacts->productInformation->company);
        $this->assertNull($message->retrievalFacts->personalFacts->productInformation->productDetails);
        $this->assertNull($message->retrievalFacts->personalFacts->ticket);
    }

    public function testCanCreatPnrRetrieveMultiOptions()
    {
        $message = new Pnr\Retrieve(
            new PnrRetrieveOptions([
                'options' => [
                    PnrRetrieveOptions::OPTION_ACTIVE_ONLY,
                    PnrRetrieveOptions::OPTION_ASSOCIATED_PNRS
                ]
            ])
        );


        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve', $message);
        /** @var Pnr\Retrieve $message */
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\RetrievalFacts', $message->retrievalFacts);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\Retrieve', $message->retrievalFacts->retrieve);
        $this->assertEquals('A', $message->retrievalFacts->retrieve->option1);
        $this->assertEquals('X', $message->retrievalFacts->retrieve->option2);
    }


    public function testCanCreatePnrRetrieveRecLocNameTicket()
    {
        $message = new Pnr\Retrieve(
            new PnrRetrieveOptions([
                'recordLocator' => 'YA76F8',
                'lastName' => 'childs',
                'ticket' => new Ticket([
                    'airline' => '057',
                    'number' => '7024209573'
                ])
            ])
        );

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve', $message);
        /** @var Pnr\Retrieve $message */
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\RetrievalFacts', $message->retrievalFacts);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\Retrieve', $message->retrievalFacts->retrieve);
        $this->assertEquals(Pnr\Retrieve::RETR_TYPE_BY_RECLOC, $message->retrievalFacts->retrieve->type);
        $this->assertEquals('YA76F8', $message->retrievalFacts->reservationOrProfileIdentifier->reservation->controlNumber);
        $this->assertEquals('childs', $message->retrievalFacts->personalFacts->travellerInformation->traveller->surname);
        $this->assertEquals('057', $message->retrievalFacts->personalFacts->ticket->airline);
        $this->assertEquals('7024209573', $message->retrievalFacts->personalFacts->ticket->ticketNumber);
        $this->assertNull($message->retrievalFacts->personalFacts->travellerInformation->passenger);
        $this->assertNull($message->retrievalFacts->personalFacts->productInformation);
        $this->assertNull($message->retrievalFacts->accounting);
        $this->assertNull($message->retrievalFacts->frequentFlyer);
        $this->assertNull($message->retrievalFacts->retrieve->office);
        $this->assertNull($message->retrievalFacts->retrieve->option1);
        $this->assertNull($message->retrievalFacts->retrieve->option2);
        $this->assertNull($message->retrievalFacts->retrieve->service);
        $this->assertNull($message->retrievalFacts->retrieve->targetSystem);
        $this->assertNull($message->settings);
    }

    public function testCanCreatePnrRetrieveByServiceAndName()
    {
        $message = new Pnr\Retrieve(
            new PnrRetrieveOptions([
                'service' => PnrRetrieveOptions::SERVICE_AIRLINE,
                'lastName' => 'childs',
                'departureDate' => \DateTime::createFromFormat(\DateTime::ISO8601, "2001-03-28T00:00:00+0000", new \DateTimeZone('UTC')),
                'company' => '6X',
                'flightNumber' => '6201',
            ])
        );

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve', $message);
        /** @var Pnr\Retrieve $message */
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\RetrievalFacts', $message->retrievalFacts);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\Retrieve', $message->retrievalFacts->retrieve);
        $this->assertEquals(Pnr\Retrieve::RETR_TYPE_BY_SERVICE_AND_NAME, $message->retrievalFacts->retrieve->type);
        $this->assertNull( $message->retrievalFacts->reservationOrProfileIdentifier);
        $this->assertEquals('childs', $message->retrievalFacts->personalFacts->travellerInformation->traveller->surname);
        $this->assertEquals('280301', $message->retrievalFacts->personalFacts->productInformation->product->depDate);
        $this->assertEquals('6X', $message->retrievalFacts->personalFacts->productInformation->company->code);
        $this->assertEquals('6201', $message->retrievalFacts->personalFacts->productInformation->productDetails->identification);
        $this->assertNull($message->retrievalFacts->personalFacts->productInformation->productDetails->classOfService);
        $this->assertNull($message->retrievalFacts->personalFacts->productInformation->productDetails->description);
        $this->assertNull($message->retrievalFacts->personalFacts->productInformation->productDetails->subtype);
        $this->assertNull($message->retrievalFacts->personalFacts->productInformation->offpointDetail);
        $this->assertNull($message->retrievalFacts->personalFacts->productInformation->boardpointDetail);
        $this->assertNull($message->retrievalFacts->personalFacts->ticket);
        $this->assertNull($message->retrievalFacts->personalFacts->travellerInformation->passenger);

        $this->assertNull($message->retrievalFacts->accounting);
        $this->assertNull($message->retrievalFacts->frequentFlyer);
        $this->assertNull($message->retrievalFacts->retrieve->office);
        $this->assertNull($message->retrievalFacts->retrieve->option1);
        $this->assertNull($message->retrievalFacts->retrieve->option2);
        $this->assertNull($message->retrievalFacts->retrieve->service);
        $this->assertNull($message->retrievalFacts->retrieve->targetSystem);
        $this->assertNull($message->settings);
    }
}
