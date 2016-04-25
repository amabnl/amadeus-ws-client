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
 * DataElementsIndiv
 *
 * all the others segments
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class DataElementsIndiv
{
    /**
     * To specify the PNR segments/elements references and action to apply
     *
     * @var ElementManagementData
     */
    public $elementManagementData;
    public $pnrSecurity;
    /**
     * @var Accounting
     */
    public $accounting;
    /**
     * To specify different kinds of remarks
     *
     * @var MiscellaneousRemark
     */
    public $miscellaneousRemark;
    /**
     * @var ServiceRequest
     */
    public $serviceRequest;
    public $dateAndTimeInformation;
    public $tourCode;
    /**
     * To specify an Amadeus PNR Ticket element
     *
     * @var TicketElement
     */
    public $ticketElement;
    /**
     * To provide free form or coded long text information.
     *
     * @var FreetextData
     */
    public $freetextData;
    /**
     * @var StructuredAddress
     */
    public $structuredAddress;
    public $optionElement;
    public $printer;
    public $seatGroup;
    public $entity;
    public $seatRequest;
    public $railSeatReferenceInformation;
    public $railSeatPreferences;
    public $fareElement;
    public $fareDiscount;
    public $manualFareDocument;
    public $commission;
    public $originalIssue;
    /**
     * To convey details describing the form of payment
     *
     * @var FormOfPayment
     */
    public $formOfPayment;
    /**
     * To convey additional details of the form of payment
     *
     * @var FopExtension[]
     */
    public $fopExtension = [];
    /**
     * To convey:
     * - The FOP service details
     * - The Corporate Security option for Remarks
     * - The Timestamp indicator for Remarks
     *
     * @var ServiceDetails[]
     */
    public $serviceDetails = [];
    public $frequentTravellerVerification;
    public $ticketingCarrier;
    public $farePrintOverride;
    /**
     * Frequent Flyer info
     *
     * @var FrequentTravellerData
     */
    public $frequentTravellerData;
    public $accessLevel;
    /**
     * To provide specific reference identification
     *
     * @var ReferenceForDataElement
     */
    public $referenceForDataElement;

    /**
     * @param string $segmentName One of the constants ElementManagementData::SEGNAME_*
     * @param int $tattoo Unique tattoo number for this element
     */
    public function __construct($segmentName = null, $tattoo = null)
    {
        $this->elementManagementData = new ElementManagementData($segmentName, $tattoo);
    }
}
