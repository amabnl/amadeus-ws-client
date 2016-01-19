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

use Amadeus\Client\RequestOptions\OfferConfirmAirOptions;
use Amadeus\Client\RequestOptions\OfferConfirmCarOptions;
use Amadeus\Client\RequestOptions\OfferConfirmHotelOptions;
use Amadeus\Client\RequestOptions\OfferVerifyOptions;
use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
use Amadeus\Client\RequestOptions\PnrRetrieveAndDisplayOptions;
use Amadeus\Client\RequestOptions\PnrRetrieveRequestOptions;
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
     * @param string $messageName
     * @param RequestOptionsInterface $params
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
     * @param PnrRetrieveRequestOptions $params
     * @return Struct\Pnr\Retrieve
     */
    protected function createRetrievePnr(PnrRetrieveRequestOptions $params)
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
        $req = new Struct\Pnr\RetrieveAndDisplay();

        //TODO

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

        return $req;
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
        $req = new Struct\Queue\PlacePnr();

        //TODO

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
        $req = new Struct\Queue\MoveItem();

        //TODO

        return $req;
    }

    /**
     * @param OfferVerifyOptions $params
     * @return Struct\Offer\Verify
     */
    protected function createOfferVerify(OfferVerifyOptions $params)
    {
        $req = new Struct\Offer\Verify();

        //TODO

        return $req;
    }

    /**
     * @param OfferConfirmAirOptions $params
     * @return Struct\Offer\ConfirmAir
     */
    protected function createOfferConfirmAir(OfferConfirmAirOptions $params)
    {
        $req = new Struct\Offer\ConfirmAir();

        //TODO

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


}
