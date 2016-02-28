===================
About / Get Started
===================
***********************
Get access from Amadeus
***********************
First, get a WSDL by requesting a WSAP (Web Service Access Point) for your project from Amadeus. You will probably have to go through your Amadeus sales channel and start a web services project with Amadeus.

See `the Amadeus Web Services website <https://webservices.amadeus.com/>`_ for more information.

The basic pieces of information you will need to use this library are:

- **The WSDL file with all its includes**: You can just extract the ZIP file you received from Amadeus to a location on your filesystem where the client can access it.
- **The authentication information required to start a session**: Office ID's, User Id (=Originator), Password, Duty Code. *For legacy WSAP's using Soap Header 1 or 2, you'll need: Office ID, Originator, Organization ID, Password Length, Password Data. Soap Header 1 & 2 is not yet implemented in this library*

You usually receive this information after the project kick-off has been done and a support person has been assigned to your project.

****************************************
Support for Amadeus Soap Header versions
****************************************
Upon receiving access, Amadeus will give you a Web Service Access Point with a specific Soap Header version to use. This will define how you can handle session management *(e.g. support for stateless calls, requiring session pooling or not)*.

This library is initially built to support the current Soap Header v4, which is the Soap Header Version you would get for a new WSAP requested today (2016).

Legacy applications using already certified WSAP's can still be running on legacy Soap Header versions - most notably Soap Header 1 & 2. This library currently doesn't support those yet, but we plan to add that in the future.

******************************************
Support for different versions of messages
******************************************
Amadeus periodically releases new versions of the messages (also called "verbs") available on their web services.

On requesting access to the Amadeus web services, you'll receive a WSDL which contains messages in the lastest stable version Amadeus has released (unless you request for specific older versions of messages).

**There could be differences** in various versions of messages: the request could be constructed differently (or have more options), you may get a slightly different response depending on the version you have received.

The client library will read the messages and versions from the WSDL and will use that to try to construct the appropriate message for each version.
However, we will introduce support for different message types as we encounter issues with different messages. When you run into problems, always check
the message constructed by this library against the documentation *for your message version*.

If you run into a situation where a specific message for your version is different from the message constructed by the library, you can either override the base message creator
:code:`Amadeus\Client\RequestCreator\Base` or implement your own :code:`Amadeus\Client\RequestCreator\RequestCreatorInterface`. If you feel like contributing, you can also implement
it yourself in a fork and provide a pull request. If you do that, please do it in a way analogous to what has been done for the :code:`Fare_PricePNRWithBookingClass` call.

******************************
Install library in PHP project
******************************
Install the client library in your PHP project by requiring the package with Composer:

``composer require amabnl/amadeus-ws-client``

********************
Set up a test client
********************

.. code-block:: php

    <?php

    use Amadeus\Client;
    use Amadeus\Client\RequestOptions\PnrRetrieveOptions;

    //Set up the client with necessary parameters:

    $params = new ClientParams([
        'sessionHandlerParams' => [
            'soapHeaderVersion' => Client::HEADER_V4, //This is the default value, can be omitted.
            'wsdl' => '/home/user/mytestproject/data/amadeuswsdl/1ASIWXXXXXX_PDT_20160101_080000.wsdl', //Points to the location of the WSDL file for your WSAP. Make sure the associated XSD's are also available.
            'stateful' => false, //Enable stateful messages by default - can be changed at will to switch between stateless & stateful.
            'logger' => new Psr\Log\NullLogger(),
            'authParams' => [
                'officeId' => 'BRUXX1111', //The Amadeus Office Id you want to sign in to - must be open on your WSAP.
                'userId' => 'WSBENXXX', //Also known as 'Originator' for Soap Header 1 & 2 WSDL's
                'passwordData' => 'dGhlIHBhc3N3b3Jk' // **base 64 encoded** password
            ]
        ],
        'requestCreatorParams' => [
            'receivedFrom' => 'my test project' // The "Received From" string that will be visible in PNR History
        ]
    ]);

    $client = new Client($params);

    $pnrContent = $client->pnrRetrieve(
        new PnrRetrieveOptions(['recordLocator' => 'ABC123'])
    );


******************
Messages supported
******************

This is the list of messages that are at least partially supported at this time:

- Security_SignOut
- PNR_Retrieve
- PNR_RetrieveAndDisplay
- PNR_AddMultiElements (pnrCreate to create a PNR from scratch)
- PNR_AddMultiElements (possibility to do actionCode operations on a PNR in context without further actions)
- PNR_Cancel
- Queue_List
- Queue_PlacePNR
- Queue_RemoveItem
- Queue_MoveItem
- Fare_MasterPricerTravelBoardSearch
- Fare_PricePNRWithBookingClass
- Air_SellFromRecommendation
- Air_FlightInfo
- Offer_VerifyOffer
- Offer_ConfirmAirOffer
- MiniRule_GetFromPricingRec
- Ticket_CreateTSTFromPricing
- Command_Cryptic
- PriceXplorer_ExtremeSearch


We plan to support an entire basic booking flow (MasterPricer, SellFromRecommendation, Pricing, ...) later on.

On the to-do list / work in progress:

- Air_RetrieveSeatMap
- DocIssuance_IssueTicket
- Fare_InformativePricingWithoutPNR
- Fare_InformativeBestPricingWithoutPNR
- Fare_PricePNRWithLowerFares
- Fare_ConvertCurrency
- Fare_CheckRules
- Fare_MasterPricerCalendar
- Fare_DisplayFaresForCityPair
- Fare_DisplayBookingCodeInformation
- Fare_CalculateMileage
- Info_EncodeDecodeCity
- Offer_ConfirmHotelOffer
- PointOfRef_Search
- Ticket_DisplayTST

