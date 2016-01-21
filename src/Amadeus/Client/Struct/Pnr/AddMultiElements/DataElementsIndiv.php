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
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class DataElementsIndiv
{
    /**
     * @var ElementManagementData
     */
    public $elementManagementData;
    public $pnrSecurity;
    public $accounting;
    /**
     * @var MiscellaneousRemark
     */
    public $miscellaneousRemark;
    public $serviceRequest;
    public $dateAndTimeInformation;
    public $tourCode;
    /**
     * @var TicketElement
     */
    public $ticketElement;
    /**
     * @var FreetextData
     */
    public $freetextData;
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
     * @var FormOfPayment
     */
    public $formOfPayment;
    /**
     * @var array
     */
    public $fopExtension = [];
    /**
     * @var array
     */
    public $serviceDetails = [];
    public $frequentTravellerVerification;
    public $ticketingCarrier;
    public $farePrintOverride;
    public $frequentTravellerData;
    public $accessLevel;
    /**
     *
     * @var ReferenceForDataElement
     */
    public $referenceForDataElement;


    /**
     * @param string $segmentName One of the constants ElementManagementData::SEGNAME_*
     */
    public function __construct($segmentName = null)
    {
        $this->elementManagementData =
            new ElementManagementData($segmentName);
    }
}
