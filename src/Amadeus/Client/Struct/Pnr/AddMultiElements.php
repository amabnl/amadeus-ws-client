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

namespace Amadeus\Client\Struct\Pnr;

use Amadeus\Client\RequestOptions\Pnr\Element;
use Amadeus\Client\RequestOptions\Pnr\Element\ReceivedFrom;
use Amadeus\Client\RequestOptions\Pnr\Segment;
use Amadeus\Client\RequestOptions\Pnr\Traveller;
use Amadeus\Client\RequestOptions\Pnr\TravellerGroup;
use Amadeus\Client\RequestOptions\PnrAddMultiElementsBase;
use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\InvalidArgumentException;
use Amadeus\Client\Struct\Pnr\AddMultiElements\Accounting;
use Amadeus\Client\Struct\Pnr\AddMultiElements\AirAuxItinerary;
use Amadeus\Client\Struct\Pnr\AddMultiElements\DataElementsIndiv;
use Amadeus\Client\Struct\Pnr\AddMultiElements\DataElementsMaster;
use Amadeus\Client\Struct\Pnr\AddMultiElements\DateOfBirth;
use Amadeus\Client\Struct\Pnr\AddMultiElements\ElementManagementData;
use Amadeus\Client\Struct\Pnr\AddMultiElements\ElementManagementItinerary;
use Amadeus\Client\Struct\Pnr\AddMultiElements\ElementManagementPassenger;
use Amadeus\Client\Struct\Pnr\AddMultiElements\Fop;
use Amadeus\Client\Struct\Pnr\AddMultiElements\FopExtension;
use Amadeus\Client\Struct\Pnr\AddMultiElements\FormOfPayment;
use Amadeus\Client\Struct\Pnr\AddMultiElements\FreetextData;
use Amadeus\Client\Struct\Pnr\AddMultiElements\FreetextDetail;
use Amadeus\Client\Struct\Pnr\AddMultiElements\FrequentTravellerData;
use Amadeus\Client\Struct\Pnr\AddMultiElements\ItineraryInfo;
use Amadeus\Client\Struct\Pnr\AddMultiElements\MiscellaneousRemark;
use Amadeus\Client\Struct\Pnr\AddMultiElements\NewFopsDetails;
use Amadeus\Client\Struct\Pnr\AddMultiElements\OriginDestinationDetails;
use Amadeus\Client\Struct\Pnr\AddMultiElements\Passenger;
use Amadeus\Client\Struct\Pnr\AddMultiElements\PassengerData;
use Amadeus\Client\Struct\Pnr\AddMultiElements\Reference;
use Amadeus\Client\Struct\Pnr\AddMultiElements\ReferenceForDataElement;
use Amadeus\Client\Struct\Pnr\AddMultiElements\ReferenceForSegment;
use Amadeus\Client\Struct\Pnr\AddMultiElements\ServiceRequest;
use Amadeus\Client\Struct\Pnr\AddMultiElements\StructuredAddress;
use Amadeus\Client\Struct\Pnr\AddMultiElements\TicketElement;
use Amadeus\Client\Struct\Pnr\AddMultiElements\TravellerInfo;
use Amadeus\Client\Struct\Pnr\AddMultiElements\Traveller as PnrAddMultiTraveller;

/**
 * Structure class for representing the PNR_AddMultiElements request message
 *
 * @package Amadeus\Client\Struct\Pnr
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class AddMultiElements extends BaseWsMessage
{
    /**
     * @var AddMultiElements\ReservationInfo
     */
    public $reservationInfo;
    /**
     * @var AddMultiElements\PnrActions
     */
    public $pnrActions;
    /**
     * @var AddMultiElements\TravellerInfo[]
     */
    public $travellerInfo = [];
    /**
     * @var AddMultiElements\OriginDestinationDetails[]
     */
    public $originDestinationDetails = [];
    /**
     * @var AddMultiElements\DataElementsMaster
     */
    public $dataElementsMaster;

    /**
     * Create PNR_AddMultiElements object
     *
     * @param PnrAddMultiElementsBase|null $params
     */
    public function __construct(PnrAddMultiElementsBase $params = null)
    {
        if (!is_null($params)) {
            if ($params instanceof PnrCreatePnrOptions) {
                $this->loadCreatePnr($params);
            } elseif ($params instanceof PnrAddMultiElementsOptions) {
                $this->loadBare($params);
            }
        }
    }

    /**
     * PNR_AddMultiElements call which only adds requested data to the message
     *
     * For doing specific actions like ignoring or saving PNR.
     *
     * @param $params
     */
    protected function loadBare(PnrAddMultiElementsOptions $params)
    {
        if (!is_null($params->actionCode)) {
            $this->pnrActions = new AddMultiElements\PnrActions(
                $params->actionCode
            );
        }

        if (!is_null($params->recordLocator)) {
            $this->reservationInfo = new AddMultiElements\ReservationInfo($params->recordLocator);
        }

        $tattooCounter = 0;

        $this->addTravellers($params->travellers);

        if (!empty($params->tripSegments)) {
            $this->addSegments($params->tripSegments, $tattooCounter);
        }

        if (!empty($params->elements)) {
            $this->addElements(
                $params->elements,
                $tattooCounter,
                $params->receivedFrom
            );
        } elseif (!is_null($params->receivedFrom)) {
            if ($this->dataElementsMaster === null) {
                $this->dataElementsMaster = new DataElementsMaster();
            }

            $tattooCounter++;

            $this->dataElementsMaster->dataElementsIndiv[] = $this->createElement(
                new ReceivedFrom(['receivedFrom' => $params->receivedFrom]), $tattooCounter
            );
        }
    }

    /**
     * Make PNR_AddMultiElements structure from a PnrCreatePnrOptions input.
     *
     * @throws InvalidArgumentException When invalid input is provided
     * @param PnrCreatePnrOptions $params
     */
    protected function loadCreatePnr(PnrCreatePnrOptions $params)
    {
        $this->pnrActions = new AddMultiElements\PnrActions(
            $params->actionCode
        );

        $tattooCounter = 0;

        if ($params->travellerGroup !== null) {
            $this->addTravellerGroup($params->travellerGroup);
        } else {
            $this->addTravellers($params->travellers);
        }

        $this->addSegments($params->tripSegments, $tattooCounter);

        $this->addElements(
            $params->elements,
            $tattooCounter,
            $params->receivedFrom
        );
    }

    /**
     * @param Segment[] $segments
     * @param int $tattooCounter
     */
    protected function addSegments($segments, &$tattooCounter)
    {
        $tmpOrigDest = new OriginDestinationDetails();

        foreach ($segments as $segment) {
            $tmpOrigDest->itineraryInfo[] = $this->createSegment($segment, $tattooCounter);
        }

        $this->originDestinationDetails[] = $tmpOrigDest;
    }

    /**
     * @param Segment $segment
     * @param $tattooCounter
     * @return ItineraryInfo
     */
    protected function createSegment($segment, &$tattooCounter)
    {
        $createdSegment = null;

        $tattooCounter++;

        $reflect = new \ReflectionClass($segment);
        $segmentType = $reflect->getShortName();

        switch ($segmentType) {
            case 'Miscellaneous':
                /** @var Segment\Miscellaneous $segment */
                $createdSegment = new ItineraryInfo($tattooCounter, ElementManagementItinerary::SEGMENT_MISCELLANEOUS);
                $createdSegment->airAuxItinerary = new AirAuxItinerary($segmentType, $segment);
                break;
            case 'Air':
                throw new \RuntimeException('NOT YET IMPLEMENTED');
                break;
            case 'Ghost':
                throw new \RuntimeException('NOT YET IMPLEMENTED');
                break;
            default:
                throw new InvalidArgumentException('Segment type ' . $segmentType . ' is not supported');
                break;
        }

        if (count($segment->references) > 0) {
            $createdSegment->referenceForSegment = new ReferenceForSegment();
            foreach ($segment->references as $singleRef) {
                $createdSegment->referenceForSegment->reference[] = new Reference($singleRef->type, $singleRef->id);
            }
        }

        return $createdSegment;
    }


    /**
     * @param Traveller[] $travellers
     */
    protected function addTravellers($travellers)
    {
        foreach ($travellers as $traveller) {
            $this->travellerInfo[] = $this->createTraveller($traveller);
        }
    }

    /**
     * @param Traveller $traveller
     * @return TravellerInfo
     */
    protected function createTraveller($traveller)
    {
        $createdTraveller = new TravellerInfo(
            ElementManagementPassenger::SEG_NAME,
            $traveller->lastName
        );

        if (!is_null($traveller->number)) {
            $createdTraveller->elementManagementPassenger->reference = new Reference(
                Reference::QUAL_PASSENGER,
                $traveller->number
            );
        }

        if ($traveller->firstName !== null || $traveller->travellerType !== null) {
            $createdTraveller->passengerData[0]->travellerInformation->passenger[] = new Passenger(
                $traveller->firstName,
                $traveller->travellerType
            );
        }

        if ($traveller->withInfant === true || $traveller->infant !== null) {
            $createdTraveller = $this->addInfant($createdTraveller, $traveller);
        }

        if ($traveller->dateOfBirth instanceof \DateTime) {
            $createdTraveller->passengerData[0]->dateOfBirth = new DateOfBirth(
                $traveller->dateOfBirth->format('dmY')
            );
        }

        return $createdTraveller;
    }

    /**
     * Add infant
     *
     * 3 scenario's:
     * - infant without additional information
     * - infant with only first name provided
     * - infant with first name, last name & date of birth provided.
     *
     * @param TravellerInfo $travellerInfo
     * @param Traveller $traveller
     * @return TravellerInfo
     */
    protected function addInfant($travellerInfo, $traveller)
    {
        $travellerInfo->passengerData[0]->travellerInformation->traveller->quantity = 2;

        if ($traveller->withInfant && is_null($traveller->infant)) {
            $travellerInfo = $this->makePassengerIfNeeded($travellerInfo);
            $travellerInfo->passengerData[0]->travellerInformation->passenger[0]->infantIndicator =
                Passenger::INF_NOINFO;
        } elseif ($traveller->infant instanceof Traveller) {
            if (empty($traveller->infant->lastName)) {
                $travellerInfo = $this->makePassengerIfNeeded($travellerInfo);
                $travellerInfo->passengerData[0]->travellerInformation->passenger[0]->infantIndicator =
                    Passenger::INF_GIVEN;

                $tmpInfantPassenger = new Passenger(
                    $traveller->infant->firstName,
                    Passenger::PASST_INFANT
                );

                $travellerInfo->passengerData[0]->travellerInformation->passenger[] = $tmpInfantPassenger;
            } else {
                $travellerInfo = $this->makePassengerIfNeeded($travellerInfo);
                $travellerInfo->passengerData[0]->travellerInformation->passenger[0]->infantIndicator =
                    Passenger::INF_FULL;

                $tmpInfant = new PassengerData($traveller->infant->lastName);
                $tmpInfant->travellerInformation->passenger[] = new Passenger(
                    $traveller->infant->firstName,
                    Passenger::PASST_INFANT
                );

                if ($traveller->infant->dateOfBirth instanceof \DateTime) {
                    $tmpInfant->dateOfBirth = new DateOfBirth(
                        $traveller->infant->dateOfBirth->format('dmY')
                    );
                }

                $travellerInfo->passengerData[] = $tmpInfant;
            }
        }

        return $travellerInfo;
    }

    /**
     * If there is no passenger node at
     * $travellerInfo->passengerData[0]->travellerInformation->passenger[0]
     * create one
     *
     * @param TravellerInfo $travellerInfo
     * @return TravellerInfo
     */
    protected function makePassengerIfNeeded($travellerInfo)
    {
        if (count($travellerInfo->passengerData[0]->travellerInformation->passenger) < 1) {
            $travellerInfo->passengerData[0]->travellerInformation->passenger[0] = new Passenger(null, null);
        }

        return $travellerInfo;
    }

    /**
     * @param TravellerGroup $group
     */
    protected function addTravellerGroup($group)
    {
        $groupInfo = new TravellerInfo(ElementManagementPassenger::SEG_GROUPNAME, $group->name);

        $groupInfo->passengerData[0]->travellerInformation->traveller->quantity = $group->nrOfTravellers;
        $groupInfo->passengerData[0]->travellerInformation->traveller->qualifier = PnrAddMultiTraveller::QUAL_GROUP;

        $this->travellerInfo[] = $groupInfo;

        $this->addTravellers($group->travellers);
    }

    /**
     * @param Element[] $elements
     * @param int $tattooCounter (BYREF)
     * @param string|null $receivedFromString
     */
    protected function addElements($elements, &$tattooCounter, $receivedFromString = null)
    {
        if ($this->dataElementsMaster === null) {
            $this->dataElementsMaster = new DataElementsMaster();
        }

        //Only add a default RF element if there is no explicitly provided RF element!
        $explicitRf = false;

        foreach ($elements as $element) {
            if ($element instanceof Element) {
                $this->dataElementsMaster->dataElementsIndiv[] = $this->createElement($element, $tattooCounter);
            }

            if ($element instanceof ReceivedFrom) {
                $explicitRf = true;
            }
        }

        if ($receivedFromString !== null && !$explicitRf) {
            $this->dataElementsMaster->dataElementsIndiv[] = $this->createElement(
                new ReceivedFrom(['receivedFrom' => $receivedFromString]), $tattooCounter
            );
        }
    }

    /**
     * @param Element $element
     * @param int $tattooCounter (BYREF)
     * @throws InvalidArgumentException
     * @return DataElementsIndiv
     */
    protected function createElement($element, &$tattooCounter)
    {
        $createdElement = null;

        $tattooCounter++;

        $reflect = new \ReflectionClass($element);
        $elementType = $reflect->getShortName();

        switch ($elementType) {
            case 'Contact':
                /** @var Element\Contact $element */
                $createdElement = new DataElementsIndiv(ElementManagementData::SEGNAME_CONTACT_ELEMENT, $tattooCounter);
                $createdElement->freetextData = new FreetextData(
                    $element->value,
                    $element->type
                );
                break;
            case 'FormOfPayment':
                /** @var Element\FormOfPayment $element */
                $createdElement = new DataElementsIndiv(ElementManagementData::SEGNAME_FORM_OF_PAYMENT, $tattooCounter);
                $createdElement->formOfPayment = new FormOfPayment($element->type);
                if ($element->type === Fop::IDENT_CREDITCARD) {
                    $createdElement->formOfPayment->fop->creditCardCode = $element->creditCardType;
                    $createdElement->formOfPayment->fop->accountNumber = $element->creditCardNumber;
                    $createdElement->formOfPayment->fop->expiryDate = $element->creditCardExpiry;
                    if (!is_null($element->creditCardCvcCode)) {
                        $ext = new FopExtension(1);
                        $ext->newFopsDetails = new NewFopsDetails();
                        $ext->newFopsDetails->cvData = $element->creditCardCvcCode;
                        $createdElement->fopExtension[] = $ext;
                    }
                } elseif ($element->type === Fop::IDENT_MISC && $element->freeText != "NONREF") {
                    $createdElement->formOfPayment->fop->freetext = $element->freeText;
                } elseif ($element->type === Fop::IDENT_MISC && $element->freeText === "NONREF") {
                    $createdElement->fopExtension[] = new FopExtension(1);
                } elseif ($element->type === Fop::IDENT_CHECK) {
                    throw new \RuntimeException("FOP CHECK NOT YET IMPLEMENTED");
                }
                break;
            case 'MiscellaneousRemark':
                /** @var Element\MiscellaneousRemark $element */
                $createdElement = new DataElementsIndiv(ElementManagementData::SEGNAME_GENERAL_REMARK, $tattooCounter);
                $createdElement->miscellaneousRemark = new MiscellaneousRemark(
                    $element->text,
                    $element->type,
                    $element->category
                );
                break;
            case 'ReceivedFrom':
                /** @var Element\ReceivedFrom $element */
                $createdElement = new DataElementsIndiv(ElementManagementData::SEGNAME_RECEIVE_FROM, $tattooCounter);
                $createdElement->freetextData = new FreetextData(
                    $element->receivedFrom,
                    FreetextDetail::TYPE_RECEIVE_FROM
                );
                break;
            case 'ServiceRequest':
                /** @var Element\ServiceRequest $element */
                $createdElement = new DataElementsIndiv(ElementManagementData::SEGNAME_SPECIAL_SERVICE_REQUEST, $tattooCounter);
                $createdElement->serviceRequest = new ServiceRequest($element);
                break;
            case 'Ticketing':
                /** @var Element\Ticketing $element */
                $createdElement = new DataElementsIndiv(ElementManagementData::SEGNAME_TICKETING_ELEMENT, $tattooCounter);
                $createdElement->ticketElement = new TicketElement($element);
                break;
            case 'AccountingInfo':
                /** @var Element\AccountingInfo $element */
                $createdElement = new DataElementsIndiv(ElementManagementData::SEGNAME_ACCOUNTING_INFORMATION, $tattooCounter);
                $createdElement->accounting = new Accounting($element);
                break;
            case 'Address':
                /** @var Element\Address $element */
                $createdElement = new DataElementsIndiv($element->type, $tattooCounter);
                if ($element->type === ElementManagementData::SEGNAME_ADDRESS_BILLING_UNSTRUCTURED || $element->type === ElementManagementData::SEGNAME_ADDRESS_MAILING_UNSTRUCTURED) {
                    $createdElement->freetextData = new FreetextData($element->freeText, FreetextDetail::TYPE_MAILING_ADDRESS);
                } else {
                    $createdElement->structuredAddress = new StructuredAddress($element);
                }
                break;
            case 'FrequentFlyer':
                /** @var Element\FrequentFlyer $element */
                $createdElement = new DataElementsIndiv(ElementManagementData::SEGNAME_SPECIAL_SERVICE_REQUEST, $tattooCounter);
                $createdElement->serviceRequest = new ServiceRequest();
                $createdElement->serviceRequest->ssr->type = 'FQTV';
                $createdElement->serviceRequest->ssr->companyId = $element->airline;
                $createdElement->frequentTravellerData = new FrequentTravellerData($element);
                break;
            default:
                throw new InvalidArgumentException('Element type ' . $elementType . ' is not supported');
        }

        if (!empty($element->references)) {
            $createdElement->referenceForDataElement = new ReferenceForDataElement($element->references);
        }

        return $createdElement;
    }
}
