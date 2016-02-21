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

use Amadeus\Client\InvalidMessageException;
use Amadeus\Client\Params\RequestCreatorParams;
use Amadeus\Client\RequestOptions\AirSellFromRecommendationOptions;
use Amadeus\Client\RequestOptions\CommandCrypticOptions;
use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
use Amadeus\Client\RequestOptions\MiniRuleGetFromPricingRecOptions;
use Amadeus\Client\RequestOptions\OfferConfirmAirOptions;
use Amadeus\Client\RequestOptions\OfferConfirmCarOptions;
use Amadeus\Client\RequestOptions\OfferConfirmHotelOptions;
use Amadeus\Client\RequestOptions\OfferVerifyOptions;
use Amadeus\Client\RequestOptions\Pnr\Element\ReceivedFrom;
use Amadeus\Client\RequestOptions\PnrAddMultiElementsBase;
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
     * Associative array of messages (as keys) and versions (as values) that are present in the WSDL.
     *
     * @var array
     */
    protected $messagesAndVersions = [];

    /**
     * @param $params
     */
    public function __construct(RequestCreatorParams $params)
    {
        $this->params = $params;
        $this->messagesAndVersions = $params->messagesAndVersions;
    }

    /**
     * @param string $messageName the message name as named in the WSDL
     * @param RequestOptionsInterface $params
     * @throws Struct\InvalidArgumentException When invalid input is detected during message creation.
     * @throws InvalidMessageException when trying to create a request for a message that is not in your WSDL.
     * @return mixed the created request
     */
    public function createRequest($messageName, RequestOptionsInterface $params)
    {
        $this->checkMessageIsInWsdl($messageName);

        $methodName = 'create' . str_replace("_", "", $messageName);

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

    protected function createSecuritySignOut()
    {
        return new Struct\Security\SignOut();
    }

    /**
     * Create request object for PNR_Retrieve message
     *
     * @param PnrRetrieveOptions $params
     * @return Struct\Pnr\Retrieve
     */
    protected function createPnrRetrieve(PnrRetrieveOptions $params)
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
            $params->recordLocator,
            $params->retrieveOption
        );

        return $req;
    }

    /**
     * @param PnrAddMultiElementsBase $params
     * @return Struct\Pnr\AddMultiElements
     */
    protected function createPnrAddMultiElements(PnrAddMultiElementsBase $params)
    {
        $params->receivedFrom = $this->params->receivedFrom;

        $req = new Struct\Pnr\AddMultiElements($params);

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
    protected function createOfferVerifyOffer(OfferVerifyOptions $params)
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
        return new Struct\Offer\ConfirmCar($params);
    }

    /**
     * createFareMasterPricerTravelBoardSearch
     *
     * @param FareMasterPricerTbSearch $params
     * @return Struct\Fare\MasterPricerTravelBoardSearch
     */
    protected function createFareMasterPricerTravelBoardSearch(FareMasterPricerTbSearch $params)
    {
        return new Struct\Fare\MasterPricerTravelBoardSearch($params);
    }

    /**
     *
     * @param AirSellFromRecommendationOptions $params
     * @return Struct\Air\SellFromRecommendation
     */
    protected function createAirSellFromRecommendation(AirSellFromRecommendationOptions $params)
    {
        return new Struct\Air\SellFromRecommendation($params);
    }

    /**
     * makeCommandCryptic
     *
     * @param CommandCrypticOptions $params
     * @return Struct\Command\Cryptic
     */
    protected function createCommandCryptic(CommandCrypticOptions $params)
    {
        return new Struct\Command\Cryptic($params->entry);
    }

    /**
     * makeMiniRuleGetFromPricingRec
     *
     * @param MiniRuleGetFromPricingRecOptions $params
     * @return Struct\MiniRule\GetFromPricingRec
     */
    protected function createMiniRuleGetFromPricingRec(MiniRuleGetFromPricingRecOptions $params)
    {
        return new Struct\MiniRule\GetFromPricingRec($params);
    }

    /**
     * makeFarePricePnrWithBookingClass
     *
     * @param FarePricePnrWithBookingClassOptions $params
     * @return Struct\Fare\PricePNRWithBookingClass12|Struct\Fare\PricePNRWithBookingClass13
     */
    protected function makeFarePricePnrWithBookingClass(FarePricePnrWithBookingClassOptions $params)
    {
        $version = $this->getActiveVersionFor('Fare_PricePNRWithBookingClass');
        if ($version < 13) {
            return new Struct\Fare\PricePNRWithBookingClass12($params);
        } else {
            return new Struct\Fare\PricePNRWithBookingClass13($params);
        }
    }

    /**
     * Check if a given message is in the active WSDL. Throws exception if it isn't.
     *
     * @throws InvalidMessageException if message is not in WSDL.
     * @param string $messageName
     */
    protected function checkMessageIsInWsdl($messageName)
    {
        if (!array_key_exists($messageName, $this->messagesAndVersions)) {
            throw new InvalidMessageException('Message "' . $messageName . '" is not in WDSL');
        }
    }

    /**
     * Get the version number active in the WSDL for the given message
     *
     * @param $messageName
     * @return float|string
     */
    protected function getActiveVersionFor($messageName)
    {
        return $this->messagesAndVersions[$messageName];
    }
}
