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

use Amadeus\Client\RequestOptions\FareGetFareRulesOptions;
use Amadeus\Client\Struct\Fare\GetFareRules;
use Amadeus\Client\Struct\Fare\MessageFunctionDetails;
use Test\Amadeus\BaseTestCase;

/**
 * GetFareRulesTest
 *
 * @package Test\Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class GetFareRulesTest extends BaseTestCase
{
    /**
     * 5.1 Operation: 01 Get fare rules
     */
    public function testCanMakeMessage()
    {
        $opt = new FareGetFareRulesOptions([
            'ticketingDate' => \DateTime::createFromFormat('dmY', '23032011'),
            'fareBasis' => 'OA21ERD1',
            'ticketDesignator' => 'DISC',
            'airline' => 'AA',
            'origin' => 'DFW',
            'destination' => 'MKC'
        ]);

        $msg = new GetFareRules($opt);

        $this->assertNull($msg->multiCorporate);
        $this->assertNull($msg->availCabinConf);
        $this->assertNull($msg->conversionRate);
        $this->assertNull($msg->corporateInfo);
        $this->assertNull($msg->dateOfFlight);
        $this->assertNull($msg->fareRule);
        $this->assertNull($msg->locationInfo);
        $this->assertNull($msg->monetaryInfo);
        $this->assertNull($msg->itemNumber);
        $this->assertEmpty($msg->pricingInfo);

        $this->assertEquals(MessageFunctionDetails::FARE_GET_FARE_RULES, $msg->msgType->messageFunctionDetails->messageFunction);

        $this->assertEquals('230311', $msg->pricingTickInfo->productDateTimeDetails->ticketingDate);
        $this->assertNull($msg->pricingTickInfo->productDateTimeDetails->arrivalDate);
        $this->assertNull($msg->pricingTickInfo->priceTicketDetails);
        $this->assertNull($msg->pricingTickInfo->idNumber);
        $this->assertNull($msg->pricingTickInfo->locationDetails);
        $this->assertNull($msg->pricingTickInfo->otherLocationDetails);

        $this->assertCount(1, $msg->flightQualification);
        $this->assertEquals('OA21ERD1', $msg->flightQualification[0]->additionalFareDetails->rateClass);
        $this->assertEquals('DISC', $msg->flightQualification[0]->additionalFareDetails->commodityCategory);
        $this->assertEmpty($msg->flightQualification[0]->fareOptionDetails);
        $this->assertEmpty($msg->flightQualification[0]->discountDetails);
        $this->assertNull($msg->flightQualification[0]->fareCategories);
        $this->assertNull($msg->flightQualification[0]->fareDetails);
        $this->assertNull($msg->flightQualification[0]->movementType);

        $this->assertCount(1, $msg->transportInformation);
        $this->assertEquals('AA', $msg->transportInformation[0]->transportService->companyIdentification->marketingCompany);
        $this->assertNull($msg->transportInformation[0]->availCabinConf);
        $this->assertNull($msg->transportInformation[0]->routingInfo);
        $this->assertNull($msg->transportInformation[0]->selectionDetail);

        $this->assertCount(1, $msg->tripDescription);
        $this->assertEquals('DFW', $msg->tripDescription[0]->origDest->origin);
        $this->assertEquals('MKC', $msg->tripDescription[0]->origDest->destination);
        $this->assertNull($msg->tripDescription[0]->dateFlightMovement);
        $this->assertEmpty($msg->tripDescription[0]->routing);
    }

    /**
     * 5.2 Operation: 02 Get fare rules with corporate number and departure date
     */
    public function testCanGetFareRulesWithCorpNrAndDepDate()
    {
        $opt = new FareGetFareRulesOptions([
            'ticketingDate' => \DateTime::createFromFormat('dmY', '23032011'),
            'uniFares' => ['0012345'],
            'fareBasis' => 'OA21ERD1',
            'ticketDesignator' => 'DISC',
            'directionality' => FareGetFareRulesOptions::DIRECTION_ORIGIN_TO_DESTINATION,
            'airline' => 'AA',
            'origin' => 'DFW',
            'destination' => 'MKC',
            'travelDate' => \DateTime::createFromFormat('dmY', '25032011')
        ]);

        $msg = new GetFareRules($opt);

        $this->assertNull($msg->availCabinConf);
        $this->assertNull($msg->conversionRate);
        $this->assertNull($msg->corporateInfo);
        $this->assertNull($msg->dateOfFlight);
        $this->assertNull($msg->fareRule);
        $this->assertNull($msg->locationInfo);
        $this->assertNull($msg->monetaryInfo);
        $this->assertNull($msg->itemNumber);
        $this->assertEmpty($msg->pricingInfo);

        $this->assertEquals(MessageFunctionDetails::FARE_GET_FARE_RULES, $msg->msgType->messageFunctionDetails->messageFunction);

        $this->assertEquals('230311', $msg->pricingTickInfo->productDateTimeDetails->ticketingDate);
        $this->assertNull($msg->pricingTickInfo->productDateTimeDetails->arrivalDate);
        $this->assertNull($msg->pricingTickInfo->priceTicketDetails);
        $this->assertNull($msg->pricingTickInfo->idNumber);
        $this->assertNull($msg->pricingTickInfo->locationDetails);
        $this->assertNull($msg->pricingTickInfo->otherLocationDetails);

        $this->assertCount(1, $msg->flightQualification);
        $this->assertEquals('OA21ERD1', $msg->flightQualification[0]->additionalFareDetails->rateClass);
        $this->assertEquals('DISC', $msg->flightQualification[0]->additionalFareDetails->commodityCategory);
        $this->assertCount(1, $msg->flightQualification[0]->fareOptionDetails);
        $this->assertEquals(GetFareRules\FareOptionDetails::QUAL_ORIGIN_TO_DESTINATION, $msg->flightQualification[0]->fareOptionDetails[0]->fareQualifier);
        $this->assertNull($msg->flightQualification[0]->fareOptionDetails[0]->amount);
        $this->assertNull($msg->flightQualification[0]->fareOptionDetails[0]->percentage);
        $this->assertNull($msg->flightQualification[0]->fareOptionDetails[0]->rateCategory);
        $this->assertEmpty($msg->flightQualification[0]->discountDetails);
        $this->assertNull($msg->flightQualification[0]->fareCategories);
        $this->assertNull($msg->flightQualification[0]->fareDetails);
        $this->assertNull($msg->flightQualification[0]->movementType);

        $this->assertCount(1, $msg->transportInformation);
        $this->assertEquals('AA', $msg->transportInformation[0]->transportService->companyIdentification->marketingCompany);
        $this->assertNull($msg->transportInformation[0]->availCabinConf);
        $this->assertNull($msg->transportInformation[0]->routingInfo);
        $this->assertNull($msg->transportInformation[0]->selectionDetail);

        $this->assertCount(1, $msg->tripDescription);
        $this->assertEquals('DFW', $msg->tripDescription[0]->origDest->origin);
        $this->assertEquals('MKC', $msg->tripDescription[0]->origDest->destination);
        $this->assertCount(1, $msg->tripDescription[0]->dateFlightMovement->dateAndTimeDetails);
        $this->assertEquals('250311', $msg->tripDescription[0]->dateFlightMovement->dateAndTimeDetails[0]->date);
        $this->assertNull($msg->tripDescription[0]->dateFlightMovement->dateAndTimeDetails[0]->qualifier);
        $this->assertNull($msg->tripDescription[0]->dateFlightMovement->dateAndTimeDetails[0]->otherQualifier);
        $this->assertEmpty($msg->tripDescription[0]->routing);

        $this->assertCount(1, $msg->multiCorporate->corporateId);
        $this->assertEquals('0012345', $msg->multiCorporate->corporateId[0]->identity);
        $this->assertEquals(GetFareRules\CorporateId::QUAL_UNIFARES, $msg->multiCorporate->corporateId[0]->corporateQualifier);
    }
}
