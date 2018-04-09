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

namespace Amadeus\Client\RequestOptions\Pnr\DisplayHistory;

use Amadeus\Client\LoadParamsFromArray;

/**
 * PredicateType
 *
 * @package Amadeus\Client\RequestOptions\Pnr\DisplayHistory
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PredicateType extends LoadParamsFromArray
{
    const REFQUAL_MARRIAGE_DOMINANT = "D";
    const REFQUAL_MARRIAGE_NON_DOMINANT = "N";
    const REFQUAL_OTHER_TATTOO = "O";
    const REFQUAL_PASSENGER_TATTOO = "PT";
    const REFQUAL_SEGMENT_TATTOO_SUBTATTOO = "SS";
    const REFQUAL_SEGMENT_TATTOO = "ST";
    const REFQUAL_PASSENGER_CLIENT_REQUEST_MESSAGE = "PR";
    const REFQUAL_SEGMENT_CLIENT_REQUEST_MESSAGE = "SR";

    /**
     * Element name, one of the following:
     *
     * AB Billing address element
     * AI Accounting information element
     * AIR Air segment
     * AM Mailing address element
     * AP Phone element
     * AQ Address verification element
     * ATT Attachment element
     * ATX Air Taxi segment
     * CAR Manual Car segment
     * CCR Car segment
     * CRU Cruise segment
     * ES Security element
     * FA FA fare element
     * FB FB fare element
     * FD FD fare element
     * FE FE fare element
     * FER Ferry segment
     * FG FG fare element
     * FH FH fare element
     * FI FI fare element
     * FIN Financial Item element
     * FK FK fare element
     * FM FM fare element
     * FN FN fare element
     * FO FO fare element
     * FP FP fare element
     * FS FS fare element
     * FT FT fare element
     * FV FV fare element
     * FY FY fare element
     * FZ FZ fare element
     * GT Group Name
     * HHL Hotel segment
     * HTL Manual Hotel segment
     * MCO Miscellaneous Charges Order
     * MIS Manual Miscellaneous segment
     * NM Name element
     * OP Option element
     * OSI Other Service Information element
     * RC RC secured remark element
     * RI Invoice remark element
     * RM Remark element
     * RQ Quality control remark element
     * RX RX secured remark element
     * SK Keyword element
     * SSR Special Service Request element
     * ST Seat element
     * SUR Surface segment
     * TK Ticket element
     * TRN Train segment
     * TTO Tour Source segment
     * TUR Manual Tour segment
     *
     * @var string
     */
    public $elementName;

    /**
     * Identification number
     *
     * @var integer
     */
    public $reference;

    /**
     * self::REFQUAL_*
     *
     * @var string
     */
    public $referenceQualifier;
}
