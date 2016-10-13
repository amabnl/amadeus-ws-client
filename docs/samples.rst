========
EXAMPLES
========

Here are some examples of how to send specific messages.

If you can't find an example of what you want to do here, check the unittests in the ``tests/Amadeus/Client/Struct`` folder.
There, you can find more examples of all the options that are supported by the library.

.. contents::

***
PNR
***

--------------------
PNR_AddMultiElements
--------------------

Creating a PNR (simplified example containing only the most basic PNR elements needed to save the PNR):

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Traveller;
    use Amadeus\Client\RequestOptions\Pnr\Itinerary;
    use Amadeus\Client\RequestOptions\Pnr\Segment;
    use Amadeus\Client\RequestOptions\Pnr\Segment\Miscellaneous;
    use Amadeus\Client\RequestOptions\Pnr\Element\Ticketing;
    use Amadeus\Client\RequestOptions\Pnr\Element\Contact;

    $opt = new PnrCreatePnrOptions();
    $opt->actionCode = PnrCreatePnrOptions::ACTION_NO_PROCESSING; //0 Do not yet save the PNR and keep in context.
    $opt->travellers[] = new Traveller([
        'number' => 1,
        'firstName' => 'FirstName',
        'lastName' => 'LastName'
    ]);
    $opt->itinerary[] = new Itinerary([
        'segments' => [
            new Miscellaneous([
                'status ' => Segment::STATUS_CONFIRMED,
                'company' => '1A',
                'date' => \DateTime::createFromFormat('Ymd', '20161022', new \DateTimeZone('UTC')),
                'cityCode' => 'BRU',
                'freeText' => 'DUMMY MISCELLANEOUS SEGMENT'
            ])
        ]
    ]);
    $opt->elements[] = new Amadeus\Client\RequestOptions\Pnr\Element\Ticketing([
        'ticketMode' => Ticketing::TICKETMODE_OK
    ]);
    $opt->elements[] = new Contact([
        'type' => Contact::TYPE_PHONE_MOBILE,
        'value' => '+3222222222'
    ]);

    //The required Received From (RF) element will automatically be added by the library if you didn't provide one.

    $createdPnr = $client->pnrCreatePnr($opt);


Save a PNR which you have in context (created with actionCode 0 for example) and is now ready to be saved:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;

    $pnrReply = $client->pnrAddMultiElements(
        new PnrAddMultiElementsOptions([
            'actionCode' => PnrAddMultiElementsOptions::ACTION_END_TRANSACT_RETRIEVE //ET: END AND RETRIEVE
        ])
    );

`More examples of PNR creation and modification <samples/pnr-create-modify.rst>`_

------------
PNR_Retrieve
------------

Retrieving a PNR:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrRetrieveOptions;

    $pnrContent = $client->pnrRetrieve(
        new PnrRetrieveOptions(['recordLocator' => 'ABC123'])
    );

**Note:** Retrieving a PNR this way is identical to performing a ``RT<recordlocator>`` cryptic entry in Amadeus Selling Platform:
This will implicitly place the PNR in the session's context *(if this action is performed in a stateful session)*.

----------------------
PNR_RetrieveAndDisplay
----------------------

Retrieving a PNR with PNR content AND all offers:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrRetrieveAndDisplayOptions;

    $pnrContent = $client->pnrRetrieveAndDisplay(
        new PnrRetrieveAndDisplayOptions([
            'recordLocator' => 'ABC123',
            'retrieveOption' => PnrRetrieveAndDisplayOptions::RETRIEVEOPTION_ALL
        ])
    );

----------
PNR_Cancel
----------

Cancel the entire itinerary of the PNR in context and do an end transact to save the changes:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCancelOptions;

    $cancelReply = $client->pnrCancel(
        new PnrCancelOptions([
            'cancelItinerary' => true,
            'actionCode' => PnrCancelOptions::ACTION_END_TRANSACT
        ])
    );


Cancel a PNR element with tattoo number 15 and do an End and Retrieve (ER) to receive the resulting PNR_Reply:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCancelOptions;

    $cancelReply = $client->pnrCancel(
        new PnrCancelOptions([
            'elementsByTattoo' => [15],
            'actionCode' => PnrCancelOptions::ACTION_END_TRANSACT_RETRIEVE
        ])
    );

Same as before, but this time without having a PNR in context (you must provide the PNR's record locator)

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCancelOptions;

    $cancelReply = $client->pnrCancel(
        new PnrCancelOptions([
            'recordLocator' => 'ABC123,
            'elementsByTattoo' => [15],
            'actionCode' => PnrCancelOptions::ACTION_END_TRANSACT_RETRIEVE
        ])
    );

Cancel the Offer with Offer reference 1:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCancelOptions;

    $cancelReply = $client->pnrCancel(
        new PnrCancelOptions([
            'offers' => [1]
        ])
    );

Remove passenger with passenger reference 2 from the PNR:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCancelOptions;

    $cancelReply = $client->pnrCancel(
        new PnrCancelOptions([
            'passengers' => [2]
        ])
    );

------------------
PNR_DisplayHistory
------------------

Retrieve the full history of a PNR:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrDisplayHistoryOptions;

    $historyResult = $client->pnrDisplayHistory(
        new PnrDisplayHistoryOptions([
            'recordLocator' => 'ABC123'
        ])
    );

Retrieve the PNR history envelopes containing RF lines only:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrDisplayHistoryOptions;
    use Amadeus\Client\RequestOptions\Pnr\DisplayHistory\Predicate;
    use Amadeus\Client\RequestOptions\Pnr\DisplayHistory\PredicateDetail;

    $historyResult = $client->pnrDisplayHistory(
        new PnrDisplayHistoryOptions([
            'recordLocator' => 'ABC123',
            'predicates' => [
                new Predicate([
                    'details' => [
                        new PredicateDetail([
                            'option' => PredicateDetail::OPT_KEEP_HISTORY_MATCHING_CRITERION,
                            'associatedOption' => PredicateDetail::ASSOC_OPT_PREDICATE_TYPE
                        ]),
                        new PredicateDetail([
                            'option' => PredicateDetail::OPT_DISPLAY_ENVELOPES_CONTAINING_RF_LINE_ONLY,
                            'associatedOption' => PredicateDetail::ASSOC_OPT_MATCH_QUEUE_UPDATE
                        ]),
                    ]
                ])
            ]
        ])
    );

Retrieve the PNR history - return maximum 20 results:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrDisplayHistoryOptions;

    $historyResult = $client->pnrDisplayHistory(
        new PnrDisplayHistoryOptions([
            'recordLocator' => 'ABC123',
            'scrollingMax' => 20
        ])
    );

Retrieve the PNR history for AIR segments and exclude Queue updates:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrDisplayHistoryOptions;
    use Amadeus\Client\RequestOptions\Pnr\DisplayHistory\Predicate;
    use Amadeus\Client\RequestOptions\Pnr\DisplayHistory\PredicateDetail;
    use Amadeus\Client\RequestOptions\Pnr\DisplayHistory\PredicateType;

   $historyResult = $client->pnrDisplayHistory(
        new PnrDisplayHistoryOptions([
            'recordLocator' => 'ABC123',
            'predicates' => [
                new Predicate([
                    'details' => [
                        new PredicateDetail([
                            'option' => PredicateDetail::OPT_KEEP_HISTORY_MATCHING_CRITERION,
                            'associatedOption' => PredicateDetail::ASSOC_OPT_PREDICATE_TYPE
                        ]),
                    ],
                    'types' => [
                        new PredicateType([
                            'elementName' => 'AIR'
                        ])
                    ]
                ]),
                new Predicate([
                    'details' => [
                        new PredicateDetail([
                            'option' => PredicateDetail::OPT_DISCARD_HISTORY_MATCHING_CRITERION,
                            'associatedOption' => PredicateDetail::ASSOC_OPT_MATCH_QUEUE_UPDATE
                        ]),
                        new PredicateDetail([
                            'option' => PredicateDetail::OPT_DISPLAY_HISTORY_WITH_QUEUEING_UPDATES,
                            'associatedOption' => PredicateDetail::ASSOC_OPT_PREDICATE_TYPE
                        ]),
                    ],
                ])
            ]
        ])
   );

---------------------
PNR_TransferOwnership
---------------------

Transfer ownership of a retrieved PNR, changing also the ticketing office, the queueing office and the office specified in the option queue element, without spreading through the AXR.:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrTransferOwnershipOptions;

    $transferResult = $client->pnrTransferOwnership(
        new PnrTransferOwnershipOptions([
            'recordLocator' => 'ABC654',
            'newOffice' => 'NCE6X0980',
            'inhibitPropagation' => true,
            'changeTicketingOffice' => true,
            'changeQueueingOffice' => true,
            'changeOptionQueueElement' => true,
        ])
    );

Transfer of ownership to a third party identification on a retrieved PNR:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrTransferOwnershipOptions;

    $transferResult = $client->pnrTransferOwnership(
        new PnrTransferOwnershipOptions([
            'recordLocator' => 'ABC987',
            'newThirdParty' => 'HDQRM',
        ])
    );

Transfer both the office Ownership and the owner User Security Entity. The Queueing office is changed as well:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrTransferOwnershipOptions;

    $transferResult = $client->pnrTransferOwnership(
        new PnrTransferOwnershipOptions([
            'recordLocator' => 'ABC987',
            'newOffice' => 'LON6X0980',
            'newUserSecurityEntity' => 'AgencyLON',
            'changeQueueingOffice' => true
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

    use Amadeus\Client\RequestOptions\QueueListOptions;
    use Amadeus\Client\RequestOptions\Queue;

    $queueContent = $client->queueList(
        new QueueListOptions([
            'queue' => new Queue([
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

    use Amadeus\Client\RequestOptions\QueuePlacePnrOptions;
    use Amadeus\Client\RequestOptions\Queue;

    $placeResult = $client->queuePlacePnr(
        new QueuePlacePnrOptions([
            'targetQueue' => new Queue([
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

    use Amadeus\Client\RequestOptions\QueueRemoveItemOptions;
    use Amadeus\Client\RequestOptions\Queue;

    $removeResult = $client->queueRemoveItem(
        new QueueRemoveItemOptions([
            'queue' => new Queue([
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

    use Amadeus\Client\RequestOptions\QueueMoveItemOptions;
    use Amadeus\Client\RequestOptions\Queue;

    $moveResult = $client->queueMoveItem(
        new QueueMoveItemOptions([
            'sourceQueue' => new Queue([
                'queue' => 50,
                'category' => 0
            ]),
            'destinationQueue' => new Queue([
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
                    'dateTime' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ])
        ]
    ]);

    $recommendations = $client->fareMasterPricerTravelBoardSearch($opt);


`More examples of MasterPricer messages <samples/masterpricertravelboard.rst>`_

-------------------------
Fare_MasterPricerCalendar
-------------------------

**In general, MasterPricerCalendar request options are exactly the same as for MasterPricerTravelBoardSearch.** The one thing that MasterPricerCalendar always requires, is a date range for each given travel date.

Example: Make a simple MasterPricer Calendar availability & fare search:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerCalendarOptions;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPDate;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;

    $opt = new FareMasterPricerCalendarOptions([
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
                    'date' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC')),
                    'rangeMode' => MPDate::RANGEMODE_MINUS_PLUS,
                    'range' => 3,
                ])
            ])
        ]
    ]);

    $recommendations = $client->fareMasterPricerCalendar($opt);

`More examples of MasterPricer messages can be found in the MasterPricerTravelBoardSearch documentation <samples/masterpricertravelboard.rst>`_

-----------------------------
Fare_PricePNRWithBookingClass
-----------------------------

Do a pricing on the PNR in context - price with validating carrier SN (Brussels Airlines):

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'validatingCarrier' => 'SN'
        ])
    );

Price PNR: use the fare basis QNC469W2 to price segments 1 and 2 with:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
    use Amadeus\Client\RequestOptions\Fare\PricePnr\FareBasis;
    use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'pricingsFareBasis' => [
                    new FareBasis([
                        'fareBasisCode' => 'QNC469W2',
                        'references' => [
                            new PaxSegRef([
                                'reference' => 1,
                                'type' => PaxSegRef::TYPE_SEGMENT
                            ]),
                            new PaxSegRef([
                                'reference' => 2,
                                'type' => PaxSegRef::TYPE_SEGMENT
                            ])
                        ]
                    ])
                ]
        ])
    );


`More examples of Fare_PricePNRWithBookingClass messages <samples/pricepnr.rst>`_

---------------------------
Fare_PricePNRWithLowerFares
---------------------------

**Fare_PricePNRWithLowerFares request options are exactly the same as for Fare_PricePNRWithBookingClass.**

An example of pricing, with options listed below:

- take published fares into account (RP)
- take Unifares into account (RU)
- use PTC "CH" for passenger 2 (PAX)
- convert fare into USD (FCO)

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithLowerFaresOptions;
    use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;

    $pricingResponse = $client->farePricePnrWithLowerFares(
        new FarePricePnrWithLowerFaresOptions([
            'overrideOptions' => [
                FarePricePnrWithLowerFaresOptions::OVERRIDE_FARETYPE_PUB,
                FarePricePnrWithLowerFaresOptions::OVERRIDE_FARETYPE_UNI
            ],
            'currencyOverride' => 'USD',
            'paxDiscountCodes' => ['CH'],
            'paxDiscountCodeRefs' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 2
                ])
            ]
        ])
    );

`More examples of Pricing messages <samples/pricepnr.rst>`_

---------------------------
Fare_PricePNRWithLowestFare
---------------------------

**Fare_PricePNRWithLowestFare request options are exactly the same as for Fare_PricePNRWithBookingClass.**

An example of pricing, with options listed below:

- take published fares into account (RP)
- take Unifares into account (RU)
- use PTC "CH" for passenger 2 (PAX)
- convert fare into USD (FCO)

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithLowestFareOptions;
    use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;

    $pricingResponse = $client->farePricePnrWithLowestFare(
        new FarePricePnrWithLowestFareOptions([
            'overrideOptions' => [
                FarePricePnrWithLowestFareOptions::OVERRIDE_FARETYPE_PUB,
                FarePricePnrWithLowestFareOptions::OVERRIDE_FARETYPE_UNI
            ],
            'currencyOverride' => 'USD',
            'paxDiscountCodes' => ['CH'],
            'paxDiscountCodeRefs' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 2
                ])
            ]
        ])
    );

`More examples of Pricing messages <samples/pricepnr.rst>`_

---------------------------------
Fare_InformativePricingWithoutPNR
---------------------------------

Do an informative pricing on BRU-LIS flight with 2 adults and no special pricing options:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareInformativePricingWithoutPnrOptions;
    use Amadeus\Client\RequestOptions\Fare\InformativePricing\Passenger;
    use Amadeus\Client\RequestOptions\Fare\InformativePricing\Segment;

    $informativePricingResponse = $client->fareInformativePricingWithoutPnr(
        new FareInformativePricingWithoutPnrOptions([
            'passengers' => [
                new Passenger([
                    'tattoos' => [1, 2],
                    'type' => Passenger::TYPE_ADULT
                ])
            ],
            'segments' => [
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2016-11-21 09:15:00'),
                    'from' => 'BRU',
                    'to' => 'LIS',
                    'marketingCompany' => 'TP',
                    'flightNumber' => '4652',
                    'bookingClass' => 'Y',
                    'segmentTattoo' => 1,
                    'groupNumber' => 1
                ]),
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2016-11-28 14:20:00'),
                    'from' => 'LIS',
                    'to' => 'BRU',
                    'marketingCompany' => 'TP',
                    'flightNumber' => '3581',
                    'bookingClass' => 'C',
                    'segmentTattoo' => 2,
                    'groupNumber' => 2
                ])
            ]
        ])
    );

The Pricing options that can be used are the same pricing options as in the ``Fare_PricePNRWithBookingClass`` message:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareInformativePricingWithoutPnrOptions;
    use Amadeus\Client\RequestOptions\Fare\InformativePricing\Passenger;
    use Amadeus\Client\RequestOptions\Fare\InformativePricing\Segment;
    use Amadeus\Client\RequestOptions\Fare\InformativePricing\PricingOptions;
    use Amadeus\Client\RequestOptions\Fare\PricePnr\FareBasis;

    $informativePricingResponse = $client->fareInformativePricingWithoutPnr(
        new FareInformativePricingWithoutPnrOptions([
            'passengers' => [
                new Passenger([
                    'tattoos' => [1, 2],
                    'type' => Passenger::TYPE_ADULT
                ])
            ],
            'segments' => [
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2016-11-21 09:15:00'),
                    'from' => 'BRU',
                    'to' => 'LIS',
                    'marketingCompany' => 'TP',
                    'flightNumber' => '4652',
                    'bookingClass' => 'Y',
                    'segmentTattoo' => 1,
                    'groupNumber' => 1
                ])
            ],
            'pricingOptions' => new PricingOptions([
                'overrideOptions' => [
                    PricingOptions::OVERRIDE_FARETYPE_NEG,
                    PricingOptions::OVERRIDE_FAREBASIS
                ],
                'validatingCarrier' => 'BA',
                'currencyOverride' => 'EUR',
                'pricingsFareBasis' => [
                    new FareBasis([
                        'fareBasisCode' => 'QNC469W2',
                    ])
                ]
            ])
        ])
    );

-------------------------------------
Fare_InformativeBestPricingWithoutPNR
-------------------------------------

**Fare_InformativeBestPricingWithoutPNR request options are exactly the same as for Fare_InformativePricingWithoutPNR.**

Pricing example of a CDG-LHR-CDG trip for 2 passengers, with options below:

- take into account published fares (RP)
- take into account Unifares (RU)
- use PTC "CH" for passenger 2 (PAX)
- convert fare into USD (FCO)

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareInformativeBestPricingWithoutPnrOptions;
    use Amadeus\Client\RequestOptions\Fare\InformativePricing\Passenger;
    use Amadeus\Client\RequestOptions\Fare\InformativePricing\Segment;
    use Amadeus\Client\RequestOptions\Fare\InformativePricing\PricingOptions;
    use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;

    $informativePricingResponse = $client->fareInformativeBestPricingWithoutPnr(
        new FareInformativeBestPricingWithoutPnrOptions([
             'passengers' => [
                new Passenger([
                    'tattoos' => [1, 2],
                    'type' => Passenger::TYPE_ADULT
                ])
            ],
            'segments' => [
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2013-12-01 07:30:00', new \DateTimeZone('UTC')),
                    'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2013-12-01 07:50:00', new \DateTimeZone('UTC')),
                    'from' => 'CDG',
                    'to' => 'LHR',
                    'marketingCompany' => '6X',
                    'operatingCompany' => '6X',
                    'flightNumber' => '1680',
                    'bookingClass' => 'T',
                    'segmentTattoo' => 1,
                    'groupNumber' => 1
                ]),
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2013-12-10 06:40:00', new \DateTimeZone('UTC')),
                    'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2013-12-10 09:00:00', new \DateTimeZone('UTC')),
                    'from' => 'LHR',
                    'to' => 'CDG',
                    'marketingCompany' => '6X',
                    'operatingCompany' => '6X',
                    'flightNumber' => '1381',
                    'bookingClass' => 'V',
                    'segmentTattoo' => 2,
                    'groupNumber' => 1
                ])
            ],
            'pricingOptions' => new PricingOptions([
                'overrideOptions' => [
                    PricingOptions::OVERRIDE_FARETYPE_PUB,
                    PricingOptions::OVERRIDE_FARETYPE_UNI
                ],
                'currencyOverride' => 'USD',
                'paxDiscountCodes' => ['CH'],
                'paxDiscountCodeRefs' => [
                    new PaxSegRef([
                        'type' => PaxSegRef::TYPE_PASSENGER,
                        'reference' => 2
                    ])
                ]
            ])
        ])
    );

---------------
Fare_CheckRules
---------------

Get Fare Rules information for a pricing in context:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareCheckRulesOptions;

    $rulesResponse = $client->fareCheckRules(
        new FareCheckRulesOptions([
            'recommendations' => [1] //Pricing nr 1
        ])
    );

Get all rule categories available for a given pricing in context:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareCheckRulesOptions;

    $rulesResponse = $client->fareCheckRules(
        new FareCheckRulesOptions([
            'recommendations' => [1], //Pricing nr 1
            'categoryList' => true
        ])
    );

Get the fare rules for specific categories for a given pricing in context:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareCheckRulesOptions;

    $rulesResponse = $client->fareCheckRules(
        new FareCheckRulesOptions([
            'recommendations' => [1], //Pricing nr 1
            'categories' => ['MX', 'SE', 'SR', 'AP', 'FL', 'CD', 'SO', 'SU']
        ])
    );


--------------------
Fare_ConvertCurrency
--------------------

Convert 200 Euro to US Dollars in today's exchange rate:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareConvertCurrencyOptions;

    $rulesResponse = $client->fareConvertCurrency(
        new FareConvertCurrencyOptions([
            'from' => 'EUR',
            'to' => 'USD',
            'amount' => '200',
            'rateOfConversion' => FareConvertCurrencyOptions::RATE_TYPE_BANKERS_SELLER_RATE
        ])
    );

Convert 200 Euro to US Dollars in the exchange rate of 25th December 2015 *(this option only works up until 12 months in the past)*:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareConvertCurrencyOptions;

    $rulesResponse = $client->fareConvertCurrency(
        new FareConvertCurrencyOptions([
            'from' => 'EUR',
            'to' => 'USD',
            'amount' => '200',
            'date' => \DateTime::createFromFormat('Y-m-d', '2015-12-25', new \DateTimeZone('UTC')),
            'rateOfConversion' => FareConvertCurrencyOptions::RATE_TYPE_BANKERS_SELLER_RATE
        ])
    );

***
Air
***

---------------------
Air_MultiAvailability
---------------------

To request a simple Air_MultiAvailability:

.. code-block:: php

    use Amadeus\Client\RequestOptions\AirMultiAvailabilityOptions;
    use Amadeus\Client\RequestOptions\Air\MultiAvailability\RequestOptions;
    use Amadeus\Client\RequestOptions\Air\MultiAvailability\FrequentTraveller;

    $opt = new AirMultiAvailabilityOptions([
        'actionCode' => AirMultiAvailabilityOptions::ACTION_AVAILABILITY,
        'requestOptions' => [
            new RequestOptions([
                'departureDate' => \DateTime::createFromFormat('Ymd-His', '20170320-000000', new \DateTimeZone('UTC')),
                'from' => 'BRU',
                'to' => 'LIS',
                'requestType' => RequestOptions::REQ_TYPE_NEUTRAL_ORDER
            ])
        ]
    ]);

    $availabilityResult = $client->airMultiAvailability($opt);

Nice - New York Schedule request, connection via Paris, flying on Air France, for 5 people,
in premium or regular Economy, sort by arrival time:

.. code-block:: php

    use Amadeus\Client\RequestOptions\AirMultiAvailabilityOptions;
    use Amadeus\Client\RequestOptions\Air\MultiAvailability\RequestOptions;
    use Amadeus\Client\RequestOptions\Air\MultiAvailability\FrequentTraveller;

    $opt = new AirMultiAvailabilityOptions([
        'actionCode' => AirMultiAvailabilityOptions::ACTION_SCHEDULE,
        'requestOptions' => [
             new RequestOptions([
                    'departureDate' => \DateTime::createFromFormat('Ymd-His', '20170215-140000', new \DateTimeZone('UTC')),
                    'from' => 'NCE',
                    'to' => 'NYC',
                    'cabinCode' => RequestOptions::CABIN_ECONOMY_PREMIUM_MAIN,
                    'includedConnections' => ['PAR'],
                    'nrOfSeats' => 5,
                    'includedAirlines' => ['AF'],
                    'requestType' => RequestOptions::REQ_TYPE_BY_ARRIVAL_TIME
                ])
        ]
    ]);

    $availabilityResult = $client->airMultiAvailability($opt);


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

--------------
Air_FlightInfo
--------------

Get flight info for a specific flight:

.. code-block:: php

    use Amadeus\Client\RequestOptions\AirFlightInfoOptions;

    $flightInfo = $client->airFlightInfo(
        new AirFlightInfoOptions([
            'airlineCode' => 'SN',
            'flightNumber' => '652',
            'departureDate' => \DateTime::createFromFormat('Y-m-d', '2016-05-18'),
            'departureLocation' => 'BRU',
            'arrivalLocation' => 'LIS'
        ])
    );

-------------------
Air_RetrieveSeatMap
-------------------

Get seat map information for a specific flight:

.. code-block:: php

    use Amadeus\Client\RequestOptions\AirRetrieveSeatMapOptions;
    use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FlightInfo;

    $seatmapInfo = $client->airRetrieveSeatMap(
        new AirRetrieveSeatMapOptions([
            'flight' => new FlightInfo([
                'departureDate' => \DateTime::createFromFormat('Ymd', '20170419'),
                'departure' => 'BRU',
                'arrival' => 'FCO',
                'airline' => 'SN',
                'flightNumber' => '3175'
            ])
        ])
    );

Get seat map information for a specific flight, specifying a specific booking class:

.. code-block:: php

    use Amadeus\Client\RequestOptions\AirRetrieveSeatMapOptions;
    use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FlightInfo;

    $seatmapInfo = $client->airRetrieveSeatMap(
        new AirRetrieveSeatMapOptions([
            'flight' => new FlightInfo([
                'departureDate' => \DateTime::createFromFormat('Ymd', '20170419'),
                'departure' => 'BRU',
                'arrival' => 'FCO',
                'airline' => 'SN',
                'flightNumber' => '3175',
                'bookingClass' => 'C'
            ])
        ])
    );

Get seat map information for a specific flight and specify Frequent Flyer:

.. code-block:: php

    use Amadeus\Client\RequestOptions\AirRetrieveSeatMapOptions;
    use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FlightInfo;
    use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FrequentFlyer;

    $seatmapInfo = $client->airRetrieveSeatMap(
        new AirRetrieveSeatMapOptions([
            'flight' => new FlightInfo([
                'departureDate' => \DateTime::createFromFormat('Ymd', '20170419'),
                'departure' => 'BRU',
                'arrival' => 'FCO',
                'airline' => 'SN',
                'flightNumber' => '3175'
            ]),
            'frequentFlyer' => new FrequentFlyer([
                'company' => 'SN',
                'cardNumber' => '4099913025539611',
                'tierLevel' => 1
            ])
        ])
    );

Get seat map information for a specific flight, request prices and specify Cabin class:

*Cabin class overrides any booking class info provided*

.. code-block:: php

    use Amadeus\Client\RequestOptions\AirRetrieveSeatMapOptions;
    use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FlightInfo;

    $seatmapInfo = $client->airRetrieveSeatMap(
        new AirRetrieveSeatMapOptions([
            'flight' => new FlightInfo([
                'departureDate' => \DateTime::createFromFormat('Ymd', '20170419'),
                'departure' => 'BRU',
                'arrival' => 'FCO',
                'airline' => 'SN',
                'flightNumber' => '3175'
            ]),
            'requestPrices' => true,
            'cabinCode' => 'B'
        ])
    );


Complex example: Seat Map with Prices

* Query: 2 passengers,
* options for pricing:
    * record locator,
    * conversion into USD,
    * ticket designator for the 1st passenger along with date of birth and fare basis.

.. code-block:: php

    use Amadeus\Client\RequestOptions\AirRetrieveSeatMapOptions;
    use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FlightInfo;
    use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FrequentFlyer;
    use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\Traveller;

    $seatmapInfo = $client->airRetrieveSeatMap(
        new AirRetrieveSeatMapOptions([
            'flight' => new FlightInfo([
                'airline' => 'AF',
                'flightNumber' => '0346',
                'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2015-06-15 00:00:00', new \DateTimeZone('UTC')),
                'departure' => 'CDG',
                'arrival' => 'YUL',
                'bookingClass' => 'Y'
            ]),
            'requestPrices' => true,
            'nrOfPassengers' => 2,
            'bookingStatus' => 'HK',
            'recordLocator' => '7BFHEJ',
            'currency' => 'USD',
            'travellers' => [
                new Traveller([
                    'uniqueId' => 1,
                    'firstName' => 'KENNETH MR',
                    'lastName' => 'NELSON',
                    'type' => Traveller::TYPE_ADULT,
                    'dateOfBirth' => \DateTime::createFromFormat('Y-m-d H:i:s', '1966-04-05 00:00:00', new \DateTimeZone('UTC')), //05041966
                    'passengerTypeCode' => 'MIL',
                    'ticketDesignator' => 'B2BAB2B',
                    'ticketNumber' => '17225466644554',
                    'fareBasisOverride' => 'YIF',
                    'frequentTravellerInfo' => new FrequentFlyer([
                        'company' => 'QF',
                        'cardNumber' => '987654321',
                        'tierLevel' => 'FFBR',
                    ]),
                ]),
                new Traveller([
                    'uniqueId' => 2,
                    'firstName' => 'PHILIP MR',
                    'lastName' => 'NELSON',
                    'type' => Traveller::TYPE_ADULT,
                    'frequentTravellerInfo' => new FrequentFlyer([
                        'company' => 'QF',
                        'cardNumber' => '1234567',
                        'tierLevel' => 'FFSL',
                    ]),
                ]),
            ]
        ])
    );


******
Ticket
******

---------------------------
Ticket_CreateTSTFromPricing
---------------------------

Create a TST from a Pricing made by a ``Fare_PricePNRWithBookingClass`` call:

.. code-block:: php

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

---------------------------
Ticket_CreateTSMFromPricing
---------------------------

Create a TSM from a Pricing previously made by a ``Service_IntegratedPricing`` call:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketCreateTsmFromPricingOptions;
    use Amadeus\Client\RequestOptions\Ticket\Pricing;
    use Amadeus\Client\RequestOptions\Ticket\PassengerReference;

    $createTstResponse = $client->ticketCreateTSMFromPricing(
        new TicketCreateTsmFromPricingOptions([
            'pricings' => [
                new Pricing([
                    'tsmNumber' => 1
                ])
            ],
            'passengerReferences' => [
                new PassengerReference([
                    'id' => 1,
                    'type' => PassengerReference::TYPE_PASSENGER
                ])
            ]
        ])
    );

----------------
Ticket_DeleteTST
----------------

Delete the TST with number 2:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketDeleteTstOptions;

    $deleteTstResult = $client->ticketDeleteTST(
        new TicketDeleteTstOptions([
            'deleteMode' => TicketDeleteTstOptions::DELETE_MODE_SELECTIVE,
            'tstNumber' => 2
        ])
    );


-----------------
Ticket_DisplayTST
-----------------

View all TST's of a PNR:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketDisplayTstOptions;

    $deleteTstResult = $client->ticketDisplayTST(
        new TicketDisplayTstOptions([
            'displayMode' => TicketDisplayTstOptions::MODE_ALL
        ])
    );

Display TST number 2:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketDisplayTstOptions;

    $deleteTstResult = $client->ticketDisplayTST(
        new TicketDisplayTstOptions([
            'displayMode' => TicketDisplayTstOptions::MODE_SELECTIVE,
            'tstNumbers' => [2]
        ])
    );

***********
DocIssuance
***********

-----------------------
DocIssuance_IssueTicket
-----------------------

Issue ticket for an entire PNR as e-Ticket (TTP/ET):

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocIssuanceIssueTicketOptions;

    $issueTicketResponse = $client->docIssuanceIssueTicket(
        new DocIssuanceIssueTicketOptions([
            'options' => [
                DocIssuanceIssueTicketOptions::OPTION_ETICKET
            ]
        ])
    );

Issue e-Ticket for one single TST and retrieve PNR (TTP/T1/ET/RT):

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocIssuanceIssueTicketOptions;

    $issueTicketResponse = $client->docIssuanceIssueTicket(
        new DocIssuanceIssueTicketOptions([
            'options' => [
                DocIssuanceIssueTicketOptions::OPTION_ETICKET,
                DocIssuanceIssueTicketOptions::OPTION_RETRIEVE_PNR
            ],
            'tsts' => [1]
        ])
    );

Issue e-Ticket with Consolidator Method:

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocIssuanceIssueTicketOptions;
    use Amadeus\Client\RequestOptions\DocIssuance\CompoundOption;

    $issueTicketResponse = $client->docIssuanceIssueTicket(
        new DocIssuanceIssueTicketOptions([
            'options' => [
                DocIssuanceIssueTicketOptions::OPTION_ETICKET
            ],
            'compoundOptions' => [
                new CompoundOption([
                    'type' => CompoundOption::TYPE_ET_CONSOLIDATOR,
                    'details' => '1A'
                ])
            ]
        ])
    );

****
Info
****
---------------------
Info_EncodeDecodeCity
---------------------

Get information about IATA code 'OPO':

.. code-block:: php

    use Amadeus\Client\RequestOptions\InfoEncodeDecodeCityOptions;

    $infoResponse = $client->infoEncodeDecodeCity(
        new InfoEncodeDecodeCityOptions([
            'locationCode' => 'OPO'
        ])
    );

Do a phonetic search for locations sounding like "Brussels":

.. code-block:: php

    use Amadeus\Client\RequestOptions\InfoEncodeDecodeCityOptions;

    $infoResponse = $client->infoEncodeDecodeCity(
        new InfoEncodeDecodeCityOptions([
            'locationName' => 'brussels',
            'searchMode' => InfoEncodeDecodeCityOptions::SEARCHMODE_PHONETIC
        ])
    );

Find all train stations in New York:

.. code-block:: php

    use Amadeus\Client\RequestOptions\InfoEncodeDecodeCityOptions;

    $infoResponse = $client->infoEncodeDecodeCity(
        new InfoEncodeDecodeCityOptions([
            'locationCode' => 'NYC',
            'selectResult' => InfoEncodeDecodeCityOptions::SELECT_TRAIN_STATIONS
        ])
    );

*****
Offer
*****

-----------------
Offer_CreateOffer
-----------------

Create an offer for AIR pricing recommendation 1, for Passenger 1

.. code-block:: php

    use Amadeus\Client\RequestOptions\OfferCreateOptions;
    use Amadeus\Client\RequestOptions\Offer\AirRecommendation;
    use Amadeus\Client\RequestOptions\Offer\PassengerDef;

    $offerCreateResponse = $client->offerCreate(
        new OfferCreateOptions([
            'airRecommendations' => [
                new AirRecommendation([
                    'type' => AirRecommendation::TYPE_FARE_RECOMMENDATION_NR,
                    'id' => 1,
                    'paxReferences' => [
                        new PassengerDef([
                            'passengerTattoo' => 1
                        ])
                    ]
                ])
            ]
        ])
    );

Create a Hotel offer for Hotel pricing with booking code 000000C and hotel property code RDLON308:

.. code-block:: php

    use Amadeus\Client\RequestOptions\OfferCreateOptions;
    use Amadeus\Client\RequestOptions\Offer\ProductReference;

    $offerCreateResponse = $client->offerCreate(
        new OfferCreateOptions([
            'productReferences' => [
                new ProductReference([
                    'reference' => '000000C',
                    'referenceType' => ProductReference::PRODREF_BOOKING_CODE,
                ]),
                new ProductReference([
                    'reference' => 'RDLON308',
                    'referenceType' => ProductReference::PRODREF_HOTEL_PROPERTY_CODE,
                ]),
            ]
        ])
    );

Create an offer for AIR pricing recommendation 1, for Adult Passenger 1 with a Markup of EUR 20:

.. code-block:: php

    use Amadeus\Client\RequestOptions\OfferCreateOptions;
    use Amadeus\Client\RequestOptions\Offer\AirRecommendation;
    use Amadeus\Client\RequestOptions\Offer\PassengerDef;

    $offerCreateResponse = $client->offerCreate(
        new OfferCreateOptions([
            'airRecommendations' => [
                new AirRecommendation([
                    'type' => AirRecommendation::TYPE_FARE_RECOMMENDATION_NR,
                    'id' => 2,
                    'paxReferences' => [
                        new PassengerDef([
                            'passengerTattoo' => 1,
                            'passengerType' => 'PA'
                        ])
                    ]
                ])
            ],
            'markupAmount' => 20,
            'markupCurrency' => 'EUR'
        ])
    );

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
Confirm a given AIR offer by providing office reference / tattoo:

.. code-block:: php

    use Amadeus\Client\RequestOptions\OfferConfirmAirOptions;

    $response = $client->offerConfirmAir(
        new OfferConfirmAirOptions([
            'tattooNumber' => 1
        ])
    );

-----------------------
Offer_ConfirmHotelOffer
-----------------------
Confirm a given HOTEL offer:

.. code-block:: php

    use Amadeus\Client\RequestOptions\OfferConfirmHotelOptions;
    use Amadeus\Client\RequestOptions\Offer\PaymentDetails;

    $opt = new OfferConfirmHotelOptions([
        'recordLocator' => 'ABC123',
        'offerReference' => 2,
        'passengers' => [1],
        'originatorId' => '123456',
        'paymentType' => OfferConfirmHotelOptions::PAYMENT_GUARANTEED,
        'formOfPayment' => OfferConfirmHotelOptions::FOP_CREDIT_CARD,
        'paymentDetails' => new PaymentDetails([
            'ccCardNumber' => '4444333322221111',
            'ccCardHolder' => 'David Bowie',
            'ccExpiry' => '1117',
            'ccVendor' => 'AX',
        ])
    ]);

    $response = $client->offerConfirmHotel($opt);

---------------------
Offer_ConfirmCarOffer
---------------------
Confirm a given CAR offer:

.. code-block:: php

    use Amadeus\Client\RequestOptions\OfferConfirmCarOptions;
    use Amadeus\Client\RequestOptions\Offer\CarLocationInfo;

    $opt = new OfferConfirmCarOptions([
        'passengerTattoo' => 1,
        'offerTattoo' => 2,
        'recordLocator' => 'ABC123',
        'pickUpInfo' => new CarLocationInfo([
            'address' => 'RUE DE LA LIBERATION',
            'city' => 'NICE',
            'zipCode' => '06000',
            'countryCode' => 'FR',
            'phoneNumber' => '1234567890'
        ]),
        'dropOffInfo' => new CarLocationInfo([
            'address' => 'ROUTE DE VALBONNE',
            'city' => 'BIOT',
            'zipCode' => '06410',
            'countryCode' => 'FR',
            'phoneNumber' => '0123456789'
        ]),
    ]);

    $response = $client->offerConfirmCar($opt);

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

    $miniRulesResponse = $client->miniRuleGetFromPricingRec(
        new MiniRuleGetFromPricingRecOptions([
            'pricings' => [
                new Pricing([
                    'type' => Pricing::TYPE_TST,
                    'id' => Pricing::ALL_PRICINGS
                ])
            ]
        ])
    );

-----------------------
MiniRule_GetFromPricing
-----------------------

Get MiniRules for a pricing in context *(After a Fare_PricePNRWithBookingClass, Fare_PricePNRWithLowerFares, FarePricePNRWithLowestFare, Fare_InformativePricingWithoutPNR or Fare_InformativeBestPricingWithoutPNR message)*:

Get Minirules for all pricings returned:

.. code-block:: php

    use Amadeus\Client\RequestOptions\MiniRuleGetFromPricingOptions;
    use Amadeus\Client\RequestOptions\MiniRule\Pricing;

    $miniRulesResponse = $client->miniRuleGetFromPricing(new MiniRuleGetFromPricingOptions());


Get Minirules for specific recommendations *(recommendations nr 1 & 2 in this example)*:

.. code-block:: php

    use Amadeus\Client\RequestOptions\MiniRuleGetFromPricingOptions;
    use Amadeus\Client\RequestOptions\MiniRule\Pricing;

    $miniRulesResponse = $client->miniRuleGetFromPricing(
        new MiniRuleGetFromPricingOptions([
            'pricings' => [1, 2]
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

**************************
PriceXplorer_ExtremeSearch
**************************

Request a basic Extreme Search result:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PriceXplorerExtremeSearchOptions;

    $opt = new PriceXplorerExtremeSearchOptions([
        'resultAggregationOption' => PriceXplorerExtremeSearchOptions::AGGR_COUNTRY,
        'origin' => 'BRU',
        'destinations' => ['SYD', 'CBR'],
        'earliestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-08-25', new \DateTimeZone('UTC')),
        'latestDepartureDate' => \DateTime::createFromFormat('Y-m-d','2016-09-28', new \DateTimeZone('UTC')),
        'searchOffice' => 'LONBG2222'
    ]);

    $extremeSearchResult = $client->priceXplorerExtremeSearch($opt);

*******************************
SalesReports_DisplayQueryReport
*******************************

Request a sales report from a certain date to another date, issued in all offices sharing the same IATA number;

.. code-block:: php

    use Amadeus\Client\RequestOptions\SalesReportsDisplayQueryReportOptions;

    $opt = new SalesReportsDisplayQueryReportOptions([
        'requestOptions' => [
            SalesReportsDisplayQueryReportOptions::SELECT_ALL_OFFICES_SHARING_IATA_NR
        ],
        'agencySourceType' => SalesReportsDisplayQueryReportOptions::AGENCY_SRC_REPORTING_OFFICE,
        'agencyIataNumber' => '23491193',
        'startDate' => \DateTime::createFromFormat('Ymd', '20150101', new \DateTimeZone('UTC')),
        'endDate' => \DateTime::createFromFormat('Ymd', '20160331', new \DateTimeZone('UTC'))
    ]);

    $salesReportResult = $client->salesReportsDisplayQueryReport($opt);

