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

use Amadeus\Client\Base;
use Amadeus\Client\Exception;
use Amadeus\Client\Params;
use Amadeus\Client\RequestOptions;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\UnsupportedOperationException;

/**
 * Amadeus Web Service Client.
 *
 * TODO:
 * - support older versions of SoapHeader (1)
 *
 * @package Amadeus
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Client extends Base
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
    const VERSION = "1.13.0-dev";

    /**
     * An identifier string for the library (to be used in Received From entries)
     *
     * @var string
     */
    const RECEIVED_FROM_IDENTIFIER = "amabnl-amadeus-ws-client";

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
     * Get TransactionFlowLink Consumer Id
     *
     * @return string|null
     */
    public function getConsumerId()
    {
        return $this->sessionHandler->getConsumerId();
    }

    /**
     * Set TransactionFlowLink Consumer Id
     *
     * @throws UnsupportedOperationException when used on unsupported WSAP versions
     * @param string $id
     * @return void
     */
    public function setConsumerId($id)
    {
        $this->sessionHandler->setTransactionFlowLink(true);
        $this->sessionHandler->setConsumerId($id);
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
     * Get the request headers for the last SOAP message that was sent out
     *
     * @return string|null
     */
    public function getLastRequestHeaders()
    {
        return $this->sessionHandler->getLastRequestHeaders($this->lastMessage);
    }

    /**
     * Get the response headers for the last SOAP message that was received
     *
     * @return string|null
     */
    public function getLastResponseHeaders()
    {
        return $this->sessionHandler->getLastResponseHeaders($this->lastMessage);
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
    public function __construct(Params $params)
    {
        $this->loadClientParams(
            $params,
            self::RECEIVED_FROM_IDENTIFIER,
            self::VERSION
        );
    }

    /**
     * Authenticate.
     *
     * Authentication Parameters were provided at construction time (authParams)
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function pnrRetrieve(RequestOptions\PnrRetrieveOptions $options, $messageOptions = [])
    {
        $msgName = 'PNR_Retrieve';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * PNR_Split
     *
     * @param RequestOptions\PnrSplitOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function pnrSplit(
        RequestOptions\PnrSplitOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'PNR_Split';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Create a PNR using PNR_AddMultiElements
     *
     * @param RequestOptions\PnrCreatePnrOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * https://webservices.amadeus.com/extranet/viewService.do?id=1922&flavourId=1&menuId=functional
     *
     * @param RequestOptions\PnrRetrieveAndDisplayOptions $options Amadeus Record Locator for PNR
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function pnrTransferOwnership(RequestOptions\PnrTransferOwnershipOptions $options, $messageOptions = [])
    {
        $msgName = 'PNR_TransferOwnership';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * PNR_NameChange
     *
     * @param RequestOptions\PnrNameChangeOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function pnrNameChange(RequestOptions\PnrNameChangeOptions $options, $messageOptions = [])
    {
        $msgName = 'PNR_NameChange';

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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function queuePlacePnr(RequestOptions\QueuePlacePnrOptions $options, $messageOptions = [])
    {
        $msgName = 'Queue_PlacePNR';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * PNR_Ignore - Ignore an Amadeus PNR by record locator
     *
     * @param RequestOptions\PnrIgnoreOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function pnrIgnore(RequestOptions\PnrIgnoreOptions $options, $messageOptions = [])
    {
        $msgName = 'PNR_Ignore';

        return $this->callMessage($msgName, $options, $messageOptions);
    }


    /**
     * Queue_RemoveItem - remove an item (a PNR) from a given queue
     *
     * @param RequestOptions\QueueRemoveItemOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function offerConfirmCar(RequestOptions\OfferConfirmCarOptions $options, $messageOptions = [])
    {
        $msgName = 'Offer_ConfirmCarOffer';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Fare_MasterPricerExpertSearch
     *
     * @param RequestOptions\FareMasterPricerExSearchOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function fareMasterPricerExpertSearch(
        RequestOptions\FareMasterPricerExSearchOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Fare_MasterPricerExpertSearch';

        return $this->callMessage($msgName, $options, $messageOptions);
    }


    /**
     * Fare_MasterPricerTravelBoardSearch
     *
     * @param RequestOptions\FareMasterPricerTbSearch $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function fareInformativePricingWithoutPnr(
        RequestOptions\FareInformativePricingWithoutPnrOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Fare_InformativePricingWithoutPNR';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Fare_PriceUpsellWithoutPNR
     *
     * @param RequestOptions\FarePriceUpsellWithoutPnrOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function farePriceUpsellWithoutPnr(
        RequestOptions\FarePriceUpsellWithoutPnrOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Fare_PriceUpsellWithoutPNR';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Fare_GetFareFamilyDescription
     *
     * @param RequestOptions\FareGetFareFamilyDescriptionOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function fareGetFareFamilyDescription(
        RequestOptions\FareGetFareFamilyDescriptionOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Fare_GetFareFamilyDescription';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Fare_InformativeBestPricingWithoutPNR
     *
     * @param RequestOptions\FareInformativeBestPricingWithoutPnrOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function fareCheckRules(RequestOptions\FareCheckRulesOptions $options, $messageOptions = [])
    {
        $msgName = 'Fare_CheckRules';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Fare_GetFareRules
     *
     * @param RequestOptions\FareGetFareRulesOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function fareGetFareRules(RequestOptions\FareGetFareRulesOptions $options, $messageOptions = [])
    {
        $msgName = 'Fare_GetFareRules';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Fare_ConvertCurrency
     *
     * @param RequestOptions\FareConvertCurrencyOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function airRetrieveSeatMap(RequestOptions\AirRetrieveSeatMapOptions $options, $messageOptions = [])
    {
        $msgName = 'Air_RetrieveSeatMap';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Air_RebookAirSegment
     *
     * @param RequestOptions\AirRebookAirSegmentOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function airRebookAirSegment(RequestOptions\AirRebookAirSegmentOptions $options, $messageOptions = [])
    {
        $msgName = 'Air_RebookAirSegment';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Command_Cryptic
     *
     * @param RequestOptions\CommandCrypticOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function commandCryptic(RequestOptions\CommandCrypticOptions $options, $messageOptions = [])
    {
        $msgName = 'Command_Cryptic';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * MiniRule_GetFromRec
     *
     * @param RequestOptions\MiniRuleGetFromRecOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function miniRuleGetFromRec(RequestOptions\MiniRuleGetFromRecOptions $options, $messageOptions = [])
    {
        $msgName = 'MiniRule_GetFromRec';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * MiniRule_GetFromPricingRec
     *
     * @param RequestOptions\MiniRuleGetFromPricingRecOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function miniRuleGetFromPricing(
        RequestOptions\MiniRuleGetFromPricingOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'MiniRule_GetFromPricing';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * MiniRule_GetFromETicket
     *
     * @param RequestOptions\MiniRuleGetFromETicketOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function miniRuleGetFromETicket(
        RequestOptions\MiniRuleGetFromETicketOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'MiniRule_GetFromETicket';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Info_EncodeDecodeCity
     *
     * @param RequestOptions\InfoEncodeDecodeCityOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function infoEncodeDecodeCity(RequestOptions\InfoEncodeDecodeCityOptions $options, $messageOptions = [])
    {
        $msgName = 'Info_EncodeDecodeCity';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * PointOfRef_Search
     *
     * @param RequestOptions\PointOfRefSearchOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function pointOfRefSearch(RequestOptions\PointOfRefSearchOptions $options, $messageOptions = [])
    {
        $msgName = 'PointOfRef_Search';

        return $this->callMessage($msgName, $options, $messageOptions);
    }


    /**
     * Ticket_CreateTSTFromPricing
     *
     * @param RequestOptions\TicketCreateTstFromPricingOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketCreateTSMFromPricing(
        RequestOptions\TicketCreateTsmFromPricingOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Ticket_CreateTSMFromPricing';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_CreateTSMFareElement
     *
     * @param RequestOptions\TicketCreateTsmFareElOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketCreateTSMFareElement(
        RequestOptions\TicketCreateTsmFareElOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Ticket_CreateTSMFareElement';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_CreateTASF
     *
     * @param RequestOptions\TicketCreateTasfOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketCreateTASF(
        RequestOptions\TicketCreateTasfOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Ticket_CreateTASF';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_DeleteTST
     *
     * @param RequestOptions\TicketDeleteTstOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketDeleteTST(RequestOptions\TicketDeleteTstOptions $options, $messageOptions = [])
    {
        $msgName = 'Ticket_DeleteTST';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_DeleteTSMP
     *
     * @param RequestOptions\TicketDeleteTsmpOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketDeleteTSMP(RequestOptions\TicketDeleteTsmpOptions $options, $messageOptions = [])
    {
        $msgName = 'Ticket_DeleteTSMP';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_DisplayTST
     *
     * @param RequestOptions\TicketDisplayTstOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketDisplayTST(RequestOptions\TicketDisplayTstOptions $options, $messageOptions = [])
    {
        $msgName = 'Ticket_DisplayTST';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_DisplayTSMP
     *
     * @param RequestOptions\TicketDisplayTsmpOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketDisplayTSMP(RequestOptions\TicketDisplayTsmpOptions $options, $messageOptions = [])
    {
        $msgName = 'Ticket_DisplayTSMP';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_RetrieveListOfTSM
     *
     * @param RequestOptions\TicketRetrieveListOfTSMOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketRetrieveListOfTSM(
        RequestOptions\TicketRetrieveListOfTSMOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Ticket_RetrieveListOfTSM';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_DisplayTSMFareElement
     *
     * @param RequestOptions\TicketDisplayTsmFareElOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketDisplayTSMFareElement(
        RequestOptions\TicketDisplayTsmFareElOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Ticket_DisplayTSMFareElement';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_CheckEligibility
     *
     * @param RequestOptions\TicketCheckEligibilityOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketCheckEligibility(
        RequestOptions\TicketCheckEligibilityOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Ticket_CheckEligibility';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_ATCShopperMasterPricerTravelBoardSearch
     *
     * @param RequestOptions\TicketAtcShopperMpTbSearchOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketAtcShopperMasterPricerTravelBoardSearch(
        RequestOptions\TicketAtcShopperMpTbSearchOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Ticket_ATCShopperMasterPricerTravelBoardSearch';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_ATCShopperMasterPricerCalendar
     *
     * @param RequestOptions\TicketAtcShopperMpCalendarOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketAtcShopperMasterPricerCalendar(
        RequestOptions\TicketAtcShopperMpCalendarOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Ticket_ATCShopperMasterPricerCalendar';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_RepricePNRWithBookingClass
     *
     * @param RequestOptions\TicketRepricePnrWithBookingClassOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketRepricePnrWithBookingClass(
        RequestOptions\TicketRepricePnrWithBookingClassOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Ticket_RepricePNRWithBookingClass';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_CancelDocument
     *
     * @param RequestOptions\TicketCancelDocumentOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketCancelDocument(
        RequestOptions\TicketCancelDocumentOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Ticket_CancelDocument';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_ReissueConfirmedPricing
     *
     * @param RequestOptions\TicketReissueConfirmedPricingOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketReissueConfirmedPricing(
        RequestOptions\TicketReissueConfirmedPricingOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Ticket_ReissueConfirmedPricing';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_ProcessEDoc
     *
     * @param RequestOptions\TicketProcessEDocOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketProcessEDoc(RequestOptions\TicketProcessEDocOptions $options, $messageOptions = [])
    {
        $msgName = 'Ticket_ProcessEDoc';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_ProcessETicket
     *
     * @param RequestOptions\TicketProcessETicketOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketProcessETicket(RequestOptions\TicketProcessETicketOptions $options, $messageOptions = [])
    {
        $msgName = 'Ticket_ProcessETicket';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * DocIssuance_IssueTicket
     *
     * @param RequestOptions\DocIssuanceIssueTicketOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function docIssuanceIssueTicket(
        RequestOptions\DocIssuanceIssueTicketOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'DocIssuance_IssueTicket';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * DocIssuance_IssueMiscellaneousDocuments
     *
     * @param RequestOptions\DocIssuanceIssueMiscDocOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function docIssuanceIssueMiscellaneousDocuments(
        RequestOptions\DocIssuanceIssueMiscDocOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'DocIssuance_IssueMiscellaneousDocuments';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * DocIssuance_IssueCombined
     *
     * @param RequestOptions\DocIssuanceIssueCombinedOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function docIssuanceIssueCombined(
        RequestOptions\DocIssuanceIssueCombinedOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'DocIssuance_IssueCombined';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * DocRefund_InitRefund
     *
     * @param RequestOptions\DocRefundInitRefundOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function docRefundInitRefund(
        RequestOptions\DocRefundInitRefundOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'DocRefund_InitRefund';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * DocRefund_IgnoreRefund
     *
     * @param RequestOptions\DocRefundIgnoreRefundOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function docRefundIgnoreRefund(
        RequestOptions\DocRefundIgnoreRefundOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'DocRefund_IgnoreRefund';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * DocRefund_UpdateRefund
     *
     * @param RequestOptions\DocRefundUpdateRefundOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function docRefundUpdateRefund(
        RequestOptions\DocRefundUpdateRefundOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'DocRefund_UpdateRefund';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * DocRefund_ProcessRefund
     *
     * @param RequestOptions\DocRefundProcessRefundOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function docRefundProcessRefund(
        RequestOptions\DocRefundProcessRefundOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'DocRefund_ProcessRefund';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_InitRefund
     *
     * @param RequestOptions\TicketInitRefundOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketInitRefund(
        RequestOptions\TicketInitRefundOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Ticket_InitRefund';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_IgnoreRefund
     *
     * @param RequestOptions\TicketIgnoreRefundOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketIgnoreRefund(
        RequestOptions\TicketIgnoreRefundOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Ticket_IgnoreRefund';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_ProcessRefund
     *
     * @param RequestOptions\TicketProcessRefundOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketProcessRefund(
        RequestOptions\TicketProcessRefundOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Ticket_ProcessRefund';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Ticket_UpdateRefund
     *
     * @param RequestOptions\TicketUpdateRefundOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function ticketUpdateRefund(
        RequestOptions\TicketUpdateRefundOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Ticket_UpdateRefund';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * FOP_CreateFormOfPayment
     *
     * @param RequestOptions\FopCreateFopOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function fopCreateFormOfPayment(RequestOptions\FopCreateFopOptions $options, $messageOptions = [])
    {
        $msgName = 'FOP_CreateFormOfPayment';

        return $this->callMessage($msgName, $options, $messageOptions);
    }


    /**
     * FOP_CreateFormOfPayment
     *
     * @param RequestOptions\FopValidateFopOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function fopValidateFOP(RequestOptions\FopValidateFopOptions $options, $messageOptions = [])
    {
        $msgName = 'FOP_ValidateFOP';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * PriceXplorer_ExtremeSearch
     *
     * @param RequestOptions\PriceXplorerExtremeSearchOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
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
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function serviceIntegratedPricing(
        RequestOptions\ServiceIntegratedPricingOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Service_IntegratedPricing';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Service_IntegratedCatalogue
     *
     * @param RequestOptions\ServiceIntegratedCatalogueOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function serviceIntegratedCatalogue(
        RequestOptions\ServiceIntegratedCatalogueOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Service_IntegratedCatalogue';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Service_BookPriceService
     *
     * @param RequestOptions\ServiceBookPriceServiceOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function serviceBookPriceService(
        RequestOptions\ServiceBookPriceServiceOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'Service_BookPriceService';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * SalesReports_DisplayorSummarizedReport
     *
     * @param RequestOptions\SalesReportsDisplayDailyOrSummarizedReportOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function salesReportsDisplayDailyOrSummarizedReport(
        RequestOptions\SalesReportsDisplayDailyOrSummarizedReportOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'SalesReports_DisplayDailyOrSummarizedReport';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * SalesReports_DisplayNetRemitReport
     *
     * @param RequestOptions\SalesReportsDisplayNetRemitReportOptions $options
     * @param array $messageOptions (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function salesReportsDisplayNetRemitReport(
        RequestOptions\SalesReportsDisplayNetRemitReportOptions $options,
        $messageOptions = []
    ) {
        $msgName = 'SalesReports_DisplayNetRemitReport';

        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Service_StandaloneCatalogue
     *
     * @param RequestOptions\ServiceStandaloneCatalogueOptions $options
     * @param array $messageOptions
     *            (OPTIONAL)
     * @return Result
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws Exception
     */
    public function serviceStandaloneCatalogue(RequestOptions\ServiceStandaloneCatalogueOptions $options, $messageOptions = [])
    {
        $msgName = 'Service_StandaloneCatalogue';

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

        $response = $this->responseHandler->analyzeResponse(
            $sendResult,
            $messageName
        );

        if ($messageOptions['returnXml'] === false) {
            $response->responseXml = null;
        }

        return $response;
    }

    /**
     * Make message options
     *
     * Message options are meta options when sending a message to the amadeus web services
     * - 'endSession' (if stateful) : should we end the current session after sending this call?
     * - 'returnXml' : Should we return the XML string in the Result::responseXml property?
     *   (this overrides the default setting returnXml in the Amadeus\Client\Params for a single message)
     *
     * @param array $incoming The Message options chosen by the caller - if any.
     * @param bool $endSession Switch if you want to terminate the current session after making the call.
     * @return array
     */
    protected function makeMessageOptions(array $incoming, $endSession = false)
    {
        $options = [
            'endSession' => $endSession,
            'returnXml' => $this->returnResultXml
        ];

        if (array_key_exists('endSession', $incoming)) {
            $options['endSession'] = $incoming['endSession'];
        }

        if (array_key_exists('returnXml', $incoming)) {
            $options['returnXml'] = $incoming['returnXml'];
        }

        return $options;
    }
}
