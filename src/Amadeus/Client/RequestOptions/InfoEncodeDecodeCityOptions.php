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

namespace Amadeus\Client\RequestOptions;

/**
 * Info_EncodeDecodeCity Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class InfoEncodeDecodeCityOptions extends Base
{
    const SEARCHMODE_EXACT = 'EXT';
    const SEARCHMODE_PHONETIC = 'PHO';

    const SELECT_ALL_LOCATIONS = 'ALL';
    const SELECT_AIRPORTS = 'A';
    const SELECT_HELIPORTS = 'H';
    const SELECT_BUS_STATIONS = 'B';
    const SELECT_TRAIN_STATIONS = 'R';
    const SELECT_ASSOCIATED_LOCATION = 'S';
    const SELECT_GROUND_TRANSPORT = 'G';
    const SELECT_OFFLINE_POINT = 'O';

    /**
     * Search for an exact result or phonetic result
     *
     * self::SEARCHMODE_*
     *
     * @var string
     */
    public $searchMode = self::SEARCHMODE_EXACT;

    /**
     * The name of the location to search.
     *
     * @var string
     */
    public $locationName;

    /**
     * The location IATA code to search
     *
     * 3-character IATA location code
     *
     * @var string
     */
    public $locationCode;

    /**
     * Search for the location in a given country
     *
     * 2-character ISO 3166-1 code
     *
     * @var string
     */
    public $restrictCountry;

    /**
     * Search for the location in a given state
     *
     * @var string
     */
    public $restrictState;

    /**
     * What kind of result to select (only airports, heliports, ...)
     *
     * self::SELECT_*
     *
     * @var string
     */
    public $selectResult;
}
