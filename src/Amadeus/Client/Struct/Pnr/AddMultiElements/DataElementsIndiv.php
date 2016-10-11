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
     * @param Element|string $element Either an element or an element name
     * @param int $tattoo Unique tattoo number for this element
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
                    if (!is_null($element->creditCardCvcCode)) {
                        $ext = new FopExtension(1);
                        $ext->newFopsDetails = new NewFopsDetails();
                        $ext->newFopsDetails->cvData = $element->creditCardCvcCode;
                        $this->fopExtension[] = $ext;
                    }
                } elseif ($element->type === Fop::IDENT_MISC && $element->freeText != "NONREF") {
                    $this->formOfPayment->fop->freetext = $element->freeText;
                } elseif ($element->type === Fop::IDENT_MISC && $element->freeText === "NONREF") {
                    $this->fopExtension[] = new FopExtension(1);
                } elseif ($element->type === Fop::IDENT_CHECK) {
                    throw new \RuntimeException("FOP CHECK NOT YET IMPLEMENTED");
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
            default:
                throw new InvalidArgumentException('Element type ' . $elementType . ' is not supported');
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
            'OtherServiceInfo' => ElementManagementData::SEGNAME_OTHER_SERVICE_INFORMATION
        ];

        if (array_key_exists($elementType, $sourceArray)) {
            $elementName = $sourceArray[$elementType];

            if ($elementType === 'Address') {
                /** @var Element\Address $element */
                $elementName = $element->type;
            }
        }

        return $elementName;
    }
}
