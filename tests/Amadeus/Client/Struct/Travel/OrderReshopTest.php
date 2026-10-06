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

namespace Test\Amadeus\Client\Struct\Travel;

use Amadeus\Client\RequestOptions\Travel\IdentityDoc;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\AddOfferItems;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\BookingRef;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\DeleteOrderItem;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\FlightRequest;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\OriginDestRequest;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\Pax;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\PaxSegment;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\RepriceOrder;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\ReshopOrder;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\ReuseTicket;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\SelectedOffer;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\SelectedOfferItem;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\SpecificOriginDestRequest;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\UpdatePax;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\UpdatePaxName;
use Amadeus\Client\RequestOptions\Travel\Party;
use Amadeus\Client\RequestOptions\Travel\Sender;
use Amadeus\Client\RequestOptions\Travel\TravelAgency;
use Amadeus\Client\RequestOptions\TravelOrderReshopOptions;
use Amadeus\Client\Struct\Travel\OrderReshop;
use Amadeus\Client\Struct\Travel\OrderReshop\FlightRequest as FlightRequestStruct;
use Amadeus\Client\Struct\Travel\OrderReshop\ReshopOrder as ReshopOrderStruct;
use Amadeus\Client\Struct\Travel\OrderReshop\UpdateOrder as UpdateOrderStruct;
use Test\Amadeus\BaseTestCase;

/**
 * OrderReshopTest
 *
 * @package Test\Amadeus\Client\Struct\Travel
 */
class OrderReshopTest extends BaseTestCase
{
    /**
     * Reprice the entire order (no OrderItemRefID in RepriceOrder), including a
     * Party, an OrderActionContextText and two BookingRefs (one with a TypeCode,
     * one without).
     */
    public function testCanMakeRepriceEntireOrderWithBookingRefsAndParty(): void
    {
        $opt = new TravelOrderReshopOptions([
            'party' => new Party([
                'sender' => new Sender([
                    'travelAgency' => new TravelAgency([
                        'agencyId' => '00012345',
                        'pseudoCityId' => 'BRUXX0000',
                    ]),
                ]),
            ]),
            'orderItemRefId' => 'OITEM-1',
            'orderActionContextText' => 'REA',
            'bookingRefs' => [
                new BookingRef([
                    'bookingId' => 'ABC123',
                    'typeCode' => '6',
                    'bookingEntityAirlineDesigCode' => '6X',
                ]),
                new BookingRef([
                    'bookingId' => 'DEF456',
                    'bookingEntityAirlineDesigCode' => '6X',
                ]),
            ],
            'repriceOrder' => new RepriceOrder([]),
        ]);

        $message = new OrderReshop($opt);

        $this->assertEquals('00012345', $message->Party->Sender->TravelAgency->AgencyID);
        $this->assertEquals('OITEM-1', $message->Request->OrderItemRefID);
        $this->assertEquals('REA', $message->Request->OrderActionContextText);
        $this->assertCount(2, $message->Request->BookingRef);
        $this->assertEquals('ABC123', $message->Request->BookingRef[0]->BookingID);
        $this->assertEquals('6', $message->Request->BookingRef[0]->TypeCode);
        $this->assertEquals('6X', $message->Request->BookingRef[0]->BookingEntity->Carrier->AirlineDesigCode);
        $this->assertEquals('DEF456', $message->Request->BookingRef[1]->BookingID);
        $this->assertNull($message->Request->BookingRef[1]->TypeCode);
        $this->assertNull($message->Request->UpdateOrder->RepriceOrder->OrderItemRefID);
        $this->assertNull($message->Request->UpdateOrder->ReshopOrder);
        $this->assertNull($message->Request->UpdateOrder->ReuseTickets);
    }

    /**
     * Reprice a specific order item (RepriceOrder carries an OrderItemRefID) and
     * no Party / context / bookingRefs (false branches in Request).
     */
    public function testCanMakeRepriceSpecificOrderItem(): void
    {
        $opt = new TravelOrderReshopOptions([
            'orderItemRefId' => 'OITEM-1',
            'repriceOrder' => new RepriceOrder([
                'orderItemRefId' => 'OITEM-2',
            ]),
        ]);

        $message = new OrderReshop($opt);

        $this->assertNull($message->Request->OrderActionContextText);
        $this->assertNull($message->Request->BookingRef);
        $this->assertEquals('OITEM-2', $message->Request->UpdateOrder->RepriceOrder->OrderItemRefID);
        // Party defaults are emitted even when no party is provided.
        $this->assertEquals('unused', $message->Party->Sender->TravelAgency->AgencyID);
    }

    /**
     * Reshop / ServiceOrder with AddOfferItems (full Pax + OriginDestRequest with
     * a DateTime destination date) and a mix of DeleteOrderItems (string + object).
     */
    public function testCanMakeReshopServiceOrderAddOfferItemsOriginDest(): void
    {
        $opt = new TravelOrderReshopOptions([
            'orderItemRefId' => 'OITEM-1',
            'reshopOrder' => new ReshopOrder([
                'addOfferItems' => new AddOfferItems([
                    'paxs' => [
                        new Pax([
                            'paxId' => 'PAX1',
                            'ptc' => 'ADT',
                            'birthdate' => new \DateTime('1990-01-01'),
                            'citizenshipCountryCode' => 'BE',
                            'residenceCountryCode' => 'BE',
                            'givenName' => 'John',
                            'surname' => 'Doe',
                            'titleName' => 'MR',
                            'genderCode' => 'M',
                            'phoneLabel' => 'Home',
                            'phoneNumber' => '+3223456789',
                            'emailLabel' => 'Personal',
                            'email' => 'john.doe@example.com',
                            'identityDocs' => [
                                new IdentityDoc([
                                    'identityDocID' => 'P1234567',
                                    'identityDocTypeCode' => 'PT',
                                    'issuingCountryCode' => 'BE',
                                    'citizenshipCountryCode' => 'BE',
                                    'residenceCountryCode' => 'BE',
                                    'givenName' => 'John',
                                    'issueDate' => new \DateTime('2015-01-01'),
                                    'expiryDate' => new \DateTime('2025-01-01'),
                                    'birthdate' => new \DateTime('1990-01-01'),
                                    'genderCode' => 'M',
                                ]),
                            ],
                        ]),
                    ],
                    'flightRequest' => new FlightRequest([
                        'originDestRequests' => [
                            new OriginDestRequest([
                                'originStationCode' => 'BRU',
                                'originDate' => new \DateTime('2025-06-01'),
                                'destStationCode' => 'JFK',
                                'destDate' => new \DateTime('2025-06-01'),
                            ]),
                        ],
                    ]),
                ]),
                'deleteOrderItems' => [
                    'OITEM-9',
                    new DeleteOrderItem([
                        'orderItemRefId' => 'OITEM-10',
                        'retainServiceId' => ['SVC-1', 'SVC-2'],
                    ]),
                ],
            ]),
        ]);

        $message = new OrderReshop($opt);

        $serviceOrder = $message->Request->UpdateOrder->ReshopOrder->ServiceOrder;

        $pax = $serviceOrder->AddOfferItems->Paxs->Pax[0];
        $this->assertEquals('PAX1', $pax->PaxID);
        $this->assertEquals('ADT', $pax->PTC);
        $this->assertEquals('1990-01-01', $pax->Birthdate);
        $this->assertEquals('BE', $pax->CitizenshipCountryCode);
        $this->assertEquals('BE', $pax->ResidenceCountryCode);
        $this->assertEquals('+3223456789', $pax->ContactInfo->Phone->PhoneNumberText);
        $this->assertEquals('Home', $pax->ContactInfo->Phone->LabelText);
        $this->assertEquals('john.doe@example.com', $pax->ContactInfo->EmailAddress->EmailAddressText);
        $this->assertEquals('Personal', $pax->ContactInfo->EmailAddress->LabelText);
        $this->assertEquals('MR', $pax->Individual->TitleName);
        $this->assertEquals('John', $pax->Individual->GivenName);
        $this->assertEquals('Doe', $pax->Individual->Surname);
        $this->assertEquals('M', $pax->Individual->GenderCode);
        $this->assertEquals('P1234567', $pax->IdentityDoc[0]->IdentityDocID);
        $this->assertEquals('2015-01-01', $pax->IdentityDoc[0]->IssueDate);
        $this->assertEquals('2025-01-01', $pax->IdentityDoc[0]->ExpiryDate);
        $this->assertEquals('1990-01-01', $pax->IdentityDoc[0]->Birthdate);

        $odr = $serviceOrder->AddOfferItems->FlightRequest->OriginDestRequest[0];
        $this->assertEquals('JFK', $odr->DestArrivalRequest->IATA_LocationCode);
        $this->assertEquals('2025-06-01', $odr->DestArrivalRequest->Date);
        $this->assertEquals('BRU', $odr->OriginDepRequest->IATA_LocationCode);
        $this->assertEquals('2025-06-01', $odr->OriginDepRequest->Date);

        $this->assertEquals('OITEM-9', $serviceOrder->DeleteOrderItem[0]->OrderItemRefID);
        $this->assertNull($serviceOrder->DeleteOrderItem[0]->RetainServiceID);
        $this->assertEquals('OITEM-10', $serviceOrder->DeleteOrderItem[1]->OrderItemRefID);
        $this->assertEquals(['SVC-1', 'SVC-2'], $serviceOrder->DeleteOrderItem[1]->RetainServiceID);
    }

    /**
     * Reshop / ServiceOrder using SpecificOriginDestRequest with two segments:
     * the first fully populated (with DateTimes, PaxSegmentID and RBD), the
     * second minimal (to exercise the null branches). Pax is minimal too.
     */
    public function testCanMakeReshopSpecificOriginDestRequest(): void
    {
        $opt = new TravelOrderReshopOptions([
            'orderItemRefId' => 'OITEM-1',
            'reshopOrder' => new ReshopOrder([
                'addOfferItems' => new AddOfferItems([
                    'paxs' => [
                        new Pax([
                            'paxId' => 'PAX1',
                        ]),
                    ],
                    'flightRequest' => new FlightRequest([
                        'specificOriginDestRequests' => [
                            new SpecificOriginDestRequest([
                                'originStationCode' => 'BRU',
                                'destStationCode' => 'JFK',
                                'paxJourneyId' => 'J1',
                                'paxSegments' => [
                                    new PaxSegment([
                                        'paxSegmentId' => 'SEG1',
                                        'departureLocationCode' => 'BRU',
                                        'departureDateTime' => new \DateTime('2025-06-01 10:00:00'),
                                        'arrivalLocationCode' => 'JFK',
                                        'arrivalDateTime' => new \DateTime('2025-06-01 13:00:00'),
                                        'marketingCarrierCode' => '6X',
                                        'marketingFlightNumber' => '100',
                                        'rbdCode' => 'Y',
                                    ]),
                                    new PaxSegment([
                                        'departureLocationCode' => 'JFK',
                                        'arrivalLocationCode' => 'BRU',
                                        'marketingCarrierCode' => '6X',
                                        'marketingFlightNumber' => '101',
                                    ]),
                                ],
                            ]),
                        ],
                    ]),
                ]),
            ]),
        ]);

        $message = new OrderReshop($opt);

        $sodr = $message->Request->UpdateOrder->ReshopOrder->ServiceOrder
            ->AddOfferItems->FlightRequest->SpecificOriginDestRequest[0];

        $this->assertEquals('BRU', $sodr->OriginStationCode);
        $this->assertEquals('JFK', $sodr->DestStationCode);
        $this->assertEquals('J1', $sodr->PaxJourney->PaxJourneyID);

        $seg1 = $sodr->PaxJourney->PaxSegment[0];
        $this->assertEquals('SEG1', $seg1->PaxSegmentID);
        $this->assertEquals('BRU', $seg1->Departure->IATA_LocationCode);
        $this->assertEquals('2025-06-01T10:00:00', $seg1->Departure->AircraftScheduledDateTime);
        $this->assertEquals('JFK', $seg1->Arrival->IATA_LocationCode);
        $this->assertEquals('2025-06-01T13:00:00', $seg1->Arrival->AircraftScheduledDateTime);
        $this->assertEquals('6X', $seg1->MarketingCarrierInfo->CarrierDesigCode);
        $this->assertEquals('100', $seg1->MarketingCarrierInfo->MarketingCarrierFlightNumberText);
        $this->assertEquals('Y', $seg1->MarketingCarrierInfo->RBD_Code);

        $seg2 = $sodr->PaxJourney->PaxSegment[1];
        $this->assertNull($seg2->PaxSegmentID);
        $this->assertNull($seg2->Departure->AircraftScheduledDateTime);
        $this->assertNull($seg2->MarketingCarrierInfo->RBD_Code);

        // Minimal Pax: only PaxID emitted.
        $pax = $message->Request->UpdateOrder->ReshopOrder->ServiceOrder->AddOfferItems->Paxs->Pax[0];
        $this->assertEquals('PAX1', $pax->PaxID);
        $this->assertNull($pax->PTC);
        $this->assertNull($pax->ContactInfo);
        $this->assertNull($pax->IdentityDoc);
        $this->assertNull($pax->Individual);
    }

    /**
     * FlightRequest using SelectedOffer(s), string dates on OriginDep and no
     * destination date (null branch in DestArrivalRequest) via AddOfferItems.
     */
    public function testCanMakeFlightRequestWithSelectedOffers(): void
    {
        $opt = new TravelOrderReshopOptions([
            'orderItemRefId' => 'OITEM-1',
            'reshopOrder' => new ReshopOrder([
                'addOfferItems' => new AddOfferItems([
                    'flightRequest' => new FlightRequest([
                        'selectedOffers' => [
                            new SelectedOffer([
                                'offerRefId' => 'OFFER1',
                                'ownerCode' => '6X',
                                'shoppingResponseRefId' => 'SHOP1',
                                'selectedOfferItems' => [
                                    new SelectedOfferItem([
                                        'offerItemRefId' => 'OITEM-A',
                                        'paxRefId' => ['PAX1'],
                                    ]),
                                ],
                            ]),
                        ],
                    ]),
                ]),
            ]),
        ]);

        $message = new OrderReshop($opt);

        $offer = $message->Request->UpdateOrder->ReshopOrder->ServiceOrder
            ->AddOfferItems->FlightRequest->SelectedOffer[0];

        $this->assertEquals('OFFER1', $offer->OfferRefID);
        $this->assertEquals('6X', $offer->OwnerCode);
        $this->assertEquals('SHOP1', $offer->ShoppingResponseRefID);
        $this->assertEquals('OITEM-A', $offer->SelectedOfferItem[0]->OfferItemRefID);
        $this->assertEquals(['PAX1'], $offer->SelectedOfferItem[0]->PaxRefID);
    }

    /**
     * FlightRequest using a bare ShoppingResponse ref id, plus an
     * OriginDestRequest with a string origin date and no destination date.
     */
    public function testCanMakeFlightRequestWithShoppingResponseAndStringDates(): void
    {
        $flightRequest = new FlightRequest([
            'originDestRequests' => [
                new OriginDestRequest([
                    'originStationCode' => 'BRU',
                    'originDate' => '2025-06-01',
                    'destStationCode' => 'JFK',
                ]),
            ],
        ]);

        $struct = new FlightRequestStruct($flightRequest);

        $this->assertEquals('2025-06-01', $struct->OriginDestRequest[0]->OriginDepRequest->Date);
        $this->assertNull($struct->OriginDestRequest[0]->DestArrivalRequest->Date);

        $shoppingResponseRequest = new FlightRequest([
            'shoppingResponseRefId' => 'SHOP-RESP-1',
        ]);

        $shoppingStruct = new FlightRequestStruct($shoppingResponseRequest);

        $this->assertEquals('SHOP-RESP-1', $shoppingStruct->ShoppingResponse->ShoppingResponseID);
        $this->assertNull($shoppingStruct->OriginDestRequest);
    }

    /**
     * Reshop / UpdatePax with a Current and a New passenger.
     */
    public function testCanMakeReshopUpdatePax(): void
    {
        $opt = new TravelOrderReshopOptions([
            'orderItemRefId' => 'OITEM-1',
            'reshopOrder' => new ReshopOrder([
                'updatePax' => [
                    new UpdatePax([
                        'current' => new Pax(['paxId' => 'PAX1', 'givenName' => 'Jon']),
                        'new' => new Pax(['paxId' => 'PAX1', 'givenName' => 'John']),
                    ]),
                ],
            ]),
        ]);

        $message = new OrderReshop($opt);

        $updatePax = $message->Request->UpdateOrder->ReshopOrder->UpdatePax[0];
        $this->assertEquals('PAX1', $updatePax->Current->PaxID);
        $this->assertEquals('Jon', $updatePax->Current->Individual->GivenName);
        $this->assertEquals('John', $updatePax->New->Individual->GivenName);
    }

    /**
     * Reshop / UpdatePaxName fully populated and, separately, minimal.
     */
    public function testCanMakeReshopUpdatePaxName(): void
    {
        $opt = new TravelOrderReshopOptions([
            'orderItemRefId' => 'OITEM-1',
            'reshopOrder' => new ReshopOrder([
                'updatePaxName' => new UpdatePaxName([
                    'paxRefId' => 'PAX1',
                    'titleName' => 'Mr',
                    'givenName' => ['John'],
                    'middleName' => ['Fitzgerald'],
                    'surname' => 'Doe',
                    'suffixName' => 'Jr',
                ]),
            ]),
        ]);

        $message = new OrderReshop($opt);

        $updatePaxName = $message->Request->UpdateOrder->ReshopOrder->UpdatePaxName;
        $this->assertEquals('PAX1', $updatePaxName->PaxRefID);
        $this->assertEquals('Mr', $updatePaxName->TitleName);
        $this->assertEquals(['John'], $updatePaxName->GivenName);
        $this->assertEquals(['Fitzgerald'], $updatePaxName->MiddleName);
        $this->assertEquals('Doe', $updatePaxName->Surname);
        $this->assertEquals('Jr', $updatePaxName->SuffixName);

        $minimal = new TravelOrderReshopOptions([
            'orderItemRefId' => 'OITEM-1',
            'reshopOrder' => new ReshopOrder([
                'updatePaxName' => new UpdatePaxName([
                    'paxRefId' => 'PAX2',
                ]),
            ]),
        ]);

        $minimalMessage = new OrderReshop($minimal);
        $minimalUpdatePaxName = $minimalMessage->Request->UpdateOrder->ReshopOrder->UpdatePaxName;
        $this->assertEquals('PAX2', $minimalUpdatePaxName->PaxRefID);
        $this->assertNull($minimalUpdatePaxName->TitleName);
        $this->assertNull($minimalUpdatePaxName->GivenName);
        $this->assertNull($minimalUpdatePaxName->MiddleName);
        $this->assertNull($minimalUpdatePaxName->Surname);
        $this->assertNull($minimalUpdatePaxName->SuffixName);
    }

    /**
     * ReuseTickets with one ticket carrying a Type and one without.
     */
    public function testCanMakeReuseTickets(): void
    {
        $opt = new TravelOrderReshopOptions([
            'orderItemRefId' => 'OITEM-1',
            'reuseTickets' => [
                new ReuseTicket([
                    'ticketNumber' => '1234567890123',
                    'paxId' => 'PAX1',
                    'type' => 'T',
                ]),
                new ReuseTicket([
                    'ticketNumber' => '1234567890124',
                    'paxId' => 'PAX2',
                ]),
            ],
        ]);

        $message = new OrderReshop($opt);

        $docs = $message->Request->UpdateOrder->ReuseTickets->RetainedTicketDoc;
        $this->assertEquals('1234567890123', $docs[0]->TicketNumber);
        $this->assertEquals('PAX1', $docs[0]->PaxID);
        $this->assertEquals('T', $docs[0]->Type);
        $this->assertEquals('1234567890124', $docs[1]->TicketNumber);
        $this->assertNull($docs[1]->Type);
    }

    /**
     * When no reprice/reshop/reuse action is provided, no UpdateOrder element is
     * emitted and the request only carries the OrderItemRefID context.
     */
    public function testCanMakeContextOnlyRequestWithoutUpdateOrder(): void
    {
        $opt = new TravelOrderReshopOptions([
            'orderItemRefId' => 'OITEM-1',
        ]);

        $message = new OrderReshop($opt);

        $this->assertEquals('OITEM-1', $message->Request->OrderItemRefID);
        $this->assertNull($message->Request->UpdateOrder);
    }

    /**
     * ReshopOrder used with only DeleteOrderItem(s) and no AddOfferItems.
     */
    public function testCanMakeReshopServiceOrderDeleteOnly(): void
    {
        $opt = new TravelOrderReshopOptions([
            'orderItemRefId' => 'OITEM-1',
            'reshopOrder' => new ReshopOrder([
                'deleteOrderItems' => [
                    new DeleteOrderItem([
                        'orderItemRefId' => 'OITEM-99',
                        'retainServiceId' => 'SVC-9',
                    ]),
                ],
            ]),
        ]);

        $message = new OrderReshop($opt);

        $serviceOrder = $message->Request->UpdateOrder->ReshopOrder->ServiceOrder;
        $this->assertNull($serviceOrder->AddOfferItems);
        $this->assertEquals('OITEM-99', $serviceOrder->DeleteOrderItem[0]->OrderItemRefID);
        $this->assertEquals('SVC-9', $serviceOrder->DeleteOrderItem[0]->RetainServiceID);
    }

    /**
     * PaxSegment departure/arrival date-times may also be provided as strings
     * (they are then passed through unchanged).
     */
    public function testPaxSegmentAcceptsStringDateTimes(): void
    {
        $opt = new TravelOrderReshopOptions([
            'orderItemRefId' => 'OITEM-1',
            'reshopOrder' => new ReshopOrder([
                'addOfferItems' => new AddOfferItems([
                    'flightRequest' => new FlightRequest([
                        'specificOriginDestRequests' => [
                            new SpecificOriginDestRequest([
                                'originStationCode' => 'BRU',
                                'destStationCode' => 'JFK',
                                'paxSegments' => [
                                    new PaxSegment([
                                        'departureLocationCode' => 'BRU',
                                        'departureDateTime' => '2025-06-01T10:00:00',
                                        'arrivalLocationCode' => 'JFK',
                                        'arrivalDateTime' => '2025-06-01T13:00:00',
                                        'marketingCarrierCode' => '6X',
                                        'marketingFlightNumber' => '100',
                                    ]),
                                ],
                            ]),
                        ],
                    ]),
                ]),
            ]),
        ]);

        $message = new OrderReshop($opt);

        $seg = $message->Request->UpdateOrder->ReshopOrder->ServiceOrder
            ->AddOfferItems->FlightRequest->SpecificOriginDestRequest[0]->PaxJourney->PaxSegment[0];

        $this->assertEquals('2025-06-01T10:00:00', $seg->Departure->AircraftScheduledDateTime);
        $this->assertEquals('2025-06-01T13:00:00', $seg->Arrival->AircraftScheduledDateTime);
    }

    public function testFlightRequestThrowsWhenNoChoiceProvided(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new FlightRequestStruct(new FlightRequest([]));
    }

    public function testReshopOrderThrowsWhenNoChoiceProvided(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ReshopOrderStruct(new ReshopOrder([]));
    }

    public function testUpdateOrderThrowsWhenNoChoiceProvided(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new UpdateOrderStruct(null, null, null);
    }
}
