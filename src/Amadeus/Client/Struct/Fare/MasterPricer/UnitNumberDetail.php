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

namespace Amadeus\Client\Struct\Fare\MasterPricer;

/**
 * UnitNumberDetail
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class UnitNumberDetail
{
    const TYPE_PASS = "PX";
    const TYPE_RESULTS = "RC";

    /**
     * @var int
     */
    public $numberOfUnits;

    /**
     *
     * Possible values:
     *
     * A  Adult
     * BB  Number of best buy to be issued by FQ
     * BD  Bad day good day
     * BS  Block space
     * C  Child
     * CP  Corporate
     * DA  Day(s) after
     * DB  Day(s) before
     * F  Female
     * G  Group
     * I  Individual
     * IF  infant female
     * IM  infant male
     * IN  Infant
     * IZ  Individual within a group
     * L  Airport lounge member
     * M  Male
     * ML  Number of meals served
     * MX  Maximum number of flights desired
     * N  Military
     * NC  Number of columns
     * NL  Number of lines
     * PX  Number of seats occupied by passengers on board
     * QW  Quality weight
     * RC  Number of requested recommendation
     * S  Same surname
     * SND  Slice and Dice
     * SP  Standby positive
     * SS  Standby space available
     * T  Frequent traveler
     * TA  Total seats available to assign
     * TC  Total cabin class/compartment capacity
     * TCA  Total cabin/compartment seats with acknowledgment
     * TD  Number of ticket or document numbers
     * TF  Total number of flight segments
     * TH  Time window size type is in hours
     * TM  Time window size type is in minutes
     * TS  Total seats sold
     * TU  Total seats unassigned
     * TUA  Total unassigned seats with acknowledgment pending
     * UM  Unaccompanied minor
     * WT  distribution weight
     *
     * https://webservices.amadeus.com/extranet/structures/viewMessageStructure.do?id=2305&serviceVersionId=2286#
     *
     * @var string
     */
    public $typeOfUnit;

    /**
     * @param int|null $mainUnitNumber
     * @param string|null $mainUnitType
     */
    public function __construct($mainUnitNumber = null, $mainUnitType = null)
    {
        $this->numberOfUnits = $mainUnitNumber;
        $this->typeOfUnit = $mainUnitType;
    }
}
