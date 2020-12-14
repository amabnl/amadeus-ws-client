## Unreleased
* Implemented `MiniRule_GetFromRec` (Amadeus docs emphasize to use `MiniRule_GetFromRec` instead of `MiniRule_GetFromETicket`, `MiniRule_GetFromPricing`, `MiniRule_GetFromPricingRec`) - Aleksandr Kalugin

## Release 1.11.0 (9 July 2020)
https://github.com/amabnl/amadeus-ws-client/pull/344: 
* Added support to add accountNumber in ``Queue_List``
* Added support to add freeText for payment type Cash and CC in ``Pnr_AddMultiElements``
* Added support for "ZapOff" in ``Fare_PricePnrWithBookingClass``
* Added support for "Fare Misc TKT Information", "Fare Endorsement", "Fare Endorsement" , "Fare Misc Information" in ``Pnr_AddMultiElements``
* Implemented ``Ticket_UpdateRefund`` (https://github.com/amabnl/amadeus-ws-client/pull/407) - Vladimir Kikot

# Release 1.10.0 (27 May 2020)
* Bugfix for a SOAP-ERROR in ``DocIssuance_IssueMiscellaneousDocuments`` (https://github.com/amabnl/amadeus-ws-client/pull/359) - Artem Zakharchenko
* Add Anchored Segment in ``Fare_MasterPricerTravelBoardSearch`` and implemented ``Service_BookPriceService`` (https://github.com/amabnl/amadeus-ws-client/pull/324) - Michal Hernas
* Implemented ``Fare_PriceUpsellWithoutPNR`` and ``Fare_GetFareFamilyDescription`` (https://github.com/amabnl/amadeus-ws-client/pull/388) - Valeriy
* Implemented ``Ticket_ATCShopperMasterPricerCalendar`` (https://github.com/amabnl/amadeus-ws-client/pull/398) - Artem Zakharchenko

# Release 1.9.0 (23 Jun 2019)
* Added support for multiple seat request in ``PNR_AddMultiElements`` (https://github.com/amabnl/amadeus-ws-client/pull/335) - Artem Zakharchenko
* Added `stockProviderDetails` to ``DocRefund_InitRefund `` (https://github.com/amabnl/amadeus-ws-client/pull/341) - Ruslan Poltayev
* Implemented ``Service_StandaloneCatalogue`` - (https://github.com/amabnl/amadeus-ws-client/pull/220) - arvind-pandey & Artem Zakharchenko

# Release 1.8.1 (29 May 2019)
* Support for Queue TimeMode in ``Queue_RemoveItem`` (https://github.com/amabnl/amadeus-ws-client/pull/333) - Ruslan Poltayev

# Release 1.8.0 (25 May 2019)
* Added support for Ticketing Price Scheme option in ``Fare_MasterPricerTravelBoardSearch`` (https://github.com/amabnl/amadeus-ws-client/pull/193) - Artem Zakharchenko
* Added support for cabin options on itinerary-level in ``Fare_MasterPricerTravelBoardSearch`` (https://github.com/amabnl/amadeus-ws-client/pull/202) - Michal Hernas
* Added support for OptionDetails when proving pricing options overrides in ``Fare_PricePNRWithBookingClass`` (v13+) and associated messages (https://github.com/amabnl/amadeus-ws-client/pull/217) - LeoTravel
* Added support for providing a Company and date in combination with a Record Locator in ``Air_RetrieveSeatMap`` (https://github.com/amabnl/amadeus-ws-client/issues/219)
* Added support for requesting Most Restrictive display ``Air_RetrieveSeatMap``
* Fixed a bug with a double authentication when using ``Security_Authenticate`` on a SoapHeader 4 WSAP (https://github.com/amabnl/amadeus-ws-client/pull/234) - Artem Zakharchenko
* Add support for Time Mode in ``Queue_*`` messages (https://github.com/amabnl/amadeus-ws-client/issues/326) - Ruslan Poltayev
* Add support for Fare Families in ``Fare_PricePNRWithBookingClass`` and associates messages (https://github.com/amabnl/amadeus-ws-client/pull/264) - Marcel Lamm
* Implemented ``Ticket_RetrieveListOfTSM`` (https://github.com/amabnl/amadeus-ws-client/pull/194) - Michal Hernas
* Implemented ``Ticket_CreateTASF`` (https://github.com/amabnl/amadeus-ws-client/pull/197) - Artem Zakharchenko
* Implemented ``PNR_Ignore`` (https://github.com/amabnl/amadeus-ws-client/pull/198) - Michael Mueller
* Implemented ``SalesReports_DisplayDailyOrSummarizedReport`` and ``SalesReports_DisplayNetRemitReport`` (https://github.com/amabnl/amadeus-ws-client/pull/241) - Artem Zakharchenko
* Implemented ``Fare_MasterPricerExpertSearch`` (https://github.com/amabnl/amadeus-ws-client/pull/170) - Patrick Kilter

# Release 1.7.1 (23 September 2018)
* Fixed a bug with wrong date format in dateOfBirth for ``PNR_AddMultiElements`` (https://github.com/amabnl/amadeus-ws-client/pull/231) - Artem Zakharchenko

# Release 1.7.0 (30 April 2018)
* Implemented support for ``TransactionFlowLink`` SOAP header (https://github.com/amabnl/amadeus-ws-client/issues/146)
* Added support for Arrival date and time in ``Air_SellFromRecommendation`` (https://github.com/amabnl/amadeus-ws-client/pull/153) - Artem Zakharchenko
* Added support for FlightTypeDetails (Master Pricer Slice and Dice) in ``Air_SellFromRecommendation`` (https://github.com/amabnl/amadeus-ws-client/pull/176) - Artem Zakharchenko
* Added support for FeeOption in ``Fare_MasterPricerTravelBoardSearch`` (https://github.com/amabnl/amadeus-ws-client/pull/157) - Friedemann Schmuhl
* Support for extra options  in ``Fare_MasterPricerTravelBoardSearch`` (https://github.com/amabnl/amadeus-ws-client/issues/158):
    - No Airport Change at itinerary level
    - Maximum Elapsed Flying Time at itinerary level
    - Segment-level options: 
        - Include connection points
        - Exclude connection points
        - Specify a list of airlines/alliances as Mandatory/Preferred/Excluded/Night Class
        - Flight Types (Direct, Non-stop, Connecting, Cheapest on-line, Overnight not allowed)
        - Number of Connections
        - No Airport Change
* Added support for ``FOP_CreateFormOfPayment`` message version 14 and lower (https://github.com/amabnl/amadeus-ws-client/issues/163)
* Added support for Special Seat Types in ``PNR_AddMultiElements`` (https://github.com/amabnl/amadeus-ws-client/issues/174)
* Added support for Manual Ticket elements in ``PNR_AddMultiElements`` (https://github.com/amabnl/amadeus-ws-client/pull/188) - Michal Hernas
* Added support for ATC ticket revalidation in ``DocIssuance_IssueTicket`` (https://github.com/amabnl/amadeus-ws-client/pull/187) - Michal Hernas
* Added support for ticketing TSM's in ``DocIssuance_IssueCombined`` (https://github.com/amabnl/amadeus-ws-client/pull/178) - Michal Hernas
* Added default Amadeus queues as constants in Queue request options (https://github.com/amabnl/amadeus-ws-client/pull/185) - Artem Zakharchenko
* Implemented ``Air_RebookAirSegment`` (https://github.com/amabnl/amadeus-ws-client/issues/149)
* Implemented no less than 5 new messages: ``DocRefund_IgnoreRefund``, ``Ticket_ProcessETicket``, ``Ticket_InitRefund``, ``Ticket_IgnoreRefund`` and ``Ticket_ProcessRefund`` (https://github.com/amabnl/amadeus-ws-client/pull/181) - Michal Hernas
* Implemented ``PNR_Split`` (https://github.com/amabnl/amadeus-ws-client/pull/184) - Michal Hernas
* Fixed a bug where a ``FOP_CreateFormOfPayment`` message with sequence number 0 generated an incorrect message. (https://github.com/amabnl/amadeus-ws-client/pull/162) - Artem Zakharchenko

# Release 1.6.2 (10 April 2018)
* Fixed the ``returnXml`` property in the Client Parameters being ignored (https://github.com/amabnl/amadeus-ws-client/issues/175)

# Release 1.6.1 (19 March 2018)
* Do not remove ``<dummy>`` node from outgoing XML (https://github.com/amabnl/amadeus-ws-client/issues/161)

# Release 1.6.0 (7 February 2018)
* Recognize Passenger-level error messages in ``PNR_Reply`` responses (https://github.com/amabnl/amadeus-ws-client/issues/139)
* Added support for Layover per connection options in ``Fare_MasterPricerTravelBoardSearch`` (https://github.com/amabnl/amadeus-ws-client/pull/138) - Artem Zakharchenko
* Implemented more retrieval options on ``PNR_Retrieve``
* Implemented ``Ticket_ProcessEDoc`` (https://github.com/amabnl/amadeus-ws-client/pull/135) - "FarahHourani"
* Implemented ``MiniRule_GetFromETicket`` (https://github.com/amabnl/amadeus-ws-client/issues/122)
* Implemented ``Ticket_CancelDocument`` (https://github.com/amabnl/amadeus-ws-client/issues/93)

# Release 1.5.0 (5 November 2017)
* Added support for Vendor Code in FOP for Fare Pricing messages (https://github.com/amabnl/amadeus-ws-client/pull/82) - Michal Hernas
* Added support in PNR_Retrieve for retrieving the PNR active in context (https://github.com/amabnl/amadeus-ws-client/pull/88) - Michal Hernas
* Added support for Tour Code elements in ``PNR_AddMultiElements`` (https://github.com/amabnl/amadeus-ws-client/issues/90)
* Added support for Multi-Ticket operation for MasterPricer messages (https://github.com/amabnl/amadeus-ws-client/pull/94) - Michal Hernas
* Added support for ``Air_MultiAvailability`` messages version 16. (https://github.com/amabnl/amadeus-ws-client/issues/99)
* Implemented ``FOP_ValidateFOP`` (https://github.com/amabnl/amadeus-ws-client/pull/86) - Michal Hernas
* Implemented ``Service_IntegratedCatalogue`` (https://github.com/amabnl/amadeus-ws-client/issues/80)

# Release 1.4.0 (15 May 2017)
* Added support for ``Fare_PricePNRWithBookingClass`` errors in message version 7.3 format (https://github.com/amabnl/amadeus-ws-client/issues/57)
* Added support for Seat Request elements in in ``PNR_AddMultiElements`` (https://github.com/amabnl/amadeus-ws-client/issues/64/)
* Added support for Credit Card Holder name when adding FP elements with ``PNR_AddMultiElements`` (https://github.com/amabnl/amadeus-ws-client/issues/69)
* Added the possibility to disable the automatic addition of an RF element on each ``PNR_AddMultiElements`` call (https://github.com/amabnl/amadeus-ws-client/issues/68)
* Added support for Form Of Payment overrides in all ``Fare_`` Pricing messages and in ``Service_IntegratedPricing`` (https://github.com/amabnl/amadeus-ws-client/issues/72)
* Added support for Frequent Flyer override in ``Service_IntegratedPricing``
* Implemented ``DocRefund_InitRefund`` (https://github.com/amabnl/amadeus-ws-client/issues/56)
* Implemented ``DocRefund_UpdateRefund`` (https://github.com/amabnl/amadeus-ws-client/issues/56)
* Implemented ``DocRefund_ProcessRefund`` (https://github.com/amabnl/amadeus-ws-client/issues/56)
* Implemented ``Fare_GetFareRules`` (https://github.com/amabnl/amadeus-ws-client/issues/63)
* Fixed a bug with building the correct version of a message (https://github.com/amabnl/amadeus-ws-client/issues/71)

# Release 1.3.1 (5 May 2017)
* Loosened psr/log dependency to allow installation with Yii2 (https://github.com/amabnl/amadeus-ws-client/issues/73)

# Release 1.3.0 (5 April 2017)
* Added support for Multiple Office ID's in ``Fare_MasterPricerTravelBoardSearch`` (https://github.com/amabnl/amadeus-ws-client/pull/44) - Michal Hernas
* Added support for Progressive Legs in ``Fare_MasterPricerTravelBoardSearch`` (https://github.com/amabnl/amadeus-ws-client/issues/55)
* Added support for DK number (customer identification number) in ``Fare_MasterPricerTravelBoardSearch``
* Added support for Manual Commission elements in ``PNR_AddMultiElements`` (https://github.com/amabnl/amadeus-ws-client/issues/45)
* Added support for Service Fee indicator in Form of Payment elements in ``PNR_AddMultiElements``
* Automatically add a Received From element when not explicitly provided while calling the ``pnrAddMultiElements()`` method (https://github.com/amabnl/amadeus-ws-client/issues/50).
* Added support for recognizing ``general`` errors in PNR_Reply versions 14.1 and lower (https://github.com/amabnl/amadeus-ws-client/issues/51)
* Added ``getLastRequestHeaders()`` and ``getLastResponseHeaders()`` methods (https://github.com/amabnl/amadeus-ws-client/issues/47)
* Implemented ``Ticket_CheckEligibility`` message for ATC Shopper flow (https://github.com/amabnl/amadeus-ws-client/issues/39)
* Implemented ``Ticket_ATCShopperMasterPricerTravelBoardSearch`` message for ATC Shopper flow (https://github.com/amabnl/amadeus-ws-client/issues/39)
* Implemented ``Ticket_RepricePNRWithBookingClass`` message for ATC Shopper flow (https://github.com/amabnl/amadeus-ws-client/issues/39)
* Implemented ``Ticket_ReissueConfirmedPricing`` message for ATC Shopper flow (https://github.com/amabnl/amadeus-ws-client/issues/39)
* Implemented ``Ticket_CreateTSMFareElement`` message for ATC Shopper flow (https://github.com/amabnl/amadeus-ws-client/issues/39)
* Refactored ``Amadeus\Client\Session\Handler\Base`` to make it more readable
* Refactored all parameter loading out of ``Amadeus\Client``

# Release 1.2.2 (8 March 2017)
* Fixed bug with Soap Header 4 WSDL's in combination with OTA XSD imports causing the AMA_SecurityHostedUser:UserID classmap to point to the wrong XSD element (https://github.com/amabnl/amadeus-ws-client/issues/48)

# Release 1.2.1 (6 March 2017)
* Fixed bug with ``Security_Authenticate`` message sending empty message when the Authentication parameters were provided as per the documentation (https://github.com/amabnl/amadeus-ws-client/issues/40)

# Release 1.2.0 (23 February 2017)
* Fixed bug with Corporate Unifare pricing in ``Fare_MasterPricerTravelBoardSearch`` and ``Fare_MasterPricerCalendar`` (https://github.com/amabnl/amadeus-ws-client/pull/41) - Michal Hernas
* Added support for requesting rules for specific Fare Components after a pricing request in ``Fare_CheckRules`` (https://github.com/amabnl/amadeus-ws-client/issues/21)
* Added support for requesting parametrized Fare Families in ``Fare_MasterPricerTravelBoardSearch`` and ``Fare_MasterPricerCalendar`` (https://github.com/amabnl/amadeus-ws-client/issues/31)
* Added a Client parameter to disable the population of the XML string in the Result object (https://github.com/amabnl/amadeus-ws-client/issues/33)
* Support for multiple ``optionCode`` in ``PNR_AddMultiElements`` and ``PNR_Cancel`` messages (https://github.com/amabnl/amadeus-ws-client/issues/34)
* Support for Currency Conversion in ``Fare_MasterPricerTravelBoardSearch`` (https://github.com/amabnl/amadeus-ws-client/issues/35)
* Support for Fee ID fare options in ``Fare_MasterPricerTravelBoardSearch`` (https://github.com/amabnl/amadeus-ws-client/pull/36) - Michal Hernas
* ``Queue_List``: added new request options:
    - Search Criteria
    - Sort by Creation, Ticketing or Departure date
    - Filter the amount of results
    - Provide different Office ID
* Implemented ``Ticket_DeleteTSMP`` message
* Implemented ``Ticket_DisplayTSMP`` message
* Implemented ``Ticket_DisplayTSMFareElement`` message
* Implemented ``DocIssuance_IssueCombined`` message
* Implemented ``PNR_NameChange`` message
* Implemented ``FOP_CreateFormOfPayment`` message
* Implemented ``PointOfRef_Search`` message
* Request Creator split up in 1 dedicated class per message.
* Response Handler split up in 1 dedicated class per message.

# Release 1.1.1 (26 January 2017)
* Fixed a bug in Offer_ConfirmAirOffer and Offer_VerifyOffer (https://github.com/amabnl/amadeus-ws-client/issues/38).

# Release 1.1.0 (19 October 2016)

* ``Fare_MasterPricerTravelBoardSearch``: added new request options (https://github.com/amabnl/amadeus-ws-client/issues/20):
    - Preferred/excluded/... airlines option 'airlineOptions'.
    - Itinerary date: support for Date range +/- 1 day.
    - Itinerary date: Specify date &amp; time of segment as departure or arrival date &amp; time.
    - Itinerary date: deprecated 'date' and 'time' properties, replaced by unified 'dateTime' property.
    - Support for flight options such as direct flight, non-stop flight, etc.
    - Support for Cabin Mode (Mandatory, Major, Recommended) when specifying a cabin code.
    - Support for Fare Options such as Published Fares, Unifares, Electronic/Paper ticketing, ...
    - Support for "Price to beat" feature.
* ``PNR_AddMultiElements``: Support for new request options:
    - Add support for adding AIR when creating or updating a PNR.
    - Add support for adding ARNK segments when creating or updating a PNR.
    - Add support for Group PNR in the regular PNR_AddMultiElements call (previously this was only in the pnrCreatePnr)
    - Add support for connected segments in an itinerary (deprecated 'tripSegments' option)
* ``DocIssuance_IssueTicket``: Support for Compound Options in request, such as Consolidator method (https://github.com/amabnl/amadeus-ws-client/issues/23)
* ``Air_RetrieveSeatMap``: Support for new request options:
    - Request prices
    - Cabin code
    - Provide Record Locator
    - Number of passengers
    - Booking status
    - Currency conversion
    - Traveller information
* ``Fare_PricePnrWithBookingClass``: added support for extra request options:
    - Negotiated corporate fares
    - Corporate unifares
    - OB Fees *(message version 13+ only)*
    - Pax/PTC Discounts
    - Point of Sale and Point of Ticketing override
    - Pricing Logic (IATA or other) *(message version 13+ only)*
    - Ticket Type (e-ticket, paper, both)
    - Add taxes
    - Exempt taxes
    - Selective pricing: select segments, passengers and/or TST's
    - Past date pricing
    - Award Pricing

*These pricing options are also available for the* ``Fare_InformativePricingWithoutPNR`` *message version 13+*

* Implemented ``PNR_TransferOwnership``
* Implemented ``Ticket_DisplayTST``
* Implemented ``Ticket_CreateTSMFromPricing``
* Implemented ``Service_IntegratedPricing``
* Implemented ``DocIssuance_IssueMiscellaneousDocuments``
* Implemented ``Fare_MasterPricerCalendar``
* Implemented ``Fare_InformativeBestPricingWithoutPNR``
* Implemented ``Fare_PricePNRWithLowerFares``
* Implemented ``Fare_PricePNRWithLowestFare``
* Implemented ``MiniRule_GetFromPricing``
* Implemented ``Offer_CreateOffer``

# Release 1.0.0 (18 September 2016)

* ``PNR_AddMultiElements``: support for adding OSI elements to a PNR.
* Implemented ``Ticket_DeleteTST``
* Updated docs for 1.0.0 release.
* Released version 1: now following [semantic versioning](http://semver.org/).

# 2016-09

* Implemented ``SalesReports_DisplayQueryReport``
* Implemented ``Air_MultiAvailability``
* Added support for multiple WSDL's (interfaces) in a WSAP (https://github.com/amabnl/amadeus-ws-client/issues/5)
* Fixed a bug while authenticating with SoapHeader 2 (https://github.com/amabnl/amadeus-ws-client/pull/15) - Sergey Gladkovskiy
* PSR-2 code style enforced via StyleCI

# 2016-08

* Implemented ``PNR_DisplayHistory``
* Implemented ``Fare_InformativePricingWithoutPNR`` (https://github.com/amabnl/amadeus-ws-client/issues/13)

# 2016-07

* Implemented ``Air_RetrieveSeatMap``

# 2016-06

* Implemented ``Fare_PricePNRWithBookingClass`` version 13 and up (https://github.com/amabnl/amadeus-ws-client/issues/6)
* Implemented ``DocIssuance_IssueTicket`` (https://github.com/amabnl/amadeus-ws-client/issues/7)
* Implemented ``Info_EncodeDecodeCity``
* Implemented ``Offer_ConfirmCarOffer``
* Implemented checking for response errors for all supported messages _except Command_Cryptic_. For ``Command_Cryptic``, you need to parse the response yourself to check for errors.
* Removed the 'asString' request option - the `Amadeus\Client\Result` object now always contains the result XML in the `responseXml` property.

# 2016-05

Completely re-worked the Result being returned by Web Service calls: the XML or PHP object is now encapsulated in an `Amadeus\Client\Result` object which makes checking for errors or warnings much easier. 

The library now always returns both the PHP object generated by `\SoapClient` as well as the result string. 

The Result object also has a `status` property which contains the status of the message performed: FATAL, OK, WARN or INFO. 

The library will now also convert `\SoapFault` exceptions to a FATAL result status. 

(https://github.com/amabnl/amadeus-ws-client/issues/2)

# 2016-04

* Added support for providing custom `\SoapClient` options (https://github.com/amabnl/amadeus-ws-client/issues/4)
* Implemented Amadeus SoapHeader 2 support (https://github.com/amabnl/amadeus-ws-client/issues/3)
* Changed all references 'tatoo' to 'tattoo' for consistency (https://github.com/amabnl/amadeus-ws-client/issues/1)

# UNSTABLE

The library's API will be unstable until we release the first version.
