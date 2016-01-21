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
use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
use Amadeus\Client\RequestOptions\RequestOptionsInterface;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\InvalidArgumentException;
use Amadeus\Client\Struct\Pnr\AddMultiElements\DataElementsIndiv;
use Amadeus\Client\Struct\Pnr\AddMultiElements\DataElementsMaster;
use Amadeus\Client\Struct\Pnr\AddMultiElements\ElementManagementData;
use Amadeus\Client\Struct\Pnr\AddMultiElements\FreetextData;
use Amadeus\Client\Struct\Pnr\AddMultiElements\MiscellaneousRemark;
use Amadeus\Client\Struct\Pnr\AddMultiElements\TicketElement;

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
     * @var array
     * @todo expand this structure
     */
    public $travellerInfo = [];
    /**
     * @var array
     * @todo expand this structure
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
            $this->dataElementsMaster->dataElementsIndiv[] = $this->createElement(new ReceivedFrom($receivedFromString), $tatooCounter);
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
                //TODO
                break;
            case 'FormOfPayment':
                /** @var Element\FormOfPayment $element */
                //TODO
                break;
            case 'MiscellaneousRemark':
                /** @var Element\MiscellaneousRemark $element */
                $createdElement = new DataElementsIndiv(ElementManagementData::SEGNAME_GENERAL_REMARK);
                $createdElement->miscellaneousRemark = new MiscellaneousRemark(
                    $element->text,
                    $element->type,
                    $element->category
                );
                break;
            case 'ReceivedFrom':
                /** @var Element\ReceivedFrom $element */
                $createdElement = new DataElementsIndiv(ElementManagementData::SEGNAME_RECEIVE_FROM);
                $createdElement->freetextData = new FreetextData($element->receivedFrom);
                break;
            case 'ServiceRequest':
                /** @var Element\ServiceRequest $element */
                //TODO
                break;
            case 'Ticketing':
                /** @var Element\Ticketing $element */
                $createdElement = new DataElementsIndiv(ElementManagementData::SEGNAME_TICKETING_ELEMENT);
                $createdElement->ticketElement = new TicketElement($element);
                break;
            default:
                throw new InvalidArgumentException('Element type ' . $elementType . 'is not supported');
        }

        return $createdElement;
    }
}
