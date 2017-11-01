<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Test\Amadeus\Client\Struct\Air;

use Amadeus\Client\RequestOptions\Air\MultiAvailability\FrequentTraveller;
use Amadeus\Client\RequestOptions\Air\MultiAvailability\RequestOptions;
use Amadeus\Client\RequestOptions\AirMultiAvailabilityOptions;
use Amadeus\Client\Struct\Air\MultiAvailability;
use Test\Amadeus\BaseTestCase;

/**
 * MultiAvailabilityTest
 *
 * @package Test\Amadeus\Client\Struct\Air
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MultiAvailabilityTest extends BaseTestCase
{
    public function testCanCreateMixedMessage()
    {
        $opt = new AirMultiAvailabilityOptions([
            'actionCode' => AirMultiAvailabilityOptions::ACTION_SCHEDULE,
            'requestOptions' => [
                new RequestOptions([
                    'departureDate' => \DateTime::createFromFormat('Ymd-His', '20170215-140000', new \DateTimeZone('UTC')),
                    'from' => 'NCE',
                    'to' => 'NYC',
                    'cabinCode' => RequestOptions::CABIN_ECONOMY_PREMIUM_MAIN,
                    'includedConnections' => ['PAR'],
                    'nrOfSeats' => 5,
                    'includedAirlines' => ['AF'],
                    'requestType' => RequestOptions::REQ_TYPE_BY_ARRIVAL_TIME
                ])
            ]
        ]);

        $msg = new MultiAvailability($opt);

        $this->assertEquals(
            MultiAvailability\FunctionDetails::ACTION_SCHEDULE,
            $msg->messageActionDetails->functionDetails->actionCode
        );
        $this->assertCount(1, $msg->requestSection);

        $this->assertEquals('150217', $msg->requestSection[0]->availabilityProductInfo->availabilityDetails[0]->departureDate);
        $this->assertEquals('1400', $msg->requestSection[0]->availabilityProductInfo->availabilityDetails[0]->departureTime);
        $this->assertNull($msg->requestSection[0]->availabilityProductInfo->availabilityDetails[0]->arrivalDate);
        $this->assertNull($msg->requestSection[0]->availabilityProductInfo->availabilityDetails[0]->arrivalTime);
        $this->assertEquals('NCE', $msg->requestSection[0]->availabilityProductInfo->departureLocationInfo->cityAirport);
        $this->assertEquals('NYC', $msg->requestSection[0]->availabilityProductInfo->arrivalLocationInfo->cityAirport);
        $this->assertEquals(
            MultiAvailability\CabinDesignation::CABIN_ECONOMY_PREMIUM_MAIN,
            $msg->requestSection[0]->cabinOption->cabinDesignation->cabinClassOfServiceList
        );
        $this->assertNull($msg->requestSection[0]->optionClass);
        $this->assertCount(1, $msg->requestSection[0]->airlineOrFlightOption);
        $this->assertNull($msg->requestSection[0]->airlineOrFlightOption[0]->excludeAirlineIndicator);
        $this->assertEquals('AF', $msg->requestSection[0]->airlineOrFlightOption[0]->flightIdentification[0]->airlineCode);
        $this->assertEquals(5, $msg->requestSection[0]->numberOfSeatsInfo->numberOfPassengers);
        $this->assertCount(1, $msg->requestSection[0]->connectionOption);
        $this->assertEquals('PAR', $msg->requestSection[0]->connectionOption[0]->firstConnection->location);
        $this->assertNull($msg->requestSection[0]->connectionOption[0]->firstConnection->indicatorList);
        $this->assertEmpty($msg->requestSection[0]->connectionOption[0]->secondConnection);

        $this->assertEquals(
            MultiAvailability\ProductTypeDetails::REQ_TYPE_BY_ARRIVAL_TIME,
            $msg->requestSection[0]->availabilityOptions->productTypeDetails->typeOfRequest
        );

        $this->assertNull($msg->frequentTraveller);
        $this->assertNull($msg->consumerReferenceInformation);
        $this->assertNull($msg->pointOfCommencement);
    }

    public function testOptionalParams()
    {
        $opt = new AirMultiAvailabilityOptions([
            'actionCode' => AirMultiAvailabilityOptions::ACTION_AVAILABILITY,
            'requestOptions' => [
                new RequestOptions([
                    'departureDate' => \DateTime::createFromFormat('Ymd-His', '20131018-000000', new \DateTimeZone('UTC')),
                    'from' => 'SYD',
                    'to' => 'CDG',
                    'requestType' => RequestOptions::REQ_TYPE_NEUTRAL_ORDER
                ])
            ],
            'commencementPoint' => 'CDG',
            'commencementDate' => \DateTime::createFromFormat('Ymd-His', '20131006-000000', new \DateTimeZone('UTC')),
            'corporationNumber' => '48906348860',
            'frequentTraveller' => new FrequentTraveller([
                'firstName' => 'David',
                'lastName' => 'Bowie',
                'carrier' => 'LH',
                'number' => '6200107371629'
            ])
        ]);

        $msg = new MultiAvailability($opt);

        $this->assertEquals(
            MultiAvailability\FunctionDetails::ACTION_AVAILABILITY,
            $msg->messageActionDetails->functionDetails->actionCode
        );
        $this->assertCount(1, $msg->requestSection);
        $this->assertEquals('181013', $msg->requestSection[0]->availabilityProductInfo->availabilityDetails[0]->departureDate);
        $this->assertNull($msg->requestSection[0]->availabilityProductInfo->availabilityDetails[0]->departureTime);
        $this->assertEquals('SYD', $msg->requestSection[0]->availabilityProductInfo->departureLocationInfo->cityAirport);
        $this->assertEquals('CDG', $msg->requestSection[0]->availabilityProductInfo->arrivalLocationInfo->cityAirport);
        $this->assertNull($msg->requestSection[0]->cabinOption);
        $this->assertNull($msg->requestSection[0]->optionClass);
        $this->assertEmpty($msg->requestSection[0]->airlineOrFlightOption);
        $this->assertNull($msg->requestSection[0]->numberOfSeatsInfo);
        $this->assertEquals(
            MultiAvailability\ProductTypeDetails::REQ_TYPE_NEUTRAL_ORDER,
            $msg->requestSection[0]->availabilityOptions->productTypeDetails->typeOfRequest
        );

        $this->assertEquals('48906348860', $msg->consumerReferenceInformation->customerReferences[0]->referenceNumber);
        $this->assertEquals(
            MultiAvailability\CustomerReferences::QUAL_701,
            $msg->consumerReferenceInformation->customerReferences[0]->referenceQualifier
        );

        $this->assertEquals('CDG', $msg->pointOfCommencement->location);
        $this->assertEquals('061013', $msg->pointOfCommencement->date);
        $this->assertNull($msg->pointOfCommencement->time);

        $this->assertEquals('LH', $msg->frequentTraveller->travelleridentification->frequentTravellerDetails->carrier);
        $this->assertEquals('6200107371629', $msg->frequentTraveller->travelleridentification->frequentTravellerDetails->number);
        $this->assertEquals('Bowie', $msg->frequentTraveller->travellerDetails->paxDetails->surname);
        $this->assertEquals('David', $msg->frequentTraveller->travellerDetails->otherPaxDetails[0]->givenName);
    }

    public function testCanLoadExcludedAirlinesAndConnectPoints()
    {
        $opt = new AirMultiAvailabilityOptions([
            'actionCode' => AirMultiAvailabilityOptions::ACTION_AVAILABILITY,
            'requestOptions' => [
                new RequestOptions([
                    'departureDate' => \DateTime::createFromFormat('Ymd-His', '20170320-000000', new \DateTimeZone('UTC')),
                    'from' => 'BRU',
                    'to' => 'LIS',
                    'excludedAirlines' => ['SU', 'MH'], // I want to live
                    'excludedConnections' => ['MAD', 'OPO', 'FAO'], // Not via spain
                    'requestType' => RequestOptions::REQ_TYPE_NEUTRAL_ORDER
                ])
            ]
        ]);

        $msg = new MultiAvailability($opt);

        $this->assertEquals(
            MultiAvailability\FunctionDetails::ACTION_AVAILABILITY,
            $msg->messageActionDetails->functionDetails->actionCode
        );
        $this->assertCount(1, $msg->requestSection);
        $this->assertEquals('200317', $msg->requestSection[0]->availabilityProductInfo->availabilityDetails[0]->departureDate);
        $this->assertNull($msg->requestSection[0]->availabilityProductInfo->availabilityDetails[0]->departureTime);
        $this->assertEquals('BRU', $msg->requestSection[0]->availabilityProductInfo->departureLocationInfo->cityAirport);
        $this->assertEquals('LIS', $msg->requestSection[0]->availabilityProductInfo->arrivalLocationInfo->cityAirport);
        $this->assertNull($msg->requestSection[0]->cabinOption);
        $this->assertNull($msg->requestSection[0]->optionClass);
        $this->assertCount(1, $msg->requestSection[0]->airlineOrFlightOption);
        $this->assertEquals(
            MultiAvailability\AirlineOrFlightOption::INDICATOR_EXCLUDE,
            $msg->requestSection[0]->airlineOrFlightOption[0]->excludeAirlineIndicator
        );
        $this->assertCount(2, $msg->requestSection[0]->airlineOrFlightOption[0]->flightIdentification);
        $this->assertEquals('SU', $msg->requestSection[0]->airlineOrFlightOption[0]->flightIdentification[0]->airlineCode);
        $this->assertEquals('MH', $msg->requestSection[0]->airlineOrFlightOption[0]->flightIdentification[1]->airlineCode);
        $this->assertNull($msg->requestSection[0]->numberOfSeatsInfo);
        $this->assertCount(1, $msg->requestSection[0]->connectionOption);
        $this->assertEquals('MAD', $msg->requestSection[0]->connectionOption[0]->firstConnection->location);
        $this->assertEquals(
            MultiAvailability\Connection::INDICATOR_EXCLUDE,
            $msg->requestSection[0]->connectionOption[0]->firstConnection->indicatorList
        );
        $this->assertCount(2, $msg->requestSection[0]->connectionOption[0]->secondConnection);
        $this->assertEquals('OPO', $msg->requestSection[0]->connectionOption[0]->secondConnection[0]->location);
        $this->assertEquals(MultiAvailability\Connection::INDICATOR_EXCLUDE, $msg->requestSection[0]->connectionOption[0]->secondConnection[0]->indicatorList);
        $this->assertEquals('FAO', $msg->requestSection[0]->connectionOption[0]->secondConnection[1]->location);
        $this->assertEquals(MultiAvailability\Connection::INDICATOR_EXCLUDE, $msg->requestSection[0]->connectionOption[0]->secondConnection[1]->indicatorList);

        $this->assertEquals(
            MultiAvailability\ProductTypeDetails::REQ_TYPE_NEUTRAL_ORDER,
            $msg->requestSection[0]->availabilityOptions->productTypeDetails->typeOfRequest
        );
    }

    public function testWithBookingClasses()
    {
        $opt = new AirMultiAvailabilityOptions([
            'actionCode' => AirMultiAvailabilityOptions::ACTION_AVAILABILITY,
            'requestOptions' => [
                new RequestOptions([
                    'departureDate' => \DateTime::createFromFormat('Ymd-His', '20170320-000000', new \DateTimeZone('UTC')),
                    'from' => 'BRU',
                    'to' => 'LIS',
                    'bookingClasses' => ['Y', 'I'],
                    'requestType' => RequestOptions::REQ_TYPE_NEUTRAL_ORDER
                ])
            ]
        ]);

        $msg = new MultiAvailability($opt);

        $this->assertEquals(
            MultiAvailability\FunctionDetails::ACTION_AVAILABILITY,
            $msg->messageActionDetails->functionDetails->actionCode
        );

        $this->assertCount(2, $msg->requestSection[0]->optionClass->productClassDetails);
        $this->assertEquals('Y', $msg->requestSection[0]->optionClass->productClassDetails[0]->serviceClass);
        $this->assertNull($msg->requestSection[0]->optionClass->productClassDetails[0]->nightModifierOption);
        $this->assertEquals('I', $msg->requestSection[0]->optionClass->productClassDetails[1]->serviceClass);
    }

    public function testWithOperationalAirline()
    {
        $opt = new AirMultiAvailabilityOptions([
            'actionCode' => AirMultiAvailabilityOptions::ACTION_AVAILABILITY,
            'requestOptions' => [
                new RequestOptions([
                    'departureDate' => \DateTime::createFromFormat('Ymd-His', '20170320-000000', new \DateTimeZone('UTC')),
                    'from' => 'BRU',
                    'to' => 'LIS',
                    'operationalAirline' => 'TP',
                    'flightNumber' => '604',
                    'requestType' => RequestOptions::REQ_TYPE_NEUTRAL_ORDER
                ])
            ]
        ]);

        $msg = new MultiAvailability($opt);

        $this->assertEquals(
            MultiAvailability\FunctionDetails::ACTION_AVAILABILITY,
            $msg->messageActionDetails->functionDetails->actionCode
        );

        $this->assertNull($msg->requestSection[0]->optionClass);

        $this->assertCount(1, $msg->requestSection[0]->airlineOrFlightOption);
        $this->assertCount(1, $msg->requestSection[0]->airlineOrFlightOption[0]->flightIdentification);
        $this->assertEquals(
            MultiAvailability\AirlineOrFlightOption::INDICATOR_OPERATIONAL_CARRIER,
            $msg->requestSection[0]->airlineOrFlightOption[0]->excludeAirlineIndicator
        );
        $this->assertEquals('TP', $msg->requestSection[0]->airlineOrFlightOption[0]->flightIdentification[0]->airlineCode);
        $this->assertEquals('604', $msg->requestSection[0]->airlineOrFlightOption[0]->flightIdentification[0]->number);
    }

    public function testWithNonStopFlightsOnly()
    {
        $opt = new AirMultiAvailabilityOptions([
            'actionCode' => AirMultiAvailabilityOptions::ACTION_AVAILABILITY,
            'requestOptions' => [
                new RequestOptions([
                    'departureDate' => \DateTime::createFromFormat('Ymd-His', '20170320-000000', new \DateTimeZone('UTC')),
                    'from' => 'BRU',
                    'to' => 'LIS',
                    'availabilityOptions' => [
                        RequestOptions::OPTION_TYPE_FLIGHT_OPTION => 'ON' //ON for non-stop flights only
                    ],
                    'requestType' => RequestOptions::REQ_TYPE_NEUTRAL_ORDER
                ])
            ]
        ]);

        $msg = new MultiAvailability($opt);

        $this->assertEquals(
            MultiAvailability\FunctionDetails::ACTION_AVAILABILITY,
            $msg->messageActionDetails->functionDetails->actionCode
        );

        $this->assertCount(0, $msg->requestSection[0]->airlineOrFlightOption);
        $this->assertCount(1, $msg->requestSection[0]->availabilityOptions->optionInfo);
        $this->assertEquals(
            MultiAvailability\OptionInfo::OPTION_TYPE_FLIGHT_OPTION,
            $msg->requestSection[0]->availabilityOptions->optionInfo[0]->type
        );
        $this->assertCount(1, $msg->requestSection[0]->availabilityOptions->optionInfo[0]->arguments);
        $this->assertEquals('ON', $msg->requestSection[0]->availabilityOptions->optionInfo[0]->arguments[0]);
    }

    public function testWithArrivalDate()
    {
        $opt = new AirMultiAvailabilityOptions([
            'actionCode' => AirMultiAvailabilityOptions::ACTION_AVAILABILITY,
            'requestOptions' => [
                new RequestOptions([
                    'arrivalDate' => \DateTime::createFromFormat('Ymd-His', '20170320-000000', new \DateTimeZone('UTC')),
                    'from' => 'BRU',
                    'to' => 'LIS',
                    'requestType' => RequestOptions::REQ_TYPE_NEUTRAL_ORDER
                ])
            ]
        ]);

        $msg = new MultiAvailability($opt);

        $this->assertEquals(
            MultiAvailability\FunctionDetails::ACTION_AVAILABILITY,
            $msg->messageActionDetails->functionDetails->actionCode
        );

        $this->assertCount(0, $msg->requestSection[0]->availabilityOptions->optionInfo);

        $this->assertCount(1, $msg->requestSection);
        $this->assertNull($msg->requestSection[0]->availabilityProductInfo->availabilityDetails[0]->departureDate);
        $this->assertNull($msg->requestSection[0]->availabilityProductInfo->availabilityDetails[0]->departureTime);
        $this->assertEquals('200317', $msg->requestSection[0]->availabilityProductInfo->availabilityDetails[0]->arrivalDate);
        $this->assertNull($msg->requestSection[0]->availabilityProductInfo->availabilityDetails[0]->arrivalTime);
        $this->assertEquals('BRU', $msg->requestSection[0]->availabilityProductInfo->departureLocationInfo->cityAirport);
        $this->assertEquals('LIS', $msg->requestSection[0]->availabilityProductInfo->arrivalLocationInfo->cityAirport);
    }
}
