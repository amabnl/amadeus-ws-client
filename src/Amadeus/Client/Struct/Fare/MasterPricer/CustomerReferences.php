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
 * CustomerReferences
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CustomerReferences extends \Amadeus\Client\Struct\Air\MultiAvailability\CustomerReferences
{
    const QUAL_UNIQUE_PAX_REF = 1;
    const QUAL_PAX_SEQUENCE_NUMBER = 2;
    const QUAL_PAX_STANDBY_NUMBER = 3;
    const QUAL_PAX_BOARDING_SECURITY_NUMBER = 4;
    const QUAL_PAX_TICKET_NUMBER = 5;
    const QUAL_PAX_CONFIRMATION_NUMBER = 6;
    const QUAL_DATE_OF_BIRTH = 7;
    const QUAL_EXCEPTIONAL_PNR_SECURITY_ID = 700;
    const QUAL_AGENCY_GROUPING_ID = 701;
    const QUAL_TICKETING_DATA = 702;
    const QUAL_MESSAGE_NUMBER_FOR_FREE_TEXT = 703;
    const QUAL_ACCOUNT_PRODUCT_REF = "A";
    const QUAL_BUSINESS = "B";
    const QUAL_FAX = "F";
    const QUAL_HOME = "H";
    const QUAL_PAX_TRAVELLER_REF = "P";
    const QUAL_SEGMENT_SERVICE_REF = "S";
    const QUAL_TELETYPE_ADDRESS = "T";
    const QUAL_NOT_KNOWN = "XX";

    /**
     * @var string
     */
    public $referencePartyName;

    /**
     * @var string
     */
    public $travellerReferenceNbr;

    /**
     * CustomerReferences constructor.
     *
     * @param string $reference Customer Reference
     * @param int|string $qualifier Type of reference (self::QUAL_*)
     */
    public function __construct($reference, $qualifier)
    {
        $this->referenceQualifier = $qualifier;

        parent::__construct($reference);
    }
}
