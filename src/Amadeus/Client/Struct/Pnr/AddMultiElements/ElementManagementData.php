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
 * ElementManagementData
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ElementManagementData
{
    /**
     * Segment name "Receive From"
     *
     * See documentation Amadeus Core webservices
     * Functional documentation PNR_AddMultiElements
     * [PNR segment or element name, coded codesets (Ref: 110P 1A 98.98.2)]
     *
     * @var string
     */
    const SEGNAME_RECEIVE_FROM = "RF";
    /**
     * Segment name "General Remark"
     *
     * See documentation Amadeus Core webservices
     * Functional documentation PNR_AddMultiElements
     * [PNR segment or element name, coded codesets (Ref: 110P 1A 98.98.2)]
     *
     * @var string
     */
    const SEGNAME_GENERAL_REMARK = "RM";
    /**
     * Segment name "Form Of Payment"
     *
     * @var string
     */
    const SEGNAME_FORM_OF_PAYMENT = "FP";
    /**
     * Segment name "Ticketing Element"
     *
     * @var string
     */
    const SEGNAME_TICKETING_ELEMENT = "TK";
    /**
     * AP and related elements
     *
     * @var string
     */
    const SEGNAME_CONTACT_ELEMENT = "AP";
    /**
     * OSI - Other Service Information
     *
     * @var string
     */
    const SEGNAME_OTHER_SERVICE_INFORMATION = "OS";
    /**
     * AI - Accounting Information
     *
     * @var string
     */
    const SEGNAME_ACCOUNTING_INFORMATION = "AI";
    /**
     * SSR - Special Service Request
     *
     * @var string
     */
    const SEGNAME_SPECIAL_SERVICE_REQUEST = "SSR";
    /**
     * AB - Billing Address element
     *
     * @var string
     */
    const SEGNAME_ADDRESS_BILLING_STRUCTURED = "AB";
    /**
     * ABU - Unstructured Billing Address element
     *
     * @var string
     */
    const SEGNAME_ADDRESS_BILLING_UNSTRUCTURED = "ABU";
    /**
     * AM - Mailing address element
     *
     * @var string
     */
    const SEGNAME_ADDRESS_MAILING_STRUCTURED = "AM";
    /**
     * AMU - Unstructured Mailing Address Element
     *
     * @var string
     */
    const SEGNAME_ADDRESS_MAILING_UNSTRUCTURED = "AMU";

    /**
     * FT - Tour Code element
     *
     * @var string
     */
    const SEGNAME_TOUR_CODE = "FT";

    /**
     * FM - Manual Commission element
     *
     * @var string
     */
    const SEGNAME_COMMISSION = "FM";

    /**
     * STR - Seat Request
     *
     * @var string
     */
    const SEGNAME_SEAT_REQUEST = "STR";

    /**
     * FHE - Manual Document Registration element - with Eletronic Ticket number
     *
     * @var string
     */
    const SEGNAME_MANUAL_DOCUMENT_REGISTRATION_WITH_ET_NUMBER = "FHE";

    /**
     * FS - Miscellaneous Ticketing Information element
     *
     * @var string
     */
    const SEGNAME_MISC_TICKET_INFO = 'FS';

    /**
     * FE - Endorsements / Restrictions element
     */
    const SEGNAME_ENDORSEMENT = 'FE';

    /**
     * FZ - Miscellaneous Information element
     */
    const SEGNAME_MISC_INFO = 'FZ';

    /**
     * FK - shadow AIR office ID element
     */
    const SEGNAME_AIR_OFFICE_ID = 'FK';

    /**
     * Segment name "Individual Security"
     *
     * @var string
     */
    const SEGNAME_INDIVIDUAL_SECURITY = "ES";
    
    /**
     * @var Reference
     */
    public $reference;

    /**
     * [PNR segment or element name, coded codesets (Ref: 110P 1A 98.98.2)]
     *
     * @var string
     */
    public $segmentName;

    /**
     * Create new ElementManagementData
     *
     * @param string|null $segmentName one of the constants SEGNAME_* defined in this class
     * @param int|null $tattoo Optional tattoo nr to reference this element
     */
    public function __construct($segmentName = null, $tattoo = null)
    {
        if (!is_null($segmentName)) {
            $this->segmentName = $segmentName;
        }
        if (!is_null($tattoo) && is_int($tattoo)) {
            $this->reference = new Reference(Reference::QUAL_OTHER, $tattoo);
        }
    }
}
