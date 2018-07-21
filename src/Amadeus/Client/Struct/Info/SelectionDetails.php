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

namespace Amadeus\Client\Struct\Info;

/**
 * SelectionDetails
 *
 * @package Amadeus\Client\Struct\Info
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SelectionDetails
{
    const OPT_SEARCH_ALGORITHM = "ALG";

    const OPT_LOCATION_TYPE = "LTY";


    const OPTINF_AIRPORT = "A";

    const OPTINF_ALL_LOCATIONS = "ALL";

    const OPTINF_BUS_STATION = "B";

    const OPTINF_CITY = "C";

    const OPTINF_SEARCH_EXACT_MATCH = "EXT";

    const OPTINF_GROUND_TRANSPORT = "G";

    const OPTINF_HELIPORT = "H";

    const OPTINF_OFFLINE_POINT = "O";

    const OPTINF_SEARCH_PHONETIC = "PHO";

    const OPTINF_RAILWAY_STATION = "R";

    const OPTINF_ASSOCIATED_LOCATION = "S";


    /**
     * self::OPT_*
     *
     * @var string
     */
    public $option;

    /**
     * self::OPTINF_*
     *
     * @var string
     */
    public $optionInformation;

    /**
     * SelectionDetails constructor.
     *
     * @param string $optionInfo self::OPTINF_*
     * @param string $option self::OPT_*
     */
    public function __construct($optionInfo, $option)
    {
        $this->optionInformation = $optionInfo;
        $this->option = $option;
    }
}
