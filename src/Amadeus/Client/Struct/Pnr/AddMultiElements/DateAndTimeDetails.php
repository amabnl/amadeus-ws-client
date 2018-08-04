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

namespace Amadeus\Client\Struct\Pnr\AddMultiElements;

/**
 * DateAndTimeDetails
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DateAndTimeDetails
{
    const QUAL_DATEOFBIRTH = 706;

    const QUAL_NOT_VALID_BEFORE = "B";

    /**
     * self::QUAL_*
     *
     * PNR_AddMultiElements/travellerInfo/passengerData/dateOfBirth/dateAndTimeDetails/qualifier
     *
     * Date/Time/Period qualifer codesets
     *
     * Value Description
     *
     * 700 CRT date and time
     * 701 Ticket effective date
     * 702 Deposit start date
     * 703 Deposit end date
     * 704 Days earlier
     * 705 Days later
     * 706 Date of birth
     * 707 By arrival time
     * 708 By departure time
     * 709 By elapsed time
     * 710 Date Ticketed
     * 711 Date/time queued
     * A Not Valid After - Last Travel Date
     * AA Actual arrival information
     * ACL Actual time, in local
     * ACT Actual time
     * AD Actual departure off blocks information
     * AI Airborne information
     * ALL Allocated time, in local
     * ALT Allocated time
     * B Not Valid Before - First Travel Date
     * COL Confirmed time, in local
     * COT Confirmed time
     * DV Flight diverted
     * DX Flight cancelled
     * E Early
     * EA Estimated arrival touchdown information
     * EAL Earliest time, in local
     * EAT Earliest time
     * EB Estimated on blocks information
     * ED Estimated departure off blocks information
     * EET Estimated elapsed time (EET) in HHMM
     * ENL End time, in local
     * ENT End time
     * EO Estimated take off information
     * ESL Estimated time, in local
     * EST Estimated time
     * EX Credit expiration date
     * FLT Estimated Flying time in HHMM
     * FR Forced return information
     * HEL Hidden Estimate, in local
     * HET Hidden Estimate
     * HIL Hidden time, in local
     * HIT Hidden time
     * HNL Hidden Next Info, in local
     * HNT Hidden Next Info
     * L Local time mode
     * LA Late
     * LAL Latest time, in local
     * LAT Latest time
     * LT Local time
     * LX Landing cancelled
     * MNG Minimum ground time in HHMM
     * NIL Next Info, in local
     * NIT Next Info
     * OB Actual on blocks information
     * RC Reclearance information
     * REL Recommended time, in local
     * RET Recommended time
     * RR Return to ramp information
     * SCL Scheduled time, in local
     * SCT Scheduled time
     * STL Start time, in local
     * STT Start time
     * T Transaction
     * U UTC time mode
     * ZT GMT time
     *
     * @var string
     */
    public $qualifier;
    /**
     * @var string
     */
    public $date;

    /**
     * @param string|null $date
     * @param string|null $qualifier
     */
    public function __construct($date = null, $qualifier = null)
    {
        $this->date = $date;
        $this->qualifier = $qualifier;
    }
}
