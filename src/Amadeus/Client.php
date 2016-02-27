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
use Amadeus\Client\RequestOptions;
use Amadeus\Client\ResponseHandler\ResponseHandlerInterface;
use Amadeus\Client\Session\Handler\HandlerFactory;
use Amadeus\Client\RequestCreator\Factory as RequestCreatorFactory;
use Amadeus\Client\Session\Handler\HandlerInterface;
use Amadeus\Client\ResponseHandler\Base as ResponseHandlerBase;

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
 *      OSI segment
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
     * Session Handler will be sending all the messages and handling all session-related things.
     *
     * @var HandlerInterface
     */
    protected $sessionHandler;

    /**
     * Request Creator is will create the correct message structure to send to the SOAP server.
     *
     * @var RequestCreatorInterface
     */
    protected $requestCreator;

    /**
     * Response Handler will check the received response for errors.
     *
     * @var ResponseHandlerInterface
     */
    protected $responseHandler;

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
    public function isStateful()
    {
        return $this->sessionHandler->isStateful();
    }

    /**
     * Get the last raw XML message that was sent out
     *
     * @return string|null
     */
    public function getLastRequest()
    {
        return $this->sessionHandler->getLastRequest();
    }

    /**
     * Get the last raw XML message that was received
     *
     * @return string|null
     */
    public function getLastResponse()
    {
        return $this->sessionHandler->getLastResponse();
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
            $this->sessionHandler->getOriginatorOffice(),
            $this->sessionHandler->getMessagesAndVersions()
        );

        $this->responseHandler = $this->loadResponseHandler(
            $params->responseHandler
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
        $msgName = 'Security_SignOut';
        $messageOptions = $this->makeMessageOptions([], false, true);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
                new RequestOptions\SecuritySignOutOptions()
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
     * @param RequestOptions\PnrRetrieveOptions $options
     * @param array $messageOptions (OPTIONAL) Set ['asString'] = 'false' to get PNR_Reply as a PHP object.
     * @return string|\stdClass|null
     * @throws Exception
     */
    public function pnrRetrieve(RequestOptions\PnrRetrieveOptions $options, $messageOptions = [])
    {
        $msgName = 'PNR_Retrieve';
        $messageOptions = $this->makeMessageOptions($messageOptions, true);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Create a PNR using PNR_AddMultiElements
     *
     * @param RequestOptions\PnrCreatePnrOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function pnrCreatePnr(RequestOptions\PnrCreatePnrOptions $options, $messageOptions = [])
    {
        $msgName = 'PNR_AddMultiElements';
        $messageOptions = $this->makeMessageOptions($messageOptions, true);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
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
     * @param RequestOptions\PnrAddMultiElementsOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function pnrAddMultiElements(RequestOptions\PnrAddMultiElementsOptions $options, $messageOptions = [])
    {
        $msgName = 'PNR_AddMultiElements';
        $messageOptions = $this->makeMessageOptions($messageOptions, true);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
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
     * @param RequestOptions\PnrRetrieveAndDisplayOptions $options Amadeus Record Locator for PNR
     * @param array $messageOptions (OPTIONAL) Set ['asString'] = 'false' to get PNR_RetrieveAndDisplayReply as a PHP object.
     * @return string|\stdClass|null
     * @throws Exception
     **/
    public function pnrRetrieveAndDisplay(RequestOptions\PnrRetrieveAndDisplayOptions $options, $messageOptions = [])
    {
        $msgName = 'PNR_RetrieveAndDisplay';
        $messageOptions = $this->makeMessageOptions($messageOptions, true);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
                $options
            ),
            $messageOptions
        );
    }

    /**
     * PNR_Cancel
     *
     * @param RequestOptions\PnrCancelOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function pnrCancel(RequestOptions\PnrCancelOptions $options, $messageOptions = [])
    {
        $msgName = 'PNR_Cancel';
        $messageOptions = $this->makeMessageOptions($messageOptions, true);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
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
     * @param RequestOptions\QueueListOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function queueList(RequestOptions\QueueListOptions $options, $messageOptions = [])
    {
        $msgName = 'Queue_List';
        $messageOptions = $this->makeMessageOptions($messageOptions);

        $wsResult = $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
                $options
            ),
            $messageOptions
        );

        $this->responseHandler->analyzeResponse($this->getLastResponse(), $msgName);

        return $wsResult;
    }

    /**
     * Queue_PlacePNR - Place a PNR on a given queue
     *
     * @param RequestOptions\QueuePlacePnrOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function queuePlacePnr(RequestOptions\QueuePlacePnrOptions $options, $messageOptions = [])
    {
        $msgName = 'Queue_PlacePNR';
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Queue_RemoveItem - remove an item (a PNR) from a given queue
     *
     * @param RequestOptions\QueueRemoveItemOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function queueRemoveItem(RequestOptions\QueueRemoveItemOptions $options, $messageOptions = [])
    {
        $msgName = 'Queue_RemoveItem';
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Queue_MoveItem - move an item (a PNR) from one queue to another.
     *
     * @param RequestOptions\QueueMoveItemOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function queueMoveItem(RequestOptions\QueueMoveItemOptions $options, $messageOptions = [])
    {
        $msgName = 'Queue_MoveItem';
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
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
     * @param RequestOptions\OfferVerifyOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function offerVerify(RequestOptions\OfferVerifyOptions $options, $messageOptions = [])
    {
        $msgName = 'Offer_VerifyOffer';
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Offer_ConfirmAirOffer
     *
     * @param RequestOptions\OfferConfirmAirOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function offerConfirmAir(RequestOptions\OfferConfirmAirOptions $options, $messageOptions = [])
    {
        $msgName = 'Offer_ConfirmAirOffer';
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Offer_ConfirmHotelOffer
     *
     * @param RequestOptions\OfferConfirmHotelOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function offerConfirmHotel(RequestOptions\OfferConfirmHotelOptions $options, $messageOptions = [])
    {
        $msgName = 'Offer_ConfirmHotelOffer';
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Offer_ConfirmCarOffer
     *
     * @param RequestOptions\OfferConfirmCarOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function offerConfirmCar(RequestOptions\OfferConfirmCarOptions $options, $messageOptions = [])
    {
        $msgName = 'Offer_ConfirmCarOffer';
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Fare_MasterPricerTravelBoardSearch
     *
     * @param RequestOptions\FareMasterPricerTbSearch $options
     * @param array $messageOptions
     * @return mixed
     */
    public function fareMasterPricerTravelBoardSearch(RequestOptions\FareMasterPricerTbSearch $options, $messageOptions = [])
    {
        $msgName = 'Fare_MasterPricerTravelBoardSearch';
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Fare_PricePnrWithBookingClass
     *
     * @param RequestOptions\FarePricePnrWithBookingClassOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function farePricePnrWithBookingClass(RequestOptions\FarePricePnrWithBookingClassOptions $options, $messageOptions = [])
    {
        $msgName = 'Fare_PricePNRWithBookingClass';
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Air_SellFromRecommendation
     *
     * @param RequestOptions\AirSellFromRecommendationOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function airSellFromRecommendation(RequestOptions\AirSellFromRecommendationOptions $options, $messageOptions = [])
    {
        $msgName = 'Air_SellFromRecommendation';
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Command_Cryptic
     *
     * @param RequestOptions\CommandCrypticOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function commandCryptic(RequestOptions\CommandCrypticOptions $options, $messageOptions = [])
    {
        $msgName = 'Command_Cryptic';
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
                $options
            ),
            $messageOptions
        );
    }

    /**
     * MiniRule_GetFromPricingRec
     *
     * @param RequestOptions\MiniRuleGetFromPricingRecOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function miniRuleGetFromPricingRec(RequestOptions\MiniRuleGetFromPricingRecOptions $options, $messageOptions = [])
    {
        $msgName = 'MiniRule_GetFromPricingRec';
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
                $options
            ),
            $messageOptions
        );
    }

    /**
     * Info_EncodeDecodeCity
     *
     * @param RequestOptions\InfoEncodeDecodeCityOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function infoEncodeDecodeCity(RequestOptions\InfoEncodeDecodeCityOptions $options, $messageOptions = [])
    {
        $msgName = 'Info_EncodeDecodeCity';
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
                $options
            ),
            $messageOptions
        );
    }


    /**
     * Ticket_CreateTSTFromPricing
     *
     * @param RequestOptions\TicketCreateTstFromPricingOptions $options
     * @param array $messageOptions
     * @return mixed
     */
    public function ticketCreateTSTFromPricing(RequestOptions\TicketCreateTstFromPricingOptions $options, $messageOptions = [])
    {
        $msgName = 'Ticket_CreateTSTFromPricing';
        $messageOptions = $this->makeMessageOptions($messageOptions);

        return $this->sessionHandler->sendMessage(
            $msgName,
            $this->requestCreator->createRequest(
                $msgName,
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
     * @param array $mesVer Messages & Versions array of active messages in the WSDL
     * @return RequestCreatorInterface
     * @throws \RuntimeException
     */
    protected function loadRequestCreator($requestCreator, $params, $libIdentifier, $originatorOffice, $mesVer)
    {
        $newRequestCreator = null;

        if ($requestCreator instanceof RequestCreatorInterface) {
            $newRequestCreator = $requestCreator;
        } else {
            $params->originatorOfficeId = $originatorOffice;
            $params->messagesAndVersions = $mesVer;

            $newRequestCreator = RequestCreatorFactory::createRequestCreator(
                $params,
                $libIdentifier
            );
        }

        return $newRequestCreator;
    }

    /**
     * Load a response handler
     *
     * @param ResponseHandlerInterface|null $responseHandler
     *
     * @return ResponseHandlerInterface
     * @throws \RuntimeException
     */
    protected function loadResponseHandler($responseHandler)
    {
        $newResponseHandler = null;

        if ($responseHandler instanceof ResponseHandlerInterface) {
            $newResponseHandler = $responseHandler;
        } else {
            $newResponseHandler = new ResponseHandlerBase();
        }

        return $newResponseHandler;
    }
}
