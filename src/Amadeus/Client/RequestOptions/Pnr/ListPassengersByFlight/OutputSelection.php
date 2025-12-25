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

namespace Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Output Selection
 *
 * @package Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight
 * @author Evan Chuang <ycchuang1999@gmail.com>
 */
class OutputSelection extends LoadParamsFromArray
{
    const OUTPUT_TYPE_COUNTERS_ONLY = 'CNT';
    const OUTPUT_TYPE_COUNTERS_AND_PNR_IMAGES = 'IMG';
    const OUTPUT_TYPE_QUEUE_PLACE_MATCHING_PNRS = 'QUE';

    const ELEMENT_TYPE_AIR_SEGMENTS = 'AIR';
    const ELEMENT_TYPE_CONTACT = 'AP';
    const ELEMENT_TYPE_ASSOCIATED_CROSS_REFERENCE_RECORD = 'AXR';
    const ELEMENT_TYPE_CUSTOMER_PROFILE_REFERENCE = 'CUS';
    const ELEMENT_TYPE_TICKET_NUMBER_AUTOMATED_TICKETS = 'FA';
    const ELEMENT_TYPE_AIR_SEQUENCE_NUMBER = 'FB';
    const ELEMENT_TYPE_FARE_DISCOUNT = 'FD';
    const ELEMENT_TYPE_ENDORSEMENTS_RESTRICTIONS = 'FE';
    const ELEMENT_TYPE_SHADOW_AIR_SEQUENCE_NUMBER = 'FG';
    const ELEMENT_TYPE_MANUAL_DOCUMENT_REGISTRATION = 'FH';
    const ELEMENT_TYPE_AUTOMATED_INVOICE_NUMBER = 'FI';
    const ELEMENT_TYPE_SHADOW_DESTINATION = 'FK';
    const ELEMENT_TYPE_COMMISSION = 'FM';
    const ELEMENT_TYPE_TRANSMISSION_CONTROL_NUMBER = 'FN';
    const ELEMENT_TYPE_ORIGINAL_ISSUE_ISSUE_IN_EXCHANGE_FOR = 'FO';
    const ELEMENT_TYPE_FORM_OF_PAYMENT = 'FP';
    const ELEMENT_TYPE_MISCELLANEOUS_TICKETING_INFORMATION = 'FS';
    const ELEMENT_TYPE_TOUR_CODE = 'FT';
    const ELEMENT_TYPE_TICKETING_CARRIER_DESIGNATOR = 'FV';
    const ELEMENT_TYPE_FARE_PRINT = 'FY';
    const ELEMENT_TYPE_MISCELLANEOUS_INFORMATION = 'FZ';
    const ELEMENT_TYPE_HEADER_PASSENGER_NAME_DATA_AND_RECORD_LOCATOR = 'HDR';
    const ELEMENT_TYPE_OPTION = 'OP';
    const ELEMENT_TYPE_OSI = 'OSI';
    const ELEMENT_TYPE_REMARK = 'RM';
    const ELEMENT_TYPE_REPLICATION_INFORMATION = 'RR';
    const ELEMENT_TYPE_SEAT = 'SEA';
    const ELEMENT_TYPE_SECURED_KEYWORD = 'SK';
    const ELEMENT_TYPE_SPLIT_INFORMATION = 'SP';
    const ELEMENT_TYPE_SSR = 'SSR';
    const ELEMENT_TYPE_TICKET = 'TK';
    const ELEMENT_TYPE_TRANSITIONAL_STORED_TICKET = 'TST';

    /**
     * To select Output: PNRs Counters only PNRs Counters & PNRs images. Queue-Placed PNRs
     *
     * self::OUTPUT_TYPE_*
     *
     * @var string
     */
    public $outputType;

    /**
     * Used for specifying the PNR data elements to be included in the response
     *
     * self::ELEMENT_TYPE_*
     *
     * @var string[]
     */
    public $elementType = [];
}
