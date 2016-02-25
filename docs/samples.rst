========
EXAMPLES
========

Here are some examples of how you can handle some problems you might encounter and how to send specific messages.

***********************************************
Switching between stateful & stateless messages
***********************************************

If you do not require an active context in your session, you're better off using stateless messages.

However, for many operations, you'll need an active context (a PNR context, a pricing context, ...).

You can easily switch from stateful to stateless messages at runtime with:

.. code-block:: php

    $client->setStateful(false); //Enable stateless messages

    $client->setStateful(true); //Enable stateful messages

*************************
Ending a stateful session
*************************

After doing multiple calls with a stateful session, there are two ways to end the session:

- do a Security_SignOut call:

.. code-block:: php

    $client->signOut(); //Terminates an active stateful session. There is no active session with stateless messages.

- set an 'endSession' message option on the last call you want to make:

.. code-block:: php

    $client->pnrRetrieve(
        new PnrRetrieveOptions(['recordLocator' => 'ABC123']),
        ['endSession' => true]
    );


*********************
Handling the response
*********************

Sometimes it's useful if the result from the SOAP call gets returned as a PHP object,
sometimes a string containing the XML document of the SOAP-BODY is more useful.

For example, when trying to extract specific information from a PNR, it can be useful to load the
PNR_Reply as a ``\DOMDocument`` and query it using a ``\DOMXPath`` object.

The library supports this through the message option 'asString':

- Retrieving a PNR's contents and requesting the result as a string:

.. code-block:: php

    $client->pnrRetrieve(
        new PnrRetrieveOptions(['recordLocator' => 'ABC123']),
        ['asString' => true] //This is the default setting for the pnrRetrieve() method
    );

- Retrieving a PNR's contents and requesting the result as a PHP Object:

.. code-block:: php

    $client->pnrRetrieve(
        new PnrRetrieveOptions(['recordLocator' => 'ABC123']),
        ['asString' => false]
    );

******
Errors
******

The Amadeus web services can be tricky with regards to error detection. In most verbs, you have to look for the presence of error nodes in the response to see if everything went allright.

We try to ease your pain a little by analyzing the messages we support and look for error nodes. If any are found, we throw them as exceptions.

To override this behaviour, look at the ``Amadeus\Client\ResponseHandler\ResponseHandlerInterface``.

***
PNR
***

--------------------
PNR_AddMultiElements
--------------------

Creating a PNR (simplified example containing only the most basic PNR elements needed to save the PNR):

.. code-block:: php

    $opt = new Amadeus\Client\RequestOptions\PnrCreatePnrOptions();
    $opt->actionCode = 11; //11	End transact with retrieve (ER)
    $opt->travellers[] = new Amadeus\Client\RequestOptions\Pnr\Traveller([
        'number' => 1,
        'firstName' => 'FirstName',
        'lastName' => 'LastName'
    ]);
    $opt->tripSegments[] = new Amadeus\Client\RequestOptions\Pnr\Segment\Miscellaneous([
        'status ' => Amadeus\Client\RequestOptions\Pnr\Segment::STATUS_CONFIRMED,
        'company' => '1A',
        'date' => \DateTime::createFromFormat('Ymd', '20161022', new \DateTimeZone('UTC')),
        'cityCode' => 'BRU',
        'freeText' => 'DUMMY MISCELLANEOUS SEGMENT'
    ]);

    $opt->elements[] = new Amadeus\Client\RequestOptions\Pnr\Element\Ticketing([
        'ticketMode' => 'OK'
    ]);
    $opt->elements[] = new Amadeus\Client\RequestOptions\Pnr\Element\Contact([
        'type' => Amadeus\Client\RequestOptions\Pnr\Element\Contact::TYPE_PHONE_MOBILE,
        'value' => '+3222222222'
    ]);

    //The required Received From (RF) element will automatically be added by the library.

    $createdPnr = $client->pnrCreatePnr($opt);


Save a PNR which you started (with actionCode 0 for example) and is now ready to be saved:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;

    $pnrReply = $client->pnrAddMultiElements(
        new PnrAddMultiElementsOptions([
            'actionCode' => 11 //ET / END AND RETRIEVE
        ])
    );

------------
PNR_Retrieve
------------

Retrieving a PNR:

.. code-block:: php

    $pnrContent = $client->pnrRetrieve(
        new Amadeus\Client\RequestOptions\PnrRetrieveOptions(['recordLocator' => 'ABC123'])
    );


----------------------
PNR_RetrieveAndDisplay
----------------------

Retrieving a PNR with PNR content AND all offers:

.. code-block:: php

    $pnrContent = $client->pnrRetrieveAndDisplay(
        new Amadeus\Client\RequestOptions\PnrRetrieveAndDisplayOptions([
            'recordLocator' => 'ABC123',
            'retrieveOption' => Client\RequestOptions\PnrRetrieveAndDisplayOptions::RETRIEVEOPTION_ALL
        ])
    );


*****
Queue
*****

----------
Queue_List
----------

Get a list of all PNR's on a given queue:

.. code-block:: php

    $queueContent = $client->queueList(
        new Amadeus\Client\RequestOptions\QueueListOptions([
            'queue' => new Client\RequestOptions\Queue([
                'queue' => 50,
                'category' => 0
            ])
        ])
    );

--------------
Queue_PlacePNR
--------------

Place a PNR on a queue:

.. code-block:: php

    $placeResult = $client->queuePlacePnr(
        new Amadeus\Client\RequestOptions\QueuePlacePnrOptions([
            'targetQueue' => new Client\RequestOptions\Queue([
                'queue' => 50,
                'category' => 0
            ]),
            'recordLocator' => 'ABC123'
        ])
    );

----------------
Queue_RemoveItem
----------------

Remove a PNR from a queue:

.. code-block:: php

    $removeResult = $client->queueRemoveItem(
        new Amadeus\Client\RequestOptions\QueueRemoveItemOptions([
            'queue' => new Amadeus\Client\RequestOptions\Queue([
                'queue' => 50,
                'category' => 0
            ]),
            'recordLocator' => 'ABC123'
        ])
    );

--------------
Queue_MoveItem
--------------

Move a PNR from one queue to another:

.. code-block:: php

    $moveResult = $client->queueMoveItem(
        new Amadeus\Client\RequestOptions\QueueMoveItemOptions([
            'sourceQueue' => new Amadeus\Client\RequestOptions\Queue([
                'queue' => 50,
                'category' => 0
            ]),
            'destinationQueue' => new Amadeus\Client\RequestOptions\Queue([
                'queue' => 60,
                'category' => 3
            ]),
            'recordLocator' => 'ABC123'
        ])
    );

****
Fare
****

----------------------------------
Fare_MasterPricerTravelboardSearch
----------------------------------

Make a simple Masterpricer availability & fare search:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPDate;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedResults' => 200,
        'nrOfRequestedPassengers' => 1,
        'passengers' => [
            new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 1
            ])
        ],
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'BRU']),
                'arrivalLocation' => new MPLocation(['city' => 'LON']),
                'date' => new MPDate([
                    'date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ])
        ]
    ]);

    $recommendations = $client->fareMasterPricerTravelBoardSearch($opt);

-----------------------------
Fare_PricePNRWithBookingClass
-----------------------------

Do a pricing on the PNR in context:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'validatingCarrier' => 'SN'
        ])
    );


***
Air
***

--------------------------
Air_SellFromRecommendation
--------------------------

To book the chosen recommendation from the Fare_MasterPricerTravelBoardSearch result:

.. code-block:: php

    use Amadeus\Client\RequestOptions\AirSellFromRecommendationOptions;
    use Amadeus\Client\RequestOptions\Air\SellFromRecommendation\Itinerary;
    use Amadeus\Client\RequestOptions\Air\SellFromRecommendation\Segment;

    $opt = new AirSellFromRecommendationOptions([
        'itinerary' => [
            new Itinerary([
                'from' => 'BRU',
                'to' => 'LON',
                'segments' => [
                    new Segment([
                        'departureDate' => \DateTime::createFromFormat('Ymd','20170120', new \DateTimeZone('UTC')),
                        'from' => 'BRU',
                        'to' => 'LGW',
                        'companyCode' => 'SN',
                        'flightNumber' => '123',
                        'bookingClass' => 'Y',
                        'nrOfPassengers' => 1,
                        'statusCode' => Segment::STATUS_SELL_SEGMENT
                    ])
                ]
            ])
        ]
    ]);

    $sellResult = $client->airSellFromRecommendation($opt);

******
Ticket
******

---------------------------
Ticket_CreateTSTFromPricing
---------------------------

Create a TST from a Pricing made by a Fare_PricePNRWithBookingClass call:

.. code-block:: php

    use Amadeus\Client;
    use Amadeus\Client\RequestOptions\TicketCreateTstFromPricingOptions;
    use Amadeus\Client\RequestOptions\Ticket\Pricing;

    $createTstResponse = $client->ticketCreateTSTFromPricing(
        new TicketCreateTstFromPricingOptions([
            'pricings' => [
                new Pricing([
                    'tstNumber' => 1
                ])
            ]
        ])
    );

-----------------
Ticket_DisplayTST
-----------------

View the TST's of a PNR:


*****
Offer
*****
-----------------
Offer_VerifyOffer
-----------------
Verify if an offer is still valid:

.. code-block:: php

    use Amadeus\Client\RequestOptions\OfferVerifyOptions;

    $offerVerifyResponse = $client->offerVerify(
        new OfferVerifyOptions([
            'offerReference' => 1,
            'segmentName' => 'AIR'
        ])
    );

---------------------
Offer_ConfirmAirOffer
---------------------
Confirm a given AIR offer:

-----------------------
Offer_ConfirmHotelOffer
-----------------------
Confirm a given HOTEL offer:

********
MiniRule
********
--------------------------
MiniRule_GetFromPricingRec
--------------------------

Get MiniRules for a pricing in context (either a TST pricing, Offers or a pricing quotation):

.. code-block:: php

    use Amadeus\Client\RequestOptions\MiniRuleGetFromPricingRecOptions;
    use Amadeus\Client\RequestOptions\MiniRule\Pricing;
    use Amadeus\Client;

    $miniRules = $client->miniRuleGetFromPricingRec(
        new MiniRuleGetFromPricingRecOptions([
            'pricings' => [
                new Pricing([
                    'type' => Pricing::TYPE_TST,
                    'id' => Pricing::ALL_PRICINGS
                ])
            ]
        ])
    );


***************
Command_Cryptic
***************

Send any cryptic Amadeus Selling Platform entry which does not have a structured equivalent in webservices:

.. code-block:: php

    use Amadeus\Client\RequestOptions\CommandCrypticOptions;
    use Amadeus\Client;

    $opt = new CommandCrypticOptions([
        'entry' => 'DAC LON'
    ]);

    $crypticResponse = $client->commandCryptic($opt);

