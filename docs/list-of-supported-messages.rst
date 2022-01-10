******************
Messages supported
******************

This is the list of messages that are at least partially supported at this time:

- Security_Authenticate
- Security_SignOut
- PNR_Retrieve
- PNR_RetrieveAndDisplay
- PNR_AddMultiElements
- PNR_Cancel
- PNR_DisplayHistory
- PNR_TransferOwnership
- PNR_NameChange
- PNR_Split
- PNR_Ignore
- Queue_List
- Queue_PlacePNR
- Queue_RemoveItem
- Queue_MoveItem
- Fare_MasterPricerTravelBoardSearch
- Fare_MasterPricerExpertSearch
- Fare_MasterPricerCalendar
- Fare_PricePNRWithBookingClass
- Fare_PricePNRWithLowerFares
- Fare_PricePNRWithLowestFare
- Fare_PriceUpsellWithoutPNR
- Fare_InformativePricingWithoutPNR
- Fare_InformativeBestPricingWithoutPNR
- Fare_ConvertCurrency
- Fare_CheckRules
- Fare_GetFareRules
- Fare_GetFareFamilyDescription
- Air_MultiAvailability
- Air_SellFromRecommendation
- Air_FlightInfo
- Air_RetrieveSeatMap
- Air_RebookAirSegment
- FOP_CreateFormOfPayment
- FOP_ValidateFOP
- Ticket_CreateTSTFromPricing
- Ticket_CreateTSMFromPricing
- Ticket_CreateTSMFareElement
- Ticket_CreateTASF
- Ticket_DisplayTST
- Ticket_DisplayTSMP
- Ticket_DeleteTST
- Ticket_DeleteTSMP
- Ticket_DisplayTSMFareElement
- Ticket_RetrieveListOfTSM
- Ticket_CheckEligibility
- Ticket_ATCShopperMasterPricerTravelBoardSearch
- Ticket_RepricePNRWithBookingClass
- Ticket_ReissueConfirmedPricing
- Ticket_CancelDocument
- Ticket_ProcessEDoc
- Ticket_ProcessETicket
- Ticket_InitRefund
- Ticket_UpdateRefund
- Ticket_IgnoreRefund
- Ticket_ProcessRefund
- DocIssuance_IssueTicket
- DocIssuance_IssueMiscellaneousDocuments
- DocIssuance_IssueCombined
- DocRefund_InitRefund
- DocRefund_UpdateRefund
- DocRefund_ProcessRefund
- DocRefund_IgnoreRefund
- Service_IntegratedPricing
- Service_IntegratedCatalogue
- Service_StandaloneCatalogue
- Service_BookPriceService
- Offer_CreateOffer
- Offer_VerifyOffer
- Offer_ConfirmAirOffer
- Offer_ConfirmHotelOffer
- Offer_ConfirmCarOffer
- MiniRule_GetFromRec
- MiniRule_GetFromPricingRec
- MiniRule_GetFromPricing
- MiniRule_GetFromETicket
- Info_EncodeDecodeCity
- PointOfRef_Search
- Command_Cryptic
- PriceXplorer_ExtremeSearch
- SalesReports_DisplayQueryReport
- SalesReports_DisplayDailyOrSummarizedReport
- SalesReports_DisplayNetRemitReport
- PAY_ListVirtualCards
- PAY_GetVirtualCardDetails
- PAY_GenerateVirtualCard (partially)
- PAY_DeleteVirtualCard

**********
To-do list
**********

These messages will be implemented at some point in the future. *Pull requests are welcome!*

- Fare_DisplayFaresForCityPair
- Fare_DisplayBookingCodeInformation
- Fare_CalculateMileage
- Fare_PriceUpsellPNR
- Fare_RebookAndCreateTST
- Air_TLAGetAvailability
- PNR_CreateAuxiliarySegment
- PointOfRef_CategoryList
- Ticket_CreateManualTSMP
- Ticket_UpdateTSMP
- Ticket_RetrieveListOfTSM
- Ticket_AddDocNumber
- TTR_DisplayTrip
- Media_GetMedia
- Service_PriceServiceViaCatalogue
- Service_PriceIntegratedMode
- Service_StandalonePricing
- Hotel_MultiSingleAvailability (see `issue 70 <https://github.com/amabnl/amadeus-ws-client/issues/70>`_)
- Hotel_DescriptiveInfo (see `issue 70 <https://github.com/amabnl/amadeus-ws-client/issues/70>`_)
- Hotel_EnhancedPricing (see `issue 70 <https://github.com/amabnl/amadeus-ws-client/issues/70>`_)
- Hotel_Sell (see `issue 70 <https://github.com/amabnl/amadeus-ws-client/issues/70>`_)
- Hotel_CompleteReservationDetails (see `issue 70 <https://github.com/amabnl/amadeus-ws-client/issues/70>`_)
- Hotel_Terms (see `issue 70 <https://github.com/amabnl/amadeus-ws-client/issues/70>`_)
- Car_Availability
- Car_RateInformationFromAvailability
- Car_Sell
- Car_RateInformationFromCarSegment
- Car_Modify
