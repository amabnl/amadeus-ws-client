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

namespace Amadeus\Client\RequestOptions;

use Amadeus\Client\RequestOptions\Pnr\Retrieve\FrequentTraveller;
use Amadeus\Client\RequestOptions\Pnr\Retrieve\Ticket;

/**
 * PnrRetrieveRequestOptions
 *
 * The options available when doing a PNR_Retrieve call.
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PnrRetrieveOptions extends Base
{
    const OPTION_ASSOCIATED_PNRS = 'X';
    const OPTION_ACTIVE_ONLY = 'A';
    const OPTION_MERGE_SPLIT_PNRS = 'V';

    const RETRIEVE_ACTIVE_PNR = 1;
    const RETRIEVE_BY_RECORD_LOCATOR = 2;
    const RETRIEVE_BY_OFFICE_AND_NAME = 3;
    const RETRIEVE_BY_SERVICE_AND_NAME = 4;
    const RETRIEVE_BY_FREQUENT_TRAVELLER = 5;
    const RETRIEVE_BY_ACCOUNT_NUMBER = 6;
    const RETRIEVE_BY_CUSTOMER_PROFILE = 7;
    const RETRIEVE_FROM_LIST = 25; //NOT SUPPORTED YET
    const RETRIEVE_BY_INSURANCE_POLICY_NUMBER = 8; //NOT SUPPORTED YET
    const RETRIEVE_BY_NUMERIC_RECORD_LOCATOR = 9; //NOT SUPPORTED YET
    const RETRIEVE_FOR_TICKETING = 95; //NOT SUPPORTED YET

    const SERVICE_AIRLINE = 'AIR';
    const SERVICE_AIR_TAXI = 'ATX';
    const SERVICE_MANUAL_CAR = 'CAR';
    const SERVICE_AUTOMATED_CAR = 'CCR';
    const SERVICE_CRUISE = 'CRU';
    const SERVICE_FERRY = 'FRR';
    const SERVICE_AUTOMATED_HOTEL = 'HHL';
    const SERVICE_MANUAL_HOTEL = 'HTL';
    const SERVICE_INSURANCE = 'INS';
    const SERVICE_MISCELLANEOUS_CHARGES_ORDER = 'MCO';
    const SERVICE_MISCELLANEOUS = 'MIS';
    const SERVICE_SURFACE_TRANSPORTATION = 'SUR';
    const SERVICE_TRAIN = 'TRN';
    const SERVICE_TOUR_SOURCE = 'TTO';
    const SERVICE_TOUR = 'TUR';

    /**
     * Amadeus Record Locator for PNR
     *
     * @var string
     */
    public $recordLocator;

    /**
     * Retrieval Type
     *
     * self::RETRIEVE_*
     *
     * @var int
     */
    public $retrievalType;

    /**
     * Amadeus Office ID - when retrieving PNR's on a specific Office.
     *
     * @var string
     */
    public $officeId;

    /**
     * Which PNR service to look for
     *
     * self::SERVICE_*
     *
     * @var string
     */
    public $service;

    /**
     * Retrieval options
     *
     * self::OPTION_*
     *
     * @var string[]
     */
    public $options = [];

    /**
     * If provided, will perform a PNR retrieval by Account number found in Account Remarks
     *
     * @var string
     */
    public $accountNumber;

    /**
     * If provided, will perform PNR retrieval by Customer Profile
     *
     * @var string
     */
    public $customerProfile;

    /**
     * If provided, will perform PNR retrieval by traveller Last Name
     *
     * @var string
     */
    public $lastName;

    /**
     * Limit search by last name to a specific departure date
     *
     * @var \DateTime
     */
    public $departureDate;

    /**
     * If provided, will perform PNR retrieval by Frequent Traveller
     *
     * @var FrequentTraveller
     */
    public $frequentTraveller;

    /**
     * Ticket information (
     *
     * @var Ticket
     */
    public $ticket;

    /**
     * Company code (for search by service and flight number)
     *
     * @var string
     */
    public $company;

    /**
     * Flight number (for search by service and flight number)
     *
     * @var string
     */
    public $flightNumber;
}
