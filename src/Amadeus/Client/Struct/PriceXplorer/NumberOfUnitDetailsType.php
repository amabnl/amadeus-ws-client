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

/**
 * Structure class for the NumberOfUnitDetailsType message part for PriceXplorer_* messages
 *
 * @package Amadeus\Client\Struct\PriceXplorer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class NumberOfUnitDetailsType
{

    /*
     * https://webservices.amadeus.com/extranet/structures/viewMessageStructure.do?id=2338&serviceVersionId=2304&isQuery=true#
     *     CNS     Cheapest non-stop
     *     COP     Cheapest over all price
     *     DAY     Day
     *     MTH     Month
     *     PR     Number of price results
     */
    const QUAL_CHEAPEST_NONSTOP = "CNS";

    const QUAL_CHEAPEST_OVERALL = "COP";

    const QUAL_DAY = "DAY";

    const QUAL_MONTH = "MTH";

    const QUAL_NR_OF_PRICE_RESULTS = "PR";


    /**
     * @var int
     */
    public $numberOfUnit;

    /**
     * @var string self::QUAL_*
     */
    public $unitQualifier;

    /**
     * Create NumberOfUnitDetailsType
     *
     * @param int|null $numberOfUnit
     * @param string $qualifier One of the constants self::QUAL_*
     */
    public function __construct($numberOfUnit = null, $qualifier = self::QUAL_DAY)
    {
        $this->numberOfUnit = $numberOfUnit;
        $this->unitQualifier = $qualifier;
    }
}
