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

namespace Test\Amadeus\Client\Struct\PointOfRef;

use Amadeus\Client\RequestOptions\PointOfRefSearchOptions;
use Amadeus\Client\Struct\PointOfRef\Search;
use Test\Amadeus\BaseTestCase;

/**
 * SearchTest
 *
 * @package Test\Amadeus\Client\Struct\PointOfRef
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SearchTest extends BaseTestCase
{
    public function testCanMakeMessageWithGeoCoordinates()
    {
        $opt = new PointOfRefSearchOptions([
            'targetCategoryCode' => PointOfRefSearchOptions::TARGET_TRAIN,
            'latitude' => '5099155',
            'longitude' => '332824',
            'searchRadius' => '15000'
        ]);

        $msg = new Search($opt);

        $this->assertCount(1, $msg->porFndQryParams->targetCategoryCode);
        $this->assertEquals('TRA', $msg->porFndQryParams->targetCategoryCode[0]);
        $this->assertEquals('5099155', $msg->porFndQryParams->geoCode->latitude);
        $this->assertEquals('332824', $msg->porFndQryParams->geoCode->longitude);
        $this->assertEquals('15000', $msg->porFndQryParams->radius);
        $this->assertNull($msg->porFndQryParams->searchType);
        $this->assertNull($msg->porFndQryParams->area);
        $this->assertNull($msg->porFndQryParams->businessId);
        $this->assertNull($msg->porFndQryParams->indexOfItem);
        $this->assertNull($msg->porFndQryParams->name);
        $this->assertNull($msg->porFndQryParams->outputLanguage);
        $this->assertNull($msg->porFndQryParams->porId);
        $this->assertNull($msg->porFndQryParams->resultListType);
        $this->assertNull($msg->porFndQryParams->resultMaxItems);
    }

    /**
     * This scenario consists in displaying all PORs with the name 'quasino' in Nice (IATA code: NCE).
     * The default search algorithm is Phonetic.
     *
     * The search for PORs with the name 'casino' in Nice (IATA code: NCE) yields the same result.
     */
    public function testCanMakeMessageByCriteriaPORNameCityIataCode()
    {
        $opt = new PointOfRefSearchOptions([
            'iata' => 'NCE',
            'name' => 'quasino'
        ]);

        $msg = new Search($opt);

        $this->assertCount(1, $msg->porFndQryParams->targetCategoryCode);
        $this->assertEquals('ALL', $msg->porFndQryParams->targetCategoryCode[0]);
        $this->assertEquals('NCE', $msg->porFndQryParams->area->iataCode);
        $this->assertNull($msg->porFndQryParams->area->countryCode);
        $this->assertNull($msg->porFndQryParams->area->stateCode);
        $this->assertEquals('quasino', $msg->porFndQryParams->name->nameOfPOR);
        $this->assertNull($msg->porFndQryParams->name->language);

        $this->assertNull($msg->porFndQryParams->searchType);
        $this->assertNull($msg->porFndQryParams->geoCode);
        $this->assertNull($msg->porFndQryParams->businessId);
        $this->assertNull($msg->porFndQryParams->indexOfItem);
        $this->assertNull($msg->porFndQryParams->radius);
        $this->assertNull($msg->porFndQryParams->outputLanguage);
        $this->assertNull($msg->porFndQryParams->porId);
        $this->assertNull($msg->porFndQryParams->resultListType);
        $this->assertNull($msg->porFndQryParams->resultMaxItems);
    }

    /**
     * Operation: Search by Criteria - 5 hotels in Rio de Janeiro state Brazil
     *
     * This scenario consists in displaying 5 hotels in the state of Rio de Janeiro, Brazil.
     */
    public function testCanMakeMessage5HotelsRioDeJaneiroBrazil()
    {
        $opt = new PointOfRefSearchOptions([
            'maxNrOfResults' => 5,
            'country' => 'BR',
            'state' => 'RJ'
        ]);

        $msg = new Search($opt);

        $this->assertCount(1, $msg->porFndQryParams->targetCategoryCode);
        $this->assertEquals('ALL', $msg->porFndQryParams->targetCategoryCode[0]);


        $this->assertEquals(5, $msg->porFndQryParams->resultMaxItems);
        $this->assertEquals('BR', $msg->porFndQryParams->area->countryCode);
        $this->assertEquals('RJ', $msg->porFndQryParams->area->stateCode);
        $this->assertNull($msg->porFndQryParams->area->iataCode);

        $this->assertNull($msg->porFndQryParams->name);
        $this->assertNull($msg->porFndQryParams->searchType);
        $this->assertNull($msg->porFndQryParams->geoCode);
        $this->assertNull($msg->porFndQryParams->businessId);
        $this->assertNull($msg->porFndQryParams->indexOfItem);
        $this->assertNull($msg->porFndQryParams->radius);
        $this->assertNull($msg->porFndQryParams->outputLanguage);
        $this->assertNull($msg->porFndQryParams->porId);
        $this->assertNull($msg->porFndQryParams->resultListType);
    }

    /**
     * Operation: Search by Area Center defined by business ID short list type
     *
     * This scenario consists in displaying all PORs on a 500m area
     * around the airport (category code: APT) of Nice (foreign key: NCE).
     */
    public function testCanSearchByAreaCenterBusinessIDShort()
    {
        $opt = new PointOfRefSearchOptions([
            'listType' => PointOfRefSearchOptions::LIST_TYPE_SHORT,
            'businessCategory' => 'APT',
            'businessForeignKey' => 'NCE'
        ]);

        $msg = new Search($opt);

        $this->assertCount(1, $msg->porFndQryParams->targetCategoryCode);
        $this->assertEquals('ALL', $msg->porFndQryParams->targetCategoryCode[0]);

        $this->assertEquals('APT', $msg->porFndQryParams->businessId->categoryCode);
        $this->assertEquals('NCE', $msg->porFndQryParams->businessId->foreignKey);
        $this->assertEquals('S', $msg->porFndQryParams->resultListType);

        $this->assertNull($msg->porFndQryParams->area);
        $this->assertNull($msg->porFndQryParams->resultMaxItems);
        $this->assertNull($msg->porFndQryParams->name);
        $this->assertNull($msg->porFndQryParams->searchType);
        $this->assertNull($msg->porFndQryParams->geoCode);
        $this->assertNull($msg->porFndQryParams->indexOfItem);
        $this->assertNull($msg->porFndQryParams->radius);
        $this->assertNull($msg->porFndQryParams->outputLanguage);
        $this->assertNull($msg->porFndQryParams->porId);
    }

    /**
     * Operation: Search both by Area and Criteria POR name center defined by geo-code
     *
     * This scenario consists in displaying all PORs with the name 'casino'
     * on a 5km area around geo-code (7.17510°, 43.65655°;).
     */
    public function testCanMakeMessageFindByNameANDGeoCoords()
    {
        $opt = new PointOfRefSearchOptions([
            'latitude' => '4365655',
            'longitude' => '717510',
            'searchRadius' => '5000',
            'name' => 'casino'
        ]);

        $msg = new Search($opt);

        $this->assertCount(1, $msg->porFndQryParams->targetCategoryCode);
        $this->assertEquals('ALL', $msg->porFndQryParams->targetCategoryCode[0]);
        $this->assertEquals('4365655', $msg->porFndQryParams->geoCode->latitude);
        $this->assertEquals('717510', $msg->porFndQryParams->geoCode->longitude);
        $this->assertEquals('5000', $msg->porFndQryParams->radius);
        $this->assertEquals('casino', $msg->porFndQryParams->name->nameOfPOR);
        $this->assertNull($msg->porFndQryParams->name->language);

        $this->assertNull($msg->porFndQryParams->searchType);
        $this->assertNull($msg->porFndQryParams->area);
        $this->assertNull($msg->porFndQryParams->businessId);
        $this->assertNull($msg->porFndQryParams->indexOfItem);
        $this->assertNull($msg->porFndQryParams->outputLanguage);
        $this->assertNull($msg->porFndQryParams->porId);
        $this->assertNull($msg->porFndQryParams->resultListType);
        $this->assertNull($msg->porFndQryParams->resultMaxItems);
    }
}
