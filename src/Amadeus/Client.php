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

namespace Amadeus;

use Amadeus\Client\Exception;
use Amadeus\Client\Params;
use Amadeus\Client\RequestCreator\RequestCreatorInterface;
use Amadeus\Client\RequestOptions\OfferConfirmAirOptions;
use Amadeus\Client\RequestOptions\OfferConfirmCarOptions;
use Amadeus\Client\RequestOptions\OfferConfirmHotelOptions;
use Amadeus\Client\RequestOptions\OfferVerifyOptions;
use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
use Amadeus\Client\RequestOptions\PnrRetrieveAndDisplayOptions;
use Amadeus\Client\RequestOptions\PnrRetrieveOptions;
use Amadeus\Client\RequestOptions\QueueListOptions;
use Amadeus\Client\RequestOptions\QueueMoveItemOptions;
use Amadeus\Client\RequestOptions\QueuePlacePnrOptions;
use Amadeus\Client\RequestOptions\QueueRemoveItemOptions;
use Amadeus\Client\RequestOptions\SecuritySignOutOptions;
use Amadeus\Client\Session\Handler\HandlerFactory;
use Amadeus\Client\RequestCreator\Factory as RequestCreatorFactory;
use Amadeus\Client\Session\Handler\HandlerInterface;

/**
 * Amadeus Web Service Client.
 *
 * TODO:
 * - have a solution for session pooling for stateful sessions (soapheader 1 & 2)
 * - support older versions of SoapHeader (1, 2)
 * - implement calls for full online booking flow:
 *      Fare_MasterPricerTravelBoardSearch,
 *      Air_SellFromRecommendation
 *      Fare_PricePnrWithBookingClass
 *      Ticket_CreateTSTFromPricing
 *      SalesReports_DisplayQueryReport
 *      Air_MultiAvailability
 *      Command_Cryptic
 *
 *
 * - implement more PNR_AddMultiElements:
 *      ABU segment
 *      OSI segment
 *      SSR segment
 *
 * @package Amadeus
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Client
{
    /**
     * Amadeus SOAP header version 1
     */
    const HEADER_V1 = "1";
    /**
     * Amadeus SOAP header version 2
     */
    const HEADER_V2 = "2";
    /**
     * Amadeus SOAP header version 4
     */
    const HEADER_V4 = "4";

    /**
     * Version string
     *
     * @var string
     */
    const version = "0.0.1dev";
    /**
     * An identifier string for the library (to be used in Received From entries)
     *
     * @var string
     */
    const receivedFromIdentifier = "amabnl-amadeus-ws-client";

    /**
     * @var HandlerInterface
     */
    protected $sessionHandler;

    /**
     * @var RequestCreatorInterface
     */
    protected $requestCreator;

    /**
     * Set the session as stateful (true) or stateless (false)
     *
     * @param bool $newStateful
     */
    public function setStateful($newStateful)
    {
        $this->sessionHandler->setStateful($newStateful);
    }

    /**
     * @return bool
     */
    public function getStateful()
    {
        return $this->sessionHandler->getStateful();
    }

    /**
     * Get the last raw XML message that was sent out
     *
     * @return string|null
     */
    public function getLastRequest()
    {
        $this->sessionHandler->getLastRequest();
    }

    /**
     * Get the last raw XML message that was received
     *
     * @return string|null
     */
    public function getLastResponse()
    {
        $this->sessionHandler->getLastRequest();
    }

    /**
     * Construct Amadeus Web Services client
     *
     * @param Params $params
     */
    public function __construct($params)
    {
        $this->sessionHandler = $this->loadSessionHandler(
            $params->sessionHandler,
            $params->sessionHandlerParams
        );

        $this->requestCreator = $this->loadRequestCreator(
            $params->requestCreator,
            $params->requestCreatorParams,
            self::receivedFromIdentifier . "-" .self::version,
            $this->sessionHandler->getOriginatorOffice()
        );
    }

    /**
     * Terminate a session - only applicable to non-stateless mode.
     *
     * @return \stdClass
     * @throws Exception
     */
    public function securitySignOut()
    {
        $messageOptions = $this->makeMessageOptions([], false, true);

        return $this->sessionHandler->sendMessage(
            'Security_SignOut',
            $this->requestCreator->createRequest(
                'securitySignOut',
                new SecuritySignOutOptions()
            ),
            $messageOptions
        );
    }

    /**
     * PNR_Retrieve - Retrieve an Amadeus PNR by record locator
     *
     * By default, the result will be the PNR_Reply XML as string.
     * That way you can easily parse the PNR's contents with XPath.
     *
     * Set $responseAsString FALSE to get the response as a PHP object.
     *
     * https://webservices.amadeus.com/extranet/viewService.do?id=27&flavourId=1&menuId=functional
     *
     * @param PnrRetrieveOptions $options
     * @param array $messageOptions (OPTIONAL) Set ['asString'] = 'false' to get PNR_Reply as a PHP object.
     * @return string|\stdClass|null
     * @throws Exception
     */
    public function pnrRetrieve($options, $messageOptions = [])
    {
        $messageOptions = $this->makeMessageOptions($messageOptions, true);

        return $this->sessionHandler->sendMessage(
            'PNR_Retrieve',
            $this->requestCreator->createRequest(
                'pnrRetrieve',
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Create a PNR using PNR_AddMultiElements
     *
     * @param PnrCreatePnrOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function pnrCreatePnr($options, $messageOptions = [])
    {
        $messageOptions = $this->makeMessageOptions($messageOptions, true);

        return $this->sessionHandler->sendMessage(
            'PNR_AddMultiElements',
            $this->requestCreator->createRequest(
                'pnrCreatePnr',
                $options
            ),
            $messageOptions
        );
    }

    /**
     * PNR_AddMultiElements - Create a new PNR or update an existing PNR.
     *
     * https://webservices.amadeus.com/extranet/viewService.do?id=25&flavourId=1&menuId=functional
     *
     * @todo implement message creation - maybe split up in separate Create & Modify PNR?
     * @param PnrAddMultiElementsOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function pnrAddMultiElements($options, $messageOptions = [])
    {
        $messageOptions = $this->makeMessageOptions($messageOptions, true);

        return $this->sessionHandler->sendMessage(
            'PNR_AddMultiElements',
            $this->requestCreator->createRequest(
                'pnrAddMultiElements',
                $options
            ),
            $messageOptions
        );
    }

    /**
     * PNR_RetrieveAndDisplay - Retrieve an Amadeus PNR by record locator including extra info
     *
     * This extra info is info you cannot see in the regular PNR, like Offers.
     *
     * By default, the result will be the PNR_RetrieveAndDisplayReply XML as string.
     * That way you can easily parse the PNR's contents with XPath.
     *
     * Set $messageOptions['asString'] = FALSE to get the response as a PHP object.
     *
     * https://webservices.amadeus.com/extranet/viewService.do?id=1922&flavourId=1&menuId=functional
     *
     * @param PnrRetrieveAndDisplayOptions $options Amadeus Record Locator for PNR
     * @param array $messageOptions (OPTIONAL) Set ['asString'] = 'false' to get PNR_RetrieveAndDisplayReply as a PHP object.
     * @return string|\stdClass|null
     * @throws Exception
     **/
    public function pnrRetrieveAndDisplay($options, $messageOptions = [])
    {
        $messageOptions = $this->makeMessageOptions($messageOptions, true);

        return $this->sessionHandler->sendMessage(
            'PNR_RetrieveAndDisplay',
            $this->requestCreator->createRequest(
                'pnrRetrieveAndDisplay',
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Queue_List - get a list of all PNR's on a given queue
     *
     * https://webservices.amadeus.com/extranet/viewService.do?id=52&flavourId=1&menuId=functional
     *
     * @param QueueListOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function queueList(QueueListOptions $options, $messageOptions = [])
    {
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            'Queue_List',
            $this->requestCreator->createRequest(
                'queueList',
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Queue_PlacePNR - Place a PNR on a given queue
     *
     * @param QueuePlacePnrOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function queuePlacePnr(QueuePlacePnrOptions $options, $messageOptions = [])
    {
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            'Queue_PlacePNR',
            $this->requestCreator->createRequest(
                'queuePlacePnr',
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Queue_RemoveItem - remove an item (a PNR) from a given queue
     *
     * @param QueueRemoveItemOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function queueRemoveItem(QueueRemoveItemOptions $options, $messageOptions = [])
    {
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            'Queue_RemoveItem',
            $this->requestCreator->createRequest(
                'queueRemoveItem',
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Queue_MoveItem - move an item (a PNR) from one queue to another.
     *
     * @param QueueMoveItemOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function queueMoveItem(QueueMoveItemOptions $options, $messageOptions = [])
    {
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            'Queue_MoveItem',
            $this->requestCreator->createRequest(
                'queueMoveItem',
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Offer_VerifyOffer
     *
     * To be called in the context of an open PNR
     *
     * @param OfferVerifyOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function offerVerify(OfferVerifyOptions $options, $messageOptions = [])
    {
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            'Offer_VerifyOffer',
            $this->requestCreator->createRequest(
                'offerVerify',
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Offer_ConfirmAirOffer
     *
     * @param OfferConfirmAirOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function offerConfirmAir(OfferConfirmAirOptions $options, $messageOptions = [])
    {
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            'Offer_ConfirmAirOffer',
            $this->requestCreator->createRequest(
                'offerConfirmAir',
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Offer_ConfirmHotelOffer
     *
     * @param OfferConfirmHotelOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function offerConfirmHotel(OfferConfirmHotelOptions $options, $messageOptions = [])
    {
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            'Offer_ConfirmHotelOffer',
            $this->requestCreator->createRequest(
                'offerConfirmHotel',
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Offer_ConfirmCarOffer
     *
     * @param OfferConfirmCarOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function offerConfirmCar(OfferConfirmCarOptions $options, $messageOptions = [])
    {
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            'Offer_ConfirmCarOffer',
            $this->requestCreator->createRequest(
                'offerConfirmCar',
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Make message options
     *
     * Message options are meta options when sending a message to the amadeus web services
     * - (if stateful) should we end the current session after sending this call?
     * - do you want the response as a PHP object or as a string?
     * - ... ?
     *
     * @param array $incoming The Message options chosen by the caller - if any.
     * @param bool $asString Switch if the response should be returned as a string (true) or a PHP object (false).
     * @param bool $endSession Switch if you want to terminate the current session after making the call.
     * @return array
     */
    protected function makeMessageOptions(array $incoming, $asString = false, $endSession = false)
    {
        $options = [
            'asString' => $asString,
            'endSession' => $endSession
        ];

        if (array_key_exists('asString', $incoming)) {
            $options['asString'] = $incoming['asString'];
        }

        if (array_key_exists('endSession', $incoming)) {
            $options['endSession'] = $incoming['endSession'];
        }

        return $options;
    }

    /**
     * Load the session handler
     *
     * Either load the provided session handler or create one depending on incoming parameters.
     *
     * @param HandlerInterface|null $sessionHandler
     * @param Params\SessionHandlerParams $params
     * @return HandlerInterface
     */
    protected function loadSessionHandler($sessionHandler, $params)
    {
        $newSessionHandler = null;

        if ($sessionHandler instanceof HandlerInterface) {
            $newSessionHandler = $sessionHandler;
        } else {
            $newSessionHandler = HandlerFactory::createHandler($params);
        }

        return $newSessionHandler;
    }

    /**
     * Load a request creator
     *
     * A request creator is responsible for generating the correct request to send.
     *
     * @param RequestCreatorInterface|null $requestCreator
     * @param Params\RequestCreatorParams $params
     * @param string $libIdentifier Library identifier & version string (for Received From)
     * @param string $originatorOffice The Office we are signed in with.
     * @return RequestCreatorInterface
     * @throws \RuntimeException
     */
    protected function loadRequestCreator($requestCreator, $params, $libIdentifier, $originatorOffice)
    {
        $newRequestCreator = null;

        if ($requestCreator instanceof RequestCreatorInterface) {
            $newRequestCreator = $requestCreator;
        } else {
            $params->originatorOfficeId = $originatorOffice;

            $newRequestCreator = RequestCreatorFactory::createRequestCreator(
                $params,
                $libIdentifier
            );
        }

        return $newRequestCreator;
    }
}
