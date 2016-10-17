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
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\HandlerFactory;
use Amadeus\Client\RequestCreator\Factory as RequestCreatorFactory;
use Amadeus\Client\Session\Handler\HandlerInterface;
use Amadeus\Client\ResponseHandler\Base as ResponseHandlerBase;

/**
 * Amadeus Web Service Client.
 *
 * TODO:
 * - support older versions of SoapHeader (1)
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
    const VERSION = "1.1.0-dev";

    /**
     * An identifier string for the library (to be used in Received From entries)
     *
     * @var string
     */
    const RECEIVED_FROM_IDENTIFIER = "amabnl-amadeus-ws-client";

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
     * Authentication parameters
     *
     * @var Params\AuthParams
     */
    protected $authParams;

    /**
     * @var string
     */
    protected $lastMessage;

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
        return $this->sessionHandler->getLastRequest($this->lastMessage);
    }

    /**
     * Get the last raw XML message that was received
     *
     * @return string|null
     */
    public function getLastResponse()
    {
        return $this->sessionHandler->getLastResponse($this->lastMessage);
    }

    /**
     * Get session information for authenticated session
     *
     * - sessionId
     * - sequenceNr
     * - securityToken
     *
     * @return array|null
     */
    public function getSessionData()
    {
        return $this->sessionHandler->getSessionData();
    }

    /**
     * Restore a previously used session
     *
     * To be used when implementing your own session pooling system on legacy Soap Header 2 applications.
     *
     * @param array $sessionData
     * @return bool
     */
    public function setSessionData(array $sessionData)
    {
        return $this->sessionHandler->setSessionData($sessionData);
    }

    /**
     * Construct Amadeus Web Services client
     *
     * @param Params $params
     */
    public function __construct($params)
    {
        if ($params->authParams instanceof Params\AuthParams) {
            $this->authParams = $params->authParams;
            if (isset($params->sessionHandlerParams) &&
                $params->sessionHandlerParams instanceof Params\SessionHandlerParams
            ) {
                $params->sessionHandlerParams->authParams = $this->authParams;
            }
        }

        $this->sessionHandler = $this->loadSessionHandler(
            $params->sessionHandler,
            $params->sessionHandlerParams
        );

        $this->requestCreator = $this->loadRequestCreator(
            $params->requestCreator,
            $params->requestCreatorParams,
            self::RECEIVED_FROM_IDENTIFIER."-".self::VERSION,
            $this->sessionHandler->getOriginatorOffice(),
            $this->sessionHandler->getMessagesAndVersions()
        );

        $this->responseHandler = $this->loadResponseHandler(
            $params->responseHandler
        );
    }

    /**
     * Authenticate.
     *
     * Parameters were provided at construction time (sessionhandlerparams)
     *
     * @return Result
     * @throws Exception
     */
    public function securityAuthenticate()
    {
        $msgName = 'Security_Authenticate';

        return $this->callMessage(
            $msgName,
            new RequestOptions\SecurityAuthenticateOptions(
                $this->authParams
            ),
            [],
            false
        );
    }

    /**
     * Terminate a session - only applicable to non-stateless mode.
     *
     * @return Result
     * @throws Exception
     */
    public function securitySignOut()
    {
        $msgName = 'Security_SignOut';

        return $this->callMessage(
            $msgName,
            new RequestOptions\SecuritySignOutOptions(),
            [],
            true
        );
    }

    /**
     * PNR_Retrieve - Retrieve an Amadeus PNR by record locator
     *
     * @param RequestOptions\PnrRetrieveOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Exception
     */
    public function pnrRetrieve(RequestOptions\PnrRetrieveOptions $options, $messageOptions = [])
    {
        $msgName = 'PNR_Retrieve';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Create a PNR using PNR_AddMultiElements
     *
     * @param RequestOptions\PnrCreatePnrOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function pnrCreatePnr(RequestOptions\PnrCreatePnrOptions $options, $messageOptions = [])
    {
        $msgName = 'PNR_AddMultiElements';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * PNR_AddMultiElements - Create a new PNR or update an existing PNR.
     *
     * https://webservices.amadeus.com/extranet/viewService.do?id=25&flavourId=1&menuId=functional
     *
     * @param RequestOptions\PnrAddMultiElementsOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function pnrAddMultiElements(RequestOptions\PnrAddMultiElementsOptions $options, $messageOptions = [])
    {
        $msgName = 'PNR_AddMultiElements';

        return $this->callMessage($msgName, $options, $messageOptions);
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
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Exception
     **/
    public function pnrRetrieveAndDisplay(RequestOptions\PnrRetrieveAndDisplayOptions $options, $messageOptions = [])
    {
        $msgName = 'PNR_RetrieveAndDisplay';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * PNR_Cancel
     *
     * @param RequestOptions\PnrCancelOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function pnrCancel(RequestOptions\PnrCancelOptions $options, $messageOptions = [])
    {
        $msgName = 'PNR_Cancel';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * PNR_DisplayHistory
     *
     * @param RequestOptions\PnrDisplayHistoryOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function pnrDisplayHistory(RequestOptions\PnrDisplayHistoryOptions $options, $messageOptions = [])
    {
        $msgName = 'PNR_DisplayHistory';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * PNR_TransferOwnership
     *
     * @param RequestOptions\PnrTransferOwnershipOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function pnrTransferOwnership(RequestOptions\PnrTransferOwnershipOptions $options, $messageOptions = [])
    {
        $msgName = 'PNR_TransferOwnership';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Queue_List - get a list of all PNR's on a given queue
     *
     * https://webservices.amadeus.com/extranet/viewService.do?id=52&flavourId=1&menuId=functional
     *
     * @param RequestOptions\QueueListOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function queueList(RequestOptions\QueueListOptions $options, $messageOptions = [])
    {
        $msgName = 'Queue_List';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Queue_PlacePNR - Place a PNR on a given queue
     *
     * @param RequestOptions\QueuePlacePnrOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function queuePlacePnr(RequestOptions\QueuePlacePnrOptions $options, $messageOptions = [])
    {
        $msgName = 'Queue_PlacePNR';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Queue_RemoveItem - remove an item (a PNR) from a given queue
     *
     * @param RequestOptions\QueueRemoveItemOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function queueRemoveItem(RequestOptions\QueueRemoveItemOptions $options, $messageOptions = [])
    {
        $msgName = 'Queue_RemoveItem';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Queue_MoveItem - move an item (a PNR) from one queue to another.
     *
     * @param RequestOptions\QueueMoveItemOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function queueMoveItem(RequestOptions\QueueMoveItemOptions $options, $messageOptions = [])
    {
        $msgName = 'Queue_MoveItem';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Offer_CreateOffer
     *
     * @param RequestOptions\OfferCreateOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function offerCreate(RequestOptions\OfferCreateOptions $options, $messageOptions = [])
    {
        $msgName = 'Offer_CreateOffer';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Offer_VerifyOffer
     *
     * To be called in the context of an open PNR
     *
     * @param RequestOptions\OfferVerifyOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function offerVerify(RequestOptions\OfferVerifyOptions $options, $messageOptions = [])
    {
        $msgName = 'Offer_VerifyOffer';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Offer_ConfirmAirOffer
     *
     * @param RequestOptions\OfferConfirmAirOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function offerConfirmAir(RequestOptions\OfferConfirmAirOptions $options, $messageOptions = [])
    {
        $msgName = 'Offer_ConfirmAirOffer';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Offer_ConfirmHotelOffer
     *
     * @param RequestOptions\OfferConfirmHotelOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function offerConfirmHotel(RequestOptions\OfferConfirmHotelOptions $options, $messageOptions = [])
    {
        $msgName = 'Offer_ConfirmHotelOffer';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Offer_ConfirmCarOffer
     *
     * @param RequestOptions\OfferConfirmCarOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function offerConfirmCar(RequestOptions\OfferConfirmCarOptions $options, $messageOptions = [])
    {
        $msgName = 'Offer_ConfirmCarOffer';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Fare_MasterPricerTravelBoardSearch
     *
     * @param RequestOptions\FareMasterPricerTbSearch $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function fareMasterPricerTravelBoardSearch(
        RequestOptions\FareMasterPricerTbSearch $options,
        $messageOptions = []
    ) {
        $msgName = 'Fare_MasterPricerTravelBoardSearch';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Fare_MasterPricerCalendar
     *
     * @param RequestOptions\FareMasterPricerCalendarOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function fareMasterPricerCalendar(
        RequestOptions\FareMasterPricerCalendarOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Fare_MasterPricerCalendar';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Fare_PricePnrWithBookingClass
     *
     * @param RequestOptions\FarePricePnrWithBookingClassOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function farePricePnrWithBookingClass(
        RequestOptions\FarePricePnrWithBookingClassOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Fare_PricePNRWithBookingClass';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Fare_PricePnrWithLowerFares
     *
     * @param RequestOptions\FarePricePnrWithLowerFaresOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function farePricePnrWithLowerFares(
        RequestOptions\FarePricePnrWithLowerFaresOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Fare_PricePNRWithLowerFares';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Fare_PricePnrWithLowestFare
     *
     * @param RequestOptions\FarePricePnrWithLowestFareOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function farePricePnrWithLowestFare(
        RequestOptions\FarePricePnrWithLowestFareOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Fare_PricePNRWithLowestFare';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Fare_InformativePricingWithoutPNR
     *
     * @param RequestOptions\FareInformativePricingWithoutPnrOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function fareInformativePricingWithoutPnr(
        RequestOptions\FareInformativePricingWithoutPnrOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Fare_InformativePricingWithoutPNR';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Fare_InformativeBestPricingWithoutPNR
     *
     * @param RequestOptions\FareInformativeBestPricingWithoutPnrOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function fareInformativeBestPricingWithoutPnr(
        RequestOptions\FareInformativeBestPricingWithoutPnrOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Fare_InformativeBestPricingWithoutPNR';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Fare_CheckRules
     *
     * @param RequestOptions\FareCheckRulesOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function fareCheckRules(RequestOptions\FareCheckRulesOptions $options, $messageOptions = [])
    {
        $msgName = 'Fare_CheckRules';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Fare_ConvertCurrency
     *
     * @param RequestOptions\FareConvertCurrencyOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function fareConvertCurrency(RequestOptions\FareConvertCurrencyOptions $options, $messageOptions = [])
    {
        $msgName = 'Fare_ConvertCurrency';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Air_MultiAvailability
     *
     * @param RequestOptions\AirMultiAvailabilityOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function airMultiAvailability(
        RequestOptions\AirMultiAvailabilityOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Air_MultiAvailability';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Air_SellFromRecommendation
     *
     * @param RequestOptions\AirSellFromRecommendationOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function airSellFromRecommendation(
        RequestOptions\AirSellFromRecommendationOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Air_SellFromRecommendation';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Air_FlightInfo
     *
     * @param RequestOptions\AirFlightInfoOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function airFlightInfo(RequestOptions\AirFlightInfoOptions $options, $messageOptions = [])
    {
        $msgName = 'Air_FlightInfo';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Air_RetrieveSeatMap
     *
     * @param RequestOptions\AirRetrieveSeatMapOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function airRetrieveSeatMap(RequestOptions\AirRetrieveSeatMapOptions $options, $messageOptions = [])
    {
        $msgName = 'Air_RetrieveSeatMap';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Command_Cryptic
     *
     * @param RequestOptions\CommandCrypticOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function commandCryptic(RequestOptions\CommandCrypticOptions $options, $messageOptions = [])
    {
        $msgName = 'Command_Cryptic';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * MiniRule_GetFromPricingRec
     *
     * @param RequestOptions\MiniRuleGetFromPricingRecOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function miniRuleGetFromPricingRec(
        RequestOptions\MiniRuleGetFromPricingRecOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'MiniRule_GetFromPricingRec';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * MiniRule_GetFromPricing
     *
     * @param RequestOptions\MiniRuleGetFromPricingOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function miniRuleGetFromPricing(
        RequestOptions\MiniRuleGetFromPricingOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'MiniRule_GetFromPricing';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Info_EncodeDecodeCity
     *
     * @param RequestOptions\InfoEncodeDecodeCityOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function infoEncodeDecodeCity(RequestOptions\InfoEncodeDecodeCityOptions $options, $messageOptions = [])
    {
        $msgName = 'Info_EncodeDecodeCity';

        return $this->callMessage($msgName, $options, $messageOptions);
    }


    /**
     * Ticket_CreateTSTFromPricing
     *
     * @param RequestOptions\TicketCreateTstFromPricingOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function ticketCreateTSTFromPricing(
        RequestOptions\TicketCreateTstFromPricingOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Ticket_CreateTSTFromPricing';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_CreateTSMFromPricing
     *
     * @param RequestOptions\TicketCreateTsmFromPricingOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function ticketCreateTSMFromPricing(
        RequestOptions\TicketCreateTsmFromPricingOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Ticket_CreateTSMFromPricing';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_DeleteTST
     *
     * @param RequestOptions\TicketDeleteTstOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function ticketDeleteTST(RequestOptions\TicketDeleteTstOptions $options, $messageOptions = [])
    {
        $msgName = 'Ticket_DeleteTST';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_DisplayTST
     *
     * @param RequestOptions\TicketDisplayTstOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function ticketDisplayTST(RequestOptions\TicketDisplayTstOptions $options, $messageOptions = [])
    {
        $msgName = 'Ticket_DisplayTST';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * DocIssuance_IssueTicket
     *
     * @param RequestOptions\DocIssuanceIssueTicketOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function docIssuanceIssueTicket(
        RequestOptions\DocIssuanceIssueTicketOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'DocIssuance_IssueTicket';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * PriceXplorer_ExtremeSearch
     *
     * @param RequestOptions\PriceXplorerExtremeSearchOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function priceXplorerExtremeSearch(
        RequestOptions\PriceXplorerExtremeSearchOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'PriceXplorer_ExtremeSearch';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * SalesReports_DisplayQueryReport
     *
     * @param RequestOptions\SalesReportsDisplayQueryReportOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function salesReportsDisplayQueryReport(
        RequestOptions\SalesReportsDisplayQueryReportOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'SalesReports_DisplayQueryReport';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Service_IntegratedPricing
     *
     * @param RequestOptions\ServiceIntegratedPricingOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     */
    public function serviceIntegratedPricing(
        RequestOptions\ServiceIntegratedPricingOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Service_IntegratedPricing';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Call a message with the given parameters
     *
     * @param string $messageName
     * @param RequestOptions\RequestOptionsInterface $options
     * @param array $messageOptions
     * @param bool $endSession
     * @return Result
     * @throws Client\Exception
     * @throws Client\Struct\InvalidArgumentException
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \SoapFault
     */
    protected function callMessage($messageName, $options, $messageOptions, $endSession = false)
    {
        $messageOptions = $this->makeMessageOptions($messageOptions, $endSession);

        $this->lastMessage = $messageName;

        $sendResult = $this->sessionHandler->sendMessage(
            $messageName,
            $this->requestCreator->createRequest(
                $messageName,
                $options
            ),
            $messageOptions
        );

        return $this->responseHandler->analyzeResponse(
            $sendResult,
            $messageName
        );
    }

    /**
     * Make message options
     *
     * Message options are meta options when sending a message to the amadeus web services
     * - (if stateful) should we end the current session after sending this call?
     * - ... ?
     *
     * @param array $incoming The Message options chosen by the caller - if any.
     * @param bool $endSession Switch if you want to terminate the current session after making the call.
     * @return array
     */
    protected function makeMessageOptions(array $incoming, $endSession = false)
    {
        $options = [
            'endSession' => $endSession
        ];

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
     * @param Params\SessionHandlerParams|null $params
     * @return HandlerInterface
     */
    protected function loadSessionHandler($sessionHandler, $params)
    {
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
     * @return ResponseHandlerInterface
     * @throws \RuntimeException
     */
    protected function loadResponseHandler($responseHandler)
    {
        if ($responseHandler instanceof ResponseHandlerInterface) {
            $newResponseHandler = $responseHandler;
        } else {
            $newResponseHandler = new ResponseHandlerBase();
        }

        return $newResponseHandler;
    }
}
