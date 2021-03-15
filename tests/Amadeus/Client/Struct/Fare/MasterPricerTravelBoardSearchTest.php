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

use Amadeus\Client\RequestOptions\Fare\MasterPricer\FeeDetails;
use Amadeus\Client\RequestOptions\Fare\MasterPricer\FFCriteria;
use Amadeus\Client\RequestOptions\Fare\MasterPricer\FFOtherCriteria;
use Amadeus\Client\RequestOptions\Fare\MasterPricer\FormOfPaymentDetails;
use Amadeus\Client\RequestOptions\Fare\MasterPricer\MonetaryDetails;
use Amadeus\Client\RequestOptions\Fare\MasterPricer\MultiTicketWeights;
use Amadeus\Client\RequestOptions\Fare\MPDate;
use Amadeus\Client\RequestOptions\Fare\MPFareFamily;
use Amadeus\Client\RequestOptions\Fare\MPFeeOption;
use Amadeus\Client\RequestOptions\Fare\MPItinerary;
use Amadeus\Client\RequestOptions\Fare\MPLocation;
use Amadeus\Client\RequestOptions\Fare\MPPassenger;
use Amadeus\Client\RequestOptions\Fare\MPFeeId;
use Amadeus\Client\RequestOptions\Fare\MPTicketingPriceScheme;
use Amadeus\Client\RequestOptions\Fare\MPAnchoredSegment;
use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
use Amadeus\Client\Struct\Fare\MasterPricer\BooleanExpression;
use Amadeus\Client\Struct\Fare\MasterPricer\CabinId;
use Amadeus\Client\Struct\Fare\MasterPricer\CompanyIdentity;
use Amadeus\Client\Struct\Fare\MasterPricer\CustomerReferences;
use Amadeus\Client\Struct\Fare\MasterPricer\ExclusionDetail;
use Amadeus\Client\Struct\Fare\MasterPricer\FareFamilyInfo;
use Amadeus\Client\Struct\Fare\MasterPricer\FirstDateTimeDetail;
use Amadeus\Client\Struct\Fare\MasterPricer\FlightDetail;
use Amadeus\Client\Struct\Fare\MasterPricer\InclusionDetail;
use Amadeus\Client\Struct\Fare\MasterPricer\OtherCriteria;
use Amadeus\Client\Struct\Fare\MasterPricer\PricingTicketing;
use Amadeus\Client\Struct\Fare\MasterPricer\RangeOfDate;
use Amadeus\Client\Struct\Fare\MasterPricer\UnitNumberDetail;
use Amadeus\Client\Struct\Fare\MasterPricerTravelBoardSearch;
use Test\Amadeus\BaseTestCase;

/**
 * MasterPricerTravelBoardSearch
 *
 * @package Test\Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
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
            'date' => new MPDate(['dateTime' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertInternalType('array', $message->itinerary);
        $this->assertCount(1, $message->itinerary);
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

        $this->assertEquals(1, $message->itinerary[0]->requestedSegmentRef->segRef);
        $this->assertNull($message->itinerary[0]->requestedSegmentRef->locationForcing);

        $this->assertNull($message->itinerary[0]->flightInfo);
        $this->assertNull($message->itinerary[0]->attributes);
        $this->assertNull($message->itinerary[0]->flightInfoPNR);
        $this->assertNull($message->itinerary[0]->requestedSegmentAction);

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

        $this->assertEmpty($message->buckets);
        $this->assertNull($message->combinationFareFamilies);
        $this->assertNull($message->customerRef);
        $this->assertEmpty($message->fareFamilies);
        $this->assertNull($message->feeOption);
        $this->assertNull($message->formOfPaymentByPassenger);
        $this->assertNull($message->globalOptions);
        $this->assertNull($message->officeIdDetails);
        $this->assertEmpty($message->passengerInfoGrp);
        $this->assertNull($message->priceToBeat);
        $this->assertNull($message->solutionFamily);
        $this->assertNull($message->taxInfo);
        $this->assertNull($message->ticketChangeInfo);
        $this->assertEmpty($message->valueSearch);
        $this->assertNull($message->travelFlightInfo);
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
            'date' => new MPDate(['dateTime' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
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
            'date' => new MPDate(['dateTime' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
        ]);
        $opt->doTicketabilityPreCheck = true;

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertEquals(PricingTicketing::PRICETYPE_TICKETABILITY_PRECHECK, $message->fareOptions->pricingTickInfo->pricingTicketing->priceType[0]);
    }

    public function testCanMakeMasterPricerMessageWithCurrencyOverride()
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
                        'dateTime' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))
                    ])
                ])
            ],
            'currencyOverride' => 'USD'
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertCount(1, $message->fareOptions->pricingTickInfo->pricingTicketing->priceType);
        $this->assertEquals(PricingTicketing::PRICETYPE_OVERRIDE_CURRENCY_CONVERSION, $message->fareOptions->pricingTickInfo->pricingTicketing->priceType[0]);
        $this->assertCount(1, $message->fareOptions->conversionRate->conversionRateDetail);
        $this->assertEquals('USD', $message->fareOptions->conversionRate->conversionRateDetail[0]->currency);
        $this->assertNull($message->fareOptions->conversionRate->conversionRateDetail[0]->conversionType);
    }

    public function testCanMakeMasterPricerMessageWithFeeIds()
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
                        'dateTime' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))
                    ])
                ])
            ],
            'feeIds' => [
                new MPFeeId(['type' => 'FFI', 'number' => 2]),
                new MPFeeId(['type' => 'UPH', 'number' => 6])
            ]
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertCount(2, $message->fareOptions->feeIdDescription->feeId);
        $this->assertEquals('FFI', $message->fareOptions->feeIdDescription->feeId[0]->feeType);
        $this->assertEquals(2, $message->fareOptions->feeIdDescription->feeId[0]->feeIdNumber);
        $this->assertEquals('UPH', $message->fareOptions->feeIdDescription->feeId[1]->feeType);
        $this->assertEquals(6, $message->fareOptions->feeIdDescription->feeId[1]->feeIdNumber);
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
            'date' => new MPDate(['dateTime' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
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
            'date' => new MPDate(['dateTime' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
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
            'date' => new MPDate(['dateTime' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
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

    public function testCanMakeMasterPricerMessageWithFeeOption()
    {
        $opt            = new FareMasterPricerTbSearch();
        $opt->feeOption = [
            new MPFeeOption([
                'type'       => MPFeeOption::TYPE_TICKETING_FEES,
                'feeDetails' => [
                    new FeeDetails([
                        'subType'         => FeeDetails::SUB_TYPE_FARE_COMPONENT_AMOUNT,
                        'option'          => FeeDetails::OPTION_MANUALLY_INCLUDED,
                        'monetaryDetails' => [
                            new MonetaryDetails(
                                [
                                    'amount' => 20.00
                                ]
                            )
                        ]
                    ])
                ]
            ])
        ];

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertEquals('OB', $message->feeOption[0]->feeTypeInfo->carrierFeeDetails->type);
        $this->assertEquals('FCA', $message->feeOption[0]->feeDetails[0]->feeInfo->dataTypeInformation->subType);
        $this->assertEquals('IN', $message->feeOption[0]->feeDetails[0]->feeInfo->dataTypeInformation->option);
        $this->assertEquals('C', $message->feeOption[0]->feeDetails[0]->associatedAmounts->monetaryDetails[0]->typeQualifier);
        $this->assertEquals(20.00, $message->feeOption[0]->feeDetails[0]->associatedAmounts->monetaryDetails[0]->amount);
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
                    'date' => new MPDate(['dateTime' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
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
            'corporateCodesUnifares' => ['123456'],
            'corporateQualifier' => FareMasterPricerTbSearch::CORPORATE_QUALIFIER_UNIFARE
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
        $this->assertEquals(FareMasterPricerTbSearch::CORPORATE_QUALIFIER_UNIFARE, $message->fareOptions->corporate->corporateId[0]->corporateQualifier);
    }

    public function testCanMakeMessageWithManyOfficeIDs()
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
            'officeIds' => ['A', 'B']
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertCount(2, $message->officeIdDetails);
        $this->assertEquals('A',
            $message->officeIdDetails[0]->officeIdInformation->officeIdentification->agentSignin
        );
        $this->assertEquals('B',
            $message->officeIdDetails[1]->officeIdInformation->officeIdentification->agentSignin
        );
    }

    /**
     * 5.39 Operation: 04.03 Fare Option - Price To Beat
     */
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

    /**
     * 5.44 Operation: 04.08 Fare Option - My Search - Fare Families
     *
     * The example below illustrates a Lowest Fare request including 6 parameterized fare families
     * defined by many attributes sets, each attribute has many occurrences:
     *
     * Itinerary: Round Trip : NCE-AMS
     * Date: 01OCT09 - 08OCT09
     * 1 ADT
     * 6 Fare Families
     * 1st Parameterized fare family:
     *
     * name: FFAMILY1
     * ranking 10
     * not combinable (NCO)
     * Attributes Set 1:
     * publishing carrier AF
     * fare basis NAP30
     * Public fare or Atp Nego fare
     * 2nd Parameterized fare family:
     *
     * name: FFAMILY2
     * ranking 50
     * Attributes Set 1:
     * publishing carriers AF or KL
     * fare basis NCD or NRT or NRF or LCO or LCD
     * 3rd Parameterized fare family:
     *
     * FFAMILY3
     * ranking 80
     * Attributes Set 1:
     * publishing carrier AF
     * Corporate Fares
     * Cabin Y
     * Attributes Set 2:
     * publishing carrier AF
     * Non-Corporate Fares
     * Cabin Y or C
     * Expanded Parameter NAP (Fares with no advance purchase)
     * Expanded Parameter NPE (Fares with no penalty)
     * Attributes Set 3:
     * publishing carrier KL
     * Cabin M, W, C
     * 4th Parameterized fare family:
     *
     * FFAMILY4
     * ranking 60
     * Attributes Set 1:
     * publishing carrier AF
     * fare basis NCD
     * Attributes Set 2:
     * publishing carriers AF,KL
     * fare basis NRT
     * Attributes Set 3:
     * publishing carrier KL
     * any fare basis including JUNIOR
     * 5th Parameterized fare family:
     *
     * name: FFAMILY5
     * ranking 100
     * Attributes Set 1:
     * Booking code L, M, N, O, P, Q, R, S, T or U
     * 6th Parameterized fare family:
     *
     * OTHERS
     * Ranking 0
     */
    public function testCanMakeMassageWithParametrizedFareFamilies()
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
                    'departureLocation' => new MPLocation(['city' => 'NCE']),
                    'arrivalLocation' => new MPLocation(['city' => 'AMS']),
                    'date' => new MPDate(['dateTime' => new \DateTime('2009-10-01T00:00:00+0000', new \DateTimeZone('UTC'))])
                ]),
                new MPItinerary([
                    'departureLocation' => new MPLocation(['city' => 'AMS']),
                    'arrivalLocation' => new MPLocation(['city' => 'NCE']),
                    'date' => new MPDate(['dateTime' => new \DateTime('2009-10-08T00:00:00+0000', new \DateTimeZone('UTC'))])
                ])
            ],
            'flightOptions' => [
                FareMasterPricerTbSearch::FLIGHTOPT_PUBLISHED,
                FareMasterPricerTbSearch::FLIGHTOPT_UNIFARES,
                FareMasterPricerTbSearch::FLIGHTOPT_CORPORATE_UNIFARES,
            ],
            'corporateCodesUnifares' => ['000001'],
            'fareFamilies' => [
                new MPFareFamily([
                    'name' => 'FFAMILY1',
                    'ranking' => 10,
                    'criteria' => new FFCriteria([
                        'combinable' => false,
                        'carriers' => ['AF'],
                        'fareBasis' => ['NAP30'],
                        'fareType' => [
                            FFCriteria::FARETYPE_PUBLISHED_FARES,
                            FFCriteria::FARETYPE_ATPCO_NEGO_FARES_CAT35
                        ]
                    ])
                ]),
                new MPFareFamily([
                    'name' => 'FFAMILY2',
                    'ranking' => 50,
                    'criteria' => new FFCriteria([
                        'carriers' => ['AF', 'KL'],
                        'fareBasis' => ['NCD', 'NRT', 'NRF', 'LCO', 'LCD']
                    ])
                ]),
                new MPFareFamily([
                    'name' => 'FFAMILY3',
                    'ranking' => 80,
                    'criteria' => new FFCriteria([
                        'carriers' => ['AF'],
                        'corporateCodes' => ['CORP'],
                        'cabins' => ['Y']
                    ]),
                    'otherCriteria' => [
                        new FFOtherCriteria([
                            'criteria' => new FFCriteria([
                                'carriers' => ['AF'],
                                'corporateCodes' => ['NONCORP'],
                                'cabins' => ['Y', 'C'],
                                'expandedParameters' => ['NAP', 'NPE']
                            ])
                        ]),
                        new FFOtherCriteria([
                            'criteria' => new FFCriteria([
                                'carriers' => ['KL'],
                                'cabins' => ['M', 'W', 'C']
                            ])
                        ])
                    ]
                ]),
                new MPFareFamily([
                    'name' => 'FFAMILY4',
                    'ranking' => 60,
                    'criteria' => new FFCriteria([
                        'carriers' => ['AF'],
                        'fareBasis' => ['NCD']
                    ]),
                    'otherCriteria' => [
                        new FFOtherCriteria([
                            'criteria' => new FFCriteria([
                                'carriers' => ['AF', 'KL'],
                                'fareBasis' => ['NRT']
                            ])
                        ]),
                        new FFOtherCriteria([
                            'criteria' => new FFCriteria([
                                'carriers' => ['KL'],
                                'fareBasis' => ['-JUNIOR']
                            ])
                        ])
                    ]
                ]),
                new MPFareFamily([
                    'name' => 'FFAMILY5',
                    'ranking' => 100,
                    'criteria' => new FFCriteria([
                        'bookingCode' => ['L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U']
                    ])
                ]),
                new MPFareFamily([
                    'name' => 'OTHERS',
                    'ranking' => '0'
                ])
            ]
        ]);


        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertCount(6, $message->fareFamilies);

        $this->assertEquals('FFAMILY1', $message->fareFamilies[0]->familyInformation->fareFamilyname);
        $this->assertEquals(10, $message->fareFamilies[0]->familyInformation->hierarchy);
        $this->assertEquals(FareFamilyInfo::QUAL_NON_COMBINABLE_FARE_FAMILY, $message->fareFamilies[0]->familyCriteria->fareFamilyInfo->fareFamilyQual);
        $this->assertCount(0, $message->fareFamilies[0]->familyCriteria->corporateInfo);
        $this->assertCount(1, $message->fareFamilies[0]->familyCriteria->carrierId);
        $this->assertEquals('AF', $message->fareFamilies[0]->familyCriteria->carrierId[0]);
        $this->assertCount(3, $message->fareFamilies[0]->familyCriteria->fareProductDetail);
        $this->assertEquals('NAP30', $message->fareFamilies[0]->familyCriteria->fareProductDetail[0]->fareBasis);
        $this->assertEmpty($message->fareFamilies[0]->familyCriteria->fareProductDetail[0]->fareType);
        $this->assertEquals('RP', $message->fareFamilies[0]->familyCriteria->fareProductDetail[1]->fareType[0]);
        $this->assertNull($message->fareFamilies[0]->familyCriteria->fareProductDetail[1]->fareBasis);
        $this->assertEquals('RA', $message->fareFamilies[0]->familyCriteria->fareProductDetail[2]->fareType[0]);
        $this->assertNull($message->fareFamilies[0]->familyCriteria->fareProductDetail[2]->fareBasis);
        $this->assertNull($message->fareFamilies[0]->familyCriteria->cabinProcessingIdentifier);
        $this->assertEmpty($message->fareFamilies[0]->familyCriteria->cabinProduct);
        $this->assertEmpty($message->fareFamilies[0]->familyCriteria->dateTimeDetails);
        $this->assertEmpty($message->fareFamilies[0]->familyCriteria->otherCriteria);
        $this->assertEmpty($message->fareFamilies[0]->familyCriteria->rdb);
        $this->assertEmpty($message->fareFamilies[0]->otherPossibleCriteria);
        $this->assertEmpty($message->fareFamilies[0]->fareFamilySegment);

        $this->assertEquals('FFAMILY2', $message->fareFamilies[1]->familyInformation->fareFamilyname);
        $this->assertEquals(50, $message->fareFamilies[1]->familyInformation->hierarchy);
        $this->assertNull($message->fareFamilies[1]->familyCriteria->fareFamilyInfo);
        $this->assertCount(0, $message->fareFamilies[1]->familyCriteria->corporateInfo);
        $this->assertCount(2, $message->fareFamilies[1]->familyCriteria->carrierId);
        $this->assertEquals('AF', $message->fareFamilies[1]->familyCriteria->carrierId[0]);
        $this->assertEquals('KL', $message->fareFamilies[1]->familyCriteria->carrierId[1]);
        $this->assertCount(5, $message->fareFamilies[1]->familyCriteria->fareProductDetail);
        $this->assertEquals('NCD', $message->fareFamilies[1]->familyCriteria->fareProductDetail[0]->fareBasis);
        $this->assertEquals('NRT', $message->fareFamilies[1]->familyCriteria->fareProductDetail[1]->fareBasis);
        $this->assertEquals('NRF', $message->fareFamilies[1]->familyCriteria->fareProductDetail[2]->fareBasis);
        $this->assertEquals('LCO', $message->fareFamilies[1]->familyCriteria->fareProductDetail[3]->fareBasis);
        $this->assertEquals('LCD', $message->fareFamilies[1]->familyCriteria->fareProductDetail[4]->fareBasis);
        $this->assertNull($message->fareFamilies[1]->familyCriteria->cabinProcessingIdentifier);
        $this->assertEmpty($message->fareFamilies[1]->familyCriteria->cabinProduct);
        $this->assertEmpty($message->fareFamilies[1]->familyCriteria->dateTimeDetails);
        $this->assertEmpty($message->fareFamilies[1]->familyCriteria->otherCriteria);
        $this->assertEmpty($message->fareFamilies[1]->familyCriteria->rdb);
        $this->assertEmpty($message->fareFamilies[1]->otherPossibleCriteria);
        $this->assertEmpty($message->fareFamilies[1]->fareFamilySegment);

        $this->assertEquals('FFAMILY3', $message->fareFamilies[2]->familyInformation->fareFamilyname);
        $this->assertEquals(80, $message->fareFamilies[2]->familyInformation->hierarchy);
        $this->assertNull($message->fareFamilies[2]->familyCriteria->fareFamilyInfo);
        $this->assertCount(1, $message->fareFamilies[2]->familyCriteria->corporateInfo);
        $this->assertEquals('CORP', $message->fareFamilies[2]->familyCriteria->corporateInfo[0]->corporateNumberIdentifier);
        $this->assertCount(1, $message->fareFamilies[2]->familyCriteria->carrierId);
        $this->assertEquals('AF', $message->fareFamilies[2]->familyCriteria->carrierId[0]);
        $this->assertCount(1, $message->fareFamilies[2]->familyCriteria->cabinProduct);
        $this->assertEquals('Y', $message->fareFamilies[2]->familyCriteria->cabinProduct[0]->cabinDesignator);
        $this->assertEmpty($message->fareFamilies[2]->familyCriteria->otherCriteria);

        $this->assertCount(2, $message->fareFamilies[2]->otherPossibleCriteria);
        $this->assertEquals(BooleanExpression::CODE_OR_OPERATOR, $message->fareFamilies[2]->otherPossibleCriteria[0]->logicalLink->booleanExpression->codeOperator);
        $this->assertEquals('AF', $message->fareFamilies[2]->otherPossibleCriteria[0]->familyCriteria->carrierId[0]);
        $this->assertEquals('NONCORP', $message->fareFamilies[2]->otherPossibleCriteria[0]->familyCriteria->corporateInfo[0]->corporateNumberIdentifier);
        $this->assertCount(2, $message->fareFamilies[2]->otherPossibleCriteria[0]->familyCriteria->cabinProduct);
        $this->assertEquals('Y', $message->fareFamilies[2]->otherPossibleCriteria[0]->familyCriteria->cabinProduct[0]->cabinDesignator);
        $this->assertEquals('C', $message->fareFamilies[2]->otherPossibleCriteria[0]->familyCriteria->cabinProduct[1]->cabinDesignator);
        $this->assertCount(2, $message->fareFamilies[2]->otherPossibleCriteria[0]->familyCriteria->otherCriteria);
        $this->assertEquals(OtherCriteria::NAME_EXPANDED_PARAMETERS, $message->fareFamilies[2]->otherPossibleCriteria[0]->familyCriteria->otherCriteria[0]->name);
        $this->assertEquals('NAP', $message->fareFamilies[2]->otherPossibleCriteria[0]->familyCriteria->otherCriteria[0]->value);
        $this->assertEquals(OtherCriteria::NAME_EXPANDED_PARAMETERS, $message->fareFamilies[2]->otherPossibleCriteria[0]->familyCriteria->otherCriteria[1]->name);
        $this->assertEquals('NPE', $message->fareFamilies[2]->otherPossibleCriteria[0]->familyCriteria->otherCriteria[1]->value);

        $this->assertEquals(BooleanExpression::CODE_OR_OPERATOR, $message->fareFamilies[2]->otherPossibleCriteria[1]->logicalLink->booleanExpression->codeOperator);
        $this->assertEmpty($message->fareFamilies[2]->otherPossibleCriteria[1]->familyCriteria->corporateInfo);
        $this->assertCount(3, $message->fareFamilies[2]->otherPossibleCriteria[1]->familyCriteria->cabinProduct);
        $this->assertEquals('M', $message->fareFamilies[2]->otherPossibleCriteria[1]->familyCriteria->cabinProduct[0]->cabinDesignator);
        $this->assertEquals('W', $message->fareFamilies[2]->otherPossibleCriteria[1]->familyCriteria->cabinProduct[1]->cabinDesignator);
        $this->assertEquals('C', $message->fareFamilies[2]->otherPossibleCriteria[1]->familyCriteria->cabinProduct[2]->cabinDesignator);
        $this->assertEmpty($message->fareFamilies[2]->otherPossibleCriteria[1]->familyCriteria->otherCriteria);

        $this->assertEmpty($message->fareFamilies[2]->familyCriteria->fareProductDetail);
        $this->assertNull($message->fareFamilies[2]->familyCriteria->cabinProcessingIdentifier);
        $this->assertEmpty($message->fareFamilies[2]->familyCriteria->dateTimeDetails);
        $this->assertEmpty($message->fareFamilies[2]->familyCriteria->rdb);
        $this->assertEmpty($message->fareFamilies[2]->fareFamilySegment);

        $this->assertEquals('FFAMILY4', $message->fareFamilies[3]->familyInformation->fareFamilyname);
        $this->assertEquals(60, $message->fareFamilies[3]->familyInformation->hierarchy);
        $this->assertNull($message->fareFamilies[3]->familyCriteria->fareFamilyInfo);
        $this->assertEmpty($message->fareFamilies[3]->familyCriteria->corporateInfo);
        $this->assertCount(1, $message->fareFamilies[3]->familyCriteria->carrierId);
        $this->assertEquals('AF', $message->fareFamilies[3]->familyCriteria->carrierId[0]);
        $this->assertCount(1, $message->fareFamilies[3]->familyCriteria->fareProductDetail);
        $this->assertEquals('NCD', $message->fareFamilies[3]->familyCriteria->fareProductDetail[0]->fareBasis);
        $this->assertEmpty($message->fareFamilies[3]->familyCriteria->fareProductDetail[0]->fareType);

        $this->assertEmpty($message->fareFamilies[3]->familyCriteria->cabinProduct);
        $this->assertEmpty($message->fareFamilies[3]->familyCriteria->otherCriteria);
        $this->assertNull($message->fareFamilies[3]->familyCriteria->cabinProcessingIdentifier);
        $this->assertEmpty($message->fareFamilies[3]->familyCriteria->dateTimeDetails);
        $this->assertEmpty($message->fareFamilies[3]->familyCriteria->rdb);
        $this->assertEmpty($message->fareFamilies[3]->fareFamilySegment);

        $this->assertCount(2, $message->fareFamilies[3]->otherPossibleCriteria);
        $this->assertEquals(BooleanExpression::CODE_OR_OPERATOR, $message->fareFamilies[3]->otherPossibleCriteria[0]->logicalLink->booleanExpression->codeOperator);
        $this->assertCount(2, $message->fareFamilies[3]->otherPossibleCriteria[0]->familyCriteria->carrierId);
        $this->assertEquals('AF', $message->fareFamilies[3]->otherPossibleCriteria[0]->familyCriteria->carrierId[0]);
        $this->assertEquals('KL', $message->fareFamilies[3]->otherPossibleCriteria[0]->familyCriteria->carrierId[1]);
        $this->assertCount(1, $message->fareFamilies[3]->otherPossibleCriteria[0]->familyCriteria->fareProductDetail);
        $this->assertEquals('NRT', $message->fareFamilies[3]->otherPossibleCriteria[0]->familyCriteria->fareProductDetail[0]->fareBasis);
        $this->assertEmpty($message->fareFamilies[3]->otherPossibleCriteria[0]->familyCriteria->fareProductDetail[0]->fareType);

        $this->assertEmpty($message->fareFamilies[3]->otherPossibleCriteria[0]->familyCriteria->rdb);
        $this->assertEmpty($message->fareFamilies[3]->otherPossibleCriteria[0]->familyCriteria->corporateInfo);
        $this->assertEmpty($message->fareFamilies[3]->otherPossibleCriteria[0]->familyCriteria->cabinProduct);
        $this->assertEmpty($message->fareFamilies[3]->otherPossibleCriteria[0]->familyCriteria->otherCriteria);

        $this->assertEquals(BooleanExpression::CODE_OR_OPERATOR, $message->fareFamilies[3]->otherPossibleCriteria[1]->logicalLink->booleanExpression->codeOperator);
        $this->assertCount(1, $message->fareFamilies[3]->otherPossibleCriteria[1]->familyCriteria->carrierId);
        $this->assertEquals('KL', $message->fareFamilies[3]->otherPossibleCriteria[1]->familyCriteria->carrierId[0]);
        $this->assertCount(1, $message->fareFamilies[3]->otherPossibleCriteria[1]->familyCriteria->fareProductDetail);
        $this->assertEquals('-JUNIOR', $message->fareFamilies[3]->otherPossibleCriteria[1]->familyCriteria->fareProductDetail[0]->fareBasis);
        $this->assertEmpty($message->fareFamilies[3]->otherPossibleCriteria[1]->familyCriteria->fareProductDetail[0]->fareType);

        $this->assertEmpty($message->fareFamilies[3]->otherPossibleCriteria[1]->familyCriteria->rdb);
        $this->assertEmpty($message->fareFamilies[3]->otherPossibleCriteria[1]->familyCriteria->corporateInfo);
        $this->assertEmpty($message->fareFamilies[3]->otherPossibleCriteria[1]->familyCriteria->cabinProduct);
        $this->assertEmpty($message->fareFamilies[3]->otherPossibleCriteria[1]->familyCriteria->otherCriteria);

        $this->assertEquals('FFAMILY5', $message->fareFamilies[4]->familyInformation->fareFamilyname);
        $this->assertEquals(100, $message->fareFamilies[4]->familyInformation->hierarchy);
        $this->assertCount(10, $message->fareFamilies[4]->familyCriteria->rdb);
        $this->assertEquals(['L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U'], $message->fareFamilies[4]->familyCriteria->rdb);
        $this->assertNull($message->fareFamilies[4]->familyCriteria->fareFamilyInfo);
        $this->assertEmpty($message->fareFamilies[4]->familyCriteria->corporateInfo);
        $this->assertEmpty($message->fareFamilies[4]->familyCriteria->carrierId);
        $this->assertEmpty($message->fareFamilies[4]->familyCriteria->fareProductDetail);
        $this->assertEmpty($message->fareFamilies[4]->familyCriteria->cabinProduct);
        $this->assertEmpty($message->fareFamilies[4]->familyCriteria->otherCriteria);
        $this->assertNull($message->fareFamilies[4]->familyCriteria->cabinProcessingIdentifier);
        $this->assertEmpty($message->fareFamilies[4]->familyCriteria->dateTimeDetails);
        $this->assertEmpty($message->fareFamilies[4]->fareFamilySegment);
        $this->assertEmpty($message->fareFamilies[4]->otherPossibleCriteria);

        $this->assertEquals('OTHERS', $message->fareFamilies[5]->familyInformation->fareFamilyname);
        $this->assertEquals(0, $message->fareFamilies[5]->familyInformation->hierarchy);
        $this->assertNull($message->fareFamilies[5]->familyCriteria);
        $this->assertEmpty($message->fareFamilies[5]->fareFamilySegment);
        $this->assertEmpty($message->fareFamilies[5]->otherPossibleCriteria);
    }

    /**
     * 5.62 Operation: 04.26 Fare Option - Alternate Price
     */
    public function testCanMakeMassageWithFareFamiliesAlternatePrice()
    {
        $opt = new FareMasterPricerTbSearch([
            'fareFamilies' => [
                new MPFareFamily([
                    'name' => 'FF1',
                    'ranking' => '20',
                    'criteria' => new FFCriteria([
                        'alternatePrice' => true,
                        'corporateNames' => ['NET', 'PKG']
                    ])
                ]),
                new MPFareFamily([
                    'name' => 'FF2',
                    'ranking' => '10',
                    'criteria' => new FFCriteria([
                        'alternatePrice' => true,
                        'fareType' => [
                            FFCriteria::FARETYPE_ATPCO_PRIVATE_FARES_CAT15,
                            FFCriteria::FARETYPE_PUBLISHED_FARES
                        ]
                    ])
                ])
            ]
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertCount(2, $message->fareFamilies);
        $this->assertEquals('FF1', $message->fareFamilies[0]->familyInformation->fareFamilyname);
        $this->assertEquals(20, $message->fareFamilies[0]->familyInformation->hierarchy);

        $this->assertEquals(FareFamilyInfo::QUAL_ALTERNATE_PRICE, $message->fareFamilies[0]->familyCriteria->fareFamilyInfo->fareFamilyQual);
        $this->assertCount(2, $message->fareFamilies[0]->familyCriteria->corporateInfo);
        $this->assertEquals('NET', $message->fareFamilies[0]->familyCriteria->corporateInfo[0]->corporateName);
        $this->assertNull($message->fareFamilies[0]->familyCriteria->corporateInfo[0]->corporateNumberIdentifier);
        $this->assertEquals('PKG', $message->fareFamilies[0]->familyCriteria->corporateInfo[1]->corporateName);
        $this->assertNull($message->fareFamilies[0]->familyCriteria->corporateInfo[1]->corporateNumberIdentifier);
        $this->assertEmpty($message->fareFamilies[0]->otherPossibleCriteria);
        $this->assertEmpty($message->fareFamilies[0]->fareFamilySegment);

        $this->assertEquals('FF2', $message->fareFamilies[1]->familyInformation->fareFamilyname);
        $this->assertEquals(10, $message->fareFamilies[1]->familyInformation->hierarchy);

        $this->assertEquals(FareFamilyInfo::QUAL_ALTERNATE_PRICE, $message->fareFamilies[1]->familyCriteria->fareFamilyInfo->fareFamilyQual);
        $this->assertCount(0, $message->fareFamilies[1]->familyCriteria->corporateInfo);
        $this->assertCount(2, $message->fareFamilies[1]->familyCriteria->fareProductDetail);

        $this->assertCount(1, $message->fareFamilies[1]->familyCriteria->fareProductDetail[0]->fareType);
        $this->assertEquals('RV', $message->fareFamilies[1]->familyCriteria->fareProductDetail[0]->fareType[0]);
        $this->assertNull($message->fareFamilies[1]->familyCriteria->fareProductDetail[0]->fareBasis);
        $this->assertCount(1, $message->fareFamilies[1]->familyCriteria->fareProductDetail[1]->fareType);
        $this->assertEquals('RP', $message->fareFamilies[1]->familyCriteria->fareProductDetail[1]->fareType[0]);
        $this->assertNull($message->fareFamilies[1]->familyCriteria->fareProductDetail[1]->fareBasis);
        $this->assertEmpty($message->fareFamilies[1]->otherPossibleCriteria);
        $this->assertEmpty($message->fareFamilies[1]->fareFamilySegment);
    }

    /**
     * 5.32 Operation: 02.32 Flight Option - Progressive legs
     */
    public function testCanMakeMessageWithProgressiveLegs()
    {
        $opt = new FareMasterPricerTbSearch([
            'nrOfRequestedPassengers' => 1,
            'passengers' => [
                new MPPassenger([
                    'type' => MPPassenger::TYPE_ADULT,
                    'count' => 1
                ])
            ],
            'flightOptions' => [
                FareMasterPricerTbSearch::FLIGHTOPT_PUBLISHED
            ],
            'itinerary' => [
                new MPItinerary([
                    'departureLocation' => new MPLocation(['city' => 'DEN']),
                    'arrivalLocation' => new MPLocation(['city' => 'LAX']),
                    'date' => new MPDate([
                        'dateTime' => new \DateTime('2015-12-11T00:00:00+0000', new \DateTimeZone('UTC'))
                    ])
                ]),
                new MPItinerary([
                    'departureLocation' => new MPLocation(['city' => 'LAX']),
                    'arrivalLocation' => new MPLocation(['city' => 'BOS']),
                    'date' => new MPDate([
                        'dateTime' => new \DateTime('2015-12-18T00:00:00+0000', new \DateTimeZone('UTC'))
                    ])
                ])
            ],
            'progressiveLegsMin' => 0,
            'progressiveLegsMax' => 1
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertCount(1, $message->numberOfUnit->unitNumberDetail);
        $this->assertEquals(1, $message->numberOfUnit->unitNumberDetail[0]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_PASS, $message->numberOfUnit->unitNumberDetail[0]->typeOfUnit);

        $this->assertCount(1, $message->fareOptions->pricingTickInfo->pricingTicketing->priceType);
        $this->assertEquals(
            [
                PricingTicketing::PRICETYPE_PUBLISHEDFARES
            ],
            $message->fareOptions->pricingTickInfo->pricingTicketing->priceType
        );

        $this->assertCount(2, $message->itinerary);
        $this->assertEquals('DEN', $message->itinerary[0]->departureLocalization->departurePoint->locationId);
        $this->assertEquals('LAX', $message->itinerary[0]->arrivalLocalization ->arrivalPointDetails->locationId);
        $this->assertEquals('111215', $message->itinerary[0]->timeDetails->firstDateTimeDetail->date);
        $this->assertNull($message->itinerary[0]->timeDetails->firstDateTimeDetail->time);
        $this->assertNull($message->itinerary[0]->timeDetails->firstDateTimeDetail->timeWindow);
        $this->assertNull($message->itinerary[0]->timeDetails->firstDateTimeDetail->timeQualifier);
        $this->assertNull($message->itinerary[0]->timeDetails->rangeOfDate);

        $this->assertEquals('LAX', $message->itinerary[1]->departureLocalization->departurePoint->locationId);
        $this->assertEquals('BOS', $message->itinerary[1]->arrivalLocalization ->arrivalPointDetails->locationId);
        $this->assertEquals('181215', $message->itinerary[1]->timeDetails->firstDateTimeDetail->date);
        $this->assertNull($message->itinerary[1]->timeDetails->firstDateTimeDetail->time);
        $this->assertNull($message->itinerary[1]->timeDetails->firstDateTimeDetail->timeWindow);
        $this->assertNull($message->itinerary[1]->timeDetails->firstDateTimeDetail->timeQualifier);
        $this->assertNull($message->itinerary[1]->timeDetails->rangeOfDate);

        $this->assertCount(2, $message->travelFlightInfo->unitNumberDetail);
    }

    /**
     * 5.31 Operation: 02.31 Flight Option - DK number (customer identification)
     */
    public function testCanMakeMessageWithDkNumber()
    {
        $opt = new FareMasterPricerTbSearch([
            'nrOfRequestedPassengers' => 1,
            'passengers' => [
                new MPPassenger([
                    'type' => MPPassenger::TYPE_ADULT,
                    'count' => 1
                ])
            ],
            'itinerary' => [
                new MPItinerary([
                    'departureLocation' => new MPLocation(['city' => 'PAR']),
                    'arrivalLocation' => new MPLocation(['city' => 'PPT']),
                    'date' => new MPDate([
                        'dateTime' => new \DateTime('2012-08-10T00:00:00+0000', new \DateTimeZone('UTC'))
                    ])
                ]),
                new MPItinerary([
                    'departureLocation' => new MPLocation(['city' => 'PPT']),
                    'arrivalLocation' => new MPLocation(['city' => 'PAR']),
                    'date' => new MPDate([
                        'dateTime' => new \DateTime('2012-08-20T00:00:00+0000', new \DateTimeZone('UTC'))
                    ])
                ])
            ],
            'dkNumber' => 'AA1234567890123456789Z01234567890'
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertCount(1, $message->customerRef->customerReferences);
        $this->assertEquals('AA1234567890123456789Z01234567890', $message->customerRef->customerReferences[0]->referenceNumber);
        $this->assertEquals(CustomerReferences::QUAL_AGENCY_GROUPING_ID, $message->customerRef->customerReferences[0]->referenceQualifier);
    }

    /**
     * 5.57 Operation: 04.21 Fare Option - Multi-Ticket - Weighted mode
     */
    public function testCanMakeBaseMasterPricerMessageWithMultiTicket()
    {
        $opt = new FareMasterPricerTbSearch();
        $opt->nrOfRequestedResults = 200;
        $opt->nrOfRequestedPassengers = 1;
        $opt->multiTicket = true;
        $opt->multiTicketWeights = new MultiTicketWeights([
            'oneWayOutbound' => 30,
            'oneWayInbound' => 20,
            'returnTrip' => 50
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertCount(5, $message->numberOfUnit->unitNumberDetail);

        $this->assertEquals(1, $message->numberOfUnit->unitNumberDetail[0]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_PASS, $message->numberOfUnit->unitNumberDetail[0]->typeOfUnit);

        $this->assertEquals(200, $message->numberOfUnit->unitNumberDetail[1]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_RESULTS, $message->numberOfUnit->unitNumberDetail[1]->typeOfUnit);

        $this->assertEquals(30, $message->numberOfUnit->unitNumberDetail[2]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_OUTBOUND_RECOMMENDATION, $message->numberOfUnit->unitNumberDetail[2]->typeOfUnit);

        $this->assertEquals(20, $message->numberOfUnit->unitNumberDetail[3]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_INBOUND_RECOMMENDATION, $message->numberOfUnit->unitNumberDetail[3]->typeOfUnit);

        $this->assertEquals(50, $message->numberOfUnit->unitNumberDetail[4]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_COMPLETE_RECOMMENDATION, $message->numberOfUnit->unitNumberDetail[4]->typeOfUnit);

        $this->assertEquals("MTK", $message->fareOptions->pricingTickInfo->pricingTicketing->priceType[0]);
    }

    /**
     * 5.16 Operation: 02.15 Flight option - Maximum layover per connection
     */
    public function testCanMakeMessageWithLayoverPerConnectionOptions()
    {
        $opt = new FareMasterPricerTbSearch([
            'nrOfRequestedPassengers' => 1,
            'passengers' => [
                new MPPassenger([
                    'type' => MPPassenger::TYPE_ADULT,
                    'count' => 1
                ])
            ],
            'itinerary' => [
                new MPItinerary([
                    'departureLocation' => new MPLocation(['city' => 'IST']),
                    'arrivalLocation' => new MPLocation(['city' => 'LON']),
                    'date' => new MPDate([
                        'dateTime' => new \DateTime('2018-05-05T00:00:00+0000', new \DateTimeZone('UTC'))
                    ])
                ]),
            ],
            'maxLayoverPerConnectionHours' => 2,
            'maxLayoverPerConnectionMinutes' => 30,
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        foreach ($message->travelFlightInfo->unitNumberDetail as $unitNumberDetail) {
            switch ($unitNumberDetail->typeOfUnit) {
                case UnitNumberDetail::TYPE_MAX_LAYOVER_PER_CONNECTION_REQUESTED_SEGMENT_HOURS:
                    $this->assertEquals(2, $unitNumberDetail->numberOfUnits);
                    break;
                case UnitNumberDetail::TYPE_MAX_LAYOVER_PER_CONNECTION_REQUESTED_SEGMENT_MINUTES:
                    $this->assertEquals(30, $unitNumberDetail->numberOfUnits);
                    break;
            }
        }
    }

    /**
     * 5.11 Operation: 02.10 Flight Option - Number of Connections
     */
    public function testCanMakeMessageWithItineraryNumberOfConnections()
    {
        $msg = new MasterPricerTravelBoardSearch(
            new FareMasterPricerTbSearch([
                'nrOfRequestedPassengers' => 1,
                'passengers' => [
                    new MPPassenger([
                        'type' => MPPassenger::TYPE_ADULT,
                        'count' => 1
                    ])
                ],
                'itinerary' => [
                    new MPItinerary([
                        'departureLocation' => new MPLocation(['city' => 'PAR']),
                        'arrivalLocation' => new MPLocation(['city' => 'MIA']),
                        'date' => new MPDate([
                            'dateTime' => new \DateTime('2018-05-05T00:00:00+0000', new \DateTimeZone('UTC'))
                        ]),
                        'nrOfConnections' => 2
                    ]),
                ],
            ])
        );

        $this->assertCount(1, $msg->itinerary);
        $this->assertCount(1, $msg->itinerary[0]->flightInfo->unitNumberDetail);
        $this->assertEquals(2, $msg->itinerary[0]->flightInfo->unitNumberDetail[0]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_NUM_OF_CONNECTIONS_ALLOWED, $msg->itinerary[0]->flightInfo->unitNumberDetail[0]->typeOfUnit);

        $this->assertEmpty($msg->itinerary[0]->flightInfo->exclusionDetail);
        $this->assertEmpty($msg->itinerary[0]->flightInfo->inclusionDetail);
        $this->assertNull($msg->itinerary[0]->flightInfo->cabinId);
        $this->assertEmpty($msg->itinerary[0]->flightInfo->companyIdentity);
        $this->assertNull($msg->itinerary[0]->flightInfo->flightDetail);
    }

    /**
     * 5.21 Operation: 02.21 Flight option - No airport change at requested segment level
     */
    public function testCanMakeMessageWithItineraryNoAirportChange()
    {
        $msg = new MasterPricerTravelBoardSearch(
            new FareMasterPricerTbSearch([
                'nrOfRequestedPassengers' => 1,
                'passengers' => [
                    new MPPassenger([
                        'type' => MPPassenger::TYPE_ADULT,
                        'count' => 1
                    ])
                ],
                'itinerary' => [
                    new MPItinerary([
                        'departureLocation' => new MPLocation(['city' => 'PAR']),
                        'arrivalLocation' => new MPLocation(['city' => 'MIA']),
                        'date' => new MPDate([
                            'dateTime' => new \DateTime('2018-05-05T00:00:00+0000', new \DateTimeZone('UTC'))
                        ]),
                        'noAirportChange' => true
                    ]),
                ],
            ])
        );

        $this->assertCount(1, $msg->itinerary);
        $this->assertCount(1, $msg->itinerary[0]->flightInfo->unitNumberDetail);
        $this->assertEquals(1, $msg->itinerary[0]->flightInfo->unitNumberDetail[0]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_NO_AIRPORT_CHANGE, $msg->itinerary[0]->flightInfo->unitNumberDetail[0]->typeOfUnit);

        $this->assertEmpty($msg->itinerary[0]->flightInfo->exclusionDetail);
        $this->assertEmpty($msg->itinerary[0]->flightInfo->inclusionDetail);
        $this->assertNull($msg->itinerary[0]->flightInfo->cabinId);
        $this->assertEmpty($msg->itinerary[0]->flightInfo->companyIdentity);
        $this->assertNull($msg->itinerary[0]->flightInfo->flightDetail);
    }

    /**
     * 5.4 Operation: 02.03 Flight Option - Connecting Point
     */
    public function testCanMakeMessageWithConnectionPoints()
    {
        $msg = new MasterPricerTravelBoardSearch(
            new FareMasterPricerTbSearch([
                'nrOfRequestedPassengers' => 1,
                'nrOfRequestedResults' => 200,
                'passengers' => [
                    new MPPassenger([
                        'type' => MPPassenger::TYPE_ADULT,
                        'count' => 1
                    ])
                ],
                'itinerary' => [
                    new MPItinerary([
                        'departureLocation' => new MPLocation(['city' => 'PAR']),
                        'arrivalLocation' => new MPLocation(['city' => 'MIA']),
                        'date' => new MPDate([
                            'dateTime' => new \DateTime('2018-05-05T00:00:00+0000', new \DateTimeZone('UTC'))
                        ]),
                        'excludedConnections' => ['LGW']
                    ]),
                    new MPItinerary([
                        'departureLocation' => new MPLocation(['city' => 'MIA']),
                        'arrivalLocation' => new MPLocation(['city' => 'PAR']),
                        'date' => new MPDate([
                            'dateTime' => new \DateTime('2018-05-10T00:00:00+0000', new \DateTimeZone('UTC')),
                        ]),
                        'includedConnections' => ['NYC', 'LON']
                    ]),
                ],
            ])
        );

        $this->assertCount(2, $msg->itinerary);

        $this->assertCount(1, $msg->itinerary[0]->flightInfo->exclusionDetail);

        $this->assertEquals(ExclusionDetail::IDENT_EXCLUDED, $msg->itinerary[0]->flightInfo->exclusionDetail[0]->exclusionIdentifier);
        $this->assertEquals('LGW', $msg->itinerary[0]->flightInfo->exclusionDetail[0]->locationId);
        $this->assertNull($msg->itinerary[0]->flightInfo->exclusionDetail[0]->airportCityQualifier);

        $this->assertEmpty($msg->itinerary[0]->flightInfo->inclusionDetail);
        $this->assertNull($msg->itinerary[0]->flightInfo->cabinId);
        $this->assertEmpty($msg->itinerary[0]->flightInfo->companyIdentity);
        $this->assertNull($msg->itinerary[0]->flightInfo->flightDetail);
        $this->assertEmpty($msg->itinerary[0]->flightInfo->unitNumberDetail);

        $this->assertCount(2, $msg->itinerary[1]->flightInfo->inclusionDetail);

        $this->assertEquals(InclusionDetail::IDENT_MANDATORY, $msg->itinerary[1]->flightInfo->inclusionDetail[0]->inclusionIdentifier);
        $this->assertEquals('NYC', $msg->itinerary[1]->flightInfo->inclusionDetail[0]->locationId);
        $this->assertNull($msg->itinerary[1]->flightInfo->inclusionDetail[0]->airportCityQualifier);
        $this->assertEquals(InclusionDetail::IDENT_MANDATORY, $msg->itinerary[1]->flightInfo->inclusionDetail[1]->inclusionIdentifier);
        $this->assertEquals('LON', $msg->itinerary[1]->flightInfo->inclusionDetail[1]->locationId);
        $this->assertNull($msg->itinerary[1]->flightInfo->inclusionDetail[1]->airportCityQualifier);

        $this->assertEmpty($msg->itinerary[1]->flightInfo->exclusionDetail);
        $this->assertNull($msg->itinerary[1]->flightInfo->cabinId);
        $this->assertEmpty($msg->itinerary[1]->flightInfo->companyIdentity);
        $this->assertNull($msg->itinerary[1]->flightInfo->flightDetail);
        $this->assertEmpty($msg->itinerary[1]->flightInfo->unitNumberDetail);
    }

    /**
     * 5.2 Operation: 02.01 Flight Option - Airline/Alliance (Include/Exclude)
     */
    public function testCanMakeMessageWithAirlinesIncludedExcludedSegmentLevel()
    {
        $msg = new MasterPricerTravelBoardSearch(
            new FareMasterPricerTbSearch([
                'nrOfRequestedPassengers' => 1,
                'nrOfRequestedResults' => 200,
                'passengers' => [
                    new MPPassenger([
                        'type' => MPPassenger::TYPE_ADULT,
                        'count' => 1
                    ])
                ],
                'airlineOptions' => [
                    FareMasterPricerTbSearch::AIRLINEOPT_MANDATORY => [
                        'AF',
                        'YY',
                    ]
                ],
                'itinerary' => [
                    new MPItinerary([
                        'departureLocation' => new MPLocation(['city' => 'PAR']),
                        'arrivalLocation' => new MPLocation(['city' => 'MIA']),
                        'date' => new MPDate([
                            'dateTime' => new \DateTime('2018-05-05T00:00:00+0000', new \DateTimeZone('UTC'))
                        ]),
                        'airlineOptions' => [
                            MPItinerary::AIRLINEOPT_EXCLUDED => ['AA']
                        ]
                    ]),
                    new MPItinerary([
                        'departureLocation' => new MPLocation(['city' => 'MIA']),
                        'arrivalLocation' => new MPLocation(['city' => 'PAR']),
                        'date' => new MPDate([
                            'dateTime' => new \DateTime('2018-05-10T00:00:00+0000', new \DateTimeZone('UTC')),
                        ]),
                        'airlineOptions' => [
                            MPItinerary::AIRLINEOPT_PREFERRED => ['BA']
                        ]
                    ]),
                ],
            ])
        );

        $this->assertCount(1, $msg->travelFlightInfo->companyIdentity);
        $this->assertEquals(CompanyIdentity::QUAL_MANDATORY, $msg->travelFlightInfo->companyIdentity[0]->carrierQualifier);
        $this->assertCount(2, $msg->travelFlightInfo->companyIdentity[0]->carrierId);
        $this->assertEquals(['AF', 'YY'], $msg->travelFlightInfo->companyIdentity[0]->carrierId);

        $this->assertCount(2, $msg->itinerary);

        $this->assertCount(1, $msg->itinerary[0]->flightInfo->companyIdentity);
        $this->assertEquals(CompanyIdentity::QUAL_EXCLUDED, $msg->itinerary[0]->flightInfo->companyIdentity[0]->carrierQualifier);
        $this->assertInternalType('array', $msg->itinerary[0]->flightInfo->companyIdentity[0]->carrierId);
        $this->assertCount(1, $msg->itinerary[0]->flightInfo->companyIdentity[0]->carrierId);
        $this->assertEquals('AA', $msg->itinerary[0]->flightInfo->companyIdentity[0]->carrierId[0]);

        $this->assertEmpty($msg->itinerary[0]->flightInfo->exclusionDetail);
        $this->assertEmpty($msg->itinerary[0]->flightInfo->inclusionDetail);
        $this->assertNull($msg->itinerary[0]->flightInfo->cabinId);
        $this->assertNull($msg->itinerary[0]->flightInfo->flightDetail);
        $this->assertEmpty($msg->itinerary[0]->flightInfo->unitNumberDetail);

        $this->assertCount(1, $msg->itinerary[1]->flightInfo->companyIdentity);
        $this->assertEquals(CompanyIdentity::QUAL_PREFERRED, $msg->itinerary[1]->flightInfo->companyIdentity[0]->carrierQualifier);
        $this->assertInternalType('array', $msg->itinerary[1]->flightInfo->companyIdentity[0]->carrierId);
        $this->assertCount(1, $msg->itinerary[1]->flightInfo->companyIdentity[0]->carrierId);
        $this->assertEquals('BA', $msg->itinerary[1]->flightInfo->companyIdentity[0]->carrierId[0]);

        $this->assertEmpty($msg->itinerary[1]->flightInfo->inclusionDetail);
        $this->assertEmpty($msg->itinerary[1]->flightInfo->exclusionDetail);
        $this->assertNull($msg->itinerary[1]->flightInfo->cabinId);
        $this->assertNull($msg->itinerary[1]->flightInfo->flightDetail);
        $this->assertEmpty($msg->itinerary[1]->flightInfo->unitNumberDetail);
    }

    /**
     * 5.3 Operation: 02.02 Flight Option - Flight Category
     */
    public function testCanMakeMessageWithFlightCategory()
    {
        $msg = new MasterPricerTravelBoardSearch(
            new FareMasterPricerTbSearch([
                'nrOfRequestedPassengers' => 1,
                'nrOfRequestedResults' => 200,
                'passengers' => [
                    new MPPassenger([
                        'type' => MPPassenger::TYPE_ADULT,
                        'count' => 1
                    ])
                ],
                'requestedFlightTypes' => [
                    FareMasterPricerTbSearch::FLIGHTTYPE_NONSTOP,
                    FareMasterPricerTbSearch::FLIGHTTYPE_DIRECT
                ],
                'itinerary' => [
                    new MPItinerary([
                        'departureLocation' => new MPLocation(['city' => 'PAR']),
                        'arrivalLocation' => new MPLocation(['city' => 'MIA']),
                        'date' => new MPDate([
                            'dateTime' => new \DateTime('2018-05-05T00:00:00+0000', new \DateTimeZone('UTC'))
                        ]),
                    ]),
                    new MPItinerary([
                        'departureLocation' => new MPLocation(['city' => 'MIA']),
                        'arrivalLocation' => new MPLocation(['city' => 'NYC']),
                        'date' => new MPDate([
                            'dateTime' => new \DateTime('2018-05-10T00:00:00+0000', new \DateTimeZone('UTC')),
                        ]),
                        'requestedFlightTypes' => [
                            MPItinerary::FLIGHTTYPE_DIRECT
                        ]
                    ]),
                ],
            ])
        );

        $this->assertEquals(
            [
                FlightDetail::FLIGHT_TYPE_NON_STOP,
                FlightDetail::FLIGHT_TYPE_DIRECT,
            ],
            $msg->travelFlightInfo->flightDetail->flightType
        );

        $this->assertCount(2, $msg->itinerary);

        $this->assertNull($msg->itinerary[0]->flightInfo);

        $this->assertInternalType('array', $msg->itinerary[1]->flightInfo->flightDetail->flightType);
        $this->assertCount(1, $msg->itinerary[1]->flightInfo->flightDetail->flightType);
        $this->assertEquals(FlightDetail::FLIGHT_TYPE_DIRECT, $msg->itinerary[1]->flightInfo->flightDetail->flightType[0]);

        $this->assertEmpty($msg->itinerary[1]->flightInfo->companyIdentity);
        $this->assertEmpty($msg->itinerary[1]->flightInfo->inclusionDetail);
        $this->assertEmpty($msg->itinerary[1]->flightInfo->exclusionDetail);
        $this->assertNull($msg->itinerary[1]->flightInfo->cabinId);
        $this->assertEmpty($msg->itinerary[1]->flightInfo->unitNumberDetail);
    }

    /**
     * 5.20 Operation: 02.20 Flight option - No airport change at itinerary level
     */
    public function testCanMakeMessageNoAirportChangeAtItineraryLevel()
    {
        $msg = new MasterPricerTravelBoardSearch(
            new FareMasterPricerTbSearch([
                'nrOfRequestedPassengers' => 1,
                'passengers' => [
                    new MPPassenger([
                        'type' => MPPassenger::TYPE_ADULT,
                        'count' => 1
                    ])
                ],
                'noAirportChange' => true,
                'itinerary' => [
                    new MPItinerary([
                        'departureLocation' => new MPLocation(['city' => 'PAR']),
                        'arrivalLocation' => new MPLocation(['city' => 'MIA']),
                        'date' => new MPDate([
                            'dateTime' => new \DateTime('2018-05-05T00:00:00+0000', new \DateTimeZone('UTC'))
                        ]),
                    ]),
                ],
            ])
        );

        $this->assertCount(1, $msg->itinerary);

        $this->assertCount(1, $msg->travelFlightInfo->unitNumberDetail);
        $this->assertEquals(1, $msg->travelFlightInfo->unitNumberDetail[0]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_NO_AIRPORT_CHANGE, $msg->travelFlightInfo->unitNumberDetail[0]->typeOfUnit);

        $this->assertNull($msg->itinerary[0]->flightInfo);
    }

    /**
     * 5.15 Operation: 02.14 Flight option - Maximum EFT
     */
    public function testCanMakeMessageMaximumElapsedFlyingTime()
    {
        $msg = new MasterPricerTravelBoardSearch(
            new FareMasterPricerTbSearch([
                'nrOfRequestedPassengers' => 1,
                'passengers' => [
                    new MPPassenger([
                        'type' => MPPassenger::TYPE_ADULT,
                        'count' => 1
                    ])
                ],
                'maxElapsedFlyingTime' => 120,
                'itinerary' => [
                    new MPItinerary([
                        'departureLocation' => new MPLocation(['city' => 'PAR']),
                        'arrivalLocation' => new MPLocation(['city' => 'MIA']),
                        'date' => new MPDate([
                            'dateTime' => new \DateTime('2018-05-05T00:00:00+0000', new \DateTimeZone('UTC'))
                        ]),
                    ]),
                ],
            ])
        );

        $this->assertCount(1, $msg->itinerary);

        $this->assertCount(1, $msg->travelFlightInfo->unitNumberDetail);
        $this->assertEquals(120, $msg->travelFlightInfo->unitNumberDetail[0]->numberOfUnits);
        $this->assertEquals(UnitNumberDetail::TYPE_PERCENTAGE_OF_SHORTEST_ELAPSED_FLYING_TIME, $msg->travelFlightInfo->unitNumberDetail[0]->typeOfUnit);

        $this->assertNull($msg->itinerary[0]->flightInfo);
    }

    public function testCanMakeMessageWithTicketingPriceScheme()
    {
        $msg = new MasterPricerTravelBoardSearch(
            new FareMasterPricerTbSearch([
                'nrOfRequestedPassengers' => 1,
                'passengers' => [
                    new MPPassenger([
                        'type' => MPPassenger::TYPE_ADULT,
                        'count' => 1
                    ])
                ],
                'itinerary' => [
                    new MPItinerary([
                        'departureLocation' => new MPLocation(['city' => 'NYC']),
                        'arrivalLocation' => new MPLocation(['city' => 'LAX']),
                        'date' => new MPDate([
                            'dateTime' => new \DateTime('2018-07-05T00:00:00+0000', new \DateTimeZone('UTC'))
                        ]),
                    ]),
                ],
                'ticketingPriceScheme' => new MPTicketingPriceScheme([
                    'referenceNumber' => '00012345'
                ])
            ])
        );

        $this->assertInstanceOf('Amadeus\Client\Struct\Fare\MasterPricer\TicketingPriceScheme', $msg->fareOptions->ticketingPriceScheme);
        $this->assertEquals('00012345', $msg->fareOptions->ticketingPriceScheme->referenceNumber);
    }

    public function testCanSpecifyCabinPerItinerary()
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
            'date' => new MPDate(['dateTime' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))]),
            'cabinClass' => FareMasterPricerTbSearch::CABIN_ECONOMY_PREMIUM
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertEquals(FareMasterPricerTbSearch::CABIN_ECONOMY_PREMIUM, $message->itinerary[0]->flightInfo->cabinId->cabin);
        $this->assertNull($message->itinerary[0]->flightInfo->cabinId->cabinQualifier);
    }

    public function testCanSpecifyFormOfPayment()
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
            'date' => new MPDate(['dateTime' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
        ]);
        $opt->formOfPayment = [
            new FormOfPaymentDetails([
                'type' => FormOfPaymentDetails::TYPE_CREDIT_CARD,
                'chargedAmount' => 100,
                'creditCardNumber' => '123456'
            ])
        ];

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertEquals('CC', $message->fareOptions->formOfPayment[0]->type);
        $this->assertEquals(100, $message->fareOptions->formOfPayment[0]->chargedAmount);
        $this->assertEquals('123456', $message->fareOptions->formOfPayment[0]->creditCardNumber);
    }

    public function testCanMakeAnchoredSearch()
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
            'date' => new MPDate(['dateTime' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))]),
            'anchoredSegments' => [
                    new MPAnchoredSegment([
                    'departureDate' => \DateTime::createFromFormat('Ymd Hi','20180315 1540', new \DateTimeZone('UTC')),
                    'arrivalDate' => \DateTime::createFromFormat('Ymd Hi','20180316 0010', new \DateTimeZone('UTC')),
                    'dateVariation' => '',
                    'from' => 'SFO',
                    'to' => 'JFK',
                    'companyCode' => 'AA',
                    'flightNumber' => '20'
                ])
            ]
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);

        $this->assertInternalType('array', $message->itinerary);
        $this->assertCount(1, $message->itinerary);
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

        $this->assertEquals(1, $message->itinerary[0]->requestedSegmentRef->segRef);
        $this->assertNull($message->itinerary[0]->requestedSegmentRef->locationForcing);

        $this->assertNull($message->itinerary[0]->flightInfo);
        $this->assertNull($message->itinerary[0]->attributes);
        $this->assertNull($message->itinerary[0]->requestedSegmentAction);

        $this->assertCount(1, $message->itinerary[0]->flightInfoPNR);
        $this->assertEquals('20180315', $message->itinerary[0]->flightInfoPNR[0]->travelResponseDetails->flightDate->departureDate);
        $this->assertEquals('1540', $message->itinerary[0]->flightInfoPNR[0]->travelResponseDetails->flightDate->departureTime);
        $this->assertEquals('20180316', $message->itinerary[0]->flightInfoPNR[0]->travelResponseDetails->flightDate->arrivalDate);
        $this->assertEquals('0010', $message->itinerary[0]->flightInfoPNR[0]->travelResponseDetails->flightDate->arrivalTime);
        $this->assertEquals('SFO', $message->itinerary[0]->flightInfoPNR[0]->travelResponseDetails->boardPointDetails->trueLocationId);
        $this->assertEquals('JFK', $message->itinerary[0]->flightInfoPNR[0]->travelResponseDetails->offpointDetails->trueLocationId);
        $this->assertEquals('20', $message->itinerary[0]->flightInfoPNR[0]->travelResponseDetails->flightIdentification->flightNumber);
        $this->assertNull($message->itinerary[0]->flightInfoPNR[0]->travelResponseDetails->flightIdentification->bookingClass);
        $this->assertEquals('AA', $message->itinerary[0]->flightInfoPNR[0]->travelResponseDetails->companyDetails->marketingCompany);

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

        $this->assertEmpty($message->buckets);
        $this->assertNull($message->combinationFareFamilies);
        $this->assertNull($message->customerRef);
        $this->assertEmpty($message->fareFamilies);
        $this->assertNull($message->feeOption);
        $this->assertNull($message->formOfPaymentByPassenger);
        $this->assertNull($message->globalOptions);
        $this->assertNull($message->officeIdDetails);
        $this->assertEmpty($message->passengerInfoGrp);
        $this->assertNull($message->priceToBeat);
        $this->assertNull($message->solutionFamily);
        $this->assertNull($message->taxInfo);
        $this->assertNull($message->ticketChangeInfo);
        $this->assertEmpty($message->valueSearch);
        $this->assertNull($message->travelFlightInfo);
    }

    public function testUseMultiplePassengerType()
    {
        $opt = new FareMasterPricerTbSearch();
        $opt->nrOfRequestedResults = 200;
        $opt->nrOfRequestedPassengers = 1;
        $opt->passengers[] = new MPPassenger([
            'type' => [
                MPPassenger::TYPE_ADULT,
                MPPassenger::TYPE_INDIVIDUAL_INCLUSIVE_TOUR,
            ],
            'count' => 1
        ]);
        $opt->itinerary[] = new MPItinerary([
            'departureLocation' => new MPLocation(['city' => 'JFK']),
            'arrivalLocation' => new MPLocation(['city' => 'KEF']),
            'date' => new MPDate(['dateTime' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))]),
        ]);

        $message = new MasterPricerTravelBoardSearch($opt);
        $this->assertInternalType('array', $message->paxReference);

        $this->assertCount(1, $message->paxReference);
        $this->assertCount(2, $message->paxReference[0]->ptc);
        $this->assertEquals('ADT', $message->paxReference[0]->ptc[0]);
        $this->assertEquals('IIT', $message->paxReference[0]->ptc[1]);
        $this->assertCount(1, $message->paxReference[0]->traveller);
        $this->assertEquals(1, $message->paxReference[0]->traveller[0]->ref);
    }
}
