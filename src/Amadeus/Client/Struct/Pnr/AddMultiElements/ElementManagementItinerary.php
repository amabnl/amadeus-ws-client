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
 * ElementManagementItinerary
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ElementManagementItinerary
{
    const SEGMENT_MISCELLANEOUS = "RU";

    const SEGMENT_AIR = "AIR";

    const SEGMENT_HOTEL_AUX = "HU";

    /**
     * @var Reference
     */
    public $reference;
    /**
     * PNR_AddMultiElements/originDestinationDetails/itineraryInfo/elementManagementItinerary/segmentName
     *
     * AB Billing Address element
     * ABU Unstructured Billing Address element
     * AI Accounting Information element
     * AIR Air segment
     * AM Mailing address element
     * AMU Unstructured Mailing Address Element
     * AP Contact element
     * AU ATX - Non-automated Air Taxi auxiliary segment
     * CU Non-automated Car auxiliary segment
     * ES Individual PNR Security element
     * FD Fare Discount element
     * FE Endorsements / Restrictions element
     * FF Frequent Flyer entry
     * FH Manual Document Registration element
     * FHA Automated ticket number
     * FHE Electronic ticket number
     * FHM Manual ticket number/document registration element
     * FK AIR destination
     * FM Commission element
     * FO Original Issue / Issue in Exchange For element
     * FP Form of Payment element
     * FS Miscellaneous Ticketing Information element
     * FT Tour Code element
     * FV Ticketing Carrier Designator element
     * FY Fare print override element
     * FZ Miscellaneous Information element
     * HU Non-automated Hotel auxiliary segment
     * NG Group Name element
     * NM Name element
     * NZ Non Commerciak PNR Name element
     * OP Option element
     * OS Other Special Information element
     * RC Confidential Remark element
     * RF Receive From element
     * RI Invoice remark
     * RM General Remark elementt
     * RQ Quality control remark element
     * RU Non-automated Miscellaneous auxiliary segment
     * RX Corporate Remark
     * SK Special Keyword elements
     * SSR SSR element
     * STR Seat Request
     * TK Ticket element
     * TU Non-automated Tour auxiliary segment
     *
     * @var string
     */
    public $segmentName;

    /**
     * ElementManagementItinerary constructor.
     *
     * @param int $tattooNr
     * @param string $segmentName
     */
    public function __construct($tattooNr, $segmentName)
    {
        $this->segmentName = $segmentName;
        $this->reference = new Reference(Reference::QUAL_OTHER, $tattooNr);
    }
}
