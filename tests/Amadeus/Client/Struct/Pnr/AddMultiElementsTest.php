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
use Amadeus\Client\RequestOptions\Pnr\Element\ManualCommission;
use Amadeus\Client\RequestOptions\Pnr\Element\ManualIssuedTicket;
use Amadeus\Client\RequestOptions\Pnr\Element\Contact;
use Amadeus\Client\RequestOptions\Pnr\Element\FormOfPayment;
use Amadeus\Client\RequestOptions\Pnr\Element\FrequentFlyer;
use Amadeus\Client\RequestOptions\Pnr\Element\MiscellaneousRemark;
use Amadeus\Client\RequestOptions\Pnr\Element\OtherServiceInfo;
use Amadeus\Client\RequestOptions\Pnr\Element\ReceivedFrom;
use Amadeus\Client\RequestOptions\Pnr\Element\SeatRequest;
use Amadeus\Client\RequestOptions\Pnr\Element\ServiceRequest;
use Amadeus\Client\RequestOptions\Pnr\Element\Ticketing;
use Amadeus\Client\RequestOptions\Pnr\Element\TourCode;
use Amadeus\Client\RequestOptions\Pnr\Element;
use Amadeus\Client\RequestOptions\Pnr\Itinerary;
use Amadeus\Client\RequestOptions\Pnr\Reference;
use Amadeus\Client\RequestOptions\Pnr\Segment\Air;
use Amadeus\Client\RequestOptions\Pnr\Segment\ArrivalUnknown;
use Amadeus\Client\RequestOptions\Pnr\Segment\Ghost;
use Amadeus\Client\RequestOptions\Pnr\Segment\Hotel;
use Amadeus\Client\RequestOptions\Pnr\Segment\Miscellaneous;
use Amadeus\Client\RequestOptions\Pnr\Traveller;
use Amadeus\Client\RequestOptions\Pnr\TravellerGroup;
use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
use Amadeus\Client\RequestOptions\Queue;
use Amadeus\Client\Struct\Pnr\AddMultiElements;
use Amadeus\Client\Struct\Pnr\AddMultiElements\PnrActions;
use Test\Amadeus\BaseTestCase;

class UnsupportedElement extends Element { }

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
        $this->assertCount(1, $requestStruct->pnrActions->optionCode);
        $this->assertEquals(PnrActions::ACTIONOPTION_END_TRANSACT_W_RETRIEVE, $requestStruct->pnrActions->optionCode[0]);

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

    public function testCanCreateMessageToCreateBasicPnrWithMultipleActionCodes()
    {
        $createPnrOptions = new PnrCreatePnrOptions([
            'actionCode' => [
                PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE,
                PnrCreatePnrOptions::ACTION_WARNING_AT_EOT,
                PnrCreatePnrOptions::ACTION_STOP_EOT_ON_SELL_ERROR
            ],
            'receivedFrom' => "unittest",
            'travellers' => [
                new Traveller([
                    'number' => 1,
                    'lastName' => 'Bowie',
                    'firstName' => 'David'
                ])
            ],
            'itineraries' => [
                new Itinerary([
                    'segments' => [
                        new Miscellaneous([
                            'date' => \DateTime::createFromFormat('Y-m-d', "2016-10-02", new \DateTimeZone('UTC')),
                            'cityCode' => 'BRU',
                            'freeText' => 'GENERIC TRAVEL REQUEST',
                            'company' => '1A'
                        ])
                    ]
                ])
            ],
            'elements' => [
                new Ticketing([
                    'ticketMode' => Ticketing::TICKETMODE_TIMELIMIT,
                    'date' => \DateTime::createFromFormat(\DateTime::ISO8601, "2016-01-27T00:00:00+0000", new \DateTimeZone('UTC')),
                    'ticketQueue' => new Queue(['queue' => 50, 'category' => 0])
                ]),
                new Contact([
                    'type' => Contact::TYPE_PHONE_GENERAL,
                    'value' => '+3223456789'
                ])
            ]
        ]);

        $message = new AddMultiElements($createPnrOptions);

        $this->assertInternalType('array', $message->pnrActions->optionCode);
        $this->assertCount(3, $message->pnrActions->optionCode);
        $this->assertEquals(PnrActions::ACTIONOPTION_END_TRANSACT_W_RETRIEVE, $message->pnrActions->optionCode[0]);
        $this->assertEquals(PnrActions::ACTIONOPTION_WARNING_AT_EOT, $message->pnrActions->optionCode[1]);
        $this->assertEquals(PnrActions::ACTIONOPTION_STOP_EOT_ON_SELL_ERROR, $message->pnrActions->optionCode[2]);

        $this->assertCount(1, $message->travellerInfo);
        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_NAME, $message->travellerInfo[0]->elementManagementPassenger->segmentName);
        $this->assertEquals(1, $message->travellerInfo[0]->elementManagementPassenger->reference->number);
        $this->assertEquals(AddMultiElements\Reference::QUAL_PASSENGER, $message->travellerInfo[0]->elementManagementPassenger->reference->qualifier);
        $this->assertCount(1, $message->travellerInfo[0]->passengerData);
        $this->assertEquals('Bowie', $message->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->surname);
        $this->assertCount(1, $message->travellerInfo[0]->passengerData[0]->travellerInformation->passenger);
        $this->assertEquals('David', $message->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->firstName);
        $this->assertEquals(AddMultiElements\Passenger::PASST_ADULT, $message->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->type);

        $this->assertCount(1, $message->originDestinationDetails);
        $this->assertNull($message->originDestinationDetails[0]->originDestination);
        $this->assertCount(1, $message->originDestinationDetails[0]->itineraryInfo);
        $this->assertEquals(AddMultiElements\ElementManagementItinerary::SEGMENT_MISCELLANEOUS, $message->originDestinationDetails[0]->itineraryInfo[0]->elementManagementItinerary->segmentName);
        $this->assertEquals(AddMultiElements\Reference::QUAL_OTHER, $message->originDestinationDetails[0]->itineraryInfo[0]->elementManagementItinerary->reference->qualifier);
        $this->assertEquals('GENERIC TRAVEL REQUEST', $message->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->freetextItinerary->longFreetext);
        $this->assertEquals(AddMultiElements\RelatedProduct::STATUS_CONFIRMED, $message->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->relatedProduct->status);

        $this->assertInternalType('array', $message->dataElementsMaster->dataElementsIndiv);
        $this->assertCount(3, $message->dataElementsMaster->dataElementsIndiv);
        $this->assertEquals('TK', $message->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals(AddMultiElements\TicketElement::PASSTYPE_PAX, $message->dataElementsMaster->dataElementsIndiv[0]->ticketElement->passengerType);
        $this->assertEquals('270116', $message->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->date);
        $this->assertNull($message->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->time);
        $this->assertEquals(50, $message->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->queueNumber);
        $this->assertEquals(0, $message->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->queueCategory);
        $this->assertEquals(AddMultiElements\Ticket::TICK_IND_TL, $message->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->indicator);

        $this->assertEquals('AP', $message->dataElementsMaster->dataElementsIndiv[1]->elementManagementData->segmentName);
        $this->assertEquals(AddMultiElements\FreetextDetail::TYPE_PHONE_GENERAL, $message->dataElementsMaster->dataElementsIndiv[1]->freetextData->freetextDetail->type);
        $this->assertEquals('+3223456789', $message->dataElementsMaster->dataElementsIndiv[1]->freetextData->longFreetext);

        $this->assertEquals('RF', $message->dataElementsMaster->dataElementsIndiv[2]->elementManagementData->segmentName);
        $this->assertEquals('unittest', $message->dataElementsMaster->dataElementsIndiv[2]->freetextData->longFreetext);
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
        $this->assertCount(1, $requestStruct->pnrActions->optionCode);
        $this->assertEquals(PnrActions::ACTIONOPTION_END_TRANSACT_W_RETRIEVE, $requestStruct->pnrActions->optionCode[0]);

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

    public function testMakePnrWithFormOfPaymentFreeTextDummy()
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
            'freeText' => 'dummy'
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertInternalType('array', $requestStruct->dataElementsMaster->dataElementsIndiv);
        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));

        $this->assertEquals('FP', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals(AddMultiElements\Fop::IDENT_MISC, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->formOfPayment->fop->identification);
        $this->assertEquals('dummy', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->formOfPayment->fop->freetext);
        $this->assertEmpty($requestStruct->dataElementsMaster->dataElementsIndiv[0]->fopExtension);
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
            'creditCardCvcCode' => 123,
            'creditCardHolder' => 'BERNDMUELLER',
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
        $this->assertEquals('BERNDMUELLER', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->fopExtension[0]->newFopsDetails->chdData);
    }

    public function testMakePnrWithFormOfPaymentCreditCardServiceFee()
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
            'creditCardCvcCode' => 123,
            'isServiceFee' => true
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

        $this->assertCount(1, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->serviceDetails);
        $this->assertEquals(AddMultiElements\StatusDetails::IND_SERVICEFEE, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->serviceDetails[0]->statusDetails->indicator);
    }

    public function testMakePnrWithFormOfPaymentCheckWillThrowException()
    {
        $this->setExpectedException('\RuntimeException', 'FOP CHECK NOT YET IMPLEMENTED');
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
            'type' => FormOfPayment::TYPE_CHECK
        ]);

        new AddMultiElements($createPnrOptions);
    }

    public function testMakePnrWithPassengerDateOfBirth()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David',
            'dateOfBirth' => \DateTime::createFromFormat('Y-m-d', '1947-01-08', new \DateTimeZone('UTC'))
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
        $this->assertEquals('08JAN47', $requestStruct->travellerInfo[0]->passengerData[0]->dateOfBirth->dateAndTimeDetails->date);
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

    public function testCanCreateOsiElement()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_NO_PROCESSING;
        $createPnrOptions->elements[] = new OtherServiceInfo([
            'airline' => '6X',
            'freeText' => '6X FB00S7 B744 UMLAUF71343'
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));
        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_OTHER_SERVICE_INFORMATION, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals('6X FB00S7 B744 UMLAUF71343', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->freetextData->longFreetext);
        $this->assertEquals(AddMultiElements\FreetextDetail::TYPE_OSI_ELEMENT, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->freetextData->freetextDetail->type);
        $this->assertEquals('6X', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->freetextData->freetextDetail->companyId);
        $this->assertEquals(AddMultiElements\FreetextDetail::QUALIFIER_LITERALTEXT, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->freetextData->freetextDetail->subjectQualifier);
    }


    public function testCanCreateCommission()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_NO_PROCESSING;
        $createPnrOptions->elements[] = new ManualCommission([
            'passengerType' => ManualCommission::PAXTYPE_PASSENGER,
            'indicator' => 'FM',
            'percentage' => 5
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));
        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_COMMISSION, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertNull($requestStruct->dataElementsMaster->dataElementsIndiv[0]->freetextData);
        $this->assertEquals('PAX', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->commission->passengerType);
        $this->assertEquals('FM', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->commission->indicator);
        $this->assertEquals(5, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->commission->commissionInfo->percentage);
        $this->assertNull($requestStruct->dataElementsMaster->dataElementsIndiv[0]->commission->commissionInfo->amount);
        $this->assertNull($requestStruct->dataElementsMaster->dataElementsIndiv[0]->commission->commissionInfo->vatIndicator);
        $this->assertNull($requestStruct->dataElementsMaster->dataElementsIndiv[0]->commission->commissionInfo->remitIndicator);
        $this->assertNull($requestStruct->dataElementsMaster->dataElementsIndiv[0]->commission->oldCommission);
        $this->assertNull($requestStruct->dataElementsMaster->dataElementsIndiv[0]->commission->manualCapping);
    }

    /**
     * 5.54 Operation: Tour Code in Free Flow Format
     *
     * The example below illustrates the request to create a tour code in free flow format.
     */
    public function testCanCreateFreeFlowTourCode()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_NO_PROCESSING;
        $createPnrOptions->elements[] = new TourCode([
            'passengerType' => TourCode::PAX_PASSENGER,
            'freeText' => 'TOUR CODE'
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));
        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_TOUR_CODE, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertNull($requestStruct->dataElementsMaster->dataElementsIndiv[0]->freetextData);
        $this->assertEquals('PAX', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->tourCode->passengerType);
        $this->assertEquals(AddMultiElements\FreeFormatTour::INDICATOR_FREE_FORMAT, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->tourCode->freeFormatTour->indicator);
        $this->assertEquals('TOUR CODE', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->tourCode->freeFormatTour->freetext);
        $this->assertNull($requestStruct->dataElementsMaster->dataElementsIndiv[0]->tourCode->formatedTour);
        $this->assertNull($requestStruct->dataElementsMaster->dataElementsIndiv[0]->tourCode->netRemit);
    }

    /**
     * 5.40 Operation: Seat Request
     *
     * This example shows a seat request for a non-smoking aisle seat (NSSA).
     */
    public function testCanCreateSeatRequest()
    {
        $opt = new PnrAddMultiElementsOptions([
            'actionCode' => PnrAddMultiElementsOptions::ACTION_NO_PROCESSING,
            'elements' => [
                new SeatRequest([
                    'type' => SeatRequest::TYPE_NO_SMOKING_AISLE_SEAT,
                    'references' => [
                        new Reference([
                            'type' => Reference::TYPE_PASSENGER_TATTOO,
                            'id' => 1
                        ]),
                        new Reference([
                            'type' => Reference::TYPE_SEGMENT_TATTOO,
                            'id' => 1
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new AddMultiElements($opt);

        $this->assertCount(1, $msg->dataElementsMaster->dataElementsIndiv);
        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_SEAT_REQUEST, $msg->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->freetextData);
        $this->assertEquals('NSSA', $msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->seatRequest->seat->type);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->seatRequest->seat->boardpoint);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->seatRequest->seat->offpoint);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->seatRequest->seat->qualifier);
        $this->assertEmpty($msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->seatRequest->special);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->railSeatPreferences);
        $this->assertEmpty($msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->railSeatReferenceInformation);

        $this->assertCount(2, $msg->dataElementsMaster->dataElementsIndiv[0]->referenceForDataElement->reference);
        $this->assertEquals(1, $msg->dataElementsMaster->dataElementsIndiv[0]->referenceForDataElement->reference[0]->number);
        $this->assertEquals(AddMultiElements\Reference::QUAL_PASSTAT, $msg->dataElementsMaster->dataElementsIndiv[0]->referenceForDataElement->reference[0]->qualifier);
        $this->assertEquals(1, $msg->dataElementsMaster->dataElementsIndiv[0]->referenceForDataElement->reference[1]->number);
        $this->assertEquals(AddMultiElements\Reference::QUAL_SEGTAT, $msg->dataElementsMaster->dataElementsIndiv[0]->referenceForDataElement->reference[1]->qualifier);
    }

    /**
     * You may use a Seat Request (STR) element either to specify a seat preference (for example: window, aisle)
     * or a specific seat number.
     *
     * This example assumes that the Passenger Name Record (PNR) already includes
     * a flight segment with Segment Tattoo (ST) 1.
     * It adds a name element with Passenger Reference (PR) 1, which is associated to the specified seat.
     *
     * The booked seat is added to the PNR as a Special Service Request (SSR).
     */
    public function testCanCreateSeatRequestWithSeatNum()
    {
        $opt = new PnrAddMultiElementsOptions([
            'actionCode' => PnrAddMultiElementsOptions::ACTION_NO_PROCESSING,
            'travellers' => [
                new Traveller([
                    'number' => 1,
                    'firstName' => 'John',
                    'lastName' => 'Doe'
                ])
            ],
            'elements' => [
                new SeatRequest([
                    'seatNumber' => '13f',
                    'references' => [
                        new Reference([
                            'type' => Reference::TYPE_PASSENGER_REQUEST,
                            'id' => 1
                        ]),
                        new Reference([
                            'type' => Reference::TYPE_SEGMENT_TATTOO,
                            'id' => 1
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new AddMultiElements($opt);

        $this->assertCount(1, $msg->travellerInfo);
        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_NAME, $msg->travellerInfo[0]->elementManagementPassenger->segmentName);
        $this->assertEquals(1, $msg->travellerInfo[0]->elementManagementPassenger->reference->number);
        $this->assertEquals(AddMultiElements\Reference::QUAL_PASSENGER, $msg->travellerInfo[0]->elementManagementPassenger->reference->qualifier);
        $this->assertCount(1, $msg->travellerInfo[0]->passengerData);
        $this->assertEquals('Doe', $msg->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->surname);
        $this->assertCount(1, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger);
        $this->assertEquals('John', $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->firstName);
        $this->assertEquals(AddMultiElements\Passenger::PASST_ADULT, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->type);


        $this->assertCount(1, $msg->dataElementsMaster->dataElementsIndiv);
        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_SEAT_REQUEST, $msg->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->freetextData);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->seatRequest->seat);
        $this->assertCount(1, $msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->seatRequest->special);
        $this->assertEquals('13f', $msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->seatRequest->special[0]->data);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->seatRequest->special[0]->seatType);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->railSeatPreferences);
        $this->assertEmpty($msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->railSeatReferenceInformation);

        $this->assertCount(2, $msg->dataElementsMaster->dataElementsIndiv[0]->referenceForDataElement->reference);
        $this->assertEquals(1, $msg->dataElementsMaster->dataElementsIndiv[0]->referenceForDataElement->reference[0]->number);
        $this->assertEquals(AddMultiElements\Reference::QUAL_PASSENGER, $msg->dataElementsMaster->dataElementsIndiv[0]->referenceForDataElement->reference[0]->qualifier);
        $this->assertEquals(1, $msg->dataElementsMaster->dataElementsIndiv[0]->referenceForDataElement->reference[1]->number);
        $this->assertEquals(AddMultiElements\Reference::QUAL_SEGTAT, $msg->dataElementsMaster->dataElementsIndiv[0]->referenceForDataElement->reference[1]->qualifier);
    }

    public function testCanCreateSeatRequestWithMultipleSeatNum()
    {
        $opt = new PnrAddMultiElementsOptions([
            'actionCode' => PnrAddMultiElementsOptions::ACTION_NO_PROCESSING,
            'travellers' => [
                new Traveller([
                    'number' => 1,
                    'firstName' => 'John',
                    'lastName' => 'Doe'
                ])
            ],
            'elements' => [
                new SeatRequest([
                    'seatNumber' => [
                        '5a',
                        '5b'
                    ],
                    'references' => [
                        new Reference([
                            'type' => Reference::TYPE_PASSENGER_REQUEST,
                            'id' => 1
                        ]),
                        new Reference([
                            'type' => Reference::TYPE_SEGMENT_TATTOO,
                            'id' => 1
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new AddMultiElements($opt);

        $this->assertCount(1, $msg->dataElementsMaster->dataElementsIndiv);
        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_SEAT_REQUEST, $msg->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->freetextData);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->seatRequest->seat);
        $this->assertCount(2, $msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->seatRequest->special);
        $this->assertEquals('5a', $msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->seatRequest->special[0]->data);
        $this->assertEquals('5b', $msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->seatRequest->special[1]->data);
    }

    /**
     * Special Seat Type
     */
    public function testCanMakeSeatRequestAisleSeatSpecialType()
    {
        $opt = new PnrAddMultiElementsOptions([
            'actionCode' => PnrAddMultiElementsOptions::ACTION_NO_PROCESSING,
            'travellers' => [
                new Traveller([
                    'number' => 1,
                    'firstName' => 'John',
                    'lastName' => 'Doe'
                ])
            ],
            'elements' => [
                new SeatRequest([
                    'specialType' => SeatRequest::SPECIAL_AISLE_SEAT,
                    'references' => [
                        new Reference([
                            'type' => Reference::TYPE_PASSENGER_REQUEST,
                            'id' => 1
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new AddMultiElements($opt);

        $this->assertCount(1, $msg->travellerInfo);
        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_NAME, $msg->travellerInfo[0]->elementManagementPassenger->segmentName);
        $this->assertEquals(1, $msg->travellerInfo[0]->elementManagementPassenger->reference->number);
        $this->assertEquals(AddMultiElements\Reference::QUAL_PASSENGER, $msg->travellerInfo[0]->elementManagementPassenger->reference->qualifier);
        $this->assertCount(1, $msg->travellerInfo[0]->passengerData);
        $this->assertEquals('Doe', $msg->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->surname);
        $this->assertCount(1, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger);
        $this->assertEquals('John', $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->firstName);
        $this->assertEquals(AddMultiElements\Passenger::PASST_ADULT, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->type);


        $this->assertCount(1, $msg->dataElementsMaster->dataElementsIndiv);
        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_SEAT_REQUEST, $msg->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->freetextData);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->seatRequest->seat);
        $this->assertCount(1, $msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->seatRequest->special);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->seatRequest->special[0]->data);
        $this->assertEquals(AddMultiElements\Special::TYPE_AISLE_SEAT, $msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->seatRequest->special[0]->seatType);

        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->railSeatPreferences);
        $this->assertEmpty($msg->dataElementsMaster->dataElementsIndiv[0]->seatGroup->railSeatReferenceInformation);

        $this->assertCount(1, $msg->dataElementsMaster->dataElementsIndiv[0]->referenceForDataElement->reference);
        $this->assertEquals(1, $msg->dataElementsMaster->dataElementsIndiv[0]->referenceForDataElement->reference[0]->number);
        $this->assertEquals(AddMultiElements\Reference::QUAL_PASSENGER, $msg->dataElementsMaster->dataElementsIndiv[0]->referenceForDataElement->reference[0]->qualifier);
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

        $this->assertCount(4, $requestStruct->travellerInfo);
        $this->assertCount(1, $requestStruct->pnrActions->optionCode);
        $this->assertEquals(PnrActions::ACTIONOPTION_NO_SPECIAL_PROCESSING, $requestStruct->pnrActions->optionCode[0]);
        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_GROUPNAME, $requestStruct->travellerInfo[0]->elementManagementPassenger->segmentName);
        $this->assertEquals('Group Name', $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->surname);
        $this->assertEquals(AddMultiElements\Traveller::QUAL_GROUP, $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->qualifier);
        $this->assertEquals(25, $requestStruct->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->quantity);

        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_NAME, $requestStruct->travellerInfo[1]->elementManagementPassenger->segmentName);
        $this->assertEquals(1, $requestStruct->travellerInfo[1]->elementManagementPassenger->reference->number);
        $this->assertEquals(AddMultiElements\Reference::QUAL_PASSENGER, $requestStruct->travellerInfo[1]->elementManagementPassenger->reference->qualifier);
        $this->assertCount(1, $requestStruct->travellerInfo[1]->passengerData);
        $this->assertEquals('Bowie', $requestStruct->travellerInfo[1]->passengerData[0]->travellerInformation->traveller->surname);
        $this->assertCount(1, $requestStruct->travellerInfo[1]->passengerData[0]->travellerInformation->passenger);
        $this->assertEquals('David', $requestStruct->travellerInfo[1]->passengerData[0]->travellerInformation->passenger[0]->firstName);
        $this->assertEquals(AddMultiElements\Passenger::PASST_ADULT, $requestStruct->travellerInfo[1]->passengerData[0]->travellerInformation->passenger[0]->type);

        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_NAME, $requestStruct->travellerInfo[2]->elementManagementPassenger->segmentName);
        $this->assertEquals(2, $requestStruct->travellerInfo[2]->elementManagementPassenger->reference->number);
        $this->assertEquals(AddMultiElements\Reference::QUAL_PASSENGER, $requestStruct->travellerInfo[2]->elementManagementPassenger->reference->qualifier);
        $this->assertCount(1, $requestStruct->travellerInfo[2]->passengerData);
        $this->assertEquals('Bowie', $requestStruct->travellerInfo[2]->passengerData[0]->travellerInformation->traveller->surname);
        $this->assertCount(1, $requestStruct->travellerInfo[2]->passengerData[0]->travellerInformation->passenger);
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

    public function testCanAddGroupToPnr()
    {
        $addmultiOptions = new PnrAddMultiElementsOptions();
        $addmultiOptions->recordLocator = 'ABC123';
        $addmultiOptions->receivedFrom = "unittest";
        $addmultiOptions->travellerGroup = new TravellerGroup([
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

        $addmultiOptions->actionCode = PnrCreatePnrOptions::ACTION_NO_PROCESSING;
        $addmultiOptions->tripSegments[] = new Miscellaneous([
            'date' => \DateTime::createFromFormat('Y-m-d', "2016-10-02", new \DateTimeZone('UTC')),
            'cityCode' => 'BRU',
            'freeText' => 'GENERIC TRAVEL REQUEST',
            'company' => '1A'
        ]);

        $requestStruct = new AddMultiElements($addmultiOptions);

        $this->assertEquals(4, count($requestStruct->travellerInfo));
        $this->assertCount(1, $requestStruct->pnrActions->optionCode);
        $this->assertEquals(PnrActions::ACTIONOPTION_NO_SPECIAL_PROCESSING, $requestStruct->pnrActions->optionCode[0]);
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
            'dateOfBirth' => \DateTime::createFromFormat('Y-m-d', '2010-01-31', new \DateTimeZone('UTC'))
        ]);

        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_NO_PROCESSING;

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(1, count($requestStruct->travellerInfo));
        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_NAME, $requestStruct->travellerInfo[0]->elementManagementPassenger->segmentName);
        $this->assertNull($requestStruct->travellerInfo[0]->elementManagementPassenger->reference);
        $this->assertEquals(1, count($requestStruct->travellerInfo[0]->passengerData));
        $this->assertEquals(706, $requestStruct->travellerInfo[0]->passengerData[0]->dateOfBirth->dateAndTimeDetails->qualifier);
        $this->assertEquals('31JAN10', $requestStruct->travellerInfo[0]->passengerData[0]->dateOfBirth->dateAndTimeDetails->date);
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
        $this->assertCount(1, $requestStruct->pnrActions->optionCode);
        $this->assertEquals(PnrActions::ACTIONOPTION_END_TRANSACT, $requestStruct->pnrActions->optionCode[0]);
        $this->assertEquals(1, count($requestStruct->dataElementsMaster->dataElementsIndiv));
        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_ADDRESS_BILLING_UNSTRUCTURED, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals('Name,Street 20, Zipcode City', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->freetextData->longFreetext);
        $this->assertEquals(AddMultiElements\FreetextDetail::TYPE_MAILING_ADDRESS, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->freetextData->freetextDetail->type);
        $this->assertEquals(AddMultiElements\FreetextDetail::QUALIFIER_LITERALTEXT, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->freetextData->freetextDetail->subjectQualifier);
    }

    public function testAddInfantPassengerNoDetails()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David',
            'withInfant' => true
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
                ])
            ]
        ]);

        $msg = new AddMultiElements($createPnrOptions);

        $this->assertCount(1, $msg->travellerInfo);
        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_NAME, $msg->travellerInfo[0]->elementManagementPassenger->segmentName);
        $this->assertCount(1, $msg->travellerInfo[0]->passengerData);
        $this->assertEquals(2, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->quantity);
        $this->assertEquals('Bowie', $msg->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->surname);
        $this->assertCount(1, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger);
        $this->assertEquals('David', $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->firstName);
        $this->assertEquals(AddMultiElements\Passenger::PASST_ADULT, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->type);
        $this->assertEquals(AddMultiElements\Passenger::INF_NOINFO, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->infantIndicator);
    }

    public function testaddInfantPassengerWithFirstnameNoSurname()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David',
            'infant' => new Traveller(['firstName' => 'Junior'])
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
                ])
            ]
        ]);

        $msg = new AddMultiElements($createPnrOptions);

        $this->assertCount(1, $msg->travellerInfo);
        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_NAME, $msg->travellerInfo[0]->elementManagementPassenger->segmentName);
        $this->assertCount(1, $msg->travellerInfo[0]->passengerData);
        $this->assertEquals(2, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->quantity);
        $this->assertEquals('Bowie', $msg->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->surname);
        $this->assertCount(2, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger);
        $this->assertEquals('David', $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->firstName);
        $this->assertEquals(AddMultiElements\Passenger::PASST_ADULT, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->type);
        $this->assertEquals(AddMultiElements\Passenger::INF_GIVEN, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->infantIndicator);
        $this->assertEquals('Junior', $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[1]->firstName);
        $this->assertEquals(AddMultiElements\Passenger::PASST_INFANT, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[1]->type);
    }

    public function testaddInfantPassengerWithFirstnameSurnameAndBirthDate()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David',
            'infant' => new Traveller([
                'firstName' => 'Junior',
                'lastName' => 'Cohen',
                'dateOfBirth' => \DateTime::createFromFormat('Y-m-d', '2016-01-08', new \DateTimeZone('UTC'))
            ])
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
                ])
            ]
        ]);

        $msg = new AddMultiElements($createPnrOptions);

        $this->assertCount(1, $msg->travellerInfo);
        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_NAME, $msg->travellerInfo[0]->elementManagementPassenger->segmentName);
        $this->assertCount(2, $msg->travellerInfo[0]->passengerData);
        $this->assertEquals(2, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->quantity);
        $this->assertEquals('Bowie', $msg->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->surname);
        $this->assertCount(1, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger);
        $this->assertEquals('David', $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->firstName);
        $this->assertEquals(AddMultiElements\Passenger::PASST_ADULT, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->type);
        $this->assertEquals(AddMultiElements\Passenger::INF_FULL, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->infantIndicator);
        $this->assertEquals('Cohen', $msg->travellerInfo[0]->passengerData[1]->travellerInformation->traveller->surname);
        $this->assertEquals('Junior', $msg->travellerInfo[0]->passengerData[1]->travellerInformation->passenger[0]->firstName);
        $this->assertEquals(AddMultiElements\Passenger::PASST_INFANT, $msg->travellerInfo[0]->passengerData[1]->travellerInformation->passenger[0]->type);
        $this->assertEquals('08JAN16', $msg->travellerInfo[0]->passengerData[1]->dateOfBirth->dateAndTimeDetails->date);
    }

    public function testCanHandleAddInfantPassengerWhereMainPassengerHasNoFirstName()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'travellerType' => null,
            'infant' => new Traveller(['firstName' => 'Junior'])
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
                ])
            ]
        ]);

        $msg = new AddMultiElements($createPnrOptions);

        $this->assertCount(1, $msg->travellerInfo);
        $this->assertEquals(AddMultiElements\ElementManagementPassenger::SEG_NAME, $msg->travellerInfo[0]->elementManagementPassenger->segmentName);
        $this->assertCount(1, $msg->travellerInfo[0]->passengerData);
        $this->assertEquals(2, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->quantity);
        $this->assertEquals('Bowie', $msg->travellerInfo[0]->passengerData[0]->travellerInformation->traveller->surname);
        $this->assertCount(2, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger);
        $this->assertNull($msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->firstName);
        $this->assertNull($msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->type);
        $this->assertEquals(AddMultiElements\Passenger::INF_GIVEN, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[0]->infantIndicator);
        $this->assertEquals('Junior', $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[1]->firstName);
        $this->assertEquals(AddMultiElements\Passenger::PASST_INFANT, $msg->travellerInfo[0]->passengerData[0]->travellerInformation->passenger[1]->type);
    }

    public function testCreateGhostSegmentWillThrowException()
    {
        $this->setExpectedException('\RuntimeException', 'NOT YET IMPLEMENTED');

        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->tripSegments[] = new Ghost([
        ]);

        new AddMultiElements($createPnrOptions);
    }

    public function testCreateHotelSegmentWillThrowException()
    {
        $this->setExpectedException(
            'Amadeus\Client\Struct\InvalidArgumentException',
            'Segment type Hotel is not supported'
        );

        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->tripSegments[] = new Hotel([
        ]);

        new AddMultiElements($createPnrOptions);
    }

    public function testCreateUnsupportedElementWillThrowException()
    {
        $this->setExpectedException(
            'Amadeus\Client\Struct\InvalidArgumentException',
            'Element type UnsupportedElement is not supported'
        );

        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->elements[] = new UnsupportedElement();

        new AddMultiElements($createPnrOptions);
    }

    public function testCreateManualTicketElement()
    {
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->travellers[] = new Traveller([
            'number' => 1,
            'lastName' => 'Bowie',
            'firstName' => 'David'
        ]);
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->elements[] = new ManualIssuedTicket([
            'ticketNumber' => '5118037484',
            'passengerType' => ManualIssuedTicket::PASSENGER_TYPE_PASSENGER,
            'companyId' => '220',
            'references' => [
                new Reference([
                    'type' => Reference::TYPE_SEGMENT_TATTOO,
                    'id' => 3
                ]),
                new Reference([
                    'type' => Reference::TYPE_PASSENGER_REQUEST,
                    'id' => 2
                ]),
            ]
        ]);

        $msg = new AddMultiElements($createPnrOptions);
        $this->assertCount(2, $msg->dataElementsMaster->dataElementsIndiv);
        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_MANUAL_DOCUMENT_REGISTRATION_WITH_ET_NUMBER, $msg->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals('OT', $msg->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->reference->qualifier);
        $this->assertEquals(1, $msg->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->reference->number);
        $this->assertEquals('PAX', $msg->dataElementsMaster->dataElementsIndiv[0]->manualFareDocument->passengerType);
        $this->assertEquals('5118037484', $msg->dataElementsMaster->dataElementsIndiv[0]->manualFareDocument->document->ticketNumber);
        $this->assertEquals('220', $msg->dataElementsMaster->dataElementsIndiv[0]->manualFareDocument->document->companyId);
    }

    public function testCanCreateAirSegment()
    {
        $createPnrOptions = new PnrCreatePnrOptions([
            'receivedFrom' => 'unittest',
            'travellers' => [
                new Traveller([
                    'number' => 1,
                    'lastName' => 'Bowie'
                ])
            ],
            'actionCode' => PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE,
            'itineraries' => [
                new Itinerary([
                    'origin' => 'CDG',
                    'destination' => 'HEL',
                    'segments' => [
                        new Air([
                            'date' => \DateTime::createFromFormat('Y-m-d His', "2013-10-02 000000", new \DateTimeZone('UTC')),
                            'origin' => 'CDG',
                            'destination' => 'HEL',
                            'flightNumber' => '3278',
                            'bookingClass' => 'Y',
                            'company' => '7S'
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new AddMultiElements($createPnrOptions);

        $this->assertCount(1, $msg->originDestinationDetails);
        $this->assertEquals('CDG', $msg->originDestinationDetails[0]->originDestination->origin);
        $this->assertEquals('HEL', $msg->originDestinationDetails[0]->originDestination->destination);

        $this->assertCount(1, $msg->originDestinationDetails[0]->itineraryInfo);
        $this->assertEquals(AddMultiElements\ElementManagementItinerary::SEGMENT_AIR, $msg->originDestinationDetails[0]->itineraryInfo[0]->elementManagementItinerary->segmentName);

        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->freetextItinerary);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->reservationInfoSell);

        $this->assertEquals('CDG', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->boardpointDetail->cityCode);
        $this->assertEquals('HEL', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->offpointDetail->cityCode);
        $this->assertEquals('7S', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->company->identification);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->flightTypeDetails);
        $this->assertEquals('021013', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->product->depDate);
        $this->assertEquals('3278', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->productDetails->identification);
        $this->assertEquals('Y', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->productDetails->classOfService);

        $this->assertEquals(1, $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->relatedProduct->quantity);
        $this->assertEquals(AddMultiElements\RelatedProduct::STATUS_NEED_SEGMENT, $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->relatedProduct->status);
    }

    public function testCanCreateAirSegmentFromBare()
    {
        $createPnrOptions = new PnrAddMultiElementsOptions([
            'receivedFrom' => 'unittest',
            'travellers' => [
                new Traveller([
                    'number' => 1,
                    'lastName' => 'Bowie'
                ])
            ],
            'actionCode' => PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE,
            'itineraries' => [
                new Itinerary([
                    'origin' => 'CDG',
                    'destination' => 'HEL',
                    'segments' => [
                        new Air([
                            'date' => \DateTime::createFromFormat('Y-m-d His', "2013-10-02 000000", new \DateTimeZone('UTC')),
                            'origin' => 'CDG',
                            'destination' => 'HEL',
                            'flightNumber' => '3278',
                            'bookingClass' => 'Y',
                            'company' => '7S'
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new AddMultiElements($createPnrOptions);

        $this->assertCount(1, $msg->originDestinationDetails);
        $this->assertEquals('CDG', $msg->originDestinationDetails[0]->originDestination->origin);
        $this->assertEquals('HEL', $msg->originDestinationDetails[0]->originDestination->destination);

        $this->assertCount(1, $msg->originDestinationDetails[0]->itineraryInfo);
        $this->assertEquals(AddMultiElements\ElementManagementItinerary::SEGMENT_AIR, $msg->originDestinationDetails[0]->itineraryInfo[0]->elementManagementItinerary->segmentName);

        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->freetextItinerary);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->reservationInfoSell);

        $this->assertEquals('CDG', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->boardpointDetail->cityCode);
        $this->assertEquals('HEL', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->offpointDetail->cityCode);
        $this->assertEquals('7S', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->company->identification);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->flightTypeDetails);
        $this->assertEquals('021013', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->product->depDate);
        $this->assertEquals('3278', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->productDetails->identification);
        $this->assertEquals('Y', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->productDetails->classOfService);

        $this->assertEquals(1, $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->relatedProduct->quantity);
        $this->assertEquals(AddMultiElements\RelatedProduct::STATUS_NEED_SEGMENT, $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->relatedProduct->status);
    }

    public function testCanCreateItineraryWithArnk()
    {
        $createPnrOptions = new PnrAddMultiElementsOptions([
            'receivedFrom' => 'unittest',
            'travellers' => [
                new Traveller([
                    'number' => 1,
                    'lastName' => 'Bowie'
                ])
            ],
            'actionCode' => PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE,
            'itineraries' => [
                new Itinerary([
                    'origin' => 'BRU',
                    'destination' => 'LIS',
                    'segments' => [
                        new Air([
                            'date' => \DateTime::createFromFormat('Y-m-d His', "2008-06-10 000000", new \DateTimeZone('UTC')),
                            'origin' => 'BRU',
                            'destination' => 'LIS',
                            'flightNumber' => '349',
                            'bookingClass' => 'Y',
                            'company' => 'TP'
                        ])
                    ]
                ]),
                new Itinerary([
                    'segments' => [
                        new ArrivalUnknown()
                    ]
                ]),
                new Itinerary([
                    'origin' => 'FAO',
                    'destination' => 'BRU',
                    'segments' => [
                        new Air([
                            'date' => \DateTime::createFromFormat('Y-m-d His', "2008-06-25 000000", new \DateTimeZone('UTC')),
                            'origin' => 'FAO',
                            'destination' => 'BRU',
                            'flightNumber' => '355',
                            'bookingClass' => 'Y',
                            'company' => 'TP'
                        ])
                    ]
                ]),
            ]
        ]);

        $msg = new AddMultiElements($createPnrOptions);

        $this->assertCount(3, $msg->originDestinationDetails);
        $this->assertEquals('BRU', $msg->originDestinationDetails[0]->originDestination->origin);
        $this->assertEquals('LIS', $msg->originDestinationDetails[0]->originDestination->destination);

        $this->assertCount(1, $msg->originDestinationDetails[0]->itineraryInfo);
        $this->assertEquals(AddMultiElements\ElementManagementItinerary::SEGMENT_AIR, $msg->originDestinationDetails[0]->itineraryInfo[0]->elementManagementItinerary->segmentName);

        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->freetextItinerary);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->reservationInfoSell);

        $this->assertEquals('BRU', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->boardpointDetail->cityCode);
        $this->assertEquals('LIS', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->offpointDetail->cityCode);
        $this->assertEquals('TP', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->company->identification);
        $this->assertNull($msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->flightTypeDetails);
        $this->assertEquals('100608', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->product->depDate);
        $this->assertEquals('349', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->productDetails->identification);
        $this->assertEquals('Y', $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->travelProduct->productDetails->classOfService);

        $this->assertEquals(1, $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->relatedProduct->quantity);
        $this->assertEquals(AddMultiElements\RelatedProduct::STATUS_NEED_SEGMENT, $msg->originDestinationDetails[0]->itineraryInfo[0]->airAuxItinerary->relatedProduct->status);

        $this->assertNull($msg->originDestinationDetails[1]->originDestination);

        $this->assertNull($msg->originDestinationDetails[1]->originDestination);
        $this->assertEquals(AddMultiElements\ElementManagementItinerary::SEGMENT_AIR, $msg->originDestinationDetails[1]->itineraryInfo[0]->elementManagementItinerary->segmentName);

        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->airAuxItinerary->freetextItinerary);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->airAuxItinerary->reservationInfoSell);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->airAuxItinerary->selectionDetailsAir);
        $this->assertEquals(0, $msg->originDestinationDetails[1]->itineraryInfo[0]->airAuxItinerary->messageAction->business->function);

        $this->assertEquals('ARNK', $msg->originDestinationDetails[1]->itineraryInfo[0]->airAuxItinerary->travelProduct->productDetails->identification);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->airAuxItinerary->travelProduct->productDetails->classOfService);

        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->airAuxItinerary->travelProduct->boardpointDetail);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->airAuxItinerary->travelProduct->offpointDetail);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->airAuxItinerary->travelProduct->company);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->airAuxItinerary->travelProduct->flightTypeDetails);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->airAuxItinerary->travelProduct->processingIndicator);
        $this->assertNull($msg->originDestinationDetails[1]->itineraryInfo[0]->airAuxItinerary->travelProduct->product);

        $this->assertEquals('FAO', $msg->originDestinationDetails[2]->originDestination->origin);
        $this->assertEquals('BRU', $msg->originDestinationDetails[2]->originDestination->destination);

        $this->assertCount(1, $msg->originDestinationDetails[2]->itineraryInfo);
        $this->assertEquals(AddMultiElements\ElementManagementItinerary::SEGMENT_AIR, $msg->originDestinationDetails[2]->itineraryInfo[0]->elementManagementItinerary->segmentName);

        $this->assertNull($msg->originDestinationDetails[2]->itineraryInfo[0]->airAuxItinerary->freetextItinerary);
        $this->assertNull($msg->originDestinationDetails[2]->itineraryInfo[0]->airAuxItinerary->reservationInfoSell);

        $this->assertEquals('FAO', $msg->originDestinationDetails[2]->itineraryInfo[0]->airAuxItinerary->travelProduct->boardpointDetail->cityCode);
        $this->assertEquals('BRU', $msg->originDestinationDetails[2]->itineraryInfo[0]->airAuxItinerary->travelProduct->offpointDetail->cityCode);
        $this->assertEquals('TP', $msg->originDestinationDetails[2]->itineraryInfo[0]->airAuxItinerary->travelProduct->company->identification);
        $this->assertNull($msg->originDestinationDetails[2]->itineraryInfo[0]->airAuxItinerary->travelProduct->flightTypeDetails);
        $this->assertEquals('250608', $msg->originDestinationDetails[2]->itineraryInfo[0]->airAuxItinerary->travelProduct->product->depDate);
        $this->assertEquals('355', $msg->originDestinationDetails[2]->itineraryInfo[0]->airAuxItinerary->travelProduct->productDetails->identification);
        $this->assertEquals('Y', $msg->originDestinationDetails[2]->itineraryInfo[0]->airAuxItinerary->travelProduct->productDetails->classOfService);

        $this->assertEquals(1, $msg->originDestinationDetails[2]->itineraryInfo[0]->airAuxItinerary->relatedProduct->quantity);
        $this->assertEquals(AddMultiElements\RelatedProduct::STATUS_NEED_SEGMENT, $msg->originDestinationDetails[2]->itineraryInfo[0]->airAuxItinerary->relatedProduct->status);
    }

    public function testCanMakePnrWithMultiPaxElementAssoc()
    {
        $addMultiElementsOptions = new PnrAddMultiElementsOptions([
            'receivedFrom' => 'unittest',
            'travellers' => [
                new Traveller([
                    'number' => 1,
                    'lastName' => 'Bowie',
                    'firstName' => 'David'
                ]),
                new Traveller([
                    'number' => 2,
                    'lastName' => 'Bowie',
                    'firstName' => 'David2'
                ])
            ],
            'actionCode' => PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE,
            'itineraries' => [
                new Itinerary([
                    'origin' => 'BRU',
                    'destination' => 'LIS',
                    'segments' => [
                        new Air([
                            'date' => \DateTime::createFromFormat('Y-m-d His', "2008-06-10 000000", new \DateTimeZone('UTC')),
                            'origin' => 'BRU',
                            'destination' => 'LIS',
                            'flightNumber' => '349',
                            'bookingClass' => 'Y',
                            'company' => 'TP'
                        ])
                    ]
                ])
            ],
            'elements' => [
                new Ticketing([
                    'ticketMode' => Ticketing::TICKETMODE_OK
                ]),
                new Contact([
                    'type' => Contact::TYPE_PHONE_GENERAL,
                    'value' => '+3223456789',
                    'references' => [
                        new Reference([
                            'type' => Reference::TYPE_PASSENGER_REQUEST,
                            'id' => 1
                        ]),
                        new Reference([
                            'type' => Reference::TYPE_PASSENGER_REQUEST,
                            'id' => 2
                        ]),
                    ]
                ])
            ]
        ]);

        $msg = new AddMultiElements($addMultiElementsOptions);

        $this->assertCount(3, $msg->dataElementsMaster->dataElementsIndiv);
        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_TICKETING_ELEMENT, $msg->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals('OT', $msg->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->reference->qualifier);
        $this->assertEquals(2, $msg->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->reference->number);
        $this->assertEquals('OK', $msg->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->indicator);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->date);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->time);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->officeId);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->freetext);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->airlineCode);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->queueCategory);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->queueNumber);
        $this->assertEmpty($msg->dataElementsMaster->dataElementsIndiv[0]->ticketElement->ticket->sitaAddress);
        $this->assertEquals(AddMultiElements\TicketElement::PASSTYPE_PAX, $msg->dataElementsMaster->dataElementsIndiv[0]->ticketElement->passengerType);
        $this->assertNull($msg->dataElementsMaster->dataElementsIndiv[0]->ticketElement->printOptions);

        $this->assertEquals(AddMultiElements\ElementManagementData::SEGNAME_CONTACT_ELEMENT, $msg->dataElementsMaster->dataElementsIndiv[1]->elementManagementData->segmentName);
        $this->assertEquals('OT', $msg->dataElementsMaster->dataElementsIndiv[1]->elementManagementData->reference->qualifier);
        $this->assertEquals(3, $msg->dataElementsMaster->dataElementsIndiv[1]->elementManagementData->reference->number);
        $this->assertCount(2, $msg->dataElementsMaster->dataElementsIndiv[1]->referenceForDataElement->reference);
        $this->assertEquals(1, $msg->dataElementsMaster->dataElementsIndiv[1]->referenceForDataElement->reference[0]->number);
        $this->assertEquals(AddMultiElements\Reference::QUAL_PASSENGER, $msg->dataElementsMaster->dataElementsIndiv[1]->referenceForDataElement->reference[0]->qualifier);
        $this->assertEquals(2, $msg->dataElementsMaster->dataElementsIndiv[1]->referenceForDataElement->reference[1]->number);
        $this->assertEquals(AddMultiElements\Reference::QUAL_PASSENGER, $msg->dataElementsMaster->dataElementsIndiv[1]->referenceForDataElement->reference[1]->qualifier);
    }

    /**
     *
     */
    public function testWillNotAddReceivedFromIfToldToDoSo()
    {
        $addMultiElementsOptions = new PnrAddMultiElementsOptions([
            'autoAddReceivedFrom' => false,
            'defaultReceivedFrom' => 'unittest',
            'travellers' => [
                new Traveller([
                    'number' => 1,
                    'lastName' => 'Bowie',
                    'firstName' => 'David'
                ]),
                new Traveller([
                    'number' => 2,
                    'lastName' => 'Bowie',
                    'firstName' => 'David2'
                ])
            ],
            'actionCode' => PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE,
            'itineraries' => [
                new Itinerary([
                    'origin' => 'BRU',
                    'destination' => 'LIS',
                    'segments' => [
                        new Air([
                            'date' => \DateTime::createFromFormat('Y-m-d His', "2008-06-10 000000", new \DateTimeZone('UTC')),
                            'origin' => 'BRU',
                            'destination' => 'LIS',
                            'flightNumber' => '349',
                            'bookingClass' => 'Y',
                            'company' => 'TP'
                        ])
                    ]
                ])
            ],
            'elements' => [
                new Ticketing([
                    'ticketMode' => Ticketing::TICKETMODE_OK
                ]),
                new Contact([
                    'type' => Contact::TYPE_PHONE_GENERAL,
                    'value' => '+3223456789',
                    'references' => [
                        new Reference([
                            'type' => Reference::TYPE_PASSENGER_REQUEST,
                            'id' => 1
                        ]),
                        new Reference([
                            'type' => Reference::TYPE_PASSENGER_REQUEST,
                            'id' => 2
                        ]),
                    ]
                ])
            ]
        ]);

        $msg = new AddMultiElements($addMultiElementsOptions);

        $this->assertCount(2, $msg->dataElementsMaster->dataElementsIndiv);
    }

    public function testFixScheduleChange(){
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_NO_PROCESSING;
        $createPnrOptions->elements[] = new Element\ScheduleChange([
            'receivedFrom' => 'SCHGTOOL'
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));
    }

    public function testFareMiscellaneousInformationFS(){
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->elements[] = new Element\FareMiscellaneousInformation([
            'passengerType' => Element\FareMiscellaneousInformation::PAXTYPE_PASSENGER,
            'indicator' => Element\FareMiscellaneousInformation::GENERAL_INDICATOR_FS,
            'freeText'  => 'MISC TICKETING INFORMATION'
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));
        $this->assertEquals('OT', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->reference->qualifier);
        $this->assertEquals('S', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->fareElement->generalIndicator);
        $this->assertEquals('PAX', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->fareElement->passengerType);
        $this->assertEquals('MISC TICKETING INFORMATION', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->fareElement->freetextLong);
    }

    public function testMakePnrWithFormOfPaymentCashAndFreeTextNotEmpty()
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
            'type' => FormOfPayment::TYPE_CASH,
            'freeText' => 'TYPE CASH FREE TEXT'
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertInternalType('array', $requestStruct->dataElementsMaster->dataElementsIndiv);
        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));

        $this->assertEquals('FP', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals(AddMultiElements\Fop::IDENT_CASH, $requestStruct->dataElementsMaster->dataElementsIndiv[0]->formOfPayment->fop->identification);
        $this->assertEquals('TYPE CASH FREE TEXT', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->formOfPayment->fop->freetext);
    }

    public function testFareMiscellaneousInformationFE(){
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->elements[] = new Element\FareMiscellaneousInformation([
            'passengerType' => Element\FareMiscellaneousInformation::PAXTYPE_PASSENGER,
            'indicator' => Element\FareMiscellaneousInformation::GENERAL_INDICATOR_FE,
            'freeText'  => 'MISC TICKETING INFORMATION'
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));
        $this->assertEquals('OT', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->reference->qualifier);
        $this->assertEquals('E', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->fareElement->generalIndicator);
        $this->assertEquals('PAX', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->fareElement->passengerType);
        $this->assertEquals('MISC TICKETING INFORMATION', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->fareElement->freetextLong);
    }

    public function testFareMiscellaneousInformationFK(){
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->elements[] = new Element\FareMiscellaneousInformation([
            'passengerType' => Element\FareMiscellaneousInformation::PAXTYPE_PASSENGER,
            'indicator' => Element\FareMiscellaneousInformation::GENERAL_INDICATOR_FK,
            'freeText'  => 'MISC TICKETING INFORMATION'
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));
        $this->assertEquals('OT', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->reference->qualifier);
        $this->assertEquals('K', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->fareElement->generalIndicator);
        $this->assertEquals('PAX', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->fareElement->passengerType);
        $this->assertEquals('MISC TICKETING INFORMATION', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->fareElement->freetextLong);
    }

    public function testFareMiscellaneousInformationFZ(){
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->elements[] = new Element\FareMiscellaneousInformation([
            'passengerType' => Element\FareMiscellaneousInformation::PAXTYPE_PASSENGER,
            'indicator' => Element\FareMiscellaneousInformation::GENERAL_INDICATOR_FZ,
            'freeText'  => 'MISC TICKETING INFORMATION'
        ]);

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));
        $this->assertEquals('OT', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->reference->qualifier);
        $this->assertEquals('Z', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->fareElement->generalIndicator);
        $this->assertEquals('PAX', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->fareElement->passengerType);
        $this->assertEquals('MISC TICKETING INFORMATION', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->fareElement->freetextLong);
    }

    public function testMakePnrWithFormOfPaymentCreditCardWithFreeText()
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
            'creditCardCvcCode' => 123,
            'creditCardHolder' => 'BERNDMUELLER',
            'freeText' => 'CREDIT CARD FREE TEXT'
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
        $this->assertEquals('BERNDMUELLER', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->fopExtension[0]->newFopsDetails->chdData);
        $this->assertEquals('CREDIT CARD FREE TEXT', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->formOfPayment->fop->freetext);
    }

    public function testPnrSecurityES(){
        $createPnrOptions = new PnrCreatePnrOptions();
        $createPnrOptions->receivedFrom = "unittest";
        $createPnrOptions->actionCode = PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE;
        $createPnrOptions->elements[] = new Element\PnrSecurity(
            [
                'identification' => 'WGFD00321',
                'accessMode' => Element\PnrSecurity::ACCESS_MODE_BOTH,
                'indicator' => Element\PnrSecurity::INDICATOR_GLOBAL
            ]
        );

        $requestStruct = new AddMultiElements($createPnrOptions);

        $this->assertEquals(2, count($requestStruct->dataElementsMaster->dataElementsIndiv));
        $this->assertEquals('ES', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->elementManagementData->segmentName);
        $this->assertEquals('WGFD00321', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->pnrSecurity->security[0]->identification);
        $this->assertEquals('B', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->pnrSecurity->security[0]->accessMode);
        $this->assertEquals('G', $requestStruct->dataElementsMaster->dataElementsIndiv[0]->pnrSecurity->indicator);
    }
}

