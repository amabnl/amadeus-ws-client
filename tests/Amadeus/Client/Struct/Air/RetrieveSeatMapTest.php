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

namespace Test\Amadeus\Client\Struct\Air;

use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FlightInfo;
use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FrequentFlyer;
use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\Traveller;
use Amadeus\Client\RequestOptions\AirRetrieveSeatMapOptions;
use Amadeus\Client\Struct\Air\RetrieveSeatMap;
use Test\Amadeus\BaseTestCase;

/**
 * RetrieveSeatMapTest
 *
 * @package Test\Amadeus\Client\Struct\Air
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class RetrieveSeatMapTest extends BaseTestCase
{
    public function testCanMakeRequestWithMandatoryParams()
    {
        $par = new AirRetrieveSeatMapOptions([
            'flight' => new FlightInfo([
                'airline' => 'SN',
                'flightNumber' => '652',
                'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2016-05-18 00:00:00', new \DateTimeZone('UTC')),
                'departure' => 'BRU',
                'arrival' => 'LIS'
            ])
        ]);

        $message = new RetrieveSeatMap($par);

        $this->assertEquals('SN', $message->travelProductIdent->companyDetails->marketingCompany);

        $this->assertEquals('652', $message->travelProductIdent->flightIdentification->flightNumber);
        $this->assertNull($message->travelProductIdent->flightIdentification->bookingClass);

        $this->assertEquals('180516', $message->travelProductIdent->flightDate->departureDate);
        $this->assertNull($message->travelProductIdent->flightDate->departureTime);

        $this->assertEquals('BRU', $message->travelProductIdent->boardPointDetails->trueLocationId);

        $this->assertEquals('LIS', $message->travelProductIdent->offpointDetails->trueLocationId);
    }

    public function testCanMakeRequestWithMandatoryParamsAndTime()
    {
        $par = new AirRetrieveSeatMapOptions([
            'flight' => new FlightInfo([
                'airline' => 'SN',
                'flightNumber' => '652',
                'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2016-05-18 14:35:00', new \DateTimeZone('UTC')),
                'departure' => 'BRU',
                'arrival' => 'LIS'
            ])
        ]);

        $message = new RetrieveSeatMap($par);

        $this->assertEquals('SN', $message->travelProductIdent->companyDetails->marketingCompany);

        $this->assertEquals('652', $message->travelProductIdent->flightIdentification->flightNumber);
        $this->assertNull($message->travelProductIdent->flightIdentification->bookingClass);

        $this->assertEquals('180516', $message->travelProductIdent->flightDate->departureDate);
        $this->assertEquals('1435', $message->travelProductIdent->flightDate->departureTime);

        $this->assertEquals('BRU', $message->travelProductIdent->boardPointDetails->trueLocationId);

        $this->assertEquals('LIS', $message->travelProductIdent->offpointDetails->trueLocationId);
    }

    public function testCanMakeRequestWithMandatoryParamsAndBookingClass()
    {
        $par = new AirRetrieveSeatMapOptions([
            'flight' => new FlightInfo([
                'airline' => 'SN',
                'flightNumber' => '652',
                'bookingClass' => 'Y',
                'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2016-05-18 00:00:00', new \DateTimeZone('UTC')),
                'departure' => 'BRU',
                'arrival' => 'LIS'
            ])
        ]);

        $message = new RetrieveSeatMap($par);

        $this->assertEquals('SN', $message->travelProductIdent->companyDetails->marketingCompany);

        $this->assertEquals('652', $message->travelProductIdent->flightIdentification->flightNumber);
        $this->assertEquals('Y', $message->travelProductIdent->flightIdentification->bookingClass);

        $this->assertEquals('180516', $message->travelProductIdent->flightDate->departureDate);
        $this->assertNull($message->travelProductIdent->flightDate->departureTime);

        $this->assertEquals('BRU', $message->travelProductIdent->boardPointDetails->trueLocationId);

        $this->assertEquals('LIS', $message->travelProductIdent->offpointDetails->trueLocationId);
    }

    public function testCanMakeRequestWithMandatoryParamsAndFrequentFlyer()
    {
        $par = new AirRetrieveSeatMapOptions([
            'flight' => new FlightInfo([
                'airline' => 'SN',
                'flightNumber' => '652',
                'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2016-05-18 00:00:00', new \DateTimeZone('UTC')),
                'departure' => 'BRU',
                'arrival' => 'LIS'
            ]),
            'frequentFlyer' => new FrequentFlyer([
                'company' => 'LH',
                'cardNumber' => '4099913025539611',
                'tierLevel' => 1,
            ])
        ]);

        $message = new RetrieveSeatMap($par);

        $this->assertEquals('LH', $message->frequentTravelerInfo->frequentTravellerDetails->carrier);
        $this->assertEquals('4099913025539611', $message->frequentTravelerInfo->frequentTravellerDetails->number);
        $this->assertEquals(1, $message->frequentTravelerInfo->frequentTravellerDetails->tierLevel);
    }

    /**
     * 5.6 Operation: Seat Map with Prices
     *
     * Query: 2 passengers, options for pricing :
     * record locator,
     * conversion into USD,
     * ticket designator for the 1st passenger along with date of birth and fare basis.
     */
    public function testCanMakeRequestWithTravellerInfo()
    {
        $par = new AirRetrieveSeatMapOptions([
            'flight' => new FlightInfo([
                'airline' => 'AF',
                'flightNumber' => '0346',
                'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2015-06-15 00:00:00', new \DateTimeZone('UTC')),
                'departure' => 'CDG',
                'arrival' => 'YUL',
                'bookingClass' => 'Y'
            ]),
            'requestPrices' => true,
            'nrOfPassengers' => 2,
            'bookingStatus' => 'HK',
            'recordLocator' => '7BFHEJ',
            'currency' => 'USD',
            'travellers' => [
                new Traveller([
                    'uniqueId' => 1,
                    'firstName' => 'KENNETH MR',
                    'lastName' => 'NELSON',
                    'type' => Traveller::TYPE_ADULT,
                    'dateOfBirth' => \DateTime::createFromFormat('Y-m-d H:i:s', '1966-04-05 00:00:00', new \DateTimeZone('UTC')), //05041966
                    'passengerTypeCode' => 'MIL',
                    'ticketDesignator' => 'B2BAB2B',
                    'ticketNumber' => '17225466644554',
                    'fareBasisOverride' => 'YIF',
                    'frequentTravellerInfo' => new FrequentFlyer([
                        'company' => 'QF',
                        'cardNumber' => '987654321',
                        'tierLevel' => 'FFBR',
                    ]),
                ]),
                new Traveller([
                    'uniqueId' => 2,
                    'firstName' => 'PHILIP MR',
                    'lastName' => 'NELSON',
                    'type' => Traveller::TYPE_ADULT,
                    'frequentTravellerInfo' => new FrequentFlyer([
                        'company' => 'QF',
                        'cardNumber' => '1234567',
                        'tierLevel' => 'FFSL',
                    ]),
                ]),
            ]
        ]);

        $message = new RetrieveSeatMap($par);


        $this->assertEquals('AF', $message->travelProductIdent->companyDetails->marketingCompany);
        $this->assertEquals('0346', $message->travelProductIdent->flightIdentification->flightNumber);
        $this->assertEquals('Y', $message->travelProductIdent->flightIdentification->bookingClass);
        $this->assertEquals('150615', $message->travelProductIdent->flightDate->departureDate);
        $this->assertNull($message->travelProductIdent->flightDate->departureTime);
        $this->assertEquals('CDG', $message->travelProductIdent->boardPointDetails->trueLocationId);
        $this->assertEquals('YUL', $message->travelProductIdent->offpointDetails->trueLocationId);

        $this->assertEquals(RetrieveSeatMap\SeatRequestParameters::INDICATOR_FARE_TAX_TOTAL, $message->seatRequestParameters->processingIndicator);
        $this->assertNull($message->seatRequestParameters->genericDetails);

        $this->assertEquals(2, $message->productInformation->quantity);
        $this->assertEquals('HK', $message->productInformation->statusCode);

        $this->assertEquals('7BFHEJ', $message->resControlInfo->reservation->controlNumber);
        $this->assertNull($message->resControlInfo->reservation->companyId);
        $this->assertNull($message->resControlInfo->reservation->controlType);
        $this->assertNull($message->resControlInfo->reservation->date);
        $this->assertNull($message->resControlInfo->reservation->time);

        $this->assertEquals('USD', $message->conversionRate->conversionRateDetails->currency);
        $this->assertEquals(RetrieveSeatMap\ConversionRateDetails::TYPE_EQUIVALENT_CONVERSION_CURRENCY, $message->conversionRate->conversionRateDetails->conversionType);

        $this->assertNull($message->additionalInfo);
        $this->assertNull($message->equipmentInformation);
        $this->assertNull($message->processIndicators);
        $this->assertNull($message->suitablePassenger);

        $this->assertCount(2, $message->traveler);

        $this->assertEquals('NELSON', $message->traveler[0]->travelerInformation->paxDetails->surname);
        $this->assertEquals(1, $message->traveler[0]->travelerInformation->paxDetails->quantity);
        $this->assertEquals('KENNETH MR', $message->traveler[0]->travelerInformation->otherPaxDetails[0]->givenName);
        $this->assertEquals('ADT', $message->traveler[0]->travelerInformation->otherPaxDetails[0]->type);
        $this->assertEquals(1, $message->traveler[0]->travelerInformation->otherPaxDetails[0]->uniqueCustomerIdentifier);

        $this->assertCount(1, $message->traveler[0]->frequentTravelerDetails);
        $this->assertEquals('QF', $message->traveler[0]->frequentTravelerDetails[0]->frequentTravellerDetails->carrier);
        $this->assertEquals('987654321', $message->traveler[0]->frequentTravelerDetails[0]->frequentTravellerDetails->number);
        $this->assertEquals('FFBR', $message->traveler[0]->frequentTravelerDetails[0]->frequentTravellerDetails->tierLevel);

        $this->assertEquals('MIL', $message->traveler[0]->fareInfo->valueQualifier);
        $this->assertEquals('B2BAB2B', $message->traveler[0]->fareInfo->rateCategory);

        $this->assertEquals('05041966', $message->traveler[0]->dateAndTimeInfo->dateAndTimeDetails[0]->date);
        $this->assertEquals(RetrieveSeatMap\DateAndTimeDetails::QUAL_DATE_OF_BIRTH, $message->traveler[0]->dateAndTimeInfo->dateAndTimeDetails[0]->qualifier);

        $this->assertEquals('17225466644554', $message->traveler[0]->ticketDetails->documentDetails->number);

        $this->assertEquals('YIF', $message->traveler[0]->fareQualifierDetails->additionalFareDetails->rateClass);
        $this->assertNull($message->traveler[0]->fareQualifierDetails->additionalFareDetails->commodityCategory);

        $this->assertEmpty($message->traveler[0]->customerCharacteristics);

        $this->assertEquals('NELSON', $message->traveler[1]->travelerInformation->paxDetails->surname);
        $this->assertEquals(1, $message->traveler[1]->travelerInformation->paxDetails->quantity);
        $this->assertEquals('PHILIP MR', $message->traveler[1]->travelerInformation->otherPaxDetails[0]->givenName);
        $this->assertEquals('ADT', $message->traveler[1]->travelerInformation->otherPaxDetails[0]->type);
        $this->assertEquals(2, $message->traveler[1]->travelerInformation->otherPaxDetails[0]->uniqueCustomerIdentifier);

        $this->assertCount(1, $message->traveler[1]->frequentTravelerDetails);
        $this->assertEquals('QF', $message->traveler[1]->frequentTravelerDetails[0]->frequentTravellerDetails->carrier);
        $this->assertEquals('1234567', $message->traveler[1]->frequentTravelerDetails[0]->frequentTravellerDetails->number);
        $this->assertEquals('FFSL', $message->traveler[1]->frequentTravelerDetails[0]->frequentTravellerDetails->tierLevel);

        $this->assertNull($message->traveler[1]->fareInfo);
        $this->assertNull($message->traveler[1]->dateAndTimeInfo);
        $this->assertNull($message->traveler[1]->ticketDetails);
        $this->assertNull($message->traveler[1]->fareQualifierDetails);
        $this->assertEmpty($message->traveler[1]->customerCharacteristics);
    }

    public function testCanMakeRequestWithCabinClass()
    {
        $par = new AirRetrieveSeatMapOptions([
            'flight' => new FlightInfo([
                'airline' => 'AF',
                'flightNumber' => '0346',
                'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2015-06-15 00:00:00', new \DateTimeZone('UTC')),
                'departure' => 'CDG',
                'arrival' => 'YUL',
                'bookingClass' => 'Y'
            ]),
            'requestPrices' => true,
            'cabinCode' => 'B'
        ]);

        $message = new RetrieveSeatMap($par);

        $this->assertEquals(RetrieveSeatMap\SeatRequestParameters::INDICATOR_FARE_TAX_TOTAL, $message->seatRequestParameters->processingIndicator);
        $this->assertEquals('B', $message->seatRequestParameters->genericDetails->cabinClassDesignator);
        $this->assertNull($message->seatRequestParameters->genericDetails->cabinClass);
        $this->assertNull($message->seatRequestParameters->genericDetails->compartmentDesignator);
        $this->assertNull($message->seatRequestParameters->genericDetails->noSmokingIndicator);
        $this->assertNull($message->seatRequestParameters->genericDetails->seatCharacteristic);
    }

    public function testCanMakeRequestMostRestrictive()
    {
        $par = new AirRetrieveSeatMapOptions([
                'flight' => new FlightInfo([
                    'departureDate' => \DateTime::createFromFormat('Ymd-His', '20150615-000000', new \DateTimeZone('UTC')),
                    'departure' => 'CDG',
                    'arrival' => 'YUL',
                    'airline' => 'AF',
                    'flightNumber' => '0346',
                    'bookingClass' => 'Y'
            ]),
            'recordLocator' => '7BFHEJ',
            'company' => '1A',
            'date' =>  \DateTime::createFromFormat('Ymd-His', '20150610-000000', new \DateTimeZone('UTC')),
            'mostRestrictive' => true
        ]);

         $message = new RetrieveSeatMap($par);

        $this->assertEquals('7BFHEJ', $message->resControlInfo->reservation->controlNumber);
        $this->assertNull($message->resControlInfo->reservation->controlType);
        $this->assertEquals('1A', $message->resControlInfo->reservation->companyId);
        $this->assertEquals('100615', $message->resControlInfo->reservation->date);
        $this->assertNull($message->resControlInfo->reservation->time);

        $this->assertEquals('MRE', $message->processIndicators->statusInformation[0]->action);
    }

    public function testCanMakeRequestWithAccurateTimeString()
    {
        $par = new AirRetrieveSeatMapOptions([
            'flight' => new FlightInfo([
                'departureDate' => \DateTime::createFromFormat('Ymd-His', '20150615-153500', new \DateTimeZone('UTC')),
                'departure' => 'CDG',
                'arrival' => 'YUL',
                'airline' => 'AF',
                'flightNumber' => '0346',
                'bookingClass' => 'Y'
            ]),
            'recordLocator' => '7BFHEJ',
            'company' => '1A',
            'date' => \DateTime::createFromFormat('Ymd-His', '20150610-153500', new \DateTimeZone('UTC'))
        ]);

         $message = new RetrieveSeatMap($par);

        $this->assertEquals('7BFHEJ', $message->resControlInfo->reservation->controlNumber);
        $this->assertEquals('100615', $message->resControlInfo->reservation->date);
        $this->assertEquals('1535', $message->resControlInfo->reservation->time);
    }
}
