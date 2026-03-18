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
 * Search Criteria
 *
 * @package Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight
 * @author Evan Chuang <ycchuang1999@gmail.com>
 */
class SearchCriteria extends LoadParamsFromArray
{
    const PRIMARY_SEARCH_CRITERION_BY_AUXILIARY_SEGMENTS = 'AUX';
    const PRIMARY_SEARCH_CRITERION_PNR_HAS_AN_AXR_TAG = 'AXR';
    const PRIMARY_SEARCH_CRITERION_BY_CABIN = 'CAB';
    const PRIMARY_SEARCH_CRITERION_BY_CHARGEABLE_SSRS = 'CGB';
    const PRIMARY_SEARCH_CRITERION_BY_CHECK_IN_FLAG = 'CHK';
    const PRIMARY_SEARCH_CRITERION_BY_CLASS_OF_SERVICE = 'CLA';
    const PRIMARY_SEARCH_CRITERION_CONNECTING_PASSENGERS = 'CNT';
    const PRIMARY_SEARCH_CRITERION_CONFIRMED_PASSENGERS = 'CON';
    const PRIMARY_SEARCH_CRITERION_CONFIRMED_AND_SPACE_AVAILABLE_PASSENGERS = 'CSA';
    const PRIMARY_SEARCH_CRITERION_CONFIRMED_AND_SPACE_AVAILABLE_TRANSFER_PASSENGER = 'CST';
    const PRIMARY_SEARCH_CRITERION_PNR_HAS_A_CUSTOMER_PROFILE_REFERENCE = 'CUS';
    const PRIMARY_SEARCH_CRITERION_BY_INACTIVE_STATUS_CODE = 'INA';
    const PRIMARY_SEARCH_CRITERION_BY_INBOUND_CONNECTION = 'INB';
    const PRIMARY_SEARCH_CRITERION_BY_ISSUAING_OFFICE = 'ISS';
    const PRIMARY_SEARCH_CRITERION_BY_SECURED_KEYWORD = 'KWD';
    const PRIMARY_SEARCH_CRITERION_LAST_BOOKED_PASSENGERS = 'LAS';
    const PRIMARY_SEARCH_CRITERION_LATE_BOOKED_PASSENGERS = 'LAT';
    const PRIMARY_SEARCH_CRITERION_BY_A_LLIANCE_FREQUENT_FLYER_DATA = 'LFQ';
    const PRIMARY_SEARCH_CRITERION_BY_PASSENGER_NAME = 'NAM';
    const PRIMARY_SEARCH_CRITERION_NON_COMMERCIAL_PNR = 'NCP';
    const PRIMARY_SEARCH_CRITERION_BY_NEGO_OR_1A_ID = 'NEG';
    const PRIMARY_SEARCH_CRITERION_BY_NUMBER_IN_PARTY = 'NIP';
    const PRIMARY_SEARCH_CRITERION_BY_CHARGEABLE_SSR_WITHOUT_AN_EMD = 'NMD';
    const PRIMARY_SEARCH_CRITERION_NO_SHOW_PASSENGERS = 'NOS';
    const PRIMARY_SEARCH_CRITERION_PASSENGERS_WITHOUT_TICKETING_DATA = 'NTK';
    const PRIMARY_SEARCH_CRITERION_OPERATING_PNR = 'OPR';
    const PRIMARY_SEARCH_CRITERION_BY_OPTION_ELEMENT = 'OPT';
    const PRIMARY_SEARCH_CRITERION_BY_OSI = 'OSI';
    const PRIMARY_SEARCH_CRITERION_BY_OUTBOUND_CONNECTION = 'OUT';
    const PRIMARY_SEARCH_CRITERION_BY_PNR_OWNER = 'OWN';
    const PRIMARY_SEARCH_CRITERION_BY_PHONE_FIELD = 'PHO';
    const PRIMARY_SEARCH_CRITERION_BY_PASSENGER_ID = 'PID';
    const PRIMARY_SEARCH_CRITERION_BY_POINT_OF_SALE = 'POS';
    const PRIMARY_SEARCH_CRITERION_PRIME_PNR = 'PRI';
    const PRIMARY_SEARCH_CRITERION_PASSENGERS_WITH_PREPAID_TICKED = 'PTA';
    const PRIMARY_SEARCH_CRITERION_BY_AIRLINE_FREQUENT_FLYER_DATA = 'RFQ';
    const PRIMARY_SEARCH_CRITERION_BY_SEAT = 'SEA';
    const PRIMARY_SEARCH_CRITERION_BY_SSR = 'SSR';
    const PRIMARY_SEARCH_CRITERION_BY_BOOKING_STATUS_CODE = 'STC';
    const PRIMARY_SEARCH_CRITERION_TICKETED_PASSENGERS = 'TKT';
    const PRIMARY_SEARCH_CRITERION_UNCONFIRMED_PASSENGERS = 'UNC';
    const PRIMARY_SEARCH_CRITERION_UNTICKETED_PASSENGERS = 'UTK';
    const PRIMARY_SEARCH_CRITERION_WAITLISTED_PASSENGERS = 'WAI';

    const NEGATIVE_MODE_NOP = 'NOP';
    const NEGATIVE_MODE_NOT = 'NOT';

    const ASSOCIATION_MODE_AND = 'AND';
    const ASSOCIATION_MODE_OR = 'OR';

    /**
     * A primary search criterion can be expressed as: - a functional rule based on different PNR elements
     *
     * self::PRIMARY_SEARCH_CRITERION_*
     *
     * @var string
     */
    public $primarySearchCriterion;

    /**
     * Indicates how the primary search criterion has to be interpreted
     *
     * self::NEGATIVE_MODE_*
     *
     * @var string
     */
    public $negativeMode;

    /**
     * Indicates how the secondary search criteria must be combined
     *
     * self::ASSOCIATION_MODE_*
     *
     * @var string
     */
    public $associationMode;

    /**
     * This group is able to convey any secondary search
     * criteria (i.e. search values). A secondary search
     * criterion can consist of: - a PNR element type (e.g.
     * FB element). In this case, EMS segment is used- the actual value of a business object (e.g. class
     * of service). Segments in this group are mutually
     * exclusive.
     *
     * @var SecondarySearchCriteria[]
     */
    public $secondarySearchCriteria = [];
}
