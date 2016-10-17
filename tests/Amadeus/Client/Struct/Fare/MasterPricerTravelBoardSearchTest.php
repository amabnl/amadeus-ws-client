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

namespace Test\Amadeus\Client\Struct\Fare;

use Amadeus\Client\RequestOptions\Fare\MPDate;
use Amadeus\Client\RequestOptions\Fare\MPItinerary;
use Amadeus\Client\RequestOptions\Fare\MPLocation;
use Amadeus\Client\RequestOptions\Fare\MPPassenger;
use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
use Amadeus\Client\Struct\Fare\MasterPricer\ArrivalLocalization;
use Amadeus\Client\Struct\Fare\MasterPricer\CabinId;
use Amadeus\Client\Struct\Fare\MasterPricer\CompanyIdentity;
use Amadeus\Client\Struct\Fare\MasterPricer\DepartureLocalization;
use Amadeus\Client\Struct\Fare\MasterPricer\FirstDateTimeDetail;
use Amadeus\Client\Struct\Fare\MasterPricer\FlightDetail;
use Amadeus\Client\Struct\Fare\MasterPricer\Itinerary;
use Amadeus\Client\Struct\Fare\MasterPricer\NumberOfUnit;
use Amadeus\Client\Struct\Fare\MasterPricer\PaxReference;
use Amadeus\Client\Struct\Fare\MasterPricer\PricingTicketing;
use Amadeus\Client\Struct\Fare\MasterPricer\RangeOfDate;
use Amadeus\Client\Struct\Fare\MasterPricer\TimeDetails;
use Amadeus\Client\Struct\Fare\MasterPricer\Traveller;
use Amadeus\Client\Struct\Fare\MasterPricer\UnitNumberDetail;
use Amadeus\Client\Struct\Fare\MasterPricerTravelBoardSearch;
use Amadeus\Client\Struct\PriceXplorer\LocationInfo;
use Test\Amadeus\BaseTestCase;

/**
 * MasterPricerTravelBoardSearch
 *
 * @package Test\Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class MasterPricerTravelBoardSearchTest extends BaseTestCase
{
    public function testCanMakeBaseMasterPricerMessage()
    {
        $opt = new FareMasterPricerTbSearch();
        $opt->nrOfRequestedResults = 200;
        $opt->nrOfRequestedPassengers = 1;
        $opt->passengers[] = new MPPassenger([
            'type' => MPPassenger::TYPE_ADULT,
            'count' => 1
        ]);
        $opt->itinerary[] = new MPItinerary([
            'departureLocation' => new MPLocation(['city' => 'BRU']),
            'arrivalLocation' => new MPLocation(['city' => 'LON']),
            'date' => new MPDate(['date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertInternalType('array', $message->itinerary);
        $this->assertEquals(1, count($message->itinerary));
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\MasterPricer\Itinerary', $message->itinerary[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\MasterPricer\TimeDetails', $message->itinerary[0]->timeDetails);
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\MasterPricer\FirstDateTimeDetail', $message->itinerary[0]->timeDetails->firstDateTimeDetail);
        $this->assertEquals('150117', $message->itinerary[0]->timeDetails->firstDateTimeDetail->date);
        $this->assertNull($message->itinerary[0]->timeDetails->firstDateTimeDetail->time);
        $this->assertNull($message->itinerary[0]->timeDetails->firstDateTimeDetail->timeQualifier);
        $this->assertEquals('BRU', $message->itinerary[0]->departureLocalization->departurePoint->locationId);
        $this->assertEquals('C', $message->itinerary[0]->departureLocalization->departurePoint->airportCityQualifier);
        $this->assertEquals('LON', $message->itinerary[0]->arrivalLocalization->arrivalPointDetails->locationId);
        $this->assertEquals('C', $message->itinerary[0]->arrivalLocalization->arrivalPointDetails->airportCityQualifier);

        $this->assertCount(2, $message->numberOfUnit->unitNumberDetail);
        $this->assertEquals(1, $message->numberOfUnit->unitNumberDetail[0]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_PASS, $message->numberOfUnit->unitNumberDetail[0]->typeOfUnit);
        $this->assertEquals(200, $message->numberOfUnit->unitNumberDetail[1]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_RESULTS, $message->numberOfUnit->unitNumberDetail[1]->typeOfUnit);

        $this->assertCount(1, $message->paxReference);
        $this->assertCount(1, $message->paxReference[0]->ptc);
        $this->assertEquals('ADT', $message->paxReference[0]->ptc[0]);
        $this->assertCount(1, $message->paxReference[0]->traveller);
        $this->assertEquals(1, $message->paxReference[0]->traveller[0]->ref);
        $this->assertNull($message->paxReference[0]->traveller[0]->infantIndicator);
    }

    public function testCanMakeReturnRequest()
    {
        $opt = new FareMasterPricerTbSearch([
            'nrOfRequestedResults' => 30,
            'nrOfRequestedPassengers' => 3,
            'passengers' => [
                new MPPassenger([
                    'type' => MPPassenger::TYPE_ADULT,
                    'count' => 2
                ]),
                new MPPassenger([
                    'type' => MPPassenger::TYPE_CHILD,
                    'count' => 1
                ]),
            ],
            'itinerary' => [
                new MPItinerary([
                    'departureLocation' => new MPLocation(['city' => 'BRU']),
                    'arrivalLocation' => new MPLocation(['city' => 'MAD']),
                    'date' => new MPDate([
                        'dateTime' => new \DateTime('2017-03-05T10:00:00+0000', new \DateTimeZone('UTC')),
                        'timeWindow' => 5,
                        'rangeMode' => MPDate::RANGEMODE_MINUS_PLUS,
                        'range' => 1
                    ])
                ]),
                new MPItinerary([
                    'departureLocation' => new MPLocation(['city' => 'MAD']),
                    'arrivalLocation' => new MPLocation(['city' => 'BRU']),
                    'date' => new MPDate([
                        'dateTime' => new \DateTime('2017-03-12T18:00:00+0000', new \DateTimeZone('UTC')),
                        'timeWindow' => 5,
                        'rangeMode' => MPDate::RANGEMODE_PLUS,
                        'range' => 1
                    ])
                ])
            ]
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertCount(2, $message->numberOfUnit->unitNumberDetail);
        $this->assertEquals(3, $message->numberOfUnit->unitNumberDetail[0]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_PASS, $message->numberOfUnit->unitNumberDetail[0]->typeOfUnit);
        $this->assertEquals(30, $message->numberOfUnit->unitNumberDetail[1]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_RESULTS, $message->numberOfUnit->unitNumberDetail[1]->typeOfUnit);

        $this->assertNull($message->combinationFareFamilies);
        $this->assertNull($message->customerRef);
        $this->assertEmpty($message->fareFamilies);
        $this->assertNull($message->fareOptions);
        $this->assertNull($message->feeOption);
        $this->assertNull($message->formOfPaymentByPassenger);
        $this->assertNull($message->globalOptions);
        $this->assertNull($message->officeIdDetails);
        $this->assertNull($message->priceToBeat);
        $this->assertNull($message->solutionFamily);
        $this->assertNull($message->taxInfo);
        $this->assertNull($message->ticketChangeInfo);
        $this->assertEmpty($message->valueSearch);
        $this->assertNull($message->travelFlightInfo);

        $this->assertCount(2, $message->paxReference);
        $this->assertEquals('ADT', $message->paxReference[0]->ptc[0]);
        $this->assertCount(2, $message->paxReference[0]->traveller);
        $this->assertEquals(1, $message->paxReference[0]->traveller[0]->ref);
        $this->assertEquals(2, $message->paxReference[0]->traveller[1]->ref);

        $this->assertEquals('CH', $message->paxReference[1]->ptc[0]);
        $this->assertCount(1, $message->paxReference[1]->traveller);
        $this->assertEquals(3, $message->paxReference[1]->traveller[0]->ref);

        $this->assertCount(2, $message->itinerary);
        $this->assertEquals('BRU', $message->itinerary[0]->departureLocalization->departurePoint->locationId);
        $this->assertEquals('MAD', $message->itinerary[0]->arrivalLocalization ->arrivalPointDetails->locationId);
        $this->assertEquals('050317', $message->itinerary[0]->timeDetails->firstDateTimeDetail->date);
        $this->assertEquals('1000', $message->itinerary[0]->timeDetails->firstDateTimeDetail->time);
        $this->assertEquals(5, $message->itinerary[0]->timeDetails->firstDateTimeDetail->timeWindow);
        $this->assertEquals(FirstDateTimeDetail::TIMEQUAL_DEPART_FROM, $message->itinerary[0]->timeDetails->firstDateTimeDetail->timeQualifier);
        $this->assertEquals(1, $message->itinerary[0]->timeDetails->rangeOfDate->dayInterval);
        $this->assertEquals(RangeOfDate::RANGEMODE_MINUS_PLUS, $message->itinerary[0]->timeDetails->rangeOfDate->rangeQualifier);

        $this->assertEquals('MAD', $message->itinerary[1]->departureLocalization->departurePoint->locationId);
        $this->assertEquals('BRU', $message->itinerary[1]->arrivalLocalization ->arrivalPointDetails->locationId);
        $this->assertEquals('120317', $message->itinerary[1]->timeDetails->firstDateTimeDetail->date);
        $this->assertEquals('1800', $message->itinerary[1]->timeDetails->firstDateTimeDetail->time);
        $this->assertEquals(5, $message->itinerary[1]->timeDetails->firstDateTimeDetail->timeWindow);
        $this->assertEquals(FirstDateTimeDetail::TIMEQUAL_DEPART_FROM, $message->itinerary[1]->timeDetails->firstDateTimeDetail->timeQualifier);
        $this->assertEquals(1, $message->itinerary[1]->timeDetails->rangeOfDate->dayInterval);
        $this->assertEquals(RangeOfDate::RANGEMODE_PLUS, $message->itinerary[1]->timeDetails->rangeOfDate->rangeQualifier);
    }

    public function testCanMakeMasterPricerMessageWithDeprecatedDateAndTimeParams()
    {
        $opt = new FareMasterPricerTbSearch();
        $opt->nrOfRequestedResults = 200;
        $opt->nrOfRequestedPassengers = 1;
        $opt->passengers[] = new MPPassenger([
            'type' => MPPassenger::TYPE_ADULT,
            'count' => 1
        ]);
        $opt->itinerary[] = new MPItinerary([
            'departureLocation' => new MPLocation(['city' => 'BRU']),
            'arrivalLocation' => new MPLocation(['city' => 'LON']),
            'date' => new MPDate([
                'date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC')),
                'time' => new \DateTime('0000-00-00T15:45:00+0000', new \DateTimeZone('UTC'))
            ])
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertEquals('150117', $message->itinerary[0]->timeDetails->firstDateTimeDetail->date);
        $this->assertEquals('1545', $message->itinerary[0]->timeDetails->firstDateTimeDetail->time);
        $this->assertNull($message->itinerary[0]->timeDetails->firstDateTimeDetail->timeWindow);
        $this->assertEquals(FirstDateTimeDetail::TIMEQUAL_DEPART_FROM, $message->itinerary[0]->timeDetails->firstDateTimeDetail->timeQualifier);
        $this->assertNull($message->itinerary[0]->timeDetails->rangeOfDate);
    }

    public function testCanMakeMasterPricerMessageWithEmptyDateParams()
    {
        $opt = new FareMasterPricerTbSearch();
        $opt->nrOfRequestedResults = 200;
        $opt->nrOfRequestedPassengers = 1;
        $opt->passengers[] = new MPPassenger([
            'type' => MPPassenger::TYPE_ADULT,
            'count' => 1
        ]);
        $opt->itinerary[] = new MPItinerary([
            'departureLocation' => new MPLocation(['city' => 'BRU']),
            'arrivalLocation' => new MPLocation(['city' => 'LON']),
            'date' => new MPDate([
            ])
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertEquals('000000', $message->itinerary[0]->timeDetails->firstDateTimeDetail->date);
        $this->assertNull($message->itinerary[0]->timeDetails->firstDateTimeDetail->time);
        $this->assertNull($message->itinerary[0]->timeDetails->firstDateTimeDetail->timeWindow);
        $this->assertNull($message->itinerary[0]->timeDetails->rangeOfDate);
    }

    public function testCanMakeMasterPricerMessageWithCabinClass()
    {
        $opt = new FareMasterPricerTbSearch();
        $opt->nrOfRequestedResults = 200;
        $opt->nrOfRequestedPassengers = 1;
        $opt->passengers[] = new MPPassenger([
            'type' => MPPassenger::TYPE_ADULT,
            'count' => 1
        ]);
        $opt->itinerary[] = new MPItinerary([
            'departureLocation' => new MPLocation(['city' => 'BRU']),
            'arrivalLocation' => new MPLocation(['city' => 'LON']),
            'date' => new MPDate(['date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
        ]);
        $opt->cabinClass = FareMasterPricerTbSearch::CABIN_ECONOMY_PREMIUM;


        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertEquals(FareMasterPricerTbSearch::CABIN_ECONOMY_PREMIUM, $message->travelFlightInfo->cabinId->cabin);
        $this->assertNull($message->travelFlightInfo->cabinId->cabinQualifier);
    }

    public function testCanMakeMasterPricerMessageWithTicketabilityPreCheck()
    {
        $opt = new FareMasterPricerTbSearch();
        $opt->nrOfRequestedResults = 200;
        $opt->nrOfRequestedPassengers = 1;
        $opt->passengers[] = new MPPassenger([
            'type' => MPPassenger::TYPE_ADULT,
            'count' => 1
        ]);
        $opt->itinerary[] = new MPItinerary([
            'departureLocation' => new MPLocation(['city' => 'BRU']),
            'arrivalLocation' => new MPLocation(['city' => 'LON']),
            'date' => new MPDate(['date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
        ]);
        $opt->doTicketabilityPreCheck = true;

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertEquals(PricingTicketing::PRICETYPE_TICKETABILITY_PRECHECK, $message->fareOptions->pricingTickInfo->pricingTicketing->priceType[0]);
    }

    public function testCanMakeMasterPricerMessageWithCabinClassAndCod()
    {
        $opt = new FareMasterPricerTbSearch();
        $opt->nrOfRequestedResults = 200;
        $opt->nrOfRequestedPassengers = 1;
        $opt->passengers[] = new MPPassenger([
            'type' => MPPassenger::TYPE_ADULT,
            'count' => 1
        ]);
        $opt->itinerary[] = new MPItinerary([
            'departureLocation' => new MPLocation(['city' => 'BRU']),
            'arrivalLocation' => new MPLocation(['city' => 'LON']),
            'date' => new MPDate(['date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
        ]);
        $opt->cabinClass = FareMasterPricerTbSearch::CABIN_ECONOMY_PREMIUM;
        $opt->cabinOption = FareMasterPricerTbSearch::CABINOPT_RECOMMENDED;


        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertEquals(CabinId::CABIN_ECONOMY_PREMIUM, $message->travelFlightInfo->cabinId->cabin);
        $this->assertEquals(CabinId::CABINOPT_RECOMMENDED, $message->travelFlightInfo->cabinId->cabinQualifier);
    }

    public function testCanMakeMasterPricerMessageWithMultiAdultAndInfant()
    {
        $opt = new FareMasterPricerTbSearch();
        $opt->nrOfRequestedResults = 200;
        $opt->nrOfRequestedPassengers = 4;
        $opt->passengers[] = new MPPassenger([
            'type' => MPPassenger::TYPE_ADULT,
            'count' => 2
        ]);
        $opt->passengers[] = new MPPassenger([
            'type' => MPPassenger::TYPE_INFANT,
            'count' => 2
        ]);
        $opt->itinerary[] = new MPItinerary([
            'departureLocation' => new MPLocation(['city' => 'BRU']),
            'arrivalLocation' => new MPLocation(['city' => 'LON']),
            'date' => new MPDate(['date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertEquals('ADT', $message->paxReference[0]->ptc[0]);
        $this->assertNull($message->paxReference[0]->traveller[0]->infantIndicator);
        $this->assertEquals(1, $message->paxReference[0]->traveller[0]->ref);
        $this->assertNull($message->paxReference[0]->traveller[1]->infantIndicator);
        $this->assertEquals(2, $message->paxReference[0]->traveller[1]->ref);

        //Infants have their own numbers
        $this->assertEquals('INF', $message->paxReference[1]->ptc[0]);
        $this->assertEquals(1, $message->paxReference[1]->traveller[0]->ref);
        $this->assertEquals(1, $message->paxReference[1]->traveller[0]->infantIndicator);

        $this->assertEquals(2, $message->paxReference[1]->traveller[1]->ref);
        $this->assertEquals(1, $message->paxReference[1]->traveller[1]->infantIndicator);
    }

    public function testCanMakeMasterPricerMessageWithCityLocationAndGeoCoordinatesAndRadius()
    {
        $opt = new FareMasterPricerTbSearch();
        $opt->nrOfRequestedResults = 200;
        $opt->nrOfRequestedPassengers = 1;
        $opt->passengers[] = new MPPassenger([
            'type' => MPPassenger::TYPE_ADULT,
            'count' => 1
        ]);
        $opt->itinerary[] = new MPItinerary([
            'departureLocation' => new MPLocation([
                'airport' => 'OST',
                'radiusDistance' => 100,
                'radiusUnit' => MPLocation::RADIUSUNIT_KILOMETERS,
            ]),
            'arrivalLocation' => new MPLocation([
                'latitude' => '50.9139922',
                'longitude' => '4.406143'
            ]),
            'date' => new MPDate(['date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertEquals(100, $message->itinerary[0]->departureLocalization->departurePoint->distance);
        $this->assertEquals('K', $message->itinerary[0]->departureLocalization->departurePoint->distanceUnit);
        $this->assertEquals('OST', $message->itinerary[0]->departureLocalization->departurePoint->locationId);
        $this->assertEquals('A', $message->itinerary[0]->departureLocalization->departurePoint->airportCityQualifier);

        $this->assertEquals('50.9139922', $message->itinerary[0]->arrivalLocalization->arrivalPointDetails->latitude);
        $this->assertEquals('4.406143', $message->itinerary[0]->arrivalLocalization->arrivalPointDetails->longitude);
    }

    public function testCanMakeMasterPricerMessageWithDateAndTimeAndTimeWindow()
    {
        $opt = new FareMasterPricerTbSearch();
        $opt->nrOfRequestedResults = 200;
        $opt->nrOfRequestedPassengers = 1;
        $opt->passengers[] = new MPPassenger([
            'type' => MPPassenger::TYPE_ADULT,
            'count' => 1
        ]);
        $opt->itinerary[] = new MPItinerary([
            'departureLocation' => new MPLocation(['city' => 'BRU']),
            'arrivalLocation' => new MPLocation(['city' => 'LON']),
            'date' => new MPDate([
                'date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC')),
                'time' => new \DateTime('2017-01-15T14:00:00+0000', new \DateTimeZone('UTC')),
                'timeWindow' => 3
            ])
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertEquals('150117', $message->itinerary[0]->timeDetails->firstDateTimeDetail->date);
        $this->assertEquals('1400', $message->itinerary[0]->timeDetails->firstDateTimeDetail->time);
        $this->assertEquals(3, $message->itinerary[0]->timeDetails->firstDateTimeDetail->timeWindow);
    }

    public function testCanMakeMasterPricerMessageWithMultiCity()
    {
        $opt = new FareMasterPricerTbSearch();
        $opt->nrOfRequestedResults = 200;
        $opt->nrOfRequestedPassengers = 1;
        $opt->passengers[] = new MPPassenger([
            'type' => MPPassenger::TYPE_ADULT,
            'count' => 1
        ]);
        $opt->itinerary[] = new MPItinerary([
            'departureLocation' => new MPLocation([
                'multiCity' => ['BRU', 'OST']
            ]),
            'arrivalLocation' => new MPLocation([
                'multiCity' => ['LON', 'MAN']
            ]),
            'date' => new MPDate([
                'date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC')),
            ])
        ]);


        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertEquals(2, count($message->itinerary[0]->departureLocalization->depMultiCity));
        $this->assertEquals('BRU', $message->itinerary[0]->departureLocalization->depMultiCity[0]->locationId);
        $this->assertEquals('OST', $message->itinerary[0]->departureLocalization->depMultiCity[1]->locationId);
        $this->assertNull($message->itinerary[0]->departureLocalization->departurePoint);
        $this->assertEquals(2, count($message->itinerary[0]->arrivalLocalization->arrivalMultiCity));
        $this->assertEquals('LON', $message->itinerary[0]->arrivalLocalization->arrivalMultiCity[0]->locationId);
        $this->assertEquals('MAN', $message->itinerary[0]->arrivalLocalization->arrivalMultiCity[1]->locationId);
        $this->assertNull($message->itinerary[0]->arrivalLocalization->arrivalPointDetails);
    }



    public function testCanMakeMessageWithFlightType()
    {
        $opt = new FareMasterPricerTbSearch([
            'nrOfRequestedResults' => 200,
            'nrOfRequestedPassengers' => 1,
            'passengers' => [
                new MPPassenger([
                    'type' => MPPassenger::TYPE_ADULT,
                    'count' => 1
                ])
            ],
            'itinerary' => [
                new MPItinerary([
                    'departureLocation' => new MPLocation(['city' => 'BRU']),
                    'arrivalLocation' => new MPLocation(['city' => 'LON']),
                    'date' => new MPDate(['date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
                ])
            ],
            'requestedFlightTypes' => [
                FareMasterPricerTbSearch::FLIGHTTYPE_DIRECT
            ]
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertInternalType('array', $message->itinerary);
        $this->assertEquals(1, count($message->itinerary));
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\MasterPricer\Itinerary', $message->itinerary[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\MasterPricer\TimeDetails', $message->itinerary[0]->timeDetails);
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\MasterPricer\FirstDateTimeDetail', $message->itinerary[0]->timeDetails->firstDateTimeDetail);
        $this->assertEquals('150117', $message->itinerary[0]->timeDetails->firstDateTimeDetail->date);
        $this->assertNull($message->itinerary[0]->timeDetails->firstDateTimeDetail->time);
        $this->assertEquals('BRU', $message->itinerary[0]->departureLocalization->departurePoint->locationId);
        $this->assertEquals('C', $message->itinerary[0]->departureLocalization->departurePoint->airportCityQualifier);
        $this->assertEquals('LON', $message->itinerary[0]->arrivalLocalization->arrivalPointDetails->locationId);
        $this->assertEquals('C', $message->itinerary[0]->arrivalLocalization->arrivalPointDetails->airportCityQualifier);

        $this->assertCount(1, $message->paxReference);
        $this->assertCount(1, $message->paxReference[0]->ptc);
        $this->assertEquals('ADT', $message->paxReference[0]->ptc[0]);
        $this->assertCount(1, $message->paxReference[0]->traveller);
        $this->assertEquals(1, $message->paxReference[0]->traveller[0]->ref);
        $this->assertNull($message->paxReference[0]->traveller[0]->infantIndicator);

        $this->assertEquals(
            [FlightDetail::FLIGHT_TYPE_DIRECT],
            $message->travelFlightInfo->flightDetail->flightType
        );
    }

    public function testCanMakeMessageWithDateTimeAndDateRange()
    {
        $opt = new FareMasterPricerTbSearch([
            'nrOfRequestedResults' => 200,
            'nrOfRequestedPassengers' => 1,
            'passengers' => [
                new MPPassenger([
                    'type' => MPPassenger::TYPE_ADULT,
                    'count' => 1
                ])
            ],
            'itinerary' => [
                new MPItinerary([
                    'departureLocation' => new MPLocation(['city' => 'BRU']),
                    'arrivalLocation' => new MPLocation(['city' => 'LON']),
                    'date' => new MPDate([
                        'dateTime' => new \DateTime('2017-01-15T14:00:00+0000', new \DateTimeZone('UTC')),
                        'isDeparture' => false,
                        'rangeMode' => MPDate::RANGEMODE_MINUS_PLUS,
                        'range' => 1
                    ])
                ])
            ]
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertInternalType('array', $message->itinerary);
        $this->assertEquals(1, count($message->itinerary));
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\MasterPricer\Itinerary', $message->itinerary[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\MasterPricer\TimeDetails', $message->itinerary[0]->timeDetails);
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\MasterPricer\FirstDateTimeDetail', $message->itinerary[0]->timeDetails->firstDateTimeDetail);
        $this->assertEquals('150117', $message->itinerary[0]->timeDetails->firstDateTimeDetail->date);
        $this->assertEquals('1400', $message->itinerary[0]->timeDetails->firstDateTimeDetail->time);
        $this->assertEquals(FirstDateTimeDetail::TIMEQUAL_ARRIVAL_BY, $message->itinerary[0]->timeDetails->firstDateTimeDetail->timeQualifier);
        $this->assertNull($message->itinerary[0]->timeDetails->firstDateTimeDetail->timeWindow);

        $this->assertEquals(1, $message->itinerary[0]->timeDetails->rangeOfDate->dayInterval);
        $this->assertEquals(RangeOfDate::RANGEMODE_MINUS_PLUS, $message->itinerary[0]->timeDetails->rangeOfDate->rangeQualifier);
        $this->assertNull($message->itinerary[0]->timeDetails->rangeOfDate->timeAtdestination);
    }

    public function testCanMakeMessageWithPreferredAirlines()
    {
        $opt = new FareMasterPricerTbSearch([
            'nrOfRequestedResults' => 200,
            'nrOfRequestedPassengers' => 1,
            'passengers' => [
                new MPPassenger([
                    'type' => MPPassenger::TYPE_ADULT,
                    'count' => 1
                ])
            ],
            'itinerary' => [
                new MPItinerary([
                    'departureLocation' => new MPLocation(['city' => 'BRU']),
                    'arrivalLocation' => new MPLocation(['city' => 'LON']),
                    'date' => new MPDate([
                        'dateTime' => new \DateTime('2017-01-15T14:00:00+0000', new \DateTimeZone('UTC'))
                    ])
                ])
            ],
            'airlineOptions' => [
                FareMasterPricerTbSearch::AIRLINEOPT_PREFERRED => ['BA', 'SN']
            ]
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertInternalType('array', $message->itinerary);
        $this->assertEquals(1, count($message->itinerary));
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\MasterPricer\Itinerary', $message->itinerary[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\MasterPricer\TimeDetails', $message->itinerary[0]->timeDetails);
        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\MasterPricer\FirstDateTimeDetail', $message->itinerary[0]->timeDetails->firstDateTimeDetail);
        $this->assertEquals('150117', $message->itinerary[0]->timeDetails->firstDateTimeDetail->date);
        $this->assertEquals('1400', $message->itinerary[0]->timeDetails->firstDateTimeDetail->time);
        $this->assertEquals(FirstDateTimeDetail::TIMEQUAL_DEPART_FROM, $message->itinerary[0]->timeDetails->firstDateTimeDetail->timeQualifier);
        $this->assertNull($message->itinerary[0]->timeDetails->firstDateTimeDetail->timeWindow);

        $this->assertCount(1, $message->travelFlightInfo->companyIdentity);
        $this->assertEquals(CompanyIdentity::QUAL_PREFERRED, $message->travelFlightInfo->companyIdentity[0]->carrierQualifier);
        $this->assertCount(2, $message->travelFlightInfo->companyIdentity[0]->carrierId);
        $this->assertEquals(['BA', 'SN'], $message->travelFlightInfo->companyIdentity[0]->carrierId);
    }

    public function testCanMakeMessageWithPublishedUnifaresCorporateUnifares()
    {
        $opt = new FareMasterPricerTbSearch([
            'nrOfRequestedResults' => 30,
            'nrOfRequestedPassengers' => 1,
            'passengers' => [
                new MPPassenger([
                    'type' => MPPassenger::TYPE_ADULT,
                    'count' => 1
                ])
            ],
            'itinerary' => [
                new MPItinerary([
                    'departureLocation' => new MPLocation(['city' => 'BER']),
                    'arrivalLocation' => new MPLocation(['city' => 'MOW']),
                    'date' => new MPDate([
                        'dateTime' => new \DateTime('2017-05-01T00:00:00+0000', new \DateTimeZone('UTC'))
                    ])
                ])
            ],
            'flightOptions' => [
                FareMasterPricerTbSearch::FLIGHTOPT_PUBLISHED,
                FareMasterPricerTbSearch::FLIGHTOPT_UNIFARES,
                FareMasterPricerTbSearch::FLIGHTOPT_CORPORATE_UNIFARES,
            ],
            'corporateCodesUnifares' => ['123456']
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertCount(3, $message->fareOptions->pricingTickInfo->pricingTicketing->priceType);
        $this->assertEquals(
            [
                PricingTicketing::PRICETYPE_PUBLISHEDFARES,
                PricingTicketing::PRICETYPE_UNIFARES,
                PricingTicketing::PRICETYPE_CORPORATE_UNIFARES
            ],
            $message->fareOptions->pricingTickInfo->pricingTicketing->priceType
        );
        $this->assertEquals('123456', $message->fareOptions->corporate->corporateId[0]->identity[0]);
    }

    public function testCanMakeMessageWithPriceToBeat()
    {
        $opt = new FareMasterPricerTbSearch([
            'nrOfRequestedResults' => 30,
            'nrOfRequestedPassengers' => 1,
            'passengers' => [
                new MPPassenger([
                    'type' => MPPassenger::TYPE_ADULT,
                    'count' => 1
                ])
            ],
            'itinerary' => [
                new MPItinerary([
                    'departureLocation' => new MPLocation(['city' => 'BER']),
                    'arrivalLocation' => new MPLocation(['city' => 'MOW']),
                    'date' => new MPDate([
                        'dateTime' => new \DateTime('2017-05-01T00:00:00+0000', new \DateTimeZone('UTC'))
                    ])
                ])
            ],
            'priceToBeat' => 500,
            'priceToBeatCurrency' => 'EUR',
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertNull($message->fareOptions);

        $this->assertEquals(500, $message->priceToBeat->moneyInfo->amount);
        $this->assertEquals('EUR', $message->priceToBeat->moneyInfo->currency);
    }
}

