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
use Amadeus\Client\RequestOptions\Pnr\Itinerary;
use Amadeus\Client\RequestOptions\Pnr\Segment;
use Amadeus\Client\RequestOptions\Pnr\Traveller;
use Amadeus\Client\RequestOptions\Pnr\TravellerGroup;
use Amadeus\Client\RequestOptions\PnrAddMultiElementsBase;
use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\InvalidArgumentException;
use Amadeus\Client\Struct\Pnr\AddMultiElements\AirAuxItinerary;
use Amadeus\Client\Struct\Pnr\AddMultiElements\DataElementsIndiv;
use Amadeus\Client\Struct\Pnr\AddMultiElements\DataElementsMaster;
use Amadeus\Client\Struct\Pnr\AddMultiElements\ElementManagementItinerary;
use Amadeus\Client\Struct\Pnr\AddMultiElements\ItineraryInfo;
use Amadeus\Client\Struct\Pnr\AddMultiElements\OriginDestination;
use Amadeus\Client\Struct\Pnr\AddMultiElements\OriginDestinationDetails;
use Amadeus\Client\Struct\Pnr\AddMultiElements\Reference;
use Amadeus\Client\Struct\Pnr\AddMultiElements\ReferenceForDataElement;
use Amadeus\Client\Struct\Pnr\AddMultiElements\ReferenceForSegment;
use Amadeus\Client\Struct\Pnr\AddMultiElements\TravellerInfo;

/**
 * Structure class for representing the PNR_AddMultiElements request message
 *
 * @package Amadeus\Client\Struct\Pnr
 * @author Dieter Devlieghere <dermikagh@gmail.com>
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
     * @throws \ReflectionException
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
     * @param PnrAddMultiElementsOptions $params
     * @throws \ReflectionException
     */
    protected function loadBare(PnrAddMultiElementsOptions $params)
    {
        $tattooCounter = 0;

        if (!is_null($params->actionCode)) {
            $this->pnrActions = new AddMultiElements\PnrActions(
                $params->actionCode
            );
        }

        if (!is_null($params->recordLocator)) {
            $this->reservationInfo = new AddMultiElements\ReservationInfo($params->recordLocator);
        }

        if ($params->travellerGroup !== null) {
            $this->addTravellerGroup($params->travellerGroup);
        } else {
            $this->addTravellers($params->travellers);
        }

        $this->addItineraries($params->itineraries, $params->tripSegments, $tattooCounter);

        if (!empty($params->elements)) {
            $this->addElements(
                $params->elements,
                $tattooCounter,
                $params->autoAddReceivedFrom,
                $params->defaultReceivedFrom,
                $params->receivedFrom
            );
        } else {
            $this->addReceivedFrom(
                $params->receivedFrom,
                $params->autoAddReceivedFrom,
                $params->defaultReceivedFrom,
                $tattooCounter
            );
        }
    }

    /**
     * Make PNR_AddMultiElements structure from a PnrCreatePnrOptions input.
     *
     * @throws InvalidArgumentException When invalid input is provided
     * @param PnrCreatePnrOptions $params
     * @throws \ReflectionException
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

        $this->addItineraries($params->itineraries, $params->tripSegments, $tattooCounter);

        $this->addElements(
            $params->elements,
            $tattooCounter,
            $params->autoAddReceivedFrom,
            $params->defaultReceivedFrom,
            $params->receivedFrom
        );
    }

    /**
     * Load Segment itinerary
     *
     * @param Itinerary[] $itineraries
     * @param Segment[] $legacySegments
     * @param int $tattooCounter (BYREF)
     * @throws \ReflectionException
     */
    protected function addItineraries($itineraries, $legacySegments, &$tattooCounter)
    {
        if (!empty($legacySegments)) {
            $this->addSegments($legacySegments, $tattooCounter);
        }

        foreach ($itineraries as $itinerary) {
            $this->addSegments(
                $itinerary->segments,
                $tattooCounter,
                $itinerary->origin,
                $itinerary->destination
            );
        }
    }

    /**
     * @param Segment[] $segments
     * @param int $tattooCounter
     * @param string|null $origin
     * @param string|null $destination
     * @throws \ReflectionException
     */
    protected function addSegments($segments, &$tattooCounter, $origin = null, $destination = null)
    {
        $tmpOrigDest = new OriginDestinationDetails();

        foreach ($segments as $segment) {
            $tmpOrigDest->itineraryInfo[] = $this->createSegment($segment, $tattooCounter);
        }

        if (!is_null($origin) && !is_null($destination)) {
            $tmpOrigDest->originDestination = new OriginDestination($origin, $destination);
        }

        $this->originDestinationDetails[] = $tmpOrigDest;
    }

    /**
     * @param Segment $segment
     * @param int $tattooCounter (BYREF)
     * @return ItineraryInfo
     * @throws \ReflectionException
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
                /** @var Segment\Air $segment */
                $createdSegment = new ItineraryInfo($tattooCounter, ElementManagementItinerary::SEGMENT_AIR);
                $createdSegment->airAuxItinerary = new AirAuxItinerary($segmentType, $segment);
                break;
            case 'ArrivalUnknown':
                /** @var Segment\ArrivalUnknown $segment */
                $createdSegment = new ItineraryInfo($tattooCounter, ElementManagementItinerary::SEGMENT_AIR);
                $createdSegment->airAuxItinerary = new AirAuxItinerary($segmentType, $segment);
                break;
            case 'Ghost':
                throw new \RuntimeException('NOT YET IMPLEMENTED');
                break;
            default:
                throw new InvalidArgumentException('Segment type '.$segmentType.' is not supported');
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
        return new TravellerInfo($traveller);
    }

    /**
     * @param TravellerGroup $group
     */
    protected function addTravellerGroup($group)
    {
        $this->travellerInfo[] = new TravellerInfo(null, $group);

        $this->addTravellers($group->travellers);
    }

    /**
     * @param Element[] $elements
     * @param int $tattooCounter (BYREF)
     * @param bool $autoAddRf
     * @param string|null $defaultRf
     * @param string|null $explicitRf
     * @throws \ReflectionException
     */
    protected function addElements($elements, &$tattooCounter, $autoAddRf, $defaultRf, $explicitRf = null)
    {
        if ($this->dataElementsMaster === null) {
            $this->dataElementsMaster = new DataElementsMaster();
        }

        //Only add a default RF element if there is no explicitly provided RF element!
        $hasReceivedFromElement = false;

        foreach ($elements as $element) {
            if ($element instanceof Element) {
                $this->dataElementsMaster->dataElementsIndiv[] = $this->createElement(
                    $element,
                    $tattooCounter
                );
            }

            if ($element instanceof ReceivedFrom) {
                $hasReceivedFromElement = true;
            }
        }

        if (!$hasReceivedFromElement) {
            $this->addReceivedFrom(
                $explicitRf,
                $autoAddRf,
                $defaultRf,
                $tattooCounter
            );
        }
    }

    /**
     * @param Element $element
     * @param int $tattooCounter (BYREF)
     * @throws InvalidArgumentException
     * @return DataElementsIndiv
     * @throws \ReflectionException
     */
    protected function createElement($element, &$tattooCounter)
    {
        $createdElement = null;

        $tattooCounter++;

        $createdElement = new DataElementsIndiv($element, $tattooCounter);

        if (!empty($element->references)) {
            $createdElement->referenceForDataElement = new ReferenceForDataElement($element->references);
        }

        return $createdElement;
    }

    /**
     * Add Received From field - if needed.
     *
     * @param string|null $explicitRf Explicitly provided RF string on request.
     * @param bool $doAutoAdd Wether to automatically add an RF field.
     * @param string|null $defaultRf The default RF string set in the client.
     * @param int $tattooCounter (BYREF)
     * @throws \ReflectionException
     */
    protected function addReceivedFrom($explicitRf, $doAutoAdd, $defaultRf, &$tattooCounter)
    {
        if ($this->dataElementsMaster === null) {
            $this->dataElementsMaster = new DataElementsMaster();
        }

        if (!empty($explicitRf) || ($doAutoAdd && !empty($defaultRf))) {
            //Set a received from if explicitly provided or if auto received from is enabled

            $tattooCounter++;

            $rfToAdd = (!empty($explicitRf)) ? $explicitRf : $defaultRf;

            $this->dataElementsMaster->dataElementsIndiv[] = $this->createElement(
                new ReceivedFrom(['receivedFrom' => $rfToAdd]),
                $tattooCounter
            );
        }
    }
}
