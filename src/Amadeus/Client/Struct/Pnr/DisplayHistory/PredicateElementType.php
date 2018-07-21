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

namespace Amadeus\Client\Struct\Pnr\DisplayHistory;

/**
 * PredicateElementType
 *
 * @package Amadeus\Client\Struct\Pnr\DisplayHistory
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PredicateElementType
{
    const SEGNAME_BILLING_ADDRESS = "AB";
    const SEGNAME_STRUCTURED_BILLING_ADDRESS = "AB/";
    const SEGNAME_ACCOUNTING_INFORMATION = "AI";
    const SEGNAME_AIR = "AIR";
    const SEGNAME_TRAIN_PENDING_ACK = "AK";
    const SEGNAME_MAILING_ADDRESS = "AM";
    const SEGNAME_STRUCTURED_MAILING_ADDRESS = "AM/";
    const SEGNAME_CONTACT = "AP";
    const SEGNAME_ADDRESS_VERIFICATION = "AQ";
    const SEGNAME_NON_AUTOMATED_AIR_TAXI_AUXILIARY = "AU";
    const SEGNAME_BACKOFFICE_INFORMATION = "AZ";
    const SEGNAME_AUTOMATED_CAR_AUXILIARY = "CCR";
    const SEGNAME_CRUISE_DISTRIBUTION = "CRU";
    const SEGNAME_NON_AUTOMATED_CAR_AUXILIARY = "CU";
    const SEGNAME_INDIVIDUAL_PNR_SECURITY = "ES";
    const SEGNAME_TICKET_NUMBER_AUTOMATED_TICKETS = "FA";
    const SEGNAME_AIR_SEQUENCE_NUMBER = "FB";
    const SEGNAME_FARE_DISCOUNT = "FD";
    const SEGNAME_ENDORSEMENTS_RESTRICTIONS = "FE";
    const SEGNAME_SHADOW_AIR_SEQUENCE_NUMBER = "FG";
    const SEGNAME_MANUAL_DOCUMENT_REGISTRATION = "FH";
    const SEGNAME_AUTOMATED_INVOICE_NUMBER = "FI";
    const SEGNAME_COMMISSION = "FM";
    const SEGNAME_TRANSMISSION_CONTROL_NUMBER = "FN";
    /**
     * Original Issue / Issue in Exchange For element
     */
    const SEGNAME_ORIGINAL_ISSUE = "FO";
    const SEGNAME_FORM_OF_PAYMENT = "FP";
    const SEGNAME_FERRY = "FRR";
    const SEGNAME_MISCELLANEOUS_TICKETING_INFORMATION = "FS";
    const SEGNAME_TOUR_CODE = "FT";
    const SEGNAME_TICKETING_CARRIER_DESIGNATOR = "FV";
    const SEGNAME_MISCELLANEOUS_INFORMATION = "FZ";
    const SEGNAME_NON_AUTOMATED_AY_DIRECT_ACCESS_HOTEL = "HAY";
    const SEGNAME_AUTOMATED_HOTEL_AUXILIARY = "HHL";
    const SEGNAME_NON_AUTOMATED_HOTEL_AUXILIARY = "HU";
    const SEGNAME_GROUP_NAME = "NG";
    const SEGNAME_OPTION = "OP";
    const SEGNAME_OTHER_SPECIAL_INFORMATION = "OS";
    const SEGNAME_PRIORITY_LINE = "PL";
    const SEGNAME_CONFIDENTIAL_REMARK = "RC";
    const SEGNAME_RECEIVE_FROM = "RF";
    const SEGNAME_INVOICE_REMARK = "RI";
    const SEGNAME_GENERAL_REMARK = "RM";
    const SEGNAME_QUALITY_CONTROL_REMARK = "RQ";
    const SEGNAME_ASSOCIATED_CROSS_REFERENCE_RECORD = "RR";
    const SEGNAME_NON_AUTOMATED_MISCELLANEOUS_AUXILIARY = "RU";
    const SEGNAME_SPLIT_PARTY = "SP";
    const SEGNAME_SPECIAL_SERVICE_REQUEST = "SSR";
    const SEGNAME_GROUND_TRANSPORTATION_SURFACE = "SUR";
    const SEGNAME_TICKET = "TK";
    const SEGNAME_TRAIN_AMTRAK = "TRN";
    const SEGNAME_TOUR_SOURCE = "TTO";
    const SEGNAME_NON_AUTOMATED_TOUR_AUXILIARY = "TU";

    /**
     * @var Reference
     */
    public $reference;

    /**
     * self::SEGNAME_*
     *
     * @var string
     */
    public $segmentName;

    /**
     * PredicateElementType constructor.
     *
     * @param string $segmentName self::SEGNAME_*
     */
    public function __construct($segmentName)
    {
        $this->segmentName = $segmentName;
    }
}
