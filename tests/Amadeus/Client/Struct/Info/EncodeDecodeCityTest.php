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

namespace Test\Amadeus\Client\Struct\Info;

use Amadeus\Client\RequestOptions\InfoEncodeDecodeCityOptions;
use Amadeus\Client\Struct\Info\EncodeDecodeCity;
use Amadeus\Client\Struct\Info\LocationInformation;
use Amadeus\Client\Struct\Info\SelectionDetails;
use Test\Amadeus\BaseTestCase;

/**
 * EncodeDecodeCityTest
 *
 * @package Test\Amadeus\Client\Struct\Info
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class EncodeDecodeCityTest extends BaseTestCase
{
    public function testCanMakeMessageForSimpleRequest()
    {
        $opt = new InfoEncodeDecodeCityOptions([
            'locationCode' => 'OPO'
        ]);

        $msg = new EncodeDecodeCity($opt);

        $this->assertEquals('OPO', $msg->locationInformation->locationDescription->code);
        $this->assertEquals(LocationInformation::TYPE_LOCATION, $msg->locationInformation->locationType);

        $this->assertEquals(SelectionDetails::OPT_SEARCH_ALGORITHM, $msg->requestOption->selectionDetails->option);
        $this->assertEquals(SelectionDetails::OPTINF_SEARCH_EXACT_MATCH, $msg->requestOption->selectionDetails->optionInformation);

        $this->assertCount(0, $msg->requestOption->otherSelectionDetails);
    }

    public function testCanMakeMessageForSimpleRequestByName()
    {
        //locationName will be converted to uppercase because this an exact search.
        $opt = new InfoEncodeDecodeCityOptions([
            'locationName' => 'brussels'
        ]);

        $msg = new EncodeDecodeCity($opt);

        $this->assertEquals('BRUSSELS', $msg->locationInformation->locationDescription->name);
        $this->assertEquals(LocationInformation::TYPE_ALL, $msg->locationInformation->locationType);

        $this->assertEquals(SelectionDetails::OPT_SEARCH_ALGORITHM, $msg->requestOption->selectionDetails->option);
        $this->assertEquals(SelectionDetails::OPTINF_SEARCH_EXACT_MATCH, $msg->requestOption->selectionDetails->optionInformation);

        $this->assertCount(0, $msg->requestOption->otherSelectionDetails);
    }

    public function testCanMakeMessageForSimpleRequestByNamePhonetic()
    {
        //search for locations sounding like "brussels"
        $opt = new InfoEncodeDecodeCityOptions([
            'locationName' => 'brussels',
            'searchMode' => InfoEncodeDecodeCityOptions::SEARCHMODE_PHONETIC
        ]);

        $msg = new EncodeDecodeCity($opt);

        $this->assertEquals('brussels', $msg->locationInformation->locationDescription->name);
        $this->assertEquals(LocationInformation::TYPE_ALL, $msg->locationInformation->locationType);

        $this->assertEquals(SelectionDetails::OPT_SEARCH_ALGORITHM, $msg->requestOption->selectionDetails->option);
        $this->assertEquals(SelectionDetails::OPTINF_SEARCH_PHONETIC, $msg->requestOption->selectionDetails->optionInformation);

        $this->assertCount(0, $msg->requestOption->otherSelectionDetails);
    }

    public function testCanMakeMessageForRequestWithSelectResults()
    {
        //Get train stations in Paris
        $opt = new InfoEncodeDecodeCityOptions([
            'locationCode' => 'PAR',
            'selectResult' => InfoEncodeDecodeCityOptions::SELECT_TRAIN_STATIONS
        ]);

        $msg = new EncodeDecodeCity($opt);

        $this->assertEquals('PAR', $msg->locationInformation->locationDescription->code);
        $this->assertEquals(LocationInformation::TYPE_LOCATION, $msg->locationInformation->locationType);

        $this->assertEquals(SelectionDetails::OPT_SEARCH_ALGORITHM, $msg->requestOption->selectionDetails->option);
        $this->assertEquals(SelectionDetails::OPTINF_SEARCH_EXACT_MATCH, $msg->requestOption->selectionDetails->optionInformation);

        $this->assertCount(1, $msg->requestOption->otherSelectionDetails);
        $this->assertEquals(SelectionDetails::OPTINF_RAILWAY_STATION, $msg->requestOption->otherSelectionDetails[0]->optionInformation);
        $this->assertEquals(SelectionDetails::OPT_LOCATION_TYPE, $msg->requestOption->otherSelectionDetails[0]->option);
    }

    public function testCanMakeMessageForRequestWithCountryRestriction()
    {
        //Find cities named like "marseille" in France
        $opt = new InfoEncodeDecodeCityOptions([
            'locationName' => 'marseille',
            'searchMode' => InfoEncodeDecodeCityOptions::SEARCHMODE_PHONETIC,
            'restrictCountry' => 'FR'
        ]);

        $msg = new EncodeDecodeCity($opt);

        $this->assertEquals('marseille', $msg->locationInformation->locationDescription->name);
        $this->assertEquals(LocationInformation::TYPE_ALL, $msg->locationInformation->locationType);

        $this->assertEquals(SelectionDetails::OPT_SEARCH_ALGORITHM, $msg->requestOption->selectionDetails->option);
        $this->assertEquals(SelectionDetails::OPTINF_SEARCH_PHONETIC, $msg->requestOption->selectionDetails->optionInformation);

        $this->assertCount(0, $msg->requestOption->otherSelectionDetails);

        $this->assertEquals('FR', $msg->countryStateRestriction->countryIdentification->countryCode);
        $this->assertNull($msg->countryStateRestriction->countryIdentification->stateCode);
    }

    /**
     * I don't think this is a valid request, but it helps with code coverage :-/
     */
    public function testCanMakeMessageExplicitRequestOption()
    {
        $opt = new InfoEncodeDecodeCityOptions([
            'locationCode' => 'OPO',
            'searchMode' => null,
            'selectResult' => InfoEncodeDecodeCityOptions::SELECT_HELIPORTS
        ]);

        $msg = new EncodeDecodeCity($opt);

        $this->assertEquals('OPO', $msg->locationInformation->locationDescription->code);
        $this->assertEquals(LocationInformation::TYPE_LOCATION, $msg->locationInformation->locationType);

        $this->assertEquals(SelectionDetails::OPT_LOCATION_TYPE, $msg->requestOption->selectionDetails->option);
        $this->assertEquals(SelectionDetails::OPTINF_HELIPORT, $msg->requestOption->selectionDetails->optionInformation);

        $this->assertCount(0, $msg->requestOption->otherSelectionDetails);
    }
}
