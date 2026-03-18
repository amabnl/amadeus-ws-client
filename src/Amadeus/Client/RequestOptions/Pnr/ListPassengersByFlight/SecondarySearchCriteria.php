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
 * Secondary Search Criteria
 *
 * @package Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight
 * @author Evan Chuang <ycchuang1999@gmail.com>
 */
class SecondarySearchCriteria extends LoadParamsFromArray
{
    const CLASS_OF_SERVICE_BUSINESS = 'C';
    const CLASS_OF_SERVICE_ECONOMY = 'Y';

    const STATUS_CODE_HOLDING_CONFIRMED = 'HK';
    const STATUS_CODE_HAVE_WAITLISTED = 'HL';
    const STATUS_CODE_UNABLE = 'UN';

    const TICKET_ARRANGEMENT_AIRPORT = 'AT';
    const TICKET_ARRANGEMENT_DOMESTIC = 'DO';
    const TICKET_ARRANGEMENT_INTERNATIONAL = 'IN';
    const TICKET_ARRANGEMENT_MA = 'MA';
    const TICKET_ARRANGEMENT_TICKETED = 'OK';
    const TICKET_ARRANGEMENT_PREPAID_TICKET = 'PT';
    const TICKET_ARRANGEMENT_SELF_SERVICE_TICKETING_PRE_VALIDATION = 'SS';
    const TICKET_ARRANGEMENT_SATELLITE_TICKETING = 'ST';
    const TICKET_ARRANGEMENT_TIME_LIMIT = 'TL';
    const TICKET_ARRANGEMENT_REVALIDATION = 'TR';
    const TICKET_ARRANGEMENT_AUTOMATIC_PNR_CANCELLATION = 'XL';

    const SEGMENT_NAME_AIR_TAXI_AUXILIARY = 'ATX';
    const SEGMENT_NAME_MANUAL_CAR = 'CAR';
    const SEGMENT_NAME_AUTOMATED_CAR = 'CCR';
    const SEGMENT_NAME_CRUISE = 'CRU';
    const SEGMENT_NAME_EMD_DE_SYNCHRONIZATION = 'EMD';
    const SEGMENT_NAME_TICKET_NUMBER_AUTOMATED = 'FA';
    const SEGMENT_NAME_AIR_SEQUENCE_NUMBER = 'FB';
    const SEGMENT_NAME_FARE_DISCOUNT = 'FD';
    const SEGMENT_NAME_ENDORSEMENTS_RESTRICTIONS = 'FE';
    const SEGMENT_NAME_FERRY = 'FER';
    const SEGMENT_NAME_SHADOW_AIR_SEQUENCE_NUMBER = 'FG';
    const SEGMENT_NAME_MANUAL_DOCUMENT_REGISTRATION = 'FH';
    const SEGMENT_NAME_AUTOMATED_INVOICE_NUMBER = 'FI';
    const SEGMENT_NAME_COMMISSION = 'FM';
    const SEGMENT_NAME_TRANSMISSION_CONTROL_NUMBER = 'FN';
    const SEGMENT_NAME_ORIGINAL_ISSUE_EXCHANGE_FOR = 'FO';
    const SEGMENT_NAME_FORM_OF_PAYMENT = 'FP';
    const SEGMENT_NAME_MISC_TICKETING_INFORMATION = 'FS';
    const SEGMENT_NAME_TOUR_CODE = 'FT';
    const SEGMENT_NAME_TICKETING_CARRIER_DESIGNATOR = 'FV';
    const SEGMENT_NAME_MISCELLANEOUS_INFORMATION = 'FZ';
    const SEGMENT_NAME_AUTOMATED_HOTEL = 'HHL';
    const SEGMENT_NAME_MANUAL_HOTEL = 'HTL';
    const SEGMENT_NAME_INSURANCE = 'INS';
    const SEGMENT_NAME_INVOLUNTARY_DE_SYNCHRONIZATION = 'INV';
    const SEGMENT_NAME_MEMO_AUXILIARY = 'MIS';
    const SEGMENT_NAME_GROUND_TRANSPORTATION = 'SUR';
    const SEGMENT_NAME_CUSTOMIZED_AUXILIARY = 'SVC';
    const SEGMENT_NAME_RAIL = 'TRN';
    const SEGMENT_NAME_AUTOMATED_TOUR = 'TTR';
    const SEGMENT_NAME_MANUAL_TOUR = 'TUR';
    const SEGMENT_NAME_VOLUNTARY_DE_SYNCHRONIZATION = 'VOL';

    /**
     * Used for a search by class of Service.
     *
     * self::CLASS_OF_SERVICE_*
     *
     * @var string
     */
    public $classOfService;

    /**
     * Used for a search by: - inbound/outbound connection - marketing carrier/flight In both cases a
     * carrier code or a full flight number can be specified. Composites are conditional because
     * they are used in mutually exclusive situations
     *
     * @var CarrierOrFlight
     */
    public $carrierOrFlight;

    /**
     * Used for a search for: - specific SSR/OSI/SK type- seat number Composites are conditional
     * because they are used in mutually exclusive situations
     *
     * @var SsrOsiSk
     */
    public $ssrOsiSk;

    /**
     * Cabin class designator
     *
     * @var string
     */
    public $cabinCode;

    /**
     * Booking status
     *
     * self::STATUS_CODE_*
     *
     * @var string
     */
    public $statusCode;

    /**
     * Used for a search by: - passenger last name- passenger type - name type in a group PNR
     * (corporate or individual name) Composites are conditional because they are
     * used in mutually exclusive situations
     *
     * @var PassengerNameOrType
     */
    public $passengerNameOrType;

    /**
     * Used for a search by: - PNR Owner - POS (system or country) - AP element (City code) Composites
     * are conditional because they are used in mutually exclusive situations
     *
     * @var Origin
     */
    public $origin;

    /**
     * Used when searching for: - ticketed passengers unticketed passengers
     *
     * self::TICKET_ARRANGEMENT_*
     *
     * @var string
     */
    public $ticketArrangement;

    /**
     * Used for a search by: - connection time (e.g. less than 4 hours) -  PNR number in party
     * (e.g. NIP between 2 and 8) - number of passengers (e.g. 10 last booked passengers)
     *
     * @var NumericRange
     */
    public $numericRange;

    /**
     * PNR segment or element name
     *
     * self::SEGMENT_NAME_*
     *
     * @var string
     */
    public $segmentName;

    /**
     * Used for a search by: - airline priority code - airline tier level - alliance tier description Data elements
     * are conditional because they are used in mutually exclusive situations.
     *
     * @var FrequentFlyer
     */
    public $frequentFlyer;

    /**
     * Unique ID for negociated space
     *
     * @var NegoRecordLocator
     */
    public $negoRecordLocator;

    /**
     * To Filter Search using Passenger Name - Supporting Non-Roman Scripts (UTF-8)
     *
     * @var string
     */
    public $extendedPassengerName;
}
