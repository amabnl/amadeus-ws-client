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

namespace Amadeus\Client\RequestCreator;

use Amadeus\Client\Params\RequestCreatorParams;
use Amadeus\Client\RequestOptions\OfferConfirmAirOptions;
use Amadeus\Client\RequestOptions\OfferConfirmCarOptions;
use Amadeus\Client\RequestOptions\OfferConfirmHotelOptions;
use Amadeus\Client\RequestOptions\OfferVerifyOptions;
use Amadeus\Client\RequestOptions\Pnr\Element\ReceivedFrom;
use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
use Amadeus\Client\RequestOptions\PnrRetrieveAndDisplayOptions;
use Amadeus\Client\RequestOptions\PnrRetrieveOptions;
use Amadeus\Client\RequestOptions\QueueListOptions;
use Amadeus\Client\RequestOptions\QueueMoveItemOptions;
use Amadeus\Client\RequestOptions\QueuePlacePnrOptions;
use Amadeus\Client\RequestOptions\QueueRemoveItemOptions;
use Amadeus\Client\RequestOptions\RequestOptionsInterface;
use Amadeus\Client\Struct;

/**
 * Base request creator - the default request creator.
 *
 * @package Amadeus\Client\RequestCreator
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Base implements RequestCreatorInterface
{
    /**
     * @var RequestCreatorParams
     */
    protected $params;

    /**
     * @param $params
     */
    public function __construct(RequestCreatorParams $params)
    {
        $this->params = $params;
    }

    /**
     * @param string $messageName
     * @param RequestOptionsInterface $params
     * @throws Struct\InvalidArgumentException When invalid input is detected during message creation.
     * @return mixed the created request
     */
    public function createRequest($messageName, RequestOptionsInterface $params)
    {
        $methodName = 'create' . ucfirst($messageName);

        if (method_exists($this, $methodName)) {
            return $this->$methodName($params);
        } else {
            throw new \RuntimeException('Message ' . $methodName . ' is not implemented in ' . __CLASS__);
        }
    }

    protected function createSecurityAuthenticate()
    {
        //TODO Only needed for SoapHeader 1 and 2 messages.
    }

    /**
     * Create request object for PNR_Retrieve message
     *
     * @param PnrRetrieveOptions $params
     * @return Struct\Pnr\Retrieve
     */
    protected function createRetrievePnr(PnrRetrieveOptions $params)
    {
        $retrieveRequest = new Struct\Pnr\Retrieve(
            Struct\Pnr\Retrieve::RETR_TYPE_BY_RECLOC,
            $params->recordLocator
        );

        return $retrieveRequest;
    }

    /**
     * @param PnrRetrieveAndDisplayOptions $params
     * @return Struct\Pnr\RetrieveAndDisplay
     */
    protected function createPnrRetrieveAndDisplay(PnrRetrieveAndDisplayOptions $params)
    {
        $req = new Struct\Pnr\RetrieveAndDisplay(
            $params->recordlocator,
            $params->retrieveOption
        );

        return $req;
    }

    protected function createPnrCreatePnr(PnrCreatePnrOptions $params)
    {
        $req = $this->makeAddMultiElementsForPnrCreate($params);

        return $req;
    }

    /**
     * @param PnrAddMultiElementsOptions $params
     * @return Struct\Pnr\AddMultiElements
     */
    protected function createPnrAddMultiElements(PnrAddMultiElementsOptions $params)
    {
        $req = new Struct\Pnr\AddMultiElements();

        //TODO
        throw new \RuntimeException(__METHOD__ . "() IS NOT YET IMPLEMENTED");

        //return $req;
    }

    /**
     * @param QueueListOptions $params
     * @return Struct\Queue\QueueList
     */
    protected function createQueueList(QueueListOptions $params)
    {
        $queueListRequest = new Struct\Queue\QueueList(
            $params->queue->queue,
            $params->queue->category
        );

        return $queueListRequest;
    }

    /**
     * @param QueuePlacePnrOptions $params
     * @return Struct\Queue\PlacePnr
     */
    protected function createQueuePlacePnr(QueuePlacePnrOptions $params)
    {
        $req = new Struct\Queue\PlacePnr(
            $params->recordLocator,
            $params->sourceOfficeId,
            $params->targetQueue
        );

        return $req;
    }

    /**
     * @param QueueRemoveItemOptions $params
     * @return Struct\Queue\RemoveItem
     */
    protected function createQueueRemoveItem(QueueRemoveItemOptions $params)
    {
        $req = new Struct\Queue\RemoveItem(
            $params->queue,
            $params->recordLocator,
            $params->originatorOfficeId
        );

        return $req;
    }

    /**
     * @param QueueMoveItemOptions $params
     * @return Struct\Queue\MoveItem
     */
    protected function createQueueMoveItem(QueueMoveItemOptions $params)
    {
        $req = new Struct\Queue\MoveItem(
            $params->recordLocator,
            $params->officeId,
            $params->sourceQueue,
            $params->destinationQueue
        );

        return $req;
    }

    /**
     * @param OfferVerifyOptions $params
     * @return Struct\Offer\Verify
     */
    protected function createOfferVerify(OfferVerifyOptions $params)
    {
        $req = new Struct\Offer\Verify(
            $params->offerReference,
            $params->segmentName
        );

        return $req;
    }

    /**
     * @param OfferConfirmAirOptions $params
     * @return Struct\Offer\ConfirmAir
     */
    protected function createOfferConfirmAir(OfferConfirmAirOptions $params)
    {
        $req = new Struct\Offer\ConfirmAir($params);

        return $req;
    }

    /**
     * @param OfferConfirmHotelOptions $params
     * @return Struct\Offer\ConfirmHotel
     */
    protected function createOfferConfirmHotel(OfferConfirmHotelOptions $params)
    {
        $req = new Struct\Offer\ConfirmHotel();

        //TODO

        return $req;
    }

    /**
     * @param OfferConfirmCarOptions $params
     * @return Struct\Offer\ConfirmCar
     */
    protected function createOfferConfirmCar(OfferConfirmCarOptions $params)
    {
        $req = new Struct\Offer\ConfirmCar();

        //TODO

        return $req;
    }

    /**
     * @param PnrCreatePnrOptions $params
     * @return Struct\Pnr\AddMultiElements
     */
    protected function makeAddMultiElementsForPnrCreate($params)
    {
        $params->receivedFrom = $this->params->receivedFrom;

        $req = new Struct\Pnr\AddMultiElements(
            $params
        );

        //TODO

        /*$receivedFrom = new ReceivedFrom(
            $this->params->receivedFrom
        );*/

        return $req;
    }


}
