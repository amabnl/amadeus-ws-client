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
use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
use Amadeus\Client\RequestOptions\RequestOptionsInterface;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\InvalidArgumentException;
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
use Amadeus\Client\Struct\Pnr\AddMultiElements\ItineraryInfo;
use Amadeus\Client\Struct\Pnr\AddMultiElements\MiscellaneousRemark;
use Amadeus\Client\Struct\Pnr\AddMultiElements\OriginDestinationDetails;
use Amadeus\Client\Struct\Pnr\AddMultiElements\Passenger;
use Amadeus\Client\Struct\Pnr\AddMultiElements\PassengerData;
use Amadeus\Client\Struct\Pnr\AddMultiElements\TicketElement;
use Amadeus\Client\Struct\Pnr\AddMultiElements\TravellerInfo;

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
     */
    public function __construct(RequestOptionsInterface $params = null)
    {
        if (!is_null($params)) {
            if ($params instanceof PnrCreatePnrOptions) {
                $this->loadCreatePnr($params);
            }
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

        $tatooCounter = 0;

        if ($params->travellerGroup !== null) {
            $this->addTravellerGroup($params->travellerGroup);
        } else {
            $this->addTravellers($params->travellers);
        }

        $this->addSegments($params->tripSegments, $tatooCounter);

        $this->addElements(
            $params->elements,
            $tatooCounter,
            $params->receivedFrom
        );
    }

    /**
     * @param Segment[] $segments
     * @param int $tatooCounter
     */
    protected function addSegments($segments, &$tatooCounter)
    {
        $tmpOrigDest = new OriginDestinationDetails();

        foreach ($segments as $segment) {
            $tmpOrigDest->itineraryInfo[] = $this->createSegment($segment, $tatooCounter);
        }

        $this->originDestinationDetails[] = $tmpOrigDest;
    }

    /**
     * @param Segment $segment
     * @param $tatooCounter
     * @return ItineraryInfo
     */
    protected function createSegment($segment, &$tatooCounter)
    {
        $createdSegment = null;

        $tatooCounter++;

        $reflect = new \ReflectionClass($segment);
        $segmentType = $reflect->getShortName();

        switch ($segmentType) {
            case 'Miscellaneous':
                /** @var Segment\Miscellaneous $segment */
                $createdSegment = new ItineraryInfo($tatooCounter, ElementManagementItinerary::SEGMENT_MISCELLANEOUS);
                $createdSegment->airAuxItinerary = new AirAuxItinerary($segmentType, $segment);
                break;
            case 'Air':
                throw new \RuntimeException('NOT YET IMPLEMENTED');
                break;
            default:
                throw new InvalidArgumentException('Segment type ' . $segmentType . 'is not supported');
                break;
        }

        return $createdSegment;
    }


    /**
     * @param Traveller[] $travellers
     * @param $group
     */
    protected function addTravellers($travellers, $group = null)
    {
        foreach ($travellers as $traveller) {
            $this->travellerInfo[] = $this->createTraveller($traveller, $group);
        }
    }

    /**
     * @param Traveller $traveller
     * @param mixed $group
     * @return TravellerInfo
     */
    protected function createTraveller($traveller, $group)
    {
        $createdTraveller = new TravellerInfo(
            ElementManagementPassenger::SEG_NAME,
            $traveller->lastName
        );

        if ($traveller->withInfant === true || $traveller->infant !== null) {
            throw new \RuntimeException('Adding Infants is not yet supported');
        }

        if ($traveller->dateOfBirth instanceof \DateTime) {
            $createdTraveller->passengerData[0]->dateOfBirth = new DateOfBirth(
                $traveller->dateOfBirth->format('dmy')
            );
        }

        if ($traveller->firstName !== null || $traveller->travellerType !== null) {
            $createdTraveller->passengerData[0]->travellerInformation->passenger[] = new Passenger(
                $traveller->firstName,
                $traveller->travellerType
            );
        }

        return $createdTraveller;
    }

    /**
     * @param TravellerGroup $group
     */
    protected function addTravellerGroup($group)
    {
        throw new \RuntimeException("Group PNR's are not yet implemented");
    }

    /**
     * @param Element[] $elements
     * @param int $tatooCounter (BYREF)
     * @param string|null $receivedFromString
     */
    protected function addElements($elements, &$tatooCounter, $receivedFromString = null)
    {
        if ($this->dataElementsMaster === null) {
            $this->dataElementsMaster = new DataElementsMaster();
        }

        foreach ($elements as $element) {
            if ($element instanceof Element) {
                $this->dataElementsMaster->dataElementsIndiv[] = $this->createElement($element, $tatooCounter);
            }
        }

        if ($receivedFromString !== null) {
            $this->dataElementsMaster->dataElementsIndiv[] = $this->createElement(new ReceivedFrom(['receivedFrom' => $receivedFromString]), $tatooCounter);
        }
    }

    /**
     * @param Element $element
     * @param int $tatooCounter (BYREF)
     * @throws InvalidArgumentException
     * @return DataElementsIndiv
     */
    protected function createElement($element, &$tatooCounter)
    {
        $createdElement = null;

        $tatooCounter++;

        $reflect = new \ReflectionClass($element);
        $elementType = $reflect->getShortName();

        switch ($elementType) {
            case 'Contact':
                /** @var Element\Contact $element */
                $createdElement = new DataElementsIndiv(ElementManagementData::SEGNAME_CONTACT_ELEMENT, $tatooCounter);
                $createdElement->freetextData = new FreetextData(
                    $element->value,
                    $element->type
                );
                break;
            case 'FormOfPayment':
                /** @var Element\FormOfPayment $element */
                $createdElement = new DataElementsIndiv(ElementManagementData::SEGNAME_FORM_OF_PAYMENT, $tatooCounter);
                $createdElement->formOfPayment = new FormOfPayment($element->type);
                if ($element->type === Fop::IDENT_CREDITCARD) {
                    $createdElement->formOfPayment->fop->creditCardCode = $element->creditCardType;
                    $createdElement->formOfPayment->fop->accountNumber = $element->creditCardNumber;
                    $createdElement->formOfPayment->fop->expiryDate = $element->creditCardExpiry;
                } elseif ($element->type === Fop::IDENT_MISC && $element->freeText != "NONREF") {
                    $createdElement->formOfPayment->fop->freetext = $element->freeText;
                } elseif ($element->type === Fop::IDENT_MISC && $element->freeText == "NONREF") {
                    $createdElement->fopExtension = new FopExtension(1);
                } elseif ($element->type === Fop::IDENT_CHECK) {
                    throw new \RuntimeException("FOP CHECK NOT YET IMPLEMENTED");
                }
                break;
            case 'MiscellaneousRemark':
                /** @var Element\MiscellaneousRemark $element */
                $createdElement = new DataElementsIndiv(ElementManagementData::SEGNAME_GENERAL_REMARK, $tatooCounter);
                $createdElement->miscellaneousRemark = new MiscellaneousRemark(
                    $element->text,
                    $element->type,
                    $element->category
                );
                break;
            case 'ReceivedFrom':
                /** @var Element\ReceivedFrom $element */
                $createdElement = new DataElementsIndiv(ElementManagementData::SEGNAME_RECEIVE_FROM, $tatooCounter);
                $createdElement->freetextData = new FreetextData(
                    $element->receivedFrom,
                    FreetextDetail::TYPE_RECEIVE_FROM
                );
                break;
            case 'ServiceRequest':
                /** @var Element\ServiceRequest $element */
                //TODO
                break;
            case 'Ticketing':
                /** @var Element\Ticketing $element */
                $createdElement = new DataElementsIndiv(ElementManagementData::SEGNAME_TICKETING_ELEMENT, $tatooCounter);
                $createdElement->ticketElement = new TicketElement($element);
                break;
            default:
                throw new InvalidArgumentException('Element type ' . $elementType . 'is not supported');
        }

        return $createdElement;
    }
}
