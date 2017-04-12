******************
Messages supported
******************

This is the list of messages that are at least partially supported at this time:

- Security_Authenticate
- Security_SignOut
- PNR_Retrieve
- PNR_RetrieveAndDisplay
- PNR_AddMultiElements *(both a dedicated PNR creation message and a "pure" PNR_AddMultiElements)*
- PNR_Cancel
- PNR_DisplayHistory
- PNR_TransferOwnership
- PNR_NameChange
- Queue_List
- Queue_PlacePNR
- Queue_RemoveItem
- Queue_MoveItem
- Fare_MasterPricerTravelBoardSearch
- Fare_MasterPricerCalendar
- Fare_PricePNRWithBookingClass
- Fare_PricePNRWithLowerFares
- Fare_PricePNRWithLowestFare
- Fare_InformativePricingWithoutPNR
- Fare_InformativeBestPricingWithoutPNR
- Fare_ConvertCurrency
- Fare_CheckRules
- Air_MultiAvailability
- Air_SellFromRecommendation
- Air_FlightInfo
- Air_RetrieveSeatMap
- FOP_CreateFormOfPayment
- Ticket_CreateTSTFromPricing
- Ticket_CreateTSMFromPricing
- Ticket_CreateTSMFareElement
- Ticket_DisplayTST
- Ticket_DisplayTSMP
- Ticket_DeleteTST
- Ticket_DeleteTSMP
- Ticket_DisplayTSMFareElement
- Ticket_CheckEligibility
- Ticket_ATCShopperMasterPricerTravelBoardSearch
- Ticket_RepricePNRWithBookingClass
- Ticket_ReissueConfirmedPricing
- DocIssuance_IssueTicket
- DocIssuance_IssueMiscellaneousDocuments
- DocIssuance_IssueCombined
- DocRefund_InitRefund
- Service_IntegratedPricing
- Hotel_Sell
- Offer_CreateOffer
- Offer_VerifyOffer
- Offer_ConfirmAirOffer
- Offer_ConfirmHotelOffer
- Offer_ConfirmCarOffer
- MiniRule_GetFromPricingRec
- MiniRule_GetFromPricing
- Info_EncodeDecodeCity
- PointOfRef_Search
- Command_Cryptic
- PriceXplorer_ExtremeSearch
- SalesReports_DisplayQueryReport

**********
To-do list
**********

These messages will be implemented at some point in the future. *Pull requests are welcome!*

- Fare_DisplayFaresForCityPair
- Fare_DisplayBookingCodeInformation
- Fare_CalculateMileage
- Fare_PriceUpsellWithoutPNR
- Fare_PriceUpsellPNR
- Fare_GetFareFamilyDescription
- Fare_RebookAndCreateTST
- Air_RebookAirSegment
- Air_TLAGetAvailability
- PointOfRef_CategoryList
- Ticket_ProcessEDoc
- Ticket_RetrieveListOfTSM
- Ticket_AddDocNumber
- TTR_DisplayTrip
- DocRefund_UpdateRefund
- DocRefund_ProcessRefund
- Media_GetMedia
- Service_IntegratedCatalogue
- Service_PriceServiceViaCatalogue
- Service_PriceIntegratedMode
- Hotel_MultiSingleAvailability
- Hotel_DescriptiveInfo
- Hotel_EnhancedPricing
- Hotel_CompleteReservationDetails
- Hotel_Terms
- Car_Availability
- Car_RateInformationFromAvailability
- Car_Sell
- Car_RateInformationFromCarSegment
- Car_Modify
