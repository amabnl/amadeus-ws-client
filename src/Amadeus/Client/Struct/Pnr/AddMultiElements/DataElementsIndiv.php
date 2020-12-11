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

use Amadeus\Client\RequestOptions\Pnr\Element;
use Amadeus\Client\Struct\InvalidArgumentException;
use Amadeus\Client\Struct\WsMessageUtility;

/**
 * DataElementsIndiv
 *
 * all the others segments
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DataElementsIndiv extends WsMessageUtility
{
    /**
     * To specify the PNR segments/elements references and action to apply
     *
     * @var ElementManagementData
     */
    public $elementManagementData;
    /**
     * @var PnrSecurity
     */
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
    /**
     * @var TourCode
     */
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
    /**
     * This group handles Seat Request with possibly rail preferences
     *
     * @var SeatGroup
     */
    public $seatGroup;
    public $entity;
    public $fareElement;
    public $fareDiscount;
    public $manualFareDocument;
    /**
     * @var Commission
     */
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
     * @param Element|string $element Either an element or an element name
     * @param int $tattoo Unique tattoo number for this element
     * @throws \ReflectionException
     */
    public function __construct($element, $tattoo)
    {
        if ($element instanceof Element) {
            $reflect = new \ReflectionClass($element);
            $elementType = $reflect->getShortName();

            $this->elementManagementData = new ElementManagementData(
                $this->makeSegmentNameForRequestElement($elementType, $element),
                $tattoo
            );

            $this->loadElement($element, $elementType);
        } elseif (is_string($element)) {
            $this->elementManagementData = new ElementManagementData(
                $element,
                $tattoo
            );
        }
    }

    /**
     * @param Element $element
     * @param string $elementType
     */
    protected function loadElement($element, $elementType)
    {
        switch ($elementType) {
            case 'Contact':
                /** @var Element\Contact $element */
                $this->freetextData = new FreetextData(
                    $element->value,
                    $element->type
                );
                break;
            case 'FormOfPayment':
                /** @var Element\FormOfPayment $element */
                $this->formOfPayment = new FormOfPayment($element->type);
                if ($element->type === Fop::IDENT_CREDITCARD) {
                    $this->formOfPayment->fop->creditCardCode = $element->creditCardType;
                    $this->formOfPayment->fop->accountNumber = $element->creditCardNumber;
                    $this->formOfPayment->fop->expiryDate = $element->creditCardExpiry;
                    if ($element->freeText && $element->freeText != "") {
                        $this->formOfPayment->fop->freetext = $element->freeText;
                    }
                    if ($this->checkAnyNotEmpty($element->creditCardCvcCode, $element->creditCardHolder)) {
                        $this->fopExtension[] = new FopExtension(
                            1,
                            $element->creditCardCvcCode,
                            $element->creditCardHolder
                        );
                    }
                } elseif ($element->type === Fop::IDENT_CASH && $element->freeText != "") {
                    $this->formOfPayment->fop->freetext = $element->freeText;
                } elseif ($element->type === Fop::IDENT_MISC && $element->freeText != "NONREF") {
                    $this->formOfPayment->fop->freetext = $element->freeText;
                } elseif ($element->type === Fop::IDENT_MISC && $element->freeText === "NONREF") {
                    $this->fopExtension[] = new FopExtension(1);
                } elseif ($element->type === Fop::IDENT_CHECK) {
                    throw new \RuntimeException("FOP CHECK NOT YET IMPLEMENTED");
                }

                if ($element->isServiceFee) {
                    $this->serviceDetails[] = new ServiceDetails(
                        StatusDetails::IND_SERVICEFEE
                    );
                }
                break;
            case 'MiscellaneousRemark':
                /** @var Element\MiscellaneousRemark $element */
                $this->miscellaneousRemark = new MiscellaneousRemark(
                    $element->text,
                    $element->type,
                    $element->category
                );
                break;
            case 'ReceivedFrom':
                /** @var Element\ReceivedFrom $element */
                $this->freetextData = new FreetextData(
                    $element->receivedFrom,
                    FreetextDetail::TYPE_RECEIVE_FROM
                );
                break;
            case 'ServiceRequest':
                /** @var Element\ServiceRequest $element */
                $this->serviceRequest = new ServiceRequest($element);
                break;
            case 'Ticketing':
                /** @var Element\Ticketing $element */
                $this->ticketElement = new TicketElement($element);
                break;
            case 'AccountingInfo':
                /** @var Element\AccountingInfo $element */
                $this->accounting = new Accounting($element);
                break;
            case 'Address':
                /** @var Element\Address $element */
                if ($element->type === ElementManagementData::SEGNAME_ADDRESS_BILLING_UNSTRUCTURED ||
                    $element->type === ElementManagementData::SEGNAME_ADDRESS_MAILING_UNSTRUCTURED
                ) {
                    $this->freetextData = new FreetextData(
                        $element->freeText,
                        FreetextDetail::TYPE_MAILING_ADDRESS
                    );
                } else {
                    $this->structuredAddress = new StructuredAddress($element);
                }
                break;
            case 'FrequentFlyer':
                /** @var Element\FrequentFlyer $element */
                $this->serviceRequest = new ServiceRequest();
                $this->serviceRequest->ssr->type = 'FQTV';
                $this->serviceRequest->ssr->companyId = $element->airline;
                $this->frequentTravellerData = new FrequentTravellerData($element);
                break;
            case 'OtherServiceInfo':
                /** @var Element\OtherServiceInfo $element */
                $this->freetextData = new FreetextData(
                    $element->freeText,
                    FreetextDetail::TYPE_OSI_ELEMENT
                );
                $this->freetextData->freetextDetail->companyId = $element->airline;
                $this->freetextData->freetextDetail->subjectQualifier = FreetextDetail::QUALIFIER_LITERALTEXT;
                break;
            case 'ManualCommission':
                /** @var Element\ManualCommission $element */
                $this->commission = new Commission($element);
                break;
            case 'SeatRequest':
                /** @var Element\SeatRequest $element */
                $this->seatGroup = new SeatGroup($element);
                break;
            case 'TourCode':
                /** @var Element\TourCode $element */
                $this->tourCode = new TourCode($element);
                break;
            case 'ManualIssuedTicket':
                /** @var Element\ManualIssuedTicket $element */
                $this->manualFareDocument = new ManualDocumentRegistration(
                    $element->passengerType,
                    $element->companyId,
                    $element->ticketNumber
                );
                break;
            case 'ScheduleChange':
                /** @var Element\ScheduleChange $element */
                $this->freetextData = new FreetextData(
                    'SCHGTOOL',
                    FreetextDetail::TYPE_RECEIVE_FROM
                );
                break;
            case 'FareMiscellaneousInformation':
                /** @var Element\FareMiscellaneousInformation $element */
                $this->fareElement = new FareElement($element->indicator, $element->passengerType, $element->freeText, $element->officeId);
                break;
            case 'Security':
                /** @var Element\Security $element */
                $this->pnrSecurity = new PnrSecurity($element);
                break;
            default:
                throw new InvalidArgumentException('Element type '.$elementType.' is not supported');
        }
    }

    /**
     * Create the correct element identifier for a given element
     *
     * @param string $elementType
     * @param Element $element
     * @return string
     */
    protected function makeSegmentNameForRequestElement($elementType, $element)
    {
        $elementName = '';

        $sourceArray = [
            'Contact' => ElementManagementData::SEGNAME_CONTACT_ELEMENT,
            'FormOfPayment' => ElementManagementData::SEGNAME_FORM_OF_PAYMENT,
            'MiscellaneousRemark' => ElementManagementData::SEGNAME_GENERAL_REMARK,
            'ReceivedFrom' => ElementManagementData::SEGNAME_RECEIVE_FROM,
            'ServiceRequest' => ElementManagementData::SEGNAME_SPECIAL_SERVICE_REQUEST,
            'Ticketing' => ElementManagementData::SEGNAME_TICKETING_ELEMENT,
            'AccountingInfo' => ElementManagementData::SEGNAME_ACCOUNTING_INFORMATION,
            'Address' => null, // Special case - the type is a parameter.
            'FrequentFlyer' => ElementManagementData::SEGNAME_SPECIAL_SERVICE_REQUEST,
            'OtherServiceInfo' => ElementManagementData::SEGNAME_OTHER_SERVICE_INFORMATION,
            'ManualCommission' => ElementManagementData::SEGNAME_COMMISSION,
            'SeatRequest' => ElementManagementData::SEGNAME_SEAT_REQUEST,
            'TourCode' => ElementManagementData::SEGNAME_TOUR_CODE,
            'ManualIssuedTicket' => ElementManagementData::SEGNAME_MANUAL_DOCUMENT_REGISTRATION_WITH_ET_NUMBER,
            'ScheduleChange' => ElementManagementData::SEGNAME_RECEIVE_FROM,
            'FareMiscellaneousInformation' => null, // Special case - the type is a parameter.
            'Security' => ElementManagementData::SEGNAME_INDIVIDUAL_SECURITY,
        ];

        if (array_key_exists($elementType, $sourceArray)) {
            $elementName = $sourceArray[$elementType];

            if ($elementType === 'Address') {
                /** @var Element\Address $element */
                $elementName = $element->type;
            }

            if ($elementType === 'FareMiscellaneousInformation') {
                /** @var Element\FareMiscellaneousInformation $element */

                switch ($element->indicator) {
                    case Element\FareMiscellaneousInformation::GENERAL_INDICATOR_FS:
                        $elementName = ElementManagementData::SEGNAME_MISC_TICKET_INFO;
                        break;
                    case Element\FareMiscellaneousInformation::GENERAL_INDICATOR_FE:
                        $elementName = ElementManagementData::SEGNAME_ENDORSEMENT;
                        break;
                    case Element\FareMiscellaneousInformation::GENERAL_INDICATOR_FK:
                        $elementName = ElementManagementData::SEGNAME_AIR_OFFICE_ID;
                        break;
                    case Element\FareMiscellaneousInformation::GENERAL_INDICATOR_FZ:
                        $elementName = ElementManagementData::SEGNAME_MISC_INFO;
                        break;
                }
            }
        }

        return $elementName;
    }
}
