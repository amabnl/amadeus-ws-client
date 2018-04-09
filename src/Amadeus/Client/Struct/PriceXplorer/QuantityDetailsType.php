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
 * Structure class for the QuantityDetailsType message part for PriceXplorer_* messages
 *
 * @package Amadeus\Client\Struct\PriceXplorer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class QuantityDetailsType
{
    /**
     * https://webservices.amadeus.com/extranet/structures/viewMessageStructure.do?id=2338&serviceVersionId=2304&isQuery=true#
     * PriceXplorer_ExtremeSearch/itineraryGrp/quantityInfo/quantityDetails/unit
     * UNITS:
     * DAY     Day
     * MTH     Month
     * WK     Week
     */
    const UNIT_DAY = "DAY";

    const UNIT_WEEK = "WK";

    const UNIT_MONTH = "MTH";

    /**
     * https://webservices.amadeus.com/extranet/structures/viewMessageStructure.do?id=2338&serviceVersionId=2304&isQuery=true#
     * PriceXplorer_ExtremeSearch/itineraryGrp/quantityInfo/quantityDetails/qualifier
     * @var string
     */
    const QUAL_PLUS = "P";

    /**
     * @var string
     */
    public $qualifier;
    /**
     * @var int
     */
    public $value;
    /**
     * @var string
     */
    public $unit;

    /**
     * @param int $value
     * @param string $unit
     * @param string $qualifier
     */
    public function __construct($value, $unit = self::UNIT_DAY, $qualifier = self::QUAL_PLUS)
    {
        $this->value = $value;
        $this->unit = $unit;
        $this->qualifier = $qualifier;
    }
}
