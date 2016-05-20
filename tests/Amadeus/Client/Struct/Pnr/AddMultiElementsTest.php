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

use Amadeus\Client\RequestOptions\Pnr\Element\AccountingInfo;
use Amadeus\Client\RequestOptions\Pnr\Element\Address;
use Amadeus\Client\RequestOptions\Pnr\Element\Contact;
use Amadeus\Client\RequestOptions\Pnr\Element\FormOfPayment;
use Amadeus\Client\RequestOptions\Pnr\Element\FrequentFlyer;
use Amadeus\Client\RequestOptions\Pnr\Element\MiscellaneousRemark;
use Amadeus\Client\RequestOptions\Pnr\Element\ReceivedFrom;
use Amadeus\Client\RequestOptions\Pnr\Element\ServiceRequest;
use Amadeus\Client\RequestOptions\Pnr\Element\Ticketing;
use Amadeus\Client\RequestOptions\Pnr\Reference;
use Amadeus\Client\RequestOptions\Pnr\Segment\Miscellaneous;
use Amadeus\Client\RequestOptions\Pnr\Traveller;
use Amadeus\Client\RequestOptions\Pnr\TravellerGroup;
use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
use Amadeus\Client\RequestOptions\Queue;
use Amadeus\Client\Struct\Pnr\AddMultiElements;
use Amadeus\Client\Struct\Pnr\AddMultiElements\PnrActions;
use Test\Amadeus\BaseTestCase;

/**
 * AddMultiElementsTest
 *
 * @package Amadeus\Client\Struct\Pnr
 */
class AddMultiElementsTest extends BaseTestCase
{

    public function testCanCreateMessageToCreateBasicPnr()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->tripSegments[] = new Miscellaneous([
            'date' => \DateTime::createFromFormat('Y-m-d', "2016-10-02", new \DateTimeZone('UTC')),
            'cityCode' => 'BRU',
            'freeText' => 'GENERIC TRAVEL REQUEST',
            'company' => '1A'
        ]);
        $createPnrOptions->elements[] = new Ticketing([
            'ticketMode' => Ticketing::TICKETMODE_TIMELIMIT,
            'date' => \DateTime::createFromFormat(\DateTime::ISO8601, "2016-01-27T00:00:00+0000", new \DateTimeZone('UTC')),
            'ticketQueue' => new Queue(['queue' => 50, 'category' => 0])
        ]);
        $createPnrOptions->elements[] = new Contact([
            'type' => Contact::TYPE_PHONE_GENERAL,
            'value' => '+3223456789'
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\DataElementsMaster', $requestStruct->dataElementsMaster);

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\PnrActions', $requestStruct->pnrActions);
        $this->assertEquals(PnrActions::ACTIONOPTION_END_TRANSACT_W_RETRIEVE, $requestStruct->pnrActions->optionCode);

        $this->assertInternalType('array', $requestStruct->travellerInfo);
        $this->assertEquals(1, count($requestStruct->travellerInfo));
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\TravellerInfo', $requestStruct->travellerInfo[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\ElementManagementPassenger', $requestStruct->travellerInfo[0]->elementManagementPassenger);
        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_NAME, $requestStruct->travellerInfo[0]->elementManagementPassenger->segmentName);
        $this->assertEquals(1, $requestStruct->travellerInfo[0]->elementManagementPassenger->reference->number);
        $this->assertEquals(AddMultiElements\Reference::QUAL_PASSENGER, $requestStruct->travellerInfo[0]->elementManagementPassenger->reference->qualifier);
        $this->assertInternalType('array', $requestStruct->travellerInfo[0]->passengerData);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\PassengerData', $requestStruct->travellerInfo[0]->passengerData[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\TravellerInformation', $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\Traveller', $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->traveller);
        $this->assertEquals('Bowie', $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->surname);
        $this->assertInternalType('array', $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->passenger);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\Passenger', $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]);
        $this->assertEquals('David', $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->firstName);
        $this->assertEquals(AddMultiElements\Passenger::PASST_ADULT, $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->type);

        $this->assertInternalType('array', $requestStruct->originDestinationDetails);
        $this->assertEquals(1, count($requestStruct->originDestinationDetails));
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\OriginDestinationDetails', $requestStruct->originDestinationDetails[0]);
        $this->assertNull($requestStruct->originDestinationDetails[0]->originDestination);
        $this->assertInternalType('array', $requestStruct->originDestinationDetails[0]->itineraryInfo);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\ItineraryInfo', $requestStruct->originDestinationDetails[0]->itineraryInfo[0]);
        $this->assertEquals(AddMultiElements\ElementManagementItinerary::SEGMENT_MISCELLANEOUS, $requestStruct->originDestinationDetails[0]->itineraryInfo[0]->elementManagementItinerary->segmentName);
        $this->assertEquals(AddMultiElements\Reference::QUAL_OTHER, $requestStruct->originDestinationDetails[0]->itineraryInfo[0]->elementManagementItinerary->reference->qualifier);
        $this->assertEquals('GENERIC TRAVEL REQUEST', $requestStruct->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->freetextItinerary->longFreetext);
        $this->assertEquals(AddMultiElements\RelatedProduct::STATUS_CONFIRMED, $requestStruct->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->relatedProduct->status);


        $this->assertInternalType('array', $requestStruct->dataElementsMaster->dataElementsIndiv);
        $this->assertEquals(3, count($requestStruct->dataElementsMaster->dataElementsIndiv));
        $this->assertEquals('TK', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals(AddMultiElements\TicketElement::PASSTYPE_PAX, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->ticketElement->passengerType);
        $this->assertEquals('270116', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->date);
        $this->assertNull($requestStruct->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->time);
        $this->assertEquals(50, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->queueNumber);
        $this->assertEquals(0, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->queueCategory);
        $this->assertEquals(AddMultiElements\Ticket::TICK_IND_TL, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->indicator);

        $this->assertEquals('AP', $requestStruct->dataElementsMaster->dataElementsIndiv[1]->elementManagementData->segmentName);
        $this->assertEquals(AddMultiElements\FreetextDetail::TYPE_PHONE_GENERAL, $requestStruct->dataElementsMaster->dataElementsIndiv[1]->freetextData->freetextDetail->type);
        $this->assertEquals('+3223456789', $requestStruct->dataElementsMaster->dataElementsIndiv[1]->freetextData->longFreetext);

        $this->assertEquals('RF', $requestStruct->dataElementsMaster->dataElementsIndiv[2]->elementManagementData->segmentName);
        $this->assertEquals('unittest', $requestStruct->dataElementsMaster->dataElementsIndiv[2]->freetextData->longFreetext);
    }

    public function testCanCreateMessageToCreateBasicPnrWithSrDocs()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->tripSegments[] = new Miscellaneous([
            'date' => \DateTime::createFromFormat('Y-m-d', "2016-10-02", new \DateTimeZone('UTC')),
            'cityCode' => 'BRU',
            'freeText' => 'GENERIC TRAVEL REQUEST',
            'company' => '1A'
        ]);
        $createPnrOptions->elements[] = new Ticketing([
            'ticketMode' => Ticketing::TICKETMODE_TIMELIMIT,
            'date' => \DateTime::createFromFormat(\DateTime::ISO8601, "2016-01-27T00:00:00+0000", new \DateTimeZone('UTC')),
            'ticketQueue' => new Queue(['queue' => 50, 'category' => 0])
        ]);
        $createPnrOptions->elements[] = new Contact([
            'type' => Contact::TYPE_PHONE_GENERAL,
            'value' => '+3223456789'
        ]);
        $createPnrOptions->elements[] = new ServiceRequest([
            'type' => 'DOCS',
            'status' => ServiceRequest::STATUS_HOLD_CONFIRMED,
            'company' => '1A',
            'quantity' => 1,
            'freeText' => [
                '----08JAN47-M--BOWIE-DAVID'
            ],
            'references' => [
                new Reference([
                    'type' => Reference::TYPE_PASSENGER_TATTOO,
                    'id' => 1
                ])
            ]
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\DataElementsMaster', $requestStruct->dataElementsMaster);

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\PnrActions', $requestStruct->pnrActions);
        $this->assertEquals(PnrActions::ACTIONOPTION_END_TRANSACT_W_RETRIEVE, $requestStruct->pnrActions->optionCode);

        $this->assertInternalType('array', $requestStruct->travellerInfo);
        $this->assertEquals(1, count($requestStruct->travellerInfo));
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\TravellerInfo', $requestStruct->travellerInfo[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\ElementManagementPassenger', $requestStruct->travellerInfo[0]->elementManagementPassenger);
        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_NAME, $requestStruct->travellerInfo[0]->elementManagementPassenger->segmentName);
        $this->assertEquals(1, $requestStruct->travellerInfo[0]->elementManagementPassenger->reference->number);
        $this->assertEquals(AddMultiElements\Reference::QUAL_PASSENGER, $requestStruct->travellerInfo[0]->elementManagementPassenger->reference->qualifier);
        $this->assertInternalType('array', $requestStruct->travellerInfo[0]->passengerData);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\PassengerData', $requestStruct->travellerInfo[0]->passengerData[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\TravellerInformation', $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\Traveller', $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->traveller);
        $this->assertEquals('Bowie', $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->surname);
        $this->assertInternalType('array', $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->passenger);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\Passenger', $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]);
        $this->assertEquals('David', $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->firstName);
        $this->assertEquals(AddMultiElements\Passenger::PASST_ADULT, $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->type);

        $this->assertInternalType('array', $requestStruct->originDestinationDetails);
        $this->assertEquals(1, count($requestStruct->originDestinationDetails));
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\OriginDestinationDetails', $requestStruct->originDestinationDetails[0]);
        $this->assertNull($requestStruct->originDestinationDetails[0]->originDestination);
        $this->assertInternalType('array', $requestStruct->originDestinationDetails[0]->itineraryInfo);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\ItineraryInfo', $requestStruct->originDestinationDetails[0]->itineraryInfo[0]);
        $this->assertEquals(AddMultiElements\ElementManagementItinerary::SEGMENT_MISCELLANEOUS, $requestStruct->originDestinationDetails[0]->itineraryInfo[0]->elementManagementItinerary->segmentName);
        $this->assertEquals(AddMultiElements\Reference::QUAL_OTHER, $requestStruct->originDestinationDetails[0]->itineraryInfo[0]->elementManagementItinerary->reference->qualifier);
        $this->assertEquals('GENERIC TRAVEL REQUEST', $requestStruct->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->freetextItinerary->longFreetext);
        $this->assertEquals(AddMultiElements\RelatedProduct::STATUS_CONFIRMED, $requestStruct->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->relatedProduct->status);


        $this->assertInternalType('array', $requestStruct->dataElementsMaster->dataElementsIndiv);
        $this->assertEquals(4, count($requestStruct->dataElementsMaster->dataElementsIndiv));
        $this->assertEquals('TK', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals(AddMultiElements\TicketElement::PASSTYPE_PAX, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->ticketElement->passengerType);
        $this->assertEquals('270116', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->date);
        $this->assertNull($requestStruct->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->time);
        $this->assertEquals(50, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->queueNumber);
        $this->assertEquals(0, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->queueCategory);
        $this->assertEquals(AddMultiElements\Ticket::TICK_IND_TL, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->indicator);

        $this->assertEquals('AP', $requestStruct->dataElementsMaster->dataElementsIndiv[1]->elementManagementData->segmentName);
        $this->assertEquals(AddMultiElements\FreetextDetail::TYPE_PHONE_GENERAL, $requestStruct->dataElementsMaster->dataElementsIndiv[1]->freetextData->freetextDetail->type);
        $this->assertEquals('+3223456789', $requestStruct->dataElementsMaster->dataElementsIndiv[1]->freetextData->longFreetext);


        $this->assertEquals('SSR', $requestStruct->dataElementsMaster->dataElementsIndiv[2]->elementManagementData->segmentName);
        $this->assertEquals(1, $requestStruct->dataElementsMaster->dataElementsIndiv[2]->serviceRequest->ssr->quantity);
        $this->assertEquals('HK', $requestStruct->dataElementsMaster->dataElementsIndiv[2]->serviceRequest->ssr->status);
        $this->assertEquals('1A', $requestStruct->dataElementsMaster->dataElementsIndiv[2]->serviceRequest->ssr->companyId);
        $this->assertEquals('DOCS', $requestStruct->dataElementsMaster->dataElementsIndiv[2]->serviceRequest->ssr->type);
        $this->assertEquals('----08JAN47-M--BOWIE-DAVID', $requestStruct->dataElementsMaster->dataElementsIndiv[2]->serviceRequest->ssr->freetext[0]);
        $this->assertEquals(AddMultiElements\Reference::QUAL_PASSTAT, $requestStruct->dataElementsMaster->dataElementsIndiv[2]->referenceForDataElement->reference[0]->qualifier);
        $this->assertEquals(1, $requestStruct->dataElementsMaster->dataElementsIndiv[2]->referenceForDataElement->reference[0]->number);


        $this->assertEquals('RF', $requestStruct->dataElementsMaster->dataElementsIndiv[3]->elementManagementData->segmentName);
        $this->assertEquals('unittest', $requestStruct->dataElementsMaster->dataElementsIndiv[3]->freetextData->longFreetext);
    }

    public function testMakePnrWithFormOfPaymentCash()
    {

        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->tripSegments[] = new Miscellaneous([
            'date' => \DateTime::createFromFormat('Y-m-d', "2016-10-02", new \DateTimeZone('UTC')),
            'cityCode' => 'BRU',
            'freeText' => 'GENERIC TRAVEL REQUEST',
            'company' => '1A'
        ]);
        $createPnrOptions->elements[] = new FormOfPayment([
            'type' => FormOfPayment::TYPE_CASH
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertInternalType('array', $requestStruct->dataElementsMaster->dataElementsIndiv);
        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));

        $this->assertEquals('FP', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals(AddMultiElements\Fop::IDENT_CASH, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->formOfPayment->fop->identification);
    }

    public function testMakePnrWithFormOfPaymentNonRef()
    {

        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->tripSegments[] = new Miscellaneous([
            'date' => \DateTime::createFromFormat('Y-m-d', "2016-10-02", new \DateTimeZone('UTC')),
            'cityCode' => 'BRU',
            'freeText' => 'GENERIC TRAVEL REQUEST',
            'company' => '1A'
        ]);
        $createPnrOptions->elements[] = new FormOfPayment([
            'type' => FormOfPayment::TYPE_MISC,
            'freeText' => 'NONREF'
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertInternalType('array', $requestStruct->dataElementsMaster->dataElementsIndiv);
        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));

        $this->assertEquals('FP', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals(AddMultiElements\Fop::IDENT_MISC, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->formOfPayment->fop->identification);
        $this->assertEquals(1, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->fopExtension[0]->fopSequenceNumber);
    }

    public function testMakePnrWithFormOfPaymentCreditCard()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->tripSegments[] = new Miscellaneous([
            'date' => \DateTime::createFromFormat('Y-m-d', "2016-10-02", new \DateTimeZone('UTC')),
            'cityCode' => 'BRU',
            'freeText' => 'GENERIC TRAVEL REQUEST',
            'company' => '1A'
        ]);
        $createPnrOptions->elements[] = new FormOfPayment([
            'type' => FormOfPayment::TYPE_CREDITCARD,
            'creditCardType' => 'VI',
            'creditCardNumber' => '4444333322221111',
            'creditCardExpiry' => '1017',
            'creditCardCvcCode' => 123
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertInternalType('array', $requestStruct->dataElementsMaster->dataElementsIndiv);
        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));

        $this->assertEquals('FP', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals(AddMultiElements\Fop::IDENT_CREDITCARD, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->formOfPayment->fop->identification);
        $this->assertEquals('4444333322221111', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->formOfPayment->fop->accountNumber);
        $this->assertEquals('VI', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->formOfPayment->fop->creditCardCode);
        $this->assertEquals('1017', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->formOfPayment->fop->expiryDate);
        $this->assertEquals(1, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->fopExtension[0]->fopSequenceNumber);
        $this->assertEquals(123, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->fopExtension[0]->newFopsDetails->cvData);
    }

    public function testMakePnrWithPassengerDateOfBirth()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David',
            'dateOfBirth' => \DateTime::createFromFormat('Y-m-d', '1947-01-08')
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->tripSegments[] = new Miscellaneous([
            'date' => \DateTime::createFromFormat('Y-m-d', "2016-10-02", new \DateTimeZone('UTC')),
            'cityCode' => 'BRU',
            'freeText' => 'GENERIC TRAVEL REQUEST',
            'company' => '1A'
        ]);


        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(1, count($requestStruct->travellerInfo));
        $this->assertEquals(1, count($requestStruct->travellerInfo[0]->passengerData));
        $this->assertEquals(706, $requestStruct->travellerInfo[0]->passengerData[0]->dateOfBirth->dateAndTimeDetails->qualifier);
        $this->assertEquals('08011947', $requestStruct->travellerInfo[0]->passengerData[0]->dateOfBirth->dateAndTimeDetails->date);
    }

    public function testMakePnrWithGenericRemarkAndExplicitReceivedFrom()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->tripSegments[] = new Miscellaneous([
            'date' => \DateTime::createFromFormat('Y-m-d', "2016-10-02", new \DateTimeZone('UTC')),
            'cityCode' => 'BRU',
            'freeText' => 'GENERIC TRAVEL REQUEST',
            'company' => '1A'
        ]);
        $createPnrOptions->elements[] = new MiscellaneousRemark([
            'text' => 'MARKUP: 20.00 EUR'
        ]);
        $createPnrOptions->elements[] = new ReceivedFrom([
            'receivedFrom' => 'my magical robot'
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));
        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_GENERAL_REMARK, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals('MARKUP: 20.00 EUR', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->miscellaneousRemark->remarks->freetext);
        $this->assertEquals(MiscellaneousRemark::TYPE_MISCELLANEOUS, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->miscellaneousRemark->remarks->type);
        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_RECEIVE_FROM, $requestStruct->dataElementsMaster->dataElementsIndiv[1]->elementManagementData->segmentName);
        $this->assertEquals('my magical robot', $requestStruct->dataElementsMaster->dataElementsIndiv[1]->freetextData->longFreetext);
        $this->assertEquals(AddMultiElements\FreetextDetail::TYPE_RECEIVE_FROM, $requestStruct->dataElementsMaster->dataElementsIndiv[1]->freetextData->freetextDetail->type);
    }

    public function testCanCreateAccountingInfoElement()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->tripSegments[] = new Miscellaneous([
            'date' => \DateTime::createFromFormat('Y-m-d', "2016-10-02", new \DateTimeZone('UTC')),
            'cityCode' => 'BRU',
            'freeText' => 'GENERIC TRAVEL REQUEST',
            'company' => '1A'
        ]);
        $createPnrOptions->elements[] = new AccountingInfo([
            'accountNumber' => 'BUZA'
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));
        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_ACCOUNTING_INFORMATION, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals('BUZA', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->accounting->account->number);
    }

    public function testCanCreateUnstructuredAddress()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->tripSegments[] = new Miscellaneous([
            'date' => \DateTime::createFromFormat('Y-m-d', "2016-10-02", new \DateTimeZone('UTC')),
            'cityCode' => 'BRU',
            'freeText' => 'GENERIC TRAVEL REQUEST',
            'company' => '1A'
        ]);
        $createPnrOptions->elements[] = new Address([
            'type' => Address::TYPE_BILLING_UNSTRUCTURED,
            'freeText' => 'Amadeus Benelux NV,Medialaan 30,1800 Vilvoorde'
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));
        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_ADDRESS_BILLING_UNSTRUCTURED, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals('Amadeus Benelux NV,Medialaan 30,1800 Vilvoorde', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->freetextData->longFreetext);
        $this->assertEquals(AddMultiElements\FreetextDetail::TYPE_MAILING_ADDRESS, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->freetextData->freetextDetail->type);
        $this->assertEquals(AddMultiElements\FreetextDetail::QUALIFIER_LITERALTEXT, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->freetextData->freetextDetail->subjectQualifier);
    }

    public function testCanCreateStructuredAddress()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->tripSegments[] = new Miscellaneous([
            'date' => \DateTime::createFromFormat('Y-m-d', "2016-10-02", new \DateTimeZone('UTC')),
            'cityCode' => 'BRU',
            'freeText' => 'GENERIC TRAVEL REQUEST',
            'company' => '1A'
        ]);
        $createPnrOptions->elements[] = new Address([
            'type' => Address::TYPE_MAILING_STRUCTURED,
            'name' => 'Mister Amadeus',
            'addressLine1' => 'Medialaan 30',
            'addressLine2' => 'no actual line 2',
            'company' => 'Amadeus Benelux NV',
            'city' => 'Vilvoorde',
            'state' => 'Vlaams-Brabant',
            'country' => 'Belgium',
            'zipCode' => '1800'
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));
        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_ADDRESS_MAILING_STRUCTURED, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals(AddMultiElements\Address::OPT_ADDRESS_LINE_1, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->structuredAddress->address->optionA1);
        $this->assertEquals('Medialaan 30', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->structuredAddress->address->optionTextA1);
        $this->assertEquals(7, count($requestStruct->dataElementsMaster->dataElementsIndiv[0]->structuredAddress->optionalData));
        $this->assertEquals(AddMultiElements\OptionalData::OPT_ADDRESS_LINE_2, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->structuredAddress->optionalData[0]->option);
        $this->assertEquals('no actual line 2', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->structuredAddress->optionalData[0]->optionText);
        $this->assertEquals(AddMultiElements\OptionalData::OPT_CITY, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->structuredAddress->optionalData[1]->option);
        $this->assertEquals('Vilvoorde', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->structuredAddress->optionalData[1]->optionText);
        $this->assertEquals(AddMultiElements\OptionalData::OPT_COUNTRY, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->structuredAddress->optionalData[2]->option);
        $this->assertEquals('Belgium', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->structuredAddress->optionalData[2]->optionText);
        $this->assertEquals(AddMultiElements\OptionalData::OPT_NAME, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->structuredAddress->optionalData[3]->option);
        $this->assertEquals('Mister Amadeus', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->structuredAddress->optionalData[3]->optionText);
        $this->assertEquals(AddMultiElements\OptionalData::OPT_STATE, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->structuredAddress->optionalData[4]->option);
        $this->assertEquals('Vlaams-Brabant', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->structuredAddress->optionalData[4]->optionText);
        $this->assertEquals(AddMultiElements\OptionalData::OPT_ZIP_CODE, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->structuredAddress->optionalData[5]->option);
        $this->assertEquals('1800', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->structuredAddress->optionalData[5]->optionText);
        $this->assertEquals(AddMultiElements\OptionalData::OPT_COMPANY, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->structuredAddress->optionalData[6]->option);
        $this->assertEquals('Amadeus Benelux NV', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->structuredAddress->optionalData[6]->optionText);
    }

    public function testCanCreateFrequentFlyer()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->tripSegments[] = new Miscellaneous([
            'date' => \DateTime::createFromFormat('Y-m-d', "2016-10-02", new \DateTimeZone('UTC')),
            'cityCode' => 'BRU',
            'freeText' => 'GENERIC TRAVEL REQUEST',
            'company' => '1A'
        ]);
        $createPnrOptions->elements[] = new FrequentFlyer([
            'airline' => 'SN',
            'number' => '1111111111'
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));
        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_SPECIAL_SERVICE_REQUEST, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals('FQTV', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->serviceRequest->ssr->type);
        $this->assertEquals('SN', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->serviceRequest->ssr->companyId);
        $this->assertEquals('SN', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->frequentTravellerData->frequentTraveller->companyId);
        $this->assertEquals('1111111111', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->frequentTravellerData->frequentTraveller->membershipNumber);
    }

    public function testCanCreateGroupPnr()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellerGroup = new TravellerGroup([
            'name' => 'Group Name',
            'nrOfTravellers' => 25,
            'travellers' => [
                new Traveller([
                    'number' => 1,
                    'lastName' => 'Bowie',
                    'firstName' => 'David'
                ]),
                new Traveller([
                    'number' => 2,
                    'lastName' => 'Bowie',
                    'firstName' => 'Ziggy'
                ]),
                new Traveller([
                    'number' => 3,
                    'lastName' => 'Jones',
                    'firstName' => 'David'
                ])
            ]
        ]);

        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_NO_PROCESSING;
        $createPnrOptions->tripSegments[] = new Miscellaneous([
            'date' => \DateTime::createFromFormat('Y-m-d', "2016-10-02", new \DateTimeZone('UTC')),
            'cityCode' => 'BRU',
            'freeText' => 'GENERIC TRAVEL REQUEST',
            'company' => '1A'
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(4, count($requestStruct->travellerInfo));
        $this->assertEquals(PnrActions::ACTIONOPTION_NO_SPECIAL_PROCESSING, $requestStruct->pnrActions->optionCode);
        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_GROUPNAME, $requestStruct->travellerInfo[0]->elementManagementPassenger->segmentName);
        $this->assertEquals('Group Name', $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->surname);
        $this->assertEquals(AddMultiElements\Traveller::QUAL_GROUP, $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->qualifier);
        $this->assertEquals(25, $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->quantity);

        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_NAME, $requestStruct->travellerInfo[1]->elementManagementPassenger->segmentName);
        $this->assertEquals(1, $requestStruct->travellerInfo[1]->elementManagementPassenger->reference->number);
        $this->assertEquals(AddMultiElements\Reference::QUAL_PASSENGER, $requestStruct->travellerInfo[1]->elementManagementPassenger->reference->qualifier);
        $this->assertEquals(1, count($requestStruct->travellerInfo[1]->passengerData));
        $this->assertEquals('Bowie', $requestStruct->travellerInfo[1]->passengerData[0]->travellerInformation->traveller->surname);
        $this->assertEquals(1, count($requestStruct->travellerInfo[1]->passengerData[0]->travellerInformation->passenger));
        $this->assertEquals('David', $requestStruct->travellerInfo[1]->passengerData[0]->travellerInformation->passenger[0]->firstName);
        $this->assertEquals(AddMultiElements\Passenger::PASST_ADULT, $requestStruct->travellerInfo[1]->passengerData[0]->travellerInformation->passenger[0]->type);

        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_NAME, $requestStruct->travellerInfo[2]->elementManagementPassenger->segmentName);
        $this->assertEquals(2, $requestStruct->travellerInfo[2]->elementManagementPassenger->reference->number);
        $this->assertEquals(AddMultiElements\Reference::QUAL_PASSENGER, $requestStruct->travellerInfo[2]->elementManagementPassenger->reference->qualifier);
        $this->assertEquals(1, count($requestStruct->travellerInfo[2]->passengerData));
        $this->assertEquals('Bowie', $requestStruct->travellerInfo[2]->passengerData[0]->travellerInformation->traveller->surname);
        $this->assertEquals(1, count($requestStruct->travellerInfo[2]->passengerData[0]->travellerInformation->passenger));
        $this->assertEquals('Ziggy', $requestStruct->travellerInfo[2]->passengerData[0]->travellerInformation->passenger[0]->firstName);
        $this->assertEquals(AddMultiElements\Passenger::PASST_ADULT, $requestStruct->travellerInfo[2]->passengerData[0]->travellerInformation->passenger[0]->type);

        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_NAME, $requestStruct->travellerInfo[3]->elementManagementPassenger->segmentName);
        $this->assertEquals(3, $requestStruct->travellerInfo[3]->elementManagementPassenger->reference->number);
        $this->assertEquals(AddMultiElements\Reference::QUAL_PASSENGER, $requestStruct->travellerInfo[3]->elementManagementPassenger->reference->qualifier);
        $this->assertEquals(1, count($requestStruct->travellerInfo[3]->passengerData));
        $this->assertEquals('Jones', $requestStruct->travellerInfo[3]->passengerData[0]->travellerInformation->traveller->surname);
        $this->assertEquals(1, count($requestStruct->travellerInfo[3]->passengerData[0]->travellerInformation->passenger));
        $this->assertEquals('David', $requestStruct->travellerInfo[3]->passengerData[0]->travellerInformation->passenger[0]->firstName);
        $this->assertEquals(AddMultiElements\Passenger::PASST_ADULT, $requestStruct->travellerInfo[3]->passengerData[0]->travellerInformation->passenger[0]->type);
    }

    public function testCanCreatePnrWithChild()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'lastName' => 'Child',
            'firstName' => 'Johnny',
            'travellerType' => Traveller::TRAV_TYPE_CHILD,
            'dateOfBirth' => \DateTime::createFromFormat('Y-m-d', '2010-01-31')
        ]);

        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_NO_PROCESSING;

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(1, count($requestStruct->travellerInfo));
        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_NAME, $requestStruct->travellerInfo[0]->elementManagementPassenger->segmentName);
        $this->assertNull($requestStruct->travellerInfo[0]->elementManagementPassenger->reference);
        $this->assertEquals(1, count($requestStruct->travellerInfo[0]->passengerData));
        $this->assertEquals(706, $requestStruct->travellerInfo[0]->passengerData[0]->dateOfBirth->dateAndTimeDetails->qualifier);
        $this->assertEquals('31012010', $requestStruct->travellerInfo[0]->passengerData[0]->dateOfBirth->dateAndTimeDetails->date);
        $this->assertEquals('CHD', $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->type);
    }

    public function testCanCreateSegmentWithMultiPassengerAssociation()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 2,
            'lastName' => 'Bowie',
            'firstName' => 'David2'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->tripSegments[] = new Miscellaneous([
            'date' => \DateTime::createFromFormat('Y-m-d', "2016-10-02", new \DateTimeZone('UTC')),
            'cityCode' => 'BRU',
            'freeText' => 'GENERIC TRAVEL REQUEST',
            'quantity' => 2,
            'company' => '1A',
            'references' => [
                new Reference([
                    'type' => Reference::TYPE_PASSENGER_TATTOO,
                    'id' => 1
                ]),
                new Reference([
                    'type' => Reference::TYPE_PASSENGER_TATTOO,
                    'id' => 2
                ])
            ]
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(2, $requestStruct->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->relatedProduct->quantity);
        $this->assertEquals('PT', $requestStruct->originDestinationDetails[0]->itineraryInfo[0]->referenceForSegment->reference[0]->qualifier);
        $this->assertEquals(1, $requestStruct->originDestinationDetails[0]->itineraryInfo[0]->referenceForSegment->reference[0]->number);
        $this->assertEquals('PT', $requestStruct->originDestinationDetails[0]->itineraryInfo[0]->referenceForSegment->reference[1]->qualifier);
        $this->assertEquals(2, $requestStruct->originDestinationDetails[0]->itineraryInfo[0]->referenceForSegment->reference[1]->number);
    }

    public function testCanCreateAddSegmentsMessageForExistingPnr()
    {
        $options = new PnrAddMultiElementsOptions([
            'recordLocator' => 'ABC123',
            'actionCode' => PnrAddMultiElementsOptions::ACTION_END_TRANSACT,
            'tripSegments' => [
                new Miscellaneous([
                    'date' => \DateTime::createFromFormat('Y-m-d', "2017-01-02", new \DateTimeZone('UTC')),
                    'cityCode' => 'BRU',
                    'freeText' => 'GENERIC TRAVEL REQUEST',
                    'company' => '1A'
                ])
            ]
        ]);


        $requestStruct = new AddMultiElements($options);

        $this->assertInternalType('array', $requestStruct->originDestinationDetails);
        $this->assertEquals(1, count($requestStruct->originDestinationDetails));
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\OriginDestinationDetails', $requestStruct->originDestinationDetails[0]);
        $this->assertNull($requestStruct->originDestinationDetails[0]->originDestination);
        $this->assertInternalType('array', $requestStruct->originDestinationDetails[0]->itineraryInfo);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\AddMultiElements\ItineraryInfo', $requestStruct->originDestinationDetails[0]->itineraryInfo[0]);
        $this->assertEquals(AddMultiElements\ElementManagementItinerary::SEGMENT_MISCELLANEOUS, $requestStruct->originDestinationDetails[0]->itineraryInfo[0]->elementManagementItinerary->segmentName);
        $this->assertEquals(AddMultiElements\Reference::QUAL_OTHER, $requestStruct->originDestinationDetails[0]->itineraryInfo[0]->elementManagementItinerary->reference->qualifier);
        $this->assertEquals('GENERIC TRAVEL REQUEST', $requestStruct->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->freetextItinerary->longFreetext);
        $this->assertEquals(AddMultiElements\RelatedProduct::STATUS_CONFIRMED, $requestStruct->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->relatedProduct->status);


    }

    public function testCanCreateMessageForManipulateExistingPnr()
    {

        $ameOptions = new PnrAddMultiElementsOptions([
            'recordLocator' => 'ABC123',
            'actionCode' => PnrAddMultiElementsOptions::ACTION_END_TRANSACT,
            'elements' => [
                new Address([
                    'type' => Address::TYPE_BILLING_UNSTRUCTURED,
                    'freeText' => 'Name,Street 20, Zipcode City'
                ])
            ]
        ]);

        $requestStruct = new AddMultiElements($ameOptions);

        $this->assertEquals('ABC123', $requestStruct->reservationInfo->reservation->controlNumber);
        $this->assertEquals(PnrActions::ACTIONOPTION_END_TRANSACT, $requestStruct->pnrActions->optionCode);
        $this->assertEquals(1, count($requestStruct->dataElementsMaster->dataElementsIndiv));
        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_ADDRESS_BILLING_UNSTRUCTURED, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals('Name,Street 20, Zipcode City', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->freetextData->longFreetext);
        $this->assertEquals(AddMultiElements\FreetextDetail::TYPE_MAILING_ADDRESS, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->freetextData->freetextDetail->type);
        $this->assertEquals(AddMultiElements\FreetextDetail::QUALIFIER_LITERALTEXT, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->freetextData->freetextDetail->subjectQualifier);
    }
}
