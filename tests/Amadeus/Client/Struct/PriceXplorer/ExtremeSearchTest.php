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

namespace Amadeus\Client\Struct\PriceXplorer;

use Amadeus\Client\RequestOptions\PriceXplorerExtremeSearchOptions;
use Test\Amadeus\BaseTestCase;

/**
 * ExtremeSearchTest
 *
 * @package Amadeus\Client\Struct\PriceXplorer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ExtremeSearchTest extends BaseTestCase
{
    public function testCanMakeFullXsMessage()
    {
        $opt = new PriceXplorerExtremeSearchOptions([
            'resultAggregationOption' => PriceXplorerExtremeSearchOptions::AGGR_DEST_WEEK_DEPART_STAY,
            'origin' => 'BRU',
            'destinations' => ['SYD','CBR'],
            'currency' => 'EUR',
            'maxBudget' => 650,
            'minBudget' => 200,
            'earliestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-08-25', new \DateTimeZone('UTC')),
            'latestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-09-28', new \DateTimeZone('UTC')),
            //'departureDaysOutbound' => [1,2,3,4,5],
            'stayDurationDays' => 2,
            'stayDurationFlexibilityDays' => 3,
            'searchOffice' => 'LONBG2222'
        ]);

        $message = new ExtremeSearch($opt);

        $this->assertEquals(3, count($message->itineraryGrp));
        $this->assertEquals('BRU', $message->itineraryGrp[0]->itineraryInfo->origin);
        $this->assertEquals(null, $message->itineraryGrp[0]->itineraryInfo->destination);
        $this->assertNull($message->itineraryGrp[0]->locationInfo);
        $this->assertEquals(null, $message->itineraryGrp[1]->itineraryInfo->origin);
        $this->assertEquals('SYD', $message->itineraryGrp[1]->itineraryInfo->destination);
        $this->assertNull($message->itineraryGrp[1]->locationInfo);
        $this->assertEquals(null, $message->itineraryGrp[2]->itineraryInfo->origin);
        $this->assertEquals('CBR', $message->itineraryGrp[2]->itineraryInfo->destination);
        $this->assertNull($message->itineraryGrp[2]->locationInfo);

        $this->assertEquals(MonetaryDetails::QUAL_MAX_BUDGET, $message->budget->monetaryDetails[0]->typeQualifier);
        $this->assertEquals(650, $message->budget->monetaryDetails[0]->amount);
        $this->assertEquals('EUR', $message->budget->monetaryDetails[0]->currency);
        $this->assertEquals(MonetaryDetails::QUAL_MIN_BUDGET, $message->budget->monetaryDetails[1]->typeQualifier);
        $this->assertEquals(200, $message->budget->monetaryDetails[1]->amount);
        $this->assertEquals('EUR', $message->budget->monetaryDetails[1]->currency);

        $this->assertEquals('250816', $message->travelDates->dateAndTimeDetails[0]->date);
        $this->assertEquals(DateAndTimeDetails::QUAL_STARTDATE, $message->travelDates->dateAndTimeDetails[0]->qualifier);
        $this->assertEquals('280916', $message->travelDates->dateAndTimeDetails[1]->date);
        $this->assertEquals(DateAndTimeDetails::QUAL_ENDDATE, $message->travelDates->dateAndTimeDetails[1]->qualifier);

        $this->assertEquals(QuantityDetailsType::UNIT_DAY, $message->stayDuration->nbOfUnitsInfo->quantityDetails[0]->unitQualifier);
        $this->assertEquals(2, $message->stayDuration->nbOfUnitsInfo->quantityDetails[0]->numberOfUnit);
        $this->assertEquals(QuantityDetailsType::QUAL_PLUS, $message->stayDuration->flexibilityInfo->quantityDetails[0]->qualifier);
        $this->assertEquals(3, $message->stayDuration->flexibilityInfo->quantityDetails[0]->value);
        $this->assertEquals(QuantityDetailsType::UNIT_DAY, $message->stayDuration->flexibilityInfo->quantityDetails[0]->unit);

        $this->assertEquals(AttributeInfo::FUNC_GROUPING, $message->attributeInfo[0]->attributeFunction);
        $this->assertEquals(4, count($message->attributeInfo[0]->attributeDetails));
        $this->assertEquals(AttributeDetails::TYPE_DESTINATION, $message->attributeInfo[0]->attributeDetails[0]->attributeType);
        $this->assertEquals(AttributeDetails::TYPE_WEEK, $message->attributeInfo[0]->attributeDetails[1]->attributeType);
        $this->assertEquals(AttributeDetails::TYPE_DEPARTURE_DAY, $message->attributeInfo[0]->attributeDetails[2]->attributeType);
        $this->assertEquals(AttributeDetails::TYPE_STAY_DURATION, $message->attributeInfo[0]->attributeDetails[3]->attributeType);

        $this->assertEquals(0, count($message->departureDays));
        $this->assertEquals('LONBG2222', $message->officeIdInfo[0]->officeId->originIdentification->inHouseIdentification1);
    }

    public function testCanMakeXSCallWithDays()
    {
        $opt = new PriceXplorerExtremeSearchOptions([
            'resultAggregationOption' => PriceXplorerExtremeSearchOptions::AGGR_DEST_WEEK_DEPART_STAY,
            'origin' => 'BRU',
            'destinations' => ['SYD'],
            'earliestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-08-25', new \DateTimeZone('UTC')),
            'latestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-09-28', new \DateTimeZone('UTC')),
            'departureDaysInbound' => [1,2,3,4,5],
            'departureDaysOutbound' => [1,2,3],
            'searchOffice' => 'LONBG2222'
        ]);

        $message = new ExtremeSearch($opt);

        $this->assertEquals(2, count($message->departureDays));
        $this->assertEquals('12345', $message->departureDays[0]->daySelection->dayOfWeek);
        $this->assertEquals(SelectionDetails::OPT_INBOUND_DEP_DAYS, $message->departureDays[0]->selectionInfo->selectionDetails[0]->option);

        $this->assertEquals('123', $message->departureDays[1]->daySelection->dayOfWeek);
        $this->assertEquals(SelectionDetails::OPT_OUTBOUND_DEP_DAYS, $message->departureDays[1]->selectionInfo->selectionDetails[0]->option);
    }

    public function testCanMakeXSCallAggregationOptions()
    {
        $opt = new PriceXplorerExtremeSearchOptions([
            'resultAggregationOption' => PriceXplorerExtremeSearchOptions::AGGR_COUNTRY,
            'origin' => 'BRU',
            'destinations' => ['SYD'],
            'earliestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-08-25', new \DateTimeZone('UTC')),
            'latestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-09-28', new \DateTimeZone('UTC')),
            'searchOffice' => 'LONBG2222'
        ]);

        $message = new ExtremeSearch($opt);

        $this->assertEquals(AttributeInfo::FUNC_GROUPING, $message->attributeInfo[0]->attributeFunction);
        $this->assertEquals(1, count($message->attributeInfo[0]->attributeDetails));
        $this->assertEquals(AttributeDetails::TYPE_COUNTRY, $message->attributeInfo[0]->attributeDetails[0]->attributeType);



        $opt = new PriceXplorerExtremeSearchOptions([
            'resultAggregationOption' => PriceXplorerExtremeSearchOptions::AGGR_DEST,
            'origin' => 'BRU',
            'destinations' => ['SYD'],
            'earliestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-08-25', new \DateTimeZone('UTC')),
            'latestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-09-28', new \DateTimeZone('UTC')),
            'searchOffice' => 'LONBG2222'
        ]);

        $message = new ExtremeSearch($opt);

        $this->assertEquals(AttributeInfo::FUNC_GROUPING, $message->attributeInfo[0]->attributeFunction);
        $this->assertEquals(1, count($message->attributeInfo[0]->attributeDetails));
        $this->assertEquals(AttributeDetails::TYPE_DESTINATION, $message->attributeInfo[0]->attributeDetails[0]->attributeType);



        $opt = new PriceXplorerExtremeSearchOptions([
            'resultAggregationOption' => PriceXplorerExtremeSearchOptions::AGGR_DEST_WEEK,
            'origin' => 'BRU',
            'destinations' => ['SYD'],
            'earliestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-08-25', new \DateTimeZone('UTC')),
            'latestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-09-28', new \DateTimeZone('UTC')),
            'searchOffice' => 'LONBG2222'
        ]);

        $message = new ExtremeSearch($opt);

        $this->assertEquals(AttributeInfo::FUNC_GROUPING, $message->attributeInfo[0]->attributeFunction);
        $this->assertEquals(2, count($message->attributeInfo[0]->attributeDetails));
        $this->assertEquals(AttributeDetails::TYPE_DESTINATION, $message->attributeInfo[0]->attributeDetails[0]->attributeType);
        $this->assertEquals(AttributeDetails::TYPE_WEEK, $message->attributeInfo[0]->attributeDetails[1]->attributeType);




        $opt = new PriceXplorerExtremeSearchOptions([
            'resultAggregationOption' => PriceXplorerExtremeSearchOptions::AGGR_DEST_WEEK_DEPART,
            'origin' => 'BRU',
            'destinations' => ['SYD'],
            'earliestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-08-25', new \DateTimeZone('UTC')),
            'latestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-09-28', new \DateTimeZone('UTC')),
            'searchOffice' => 'LONBG2222'
        ]);

        $message = new ExtremeSearch($opt);

        $this->assertEquals(AttributeInfo::FUNC_GROUPING, $message->attributeInfo[0]->attributeFunction);
        $this->assertEquals(3, count($message->attributeInfo[0]->attributeDetails));
        $this->assertEquals(AttributeDetails::TYPE_DESTINATION, $message->attributeInfo[0]->attributeDetails[0]->attributeType);
        $this->assertEquals(AttributeDetails::TYPE_WEEK, $message->attributeInfo[0]->attributeDetails[1]->attributeType);
        $this->assertEquals(AttributeDetails::TYPE_DEPARTURE_DAY, $message->attributeInfo[0]->attributeDetails[2]->attributeType);
    }

    public function testCanMakeXSCallWithDestCountries()
    {
        $opt = new PriceXplorerExtremeSearchOptions([
            'resultAggregationOption' => PriceXplorerExtremeSearchOptions::AGGR_DEST_WEEK_DEPART_STAY,
            'origin' => 'BRU',
            'destinationCountries' => ['AU','NZ'],
            'earliestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-08-25', new \DateTimeZone('UTC')),
            'latestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-09-28', new \DateTimeZone('UTC')),
            'searchOffice' => 'LONBG2222'
        ]);

        $message = new ExtremeSearch($opt);

        $this->assertEquals(3, count($message->itineraryGrp));
        $this->assertEquals('BRU', $message->itineraryGrp[0]->itineraryInfo->origin);
        $this->assertNull($message->itineraryGrp[0]->itineraryInfo->destination);
        $this->assertNull( $message->itineraryGrp[1]->itineraryInfo->origin);
        $this->assertNull($message->itineraryGrp[1]->itineraryInfo->destination);
        $this->assertEquals(LocationInfo::LOC_COUNTRY, $message->itineraryGrp[1]->locationInfo->locationType);
        $this->assertEquals('AU', $message->itineraryGrp[1]->locationInfo->locationDescription->code);
        $this->assertEquals(LocationIdentificationType::QUAL_DESTINATION, $message->itineraryGrp[1]->locationInfo->locationDescription->qualifier);
        $this->assertNull( $message->itineraryGrp[2]->itineraryInfo->origin);
        $this->assertNull( $message->itineraryGrp[2]->itineraryInfo->destination);
        $this->assertEquals(LocationInfo::LOC_COUNTRY, $message->itineraryGrp[2]->locationInfo->locationType);
        $this->assertEquals('NZ', $message->itineraryGrp[2]->locationInfo->locationDescription->code);
        $this->assertEquals(LocationIdentificationType::QUAL_DESTINATION, $message->itineraryGrp[2]->locationInfo->locationDescription->qualifier);
    }

    public function testCanMakeXSCallWithCheapestFlags()
    {
        $opt = new PriceXplorerExtremeSearchOptions([
            'resultAggregationOption' => PriceXplorerExtremeSearchOptions::AGGR_DEST_WEEK_DEPART_STAY,
            'origin' => 'BRU',
            'destinations' => ['SYD'],
            'earliestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-08-25', new \DateTimeZone('UTC')),
            'latestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-09-28', new \DateTimeZone('UTC')),
            'returnCheapestNonStop' => true,
            'returnCheapestOverall' => true,
            'searchOffice' => 'LONBG2222'
        ]);

        $message = new ExtremeSearch($opt);

        $this->assertEquals(1, count($message->selectionDetailsGroup));
        $this->assertEmpty($message->selectionDetailsGroup[0]->attributeInfo);
        $this->assertEquals(SelectionDetails::OPT_PRICE_RESULT_DISTRIBUTION, $message->selectionDetailsGroup[0]->selectionDetailsInfo->selectionDetails[0]->option);
        $this->assertEquals(2, count($message->selectionDetailsGroup[0]->nbOfUnitsInfo->quantityDetails));
        $this->assertEquals(NumberOfUnitDetailsType::QUAL_CHEAPEST_NONSTOP, $message->selectionDetailsGroup[0]->nbOfUnitsInfo->quantityDetails[0]->unitQualifier);
        $this->assertEquals(NumberOfUnitDetailsType::QUAL_CHEAPEST_OVERALL, $message->selectionDetailsGroup[0]->nbOfUnitsInfo->quantityDetails[1]->unitQualifier);
    }
}
