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
    $opt->itineraries[] = new Itinerary([
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

Retrieve the PNR that is active in the current session context:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrRetrieveOptions;

    $pnrContent = $client->pnrRetrieve(new PnrRetrieveOptions());

**Note:** Retrieving a PNR this way is identical to performing a ``RT`` cryptic entry in Amadeus Selling Platform:
This will re-retrieve the PNR that is currently active in the session's context *(if this action is performed in a stateful session)*.

Retrieve PNR(s) by Customer Profile:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrRetrieveOptions;

    $pnrContent = $client->pnrRetrieve(new PnrRetrieveOptions([
        'customerProfile' => 'ABC987'
    ]));

Retrieve PNR(s) by Account Number:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrRetrieveOptions;

    $pnrContent = $client->pnrRetrieve(new PnrRetrieveOptions([
        'accountNumber' => '12345'
    ]));

Retrieve PNR(s) by Name on a specific Office ID:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrRetrieveOptions;

    $pnrContent = $client->pnrRetrieve(new PnrRetrieveOptions([
        'officeId' => 'MIA1S213F',
        'lastName' => 'childs'
    ]));

Retrieve PNR(s) by last name and departure date:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrRetrieveOptions;

    $pnrContent = $client->pnrRetrieve(new PnrRetrieveOptions([
        'lastName' => 'childs',
        'departureDate' => \DateTime::createFromFormat(\DateTime::ISO8601, "2018-01-27T00:00:00+0000", new \DateTimeZone('UTC')),
    ]));

Retrieve PNR(s) by last name and departure date, retrieve only active PNR's:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrRetrieveOptions;

    $pnrContent = $client->pnrRetrieve(new PnrRetrieveOptions([
        'options' => [
            PnrRetrieveOptions::OPTION_ACTIVE_ONLY
        ],
        'lastName' => 'childs',
        'departureDate' => \DateTime::createFromFormat(\DateTime::ISO8601, "2018-01-27T00:00:00+0000", new \DateTimeZone('UTC')),
    ]));

Retrieve PNR by Record Locator with name and ticket number:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrRetrieveOptions;
    use Amadeus\Client\RequestOptions\Pnr\Retrieve\Ticket;

    $pnrContent = $client->pnrRetrieve(new PnrRetrieveOptions([
        'recordLocator' => 'YA76F8',
        'lastName' => 'childs',
        'ticket' => new Ticket([
            'airline' => '057',
            'number' => '7024209573'
        ])
    ]));

Retrieve PNR(s) by service, last name and flight number:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrRetrieveOptions;
    use Amadeus\Client\RequestOptions\Pnr\Retrieve\Ticket;

    $pnrContent = $client->pnrRetrieve(new PnrRetrieveOptions([
        'service' => PnrRetrieveOptions::SERVICE_AIRLINE,
        'lastName' => 'childs',
        'departureDate' => \DateTime::createFromFormat(\DateTime::ISO8601, "2001-03-28T00:00:00+0000", new \DateTimeZone('UTC')),
        'company' => '6X',
        'flightNumber' => '6201',
    ]));

Retrieve PNR(s) by Frequent Traveller information:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrRetrieveOptions;
    use Amadeus\Client\RequestOptions\Pnr\Retrieve\FrequentTraveller;

    $pnrContent = $client->pnrRetrieve(new PnrRetrieveOptions([
        'frequentTraveller' => new FrequentTraveller([
            'airline' => 'LH',
            'number' => '992222899525661'
        ])
    ]));

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

--------------
PNR_NameChange
--------------

Example: Name change on retrieved PNR

The example shows the message required to change the name of the passenger specified by the reference number with the following data:

- Passenger surname: SURNAME
- Passenger given name / title: GIVENNAME MR
- Passenger reference number: 1
- Passenger type code: ADT
- Infant name: SMITH
- Infant given name: BABY
- Infant date of birth: 15 SEP 2007

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrNameChangeOptions;
    use Amadeus\Client\RequestOptions\Pnr\NameChange\Passenger;
    use Amadeus\Client\RequestOptions\Pnr\NameChange\Infant;

    $changeResult = $client->pnrNameChange(
        new PnrNameChangeOptions([
            'operation' => PnrNameChangeOptions::OPERATION_CHANGE,
            'passengers' => [
                new Passenger([
                    'reference' => 1,
                    'type' => 'ADT',
                    'lastName' => 'SURNAME',
                    'firstName' => 'GIVENNAME MR',
                    'infant' => new Infant([
                        'lastName' => 'SMITH',
                        'firstName' => 'BABY',
                        'dateOfBirth' => \DateTime::createFromFormat('Y-m-d', '2007-09-15', new \DateTimeZone('UTC'))
                    ])
                ])
            ]
        ])
    );

------------
PNR_Split
------------

Split passengers 1 and 2 from PNR ABC123:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrSplitOptions;

    $pnrContent = $client->pnrSplit(
        new PnrSplitOptions(['recordLocator' => 'ABC123', 'passengerTattoos' => [1, 2]])
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

Get a list of all PNR's on a given queue on a different office:

.. code-block:: php

    use Amadeus\Client\RequestOptions\QueueListOptions;
    use Amadeus\Client\RequestOptions\Queue;

    $queueContent = $client->queueList(
        new QueueListOptions([
            'queue' => new Queue([
                'queue' => 50,
                'category' => 0,
                'officeId' => 'NCE1A0950'
            ])
        ])
    );

Get a list of all PNR's on a given queue for specific account number:

.. code-block:: php

    use Amadeus\Client\RequestOptions\QueueListOptions;
    use Amadeus\Client\RequestOptions\Queue;

    $queueContent = $client->queueList(
        new QueueListOptions([
            'queue' => new Queue([
                'queue' => 50,
                'category' => 0,
                'accountNumber' => 'TESTACC99'
            ])
        ])
    );

Get a list of PNR's on a queue, provide a filter on Ticketing & Departure date:

This example will display a List of the queue 12C0 in the office NCE1A0950 and search with ticketing date between 20 APR and 21 APR and departure date between 3 May and 4 May.

.. code-block:: php

    use Amadeus\Client\RequestOptions\QueueListOptions;
    use Amadeus\Client\RequestOptions\Queue;
    use Amadeus\Client\RequestOptions\Queue\SearchCriteriaOpt;

    $queueContent = $client->queueList(
        new QueueListOptions([
            'queue' => new Queue([
                'queue' => 12,
                'category' => 0,
                'officeId' => 'NCE1A0950'
            ]),
            'searchCriteria' => [
                new SearchCriteriaOpt([
                    'type' => SearchCriteriaOpt::TYPE_TICKETING_DATE,
                    'start' => \DateTime::createFromFormat('Ymd', '20090420', new \DateTimeZone('UTC')),
                    'end' => \DateTime::createFromFormat('Ymd', '20090421', new \DateTimeZone('UTC'))
                ]),
                new SearchCriteriaOpt([
                    'type' => SearchCriteriaOpt::TYPE_DEPARTURE_DATE,
                    'start' => \DateTime::createFromFormat('Ymd', '20090503', new \DateTimeZone('UTC')),
                    'end' => \DateTime::createFromFormat('Ymd', '20090504', new \DateTimeZone('UTC'))
                ]),
            ]
        ])
    );

Get a list of PNR's on a queue, sorted by Ticketing date:

.. code-block:: php

    use Amadeus\Client\RequestOptions\QueueListOptions;
    use Amadeus\Client\RequestOptions\Queue;

    $queueContent = $client->queueList(
        new QueueListOptions([
            'sortType' => QueueListOptions::SORT_TICKETING_DATE,
            'queue' => new Queue([
                'queue' => 50,
                'category' => 3
            ])
        ])
    );

Get the first 10 PNR's on a queue:

.. code-block:: php

    use Amadeus\Client\RequestOptions\QueueListOptions;
    use Amadeus\Client\RequestOptions\Queue;

    $queueContent = $client->queueList(
        new QueueListOptions([
            'queue' => new Queue([
                'queue' => 50,
                'category' => 3
            ]),
            'firstItemNr' => 0,
            'lastItemNr' => 10
        ])
    );

Also You can use predefined queues of Amadeus Queue Bank:

.. code-block:: php

    use Amadeus\Client\RequestOptions\QueueListOptions;
    use Amadeus\Client\RequestOptions\Queue;

    $pnrsOnGeneralQueue = $client->queueList(
        new QueueListOptions([
            'queue' => new Queue([
                'queue' => Queue::QUEUE_GENERAL,
                'category' => 0
            ])
        ])
    );

    $pnrsOnTicketingQueue = $client->queueList(
        new QueueListOptions([
            'queue' => new Queue([
                'queue' => Queue::QUEUE_TICKETING,
                'category' => 1
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
Fare_MasterPricerExpertSearch
----------------------------------

The Expert Search is nearly identical to the Travelboard search, except it focus on business flights.

It supports all features of the Travelboard Search, but not the options "noAirportChange" and "maxElapsedFlyingTime"

Make a simple Masterpricer Expert availability & fare search:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerExSearch;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPDate;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;

    $opt = new FareMasterPricerExSearch([
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

    $recommendations = $client->fareMasterPricerExpertSearch($opt);

Since the Expert Search is nearly similar to the Travelboard Search, check out the Travelboard Search examples too

----------------------------------
Fare_MasterPricerTravelboardSearch
----------------------------------

Make a simple Masterpricer Travelboard availability & fare search:

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

---------------------------
Fare_PriceUpsellWithoutPNR
---------------------------

**Fare_PriceUpsellWithoutPNR allows an user to get a list of upsell proposition without having to create corresponding Passenger Name Record (PNR).**

This request similar to Fare_PricePNRWithBookingClass:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'validatingCarrier' => 'SN'
        ])
    );

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

Get Fare Rules information after a pricing request, specify a specific Fare Component:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareCheckRulesOptions;

    $rulesResponse = $client->fareCheckRules(
        new FareCheckRulesOptions([
            'recommendations' => [2],
            'fareComponents' => [2],
            'categoryList' => true
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

-----------------
Fare_GetFareRules
-----------------

Basic request to get Fare Rules:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareGetFareRulesOptions;

    $rulesResponse = $client->fareGetFareRules(
        new FareGetFareRulesOptions([
            'ticketingDate' => \DateTime::createFromFormat('dmY', '23032011'),
            'fareBasis' => 'OA21ERD1',
            'ticketDesignator' => 'DISC',
            'airline' => 'AA',
            'origin' => 'DFW',
            'destination' => 'MKC'
        ])
    );


Get fare rules providing corporate number and departure date:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareGetFareRulesOptions;

    $rulesResponse = $client->fareGetFareRules(
        new FareGetFareRulesOptions([
            'ticketingDate' => \DateTime::createFromFormat('dmY', '23032011'),
            'uniFares' => ['0012345'],
            'fareBasis' => 'OA21ERD1',
            'ticketDesignator' => 'DISC',
            'directionality' => FareGetFareRulesOptions::DIRECTION_ORIGIN_TO_DESTINATION,
            'airline' => 'AA',
            'origin' => 'DFW',
            'destination' => 'MKC',
            'travelDate' => \DateTime::createFromFormat('dmY', '25032011')
        ])
    );

-----------------
Fare_GetFareFamilyDescription
-----------------

Basic request to get Fare Families in stateful mode (after pricing):

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareGetFareFamilyDescriptionOptions;

    $fareFamiliesResponse = $client->fareGetFareFamilyDescription(
        new FareGetFareFamilyDescriptionOptions([
            'referenceGroups' => [
                new ReferenceGroup([
                    new Reference('REC', 1),
                    new Reference('FC', 1),
                    new Reference('FC', 2),
                ])
            ]
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

To book the chosen recommendation with specifying segment's arrival date, which is not mandatory but it may help with flights that are overnight.

.. code-block:: php

    use Amadeus\Client\RequestOptions\AirSellFromRecommendationOptions;
    use Amadeus\Client\RequestOptions\Air\SellFromRecommendation\Itinerary;
    use Amadeus\Client\RequestOptions\Air\SellFromRecommendation\Segment;

    $opt = new AirSellFromRecommendationOptions([
        'itinerary' => [
            new Itinerary([
                'from' => 'SFO',
                'to' => 'NYC',
                'segments' => [
                    new Segment([
                        'departureDate' => \DateTime::createFromFormat('Ymd Hi','20180315 1540', new \DateTimeZone('UTC')),
                        'arrivalDate' => \DateTime::createFromFormat('Ymd Hi','20180316 0010', new \DateTimeZone('UTC')),
                        'from' => 'SFO',
                        'to' => 'JFK',
                        'companyCode' => 'AA',
                        'flightNumber' => '20',
                        'bookingClass' => 'S',
                        'nrOfPassengers' => 1,
                        'statusCode' => Segment::STATUS_SELL_SEGMENT
                    ])
                ]
            ])
        ]
    ]);

    $sellResult = $client->airSellFromRecommendation($opt);

Selling connecting segments with the slice and dice option:

.. code-block:: php

    use Amadeus\Client\RequestOptions\AirSellFromRecommendationOptions;
    use Amadeus\Client\RequestOptions\Air\SellFromRecommendation\Itinerary;
    use Amadeus\Client\RequestOptions\Air\SellFromRecommendation\Segment;

    $opt = new AirSellFromRecommendationOptions([
        'itinerary' => [
            new Itinerary([
                'from' => 'PBI',
                'to' => 'YYZ',
                'segments' => [
                    new Segment([
                        'departureDate' => \DateTime::createFromFormat('Ymd','20181123', new \DateTimeZone('UTC')),
                        'arrivalDate' => \DateTime::createFromFormat('Ymd','231115', new \DateTimeZone('UTC')),
                        'from' => 'PBI',
                        'to' => 'CLT',
                        'companyCode' => '8X',
                        'flightNumber' => '001',
                        'bookingClass' => 'V',
                        'nrOfPassengers' => 1,
                        'statusCode' => Segment::STATUS_SELL_SEGMENT,
                        'flightTypeDetails' => Segment::INDICATOR_LOCAL_AVAILABILITY,
                    ]),
                    new Segment([
                        'departureDate' => \DateTime::createFromFormat('Ymd','20181123', new \DateTimeZone('UTC')),
                        'arrivalDate' => \DateTime::createFromFormat('Ymd','231115', new \DateTimeZone('UTC')),
                        'from' => 'CLT',
                        'to' => 'YYZ',
                        'companyCode' => '8X',
                        'flightNumber' => '002',
                        'bookingClass' => 'M',
                        'nrOfPassengers' => 1,
                        'statusCode' => Segment::STATUS_SELL_SEGMENT,
                        'flightTypeDetails' => Segment::INDICATOR_LOCAL_AVAILABILITY,
                    ]),
                ],
            ]),
        ],
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

- Query: 2 passengers
- Options for pricing:
    - record locator,
    - conversion into USD,
    - ticket designator for the 1st passenger along with date of birth and fare basis.

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

Most restrictive Seat Map request:

- Multiple passenger context
- Requesting the most restrictive seat map display

.. code-block:: php

    use Amadeus\Client\RequestOptions\AirRetrieveSeatMapOptions;
    use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FlightInfo;

    $seatmapInfo = $client->airRetrieveSeatMap(
        new AirRetrieveSeatMapOptions([
            'flight' => new FlightInfo([
                'departureDate' => \DateTime::createFromFormat('Ymd', '20150615'),
                'departure' => 'CDG',
                'arrival' => 'YUL',
                'airline' => 'AF',
                'flightNumber' => '0346',
                'bookingClass' => 'Y'
            ]),
            'recordLocator' => '7BFHEJ',
            'company' => '1A',
            'date' =>  \DateTime::createFromFormat('Ymd', '20150610'),
            'mostRestrictive' => true
        ])
    );

--------------------
Air_RebookAirSegment
--------------------

Class Rebook: Rebook a segment from class F to C:

.. code-block:: php

    use Amadeus\Client\RequestOptions\AirRebookAirSegmentOptions;
    use Amadeus\Client\RequestOptions\Air\RebookAirSegment\Itinerary;
    use Amadeus\Client\RequestOptions\Air\RebookAirSegment\Segment;

    $rebookResponse = $client->airRebookAirSegment(
        new AirRebookAirSegmentOptions([
            'itinerary' => [
                new Itinerary([
                    'from' => 'FRA',
                    'to' => 'BKK',
                    'segments' => [
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040308220000', new \DateTimeZone('UTC')),
                            'arrivalDate' =>  \DateTime::createFromFormat('YmdHis','20040309141000', new \DateTimeZone('UTC')),
                            'dateVariation' => 1,
                            'from' => 'FRA',
                            'to' => 'BKK',
                            'companyCode' => 'LH',
                            'flightNumber' => '744',
                            'bookingClass' => 'F',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_CANCEL_SEGMENT
                        ]),
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040308220000', new \DateTimeZone('UTC')),
                            'arrivalTime' =>  \DateTime::createFromFormat('His','141000', new \DateTimeZone('UTC')),
                            'from' => 'FRA',
                            'to' => 'BKK',
                            'companyCode' => 'LH',
                            'flightNumber' => '744',
                            'bookingClass' => 'C',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_SELL_SEGMENT
                        ])
                    ]
                ])
            ]
        ]);
    );


Class Rebook after pricing PNR with lower fare: This example is the same as the previous one, but in the case where rebook is performed following a PricePNRWithLowerFares request, and the reference of the recommendation selected by the user (number 2) is transmitted in the rebook:

.. code-block:: php

    use Amadeus\Client\RequestOptions\AirRebookAirSegmentOptions;
    use Amadeus\Client\RequestOptions\Air\RebookAirSegment\Itinerary;
    use Amadeus\Client\RequestOptions\Air\RebookAirSegment\Segment;

    $rebookResponse = $client->airRebookAirSegment(
        new AirRebookAirSegmentOptions([
            'bestPricerOption' => 2,
            'itinerary' => [
                new Itinerary([
                    'from' => 'FRA',
                    'to' => 'BKK',
                    'segments' => [
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040308220000', new \DateTimeZone('UTC')),
                            'arrivalDate' =>  \DateTime::createFromFormat('YmdHis','20040309141000', new \DateTimeZone('UTC')),
                            'dateVariation' => 1,
                            'from' => 'FRA',
                            'to' => 'BKK',
                            'companyCode' => 'LH',
                            'flightNumber' => '744',
                            'bookingClass' => 'F',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_CANCEL_SEGMENT
                        ]),
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040308220000', new \DateTimeZone('UTC')),
                            'arrivalTime' =>  \DateTime::createFromFormat('His','141000', new \DateTimeZone('UTC')),
                            'from' => 'FRA',
                            'to' => 'BKK',
                            'companyCode' => 'LH',
                            'flightNumber' => '744',
                            'bookingClass' => 'C',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_SELL_SEGMENT
                        ])
                    ]
                ])
            ]
        ]);
    );

Force Rebook: This example is for the Force Rebook of the second segment from F Class to C Class:

.. code-block:: php

    use Amadeus\Client\RequestOptions\AirRebookAirSegmentOptions;
    use Amadeus\Client\RequestOptions\Air\RebookAirSegment\Itinerary;
    use Amadeus\Client\RequestOptions\Air\RebookAirSegment\Segment;

    $rebookResponse = $client->airRebookAirSegment(
        new AirRebookAirSegmentOptions([
            'itinerary' => [
                new Itinerary([
                    'from' => 'FRA',
                    'to' => 'BKK',
                    'segments' => [
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040308220000', new \DateTimeZone('UTC')),
                            'arrivalDate' =>  \DateTime::createFromFormat('YmdHis','20040309141000', new \DateTimeZone('UTC')),
                            'dateVariation' => 1,
                            'from' => 'FRA',
                            'to' => 'BKK',
                            'companyCode' => 'LH',
                            'flightNumber' => '744',
                            'bookingClass' => 'F',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_CANCEL_SEGMENT
                        ]),
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040308220000', new \DateTimeZone('UTC')),
                            'arrivalTime' =>  \DateTime::createFromFormat('His','141000', new \DateTimeZone('UTC')),
                            'from' => 'FRA',
                            'to' => 'BKK',
                            'companyCode' => 'LH',
                            'flightNumber' => '744',
                            'bookingClass' => 'C',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_FORCE_BOOKING
                        ])
                    ]
                ])
            ]
        ]);
    );

Rebook Two Segment Classes: This example shows the rebook of LH 744 from class F to class C and LX 182 from class J to class C:

.. code-block:: php

    use Amadeus\Client\RequestOptions\AirRebookAirSegmentOptions;
    use Amadeus\Client\RequestOptions\Air\RebookAirSegment\Itinerary;
    use Amadeus\Client\RequestOptions\Air\RebookAirSegment\Segment;

    $rebookResponse = $client->airRebookAirSegment(
        new AirRebookAirSegmentOptions([
            'itinerary' => [
                new Itinerary([
                    'from' => 'FRA',
                    'to' => 'BKK',
                    'segments' => [
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040308220000', new \DateTimeZone('UTC')),
                            'arrivalDate' =>  \DateTime::createFromFormat('YmdHis','20040309141000', new \DateTimeZone('UTC')),
                            'dateVariation' => 1,
                            'from' => 'FRA',
                            'to' => 'BKK',
                            'companyCode' => 'LH',
                            'flightNumber' => '744',
                            'bookingClass' => 'F',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_CANCEL_SEGMENT
                        ]),
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040308220000', new \DateTimeZone('UTC')),
                            'arrivalDate' =>  \DateTime::createFromFormat('YmdHis','20040309141000', new \DateTimeZone('UTC')),
                            'dateVariation' => 1,
                            'from' => 'FRA',
                            'to' => 'BKK',
                            'companyCode' => 'LH',
                            'flightNumber' => '744',
                            'bookingClass' => 'C',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_SELL_SEGMENT
                        ])
                    ]
                ]),
                new Itinerary([
                    'from' => 'BKK',
                    'to' => 'SIN',
                    'segments' => [
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040309153000', new \DateTimeZone('UTC')),
                            'arrivalDate' =>  \DateTime::createFromFormat('YmdHis','20040309184500', new \DateTimeZone('UTC')),
                            'dateVariation' => 0,
                            'from' => 'BKK',
                            'to' => 'SIN',
                            'companyCode' => 'LX',
                            'flightNumber' => '182',
                            'bookingClass' => 'J',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_CANCEL_SEGMENT
                        ]),
                        new Segment([
                            'departureDate' => \DateTime::createFromFormat('YmdHis','20040309153000', new \DateTimeZone('UTC')),
                            'arrivalDate' =>  \DateTime::createFromFormat('YmdHis','20040309184500', new \DateTimeZone('UTC')),
                            'dateVariation' => 0,
                            'from' => 'BKK',
                            'to' => 'SIN',
                            'companyCode' => 'LX',
                            'flightNumber' => '182',
                            'bookingClass' => 'C',
                            'nrOfPassengers' => 1,
                            'statusCode' => Segment::STATUS_SELL_SEGMENT
                        ])
                    ]
                ])
            ]
        ]);
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

    $createTsmResponse = $client->ticketCreateTSMFromPricing(
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

---------------------------
Ticket_CreateTSMFareElement
---------------------------

Delete the form of payment from the TSM of tattoo 18:

*In order to delete a fare element, enter '##### ' as info*

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketCreateTsmFareElOptions;

    $createTsmResponse = $client->ticketCreateTSMFareElement(
        new TicketCreateTsmFareElOptions([
            'type' => TicketCreateTsmFareElOptions::TYPE_FORM_OF_PAYMENT,
            'tattoo' => 18,
            'info' => '#####'
        ])
    );


Set the form of payment Check to the TSM of tattoo 18:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketCreateTsmFareElOptions;

    $createTsmResponse = $client->ticketCreateTSMFareElement(
        new TicketCreateTsmFareElOptions([
            'type' => TicketCreateTsmFareElOptions::TYPE_FORM_OF_PAYMENT,
            'tattoo' => 18,
            'info' => 'CHECK/EUR304.89'
        ])
    );

---------------------------
Ticket_CreateTASF
---------------------------

Create TASF of 30 euros with no RFIC (generic TASF):

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketCreateTasfOptions;
    use Amadeus\Client\RequestOptions\Ticket\PassengerTattoo;
    use Amadeus\Client\RequestOptions\Ticket\MonetaryInformation;

    $createTasfResponse = $client->ticketCreateTASF(
        new TicketCreateTasfOptions([
            'passengerTattoo' => new PassengerTattoo([
                'type' => PassengerTattoo::TYPE_ADULT,
                'value' => 1
            ]),
            'monetaryInformation' => new MonetaryInformation([
                'amount' => 30,
                'currency' => 'EUR'
            ])
        ])
    );


TASF of 30 euros with an RFIC ("TASF for ticket issuance"). In this example, the RFIC "T" is used:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketCreateTasfOptions;
    use Amadeus\Client\RequestOptions\Ticket\PassengerTattoo;
    use Amadeus\Client\RequestOptions\Ticket\MonetaryInformation;

    $createTasfResponse = $client->ticketCreateTASF(
        new TicketCreateTasfOptions([
            'passengerTattoo' => new PassengerTattoo([
                'type' => PassengerTattoo::TYPE_ADULT,
                'value' => 1
            ]),
            'monetaryInformation' => new MonetaryInformation([
                'amount' => 30,
                'currency' => 'EUR'
            ]),
            'reasonForIssuanceCode' => 'T'
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
Ticket_DeleteTSMP
-----------------

Delete TSMs attached to passengers with tattoos 2 and 3:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketDeleteTsmpOptions;

    $deleteTstResult = $client->ticketDeleteTSMP(
        new TicketDeleteTsmpOptions([
            'paxTattoos' => [2, 3]
        ])
    );

Delete TSMs attached to the infant of passenger with tattoo 1:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketDeleteTsmpOptions;

    $deleteTstResult = $client->ticketDeleteTSMP(
        new TicketDeleteTsmpOptions([
            'infantTattoos' => [1]
        ])
    );

Delete TSMs for TSMs tattoo 2 and 4:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketDeleteTsmpOptions;

    $deleteTstResult = $client->ticketDeleteTSMP(
        new TicketDeleteTsmpOptions([
            'tsmTattoos' => [2, 4]
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

    $displayTstResult = $client->ticketDisplayTST(
        new TicketDisplayTstOptions([
            'displayMode' => TicketDisplayTstOptions::MODE_SELECTIVE,
            'tstNumbers' => [2]
        ])
    );

------------------
Ticket_DisplayTSMP
------------------

Display a TSM-P in a PNR in context with tattoo 3:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketDisplayTsmpOptions;

    $displayTsmpResult = $client->ticketDisplayTSMP(
        new TicketDisplayTsmpOptions([
            'tattoo' => 3
        ])
    );

----------------------------
Ticket_DisplayTSMFareElement
----------------------------

Get the details of all fare elements associated to the TSM of tattoo 18:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketDisplayTsmFareElOptions;

    $displayTsmpResult = $client->ticketDisplayTSMFareElement(
        new TicketDisplayTsmFareElOptions([
            'tattoo' => 18
        ])
    );

Get details of the form of payment associated to TSM of tattoo 18:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketDisplayTsmFareElOptions;

    $displayTsmpResult = $client->ticketDisplayTSMFareElement(
        new TicketDisplayTsmFareElOptions([
            'tattoo' => 18,
            'type' => TicketDisplayTsmFareElOptions::TYPE_FORM_OF_PAYMENT
        ])
    );

------------------
Ticket_RetrieveListOfTSM
------------------

Retrieve all the active TSMs of the current PNR with adults and infants:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketRetrieveListOfTSMOptions;

    $retrieveListOfTsmResult = $client->ticketRetrieveListOfTSM(
        new TicketRetrieveListOfTSMOptions()
    );


-----------------------
Ticket_CheckEligibility
-----------------------

Ticket eligibility request for one Adult passenger with ticket number 172-23000000004. The ticket was originally priced with Public Fare.

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketCheckEligibilityOptions;
    use Amadeus\Client\RequestOptions\MPPassenger;

    $response = $client->ticketCheckEligibility(
        new TicketCheckEligibilityOptions([
            'nrOfRequestedPassengers' => 1,
            'passengers' => [
                new MPPassenger([
                    'type' => MPPassenger::TYPE_ADULT,
                    'count' => 1
                ])
            ],
            'flightOptions' => [
                TicketCheckEligibilityOptions::FLIGHTOPT_PUBLISHED,
            ],
            'ticketNumbers' => [
                '1722300000004'
            ]
        ])
    );

----------------------------------------------
Ticket_ATCShopperMasterPricerTravelBoardSearch
----------------------------------------------

Basic Search With Mandatory Elements:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketAtcShopperMpTbSearchOptions;
    use Amadeus\Client\RequestOptions\Fare\MPDate;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Ticket\ReqSegOptions;

    $response = $client->ticketAtcShopperMasterPricerTravelBoardSearch(
        new TicketAtcShopperMpTbSearchOptions([
            'nrOfRequestedPassengers' => 2,
            'nrOfRequestedResults' => 2,
            'passengers' => [
                new MPPassenger([
                    'type' => MPPassenger::TYPE_ADULT,
                    'count' => 1
                ]),
                new MPPassenger([
                    'type' => MPPassenger::TYPE_CHILD,
                    'count' => 1
                ])
            ],
            'flightOptions' => [
                TicketAtcShopperMpTbSearchOptions::FLIGHTOPT_PUBLISHED,
                TicketAtcShopperMpTbSearchOptions::FLIGHTOPT_UNIFARES
            ],
            'itinerary' => [
                new MPItinerary([
                    'segmentReference' => 1,
                    'departureLocation' => new MPLocation(['city' => 'MAD']),
                    'arrivalLocation' => new MPLocation(['city' => 'LHR']),
                    'date' => new MPDate([
                        'date' => new \DateTime('2013-08-12T00:00:00+0000', new \DateTimeZone('UTC'))
                    ])
                ]),
                new MPItinerary([
                    'segmentReference' => 2,
                    'departureLocation' => new MPLocation(['city' => 'LHR']),
                    'arrivalLocation' => new MPLocation(['city' => 'MAD']),
                    'date' => new MPDate([
                        'date' => new \DateTime('2013-12-12T00:00:00+0000', new \DateTimeZone('UTC'))
                    ])
                ])
            ],
            'ticketNumbers' => [
                '0572187777498',
                '0572187777499'
            ],
            'requestedSegments' => [
                new ReqSegOptions([
                    'requestCode' => ReqSegOptions::REQUEST_CODE_KEEP_FLIGHTS_AND_FARES,
                    'connectionLocations' => [
                        'MAD',
                        'LHR'
                    ]
                ]),
                new ReqSegOptions([
                    'requestCode' => ReqSegOptions::REQUEST_CODE_CHANGE_REQUESTED_SEGMENT,
                    'connectionLocations' => [
                        'LHR',
                        'MAD'
                    ]
                ])
            ]
        ])
    );

----------------------------------------------
Ticket_ATCShopperMasterPricerCalendar
----------------------------------------------

Basic Search with 3 plus/minus days range:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketAtcShopperMpTbSearchOptions;
    use Amadeus\Client\RequestOptions\Fare\MPDate;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Ticket\ReqSegOptions;

    $response = $client->ticketAtcShopperMasterPricerTravelBoardSearch(
        new TicketAtcShopperMpTbSearchOptions([
            'nrOfRequestedPassengers' => 2,
            'nrOfRequestedResults' => 2,
            'passengers' => [
                new MPPassenger([
                    'type' => MPPassenger::TYPE_ADULT,
                    'count' => 1
                ]),
                new MPPassenger([
                    'type' => MPPassenger::TYPE_CHILD,
                    'count' => 1
                ])
            ],
            'flightOptions' => [
                TicketAtcShopperMpTbSearchOptions::FLIGHTOPT_PUBLISHED,
                TicketAtcShopperMpTbSearchOptions::FLIGHTOPT_UNIFARES
            ],
            'itinerary' => [
                new MPItinerary([
                    'segmentReference' => 1,
                    'departureLocation' => new MPLocation(['city' => 'MAD']),
                    'arrivalLocation' => new MPLocation(['city' => 'LHR']),
                    'date' => new MPDate([
                        'date' => new \DateTime('2013-08-12T00:00:00+0000', new \DateTimeZone('UTC')),
                        'rangeMode' => MPDate::RANGEMODE_MINUS_PLUS,
                        'range' => 3,
                    ])
                ]),
                new MPItinerary([
                    'segmentReference' => 2,
                    'departureLocation' => new MPLocation(['city' => 'LHR']),
                    'arrivalLocation' => new MPLocation(['city' => 'MAD']),
                    'date' => new MPDate([
                        'date' => new \DateTime('2013-12-12T00:00:00+0000', new \DateTimeZone('UTC')),
                        'rangeMode' => MPDate::RANGEMODE_MINUS_PLUS,
                        'range' => 3,
                    ])
                ])
            ],
            'ticketNumbers' => [
                '0572187777498',
                '0572187777499'
            ],
            'requestedSegments' => [
                new ReqSegOptions([
                    'requestCode' => ReqSegOptions::REQUEST_CODE_KEEP_FLIGHTS_AND_FARES,
                    'connectionLocations' => [
                        'MAD',
                        'LHR'
                    ]
                ]),
                new ReqSegOptions([
                    'requestCode' => ReqSegOptions::REQUEST_CODE_CHANGE_REQUESTED_SEGMENT,
                    'connectionLocations' => [
                        'LHR',
                        'MAD'
                    ]
                ])
            ]
        ])
    );

---------------------------------
Ticket_RepricePNRWithBookingClass
---------------------------------

Sample: Reprice ticket 999-8550225521

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketRepricePnrWithBookingClassOptions;
    use Amadeus\Client\RequestOptions\Ticket\ExchangeInfoOptions;
    use Amadeus\Client\RequestOptions\Ticket\MultiRefOpt;
    use Amadeus\Client\RequestOptions\Ticket\PaxSegRef;


    $repriceResp = $client->ticketRepricePnrWithBookingClass(
        new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                'number' => 1,
                'eTickets' => [
                    '9998550225521'
                    ]
                ])
            ],
            'multiReferences' => [
                new MultiRefOpt([
                    'references' => [
                        new PaxSegRef([
                            'reference' => 3,
                            'type' => PaxSegRef::TYPE_SEGMENT
                        ]),
                        new PaxSegRef([
                            'reference' => 4,
                            'type' => PaxSegRef::TYPE_SEGMENT
                        ])
                    ]
                ]),
                new MultiRefOpt([
                    'references' => [
                        new PaxSegRef([
                            'reference' => 1,
                            'type' => PaxSegRef::TYPE_PASSENGER_ADULT
                        ]),
                        new PaxSegRef([
                            'reference' => 1,
                            'type' => PaxSegRef::TYPE_SERVICE
                        ])
                    ]
                ]),
            ]
        ])
    );

Many repricing options are identical to the pricing options in the ``Fare_PricePNRWithBookingClass`` message.

------------------------------
Ticket_ReissueConfirmedPricing
------------------------------

Reissue pricing for e-Ticket 057-2146640300:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketReissueConfirmedPricingOptions;

    $reissueResponse = $client->ticketReissueConfirmedPricing(
        new TicketReissueConfirmedPricingOptions([
            'eTickets' => ['0572146640300']
        ])
    );

---------------------
Ticket_CancelDocument
---------------------

Request E-ticket Direct cancellation

This operation allows the end user to initiate a void transaction using E-ticket direct feature. E-ticket direct cancellation is initiated from TNRMG210C office on XX airline stock:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketCancelDocumentOptions;

    $response = $client->ticketCancelDocument(
        new TicketCancelDocumentOptions([
            'eTicket' => '2327176820',
            'airlineStockProvider' => 'XX',
            'officeId' => 'TNRMG210C'
        ])
    );

Request cancellation of a transaction by ticket number associated to sales report process(TRDC/SR)

The void action has been requested by an authorized agent signed in office NCE6X0100, the ticket 1721587458965 is eligible for the void and option "sales report only" is used:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketCancelDocumentOptions;

    $response = $client->ticketCancelDocument(
        new TicketCancelDocumentOptions([
            'eTicket' => '1721587458965',
            'airlineStockProvider' => '6X',
            'officeId' => 'NCE6X0100',
            'void' => true,
        ])
    );

Request cancellation of a transaction by ticket number for Travel Agent:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketCancelDocumentOptions;

    $response = $client->ticketCancelDocument(
        new TicketCancelDocumentOptions([
            'eTicket' => '4600052609',
            'marketStockProvider' => 'DE',
            'officeId' => 'FRAL12177',
        ])
    );

Request cancellation of a transaction from query report:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketCancelDocumentOptions;
    use Amadeus\Client\RequestOptions\Ticket\SequenceRange;

    $response = $client->ticketCancelDocument(
        new TicketCancelDocumentOptions([
            'sequenceRanges' => [
                new SequenceRange([
                    'from' => '1408'
                ])
            ],
            'airlineStockProvider' => '6X',
            'officeId' => 'NCE6X0100',
        ])
    );

Request cancellation of several tickets, individual items and ranges of items from query report:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketCancelDocumentOptions;
    use Amadeus\Client\RequestOptions\Ticket\SequenceRange;

    $response = $client->ticketCancelDocument(
        new TicketCancelDocumentOptions([
            'sequenceRanges' => [
                new SequenceRange([
                    'from' => '1408'
                ]),
                new SequenceRange([
                    'from' => '1410',
                    'to' => '1412'
                ]),
                new SequenceRange([
                    'from' => '1414'
                ])
            ],
            'airlineStockProvider' => '6X',
            'officeId' => 'NCE6X0100',
        ])
    );

------------------
Ticket_ProcessETicket
------------------

Display an e-ticket by document (ticket) number:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketProcessETicketOptions;

    $response = $client->ticketProcessETicket(
        new TicketProcessETicketOptions([
            'action' => TicketProcessETicketOptions::ACTION_ETICKET_DISPLAY,
            'ticketNumber' => '5125756077483'
        ])
    );

------------------
Ticket_ProcessEDoc
------------------

Display an e-ticket by document (ticket) number:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketProcessEDocOptions;

    $response = $client->ticketProcessEDoc(
        new TicketProcessEDocOptions([
            'action' => TicketProcessEDocOptions::ACTION_ETICKET_DISPLAY,
            'ticketNumber' => '5125756077483'
        ])
    );

Enhanced ETKT list display:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketProcessEDocOptions;
    use Amadeus\Client\RequestOptions\Ticket\FrequentFlyer;

    $response = $client->ticketProcessEDoc(
        new TicketProcessEDocOptions([
            'action' => TicketProcessEDocOptions::ACTION_ETICKET_DISPLAY,
            'additionalActions' => [
                TicketProcessEDocOptions::ADD_ACTION_ENHANCED_LIST_DISPLAY
            ],
            'frequentTravellers' => [
                new FrequentFlyer([
                    'number' => '21354657',
                    'carrier' => '6X'
                ])
            ]
        ])
    );

---------------------------
Ticket_InitRefund
---------------------------

Initiate Automated Refund:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketInitRefundOptions;

    $response = $client->ticketInitRefund(
        new TicketInitRefundOptions([
            'ticketNumbers' => ['123456789'],
            'actionDetails' => [
                TicketInitRefundOptions::ACTION_ATC_REFUND
            ]
        ])
    );

---------------------------
Ticket_UpdateRefund
---------------------------

Apply waiver code (for now only waiver codes are supported):

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketUpdateRefundOptions;

    $response = $client->ticketUpdateRefund(
        new TicketUpdateRefundOptions([
            'waiverCode' => 'TESTWAIVER11',
            'contractBundleId' => 1,
        ])
    );

---------------------------
Ticket_IgnoreRefund
---------------------------

Ignore initiated refund:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketIgnoreRefundOptions;

    $response = $client->ticketIgnoreRefund(
        new TicketIgnoreRefundOptions([])
    );


---------------------------
Ticket_ProcessRefund
---------------------------

Process initiated refund:

.. code-block:: php

    use Amadeus\Client\RequestOptions\TicketProcessRefundOptions;

    $response = $client->ticketProcessRefund(
        new TicketProcessRefundOptions([])
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

Template Override (cryptic equivalent TTP/*CO.....).:

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocIssuanceIssueTicketOptions;
    use Amadeus\Client\RequestOptions\DocIssuance\Option;

    $issueDocResponse = $client->docIssuanceIssueTicket(
        new DocIssuanceIssueTicketOptions([
            'options' => [
                new Option([
                    'indicator' => Option::INDICATOR_TEMPLATE_OVERRIDE,
                    'subCompoundType' => 'ITJTAF0FRLEBUSEXT01A'
                ])
            ]
        ])
    );

Revalidate ticket for ATC (changed segments: [3, 4], FA element line number: 14, coupon changed: [2, 3]):

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocIssuanceIssueTicketOptions;

    $issueTicketResponse = $client->docIssuanceIssueTicket(
        new DocIssuanceIssueTicketOptions([
            'options' => [
                DocIssuanceIssueTicketOptions::OPTION_ETICKET_REVALIDATION
            ],
            'segmentTattoos' => [3, 4],
            'lineNumbers' => [14],
            'couponNumbers' => [2, 3]
        ])
    );


---------------------------------------
DocIssuance_IssueMiscellaneousDocuments
---------------------------------------

Issue miscellaneous document - Electronic override

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocIssuanceIssueMiscDocOptions;

    $issueDocResponse = $client->docIssuanceIssueMiscellaneousDocuments(
        new DocIssuanceIssueMiscDocOptions([
            'options' => [
                DocIssuanceIssueMiscDocOptions::OPTION_EMD_ISSUANCE
            ]
        ])
    );

Issue miscellaneous document with Consolidator Method:

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocIssuanceIssueMiscDocOptions;
    use Amadeus\Client\RequestOptions\DocIssuance\CompoundOption;

    $issueDocResponse = $client->docIssuanceIssueMiscellaneousDocuments(
        new DocIssuanceIssueMiscDocOptions([
            'compoundOptions' => [
                new CompoundOption([
                    'type' => CompoundOption::TYPE_ET_CONSOLIDATOR,
                    'details' => '1A'
                ])
            ]
        ])
    );

Specify TSM numbers or TSM tattoo's to issue:

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocIssuanceIssueMiscDocOptions;

    //TSM Numbers:
    $issueDocResponse = $client->docIssuanceIssueMiscellaneousDocuments(
        new DocIssuanceIssueMiscDocOptions([
            'tsmNumbers' => [1]
        ])
    );

    //TSM Tattoos:
    $issueDocResponse = $client->docIssuanceIssueMiscellaneousDocuments(
        new DocIssuanceIssueMiscDocOptions([
            'tsmTattoos' => [3]
        ])
    );

Specify specific passengers for which to issue the EMD's:

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocIssuanceIssueMiscDocOptions;

    //Pax Numbers:
    $issueDocResponse = $client->docIssuanceIssueMiscellaneousDocuments(
        new DocIssuanceIssueMiscDocOptions([
            'passengerNumbers' => [1, 2]
        ])
    );

    //Pax Tattoos:
    $issueDocResponse = $client->docIssuanceIssueMiscellaneousDocuments(
        new DocIssuanceIssueMiscDocOptions([
            'passengerTattoos' => [3, 4]
        ])
    );

-------------------------
DocIssuance_IssueCombined
-------------------------

**In general, the ``DocIssuance_IssueCombined`` message has the same options as the ``DocIssuance_IssueTicket`` message.**

Issue ticket for an entire PNR as e-Ticket (TTP/TTM/ET):

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocIssuanceIssueCombinedOptions;

    $issueTicketResponse = $client->docIssuanceIssueCombined(
        new DocIssuanceIssueCombinedOptions([
            'options' => [
                DocIssuanceIssueCombinedOptions::OPTION_ETICKET
            ]
        ])
    );

Document Receipts option (TTP/TTM/TRP):

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocIssuanceIssueCombinedOptions;
    use Amadeus\Client\RequestOptions\DocIssuance\Option;

    $issueDocResponse = $client->docIssuanceIssueCombined(
        new DocIssuanceIssueCombinedOptions([
            'options' => [
                new Option([
                    'indicator' => Option::INDICATOR_DOCUMENT_RECEIPT,
                    'subCompoundType' => 'EMPRA'
                ])
            ]
        ])
    );

Issue ticket for specific TSTs and specific TSMs (for example used in ATC):

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocIssuanceIssueCombinedOptions;

    $issueTicketResponse = $client->docIssuanceIssueCombined(
        new DocIssuanceIssueCombinedOptions([
            'tsts' => [1, 2],
            'tsmTattoos' => [5, 8]
        ])
    );

*********
DocRefund
*********
--------------------
DocRefund_InitRefund
--------------------

ATC refund on a ticket:

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocRefundInitRefundOptions;

    $refundResponse = $client->docRefundInitRefund(
        new DocRefundInitRefundOptions([
            'ticketNumber' => '5272404450587',
            'actionCodes' => [
                DocRefundInitRefundOptions::ACTION_ATC_REFUND
            ]
        ])
    );


ATC refund with hold-for-future-use option:

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocRefundInitRefundOptions;

    $refundResponse = $client->docRefundInitRefund(
        new DocRefundInitRefundOptions([
            'ticketNumber' => '5272404450587',
            'actionCodes' => [
                DocRefundInitRefundOptions::ACTION_ATC_REFUND,
                DocRefundInitRefundOptions::ACTION_HOLD_FOR_FUTURE_USE
            ]
        ])
    );


Redisplay an already processed refund:

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocRefundInitRefundOptions;

    $refundResponse = $client->docRefundInitRefund(
        new DocRefundInitRefundOptions([
            'itemNumber' => 2
        ])
    );


Refund with item number and coupon number:

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocRefundInitRefundOptions;

    $refundResponse = $client->docRefundInitRefund(
        new DocRefundInitRefundOptions([
            'itemNumber' => '022431',
            'itemNumberType' => DocRefundInitRefundOptions::TYPE_FROM_NUMBER,
            'couponNumber' => 1
        ])
    );


----------------------
DocRefund_UpdateRefund
----------------------

Example how to perform a ticket conjunction:

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocRefundUpdateRefundOptions;
    use Amadeus\Client\RequestOptions\DocRefund\Reference;
    use Amadeus\Client\RequestOptions\DocRefund\Ticket;
    use Amadeus\Client\RequestOptions\DocRefund\TickGroupOpt;
    use Amadeus\Client\RequestOptions\DocRefund\MonetaryData;
    use Amadeus\Client\RequestOptions\DocRefund\TaxData;
    use Amadeus\Client\RequestOptions\DocRefund\FopOpt;
    use Amadeus\Client\RequestOptions\DocRefund\FreeTextOpt;

    $refundResponse = $client->docRefundUpdateRefund(
        new DocRefundUpdateRefundOptions([
            'originator' => '0001AA',
            'originatorId' => '23491193',
            'refundDate' => \DateTime::createFromFormat('Ymd', '20031125'),
            'ticketedDate' => \DateTime::createFromFormat('Ymd', '20030522'),
            'references' => [
                new Reference([
                    'type' => Reference::TYPE_TKT_INDICATOR,
                    'value' => 'Y'
                ]),
                new Reference([
                    'type' => Reference::TYPE_DATA_SOURCE,
                    'value' => 'F'
                ])
            ],
            'tickets' => [
                new Ticket([
                    'number' => '22021541124593',
                    'ticketGroup' => [
                        new TickGroupOpt([
                            'couponNumber' => TickGroupOpt::COUPON_1,
                            'couponStatus' => TickGroupOpt::STATUS_REFUNDED,
                            'boardingPriority' => 'LH07A'
                        ]),
                        new TickGroupOpt([
                            'couponNumber' => TickGroupOpt::COUPON_2,
                            'couponStatus' => TickGroupOpt::STATUS_REFUNDED,
                            'boardingPriority' => 'LH07A'
                        ]),
                        new TickGroupOpt([
                            'couponNumber' => TickGroupOpt::COUPON_3,
                            'couponStatus' => TickGroupOpt::STATUS_REFUNDED,
                            'boardingPriority' => 'LH07A'
                        ]),
                        new TickGroupOpt([
                            'couponNumber' => TickGroupOpt::COUPON_4,
                            'couponStatus' => TickGroupOpt::STATUS_REFUNDED,
                            'boardingPriority' => 'LH07A'
                        ])
                    ]
                ]),
                new Ticket([
                    'number' => '22021541124604',
                    'ticketGroup' => [
                        new TickGroupOpt([
                            'couponNumber' => TickGroupOpt::COUPON_1,
                            'couponStatus' => TickGroupOpt::STATUS_REFUNDED,
                            'boardingPriority' => 'LH07A'
                        ]),
                        new TickGroupOpt([
                            'couponNumber' => TickGroupOpt::COUPON_2,
                            'couponStatus' => TickGroupOpt::STATUS_REFUNDED,
                            'boardingPriority' => 'LH07A'
                        ])
                    ]
                ])
            ],
            'travellerPrioDateOfJoining' => \DateTime::createFromFormat('Ymd', '20070101'),
            'travellerPrioReference' => '0077701F',
            'monetaryData' => [
                new MonetaryData([
                    'type' => MonetaryData::TYPE_BASE_FARE,
                    'amount' => 401.00,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_FARE_USED,
                    'amount' => 0.00,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_FARE_REFUND,
                    'amount' => 401.00,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_REFUND_TOTAL,
                    'amount' => 457.74,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_TOTAL_TAXES,
                    'amount' => 56.74,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => 'TP',
                    'amount' => 56.74,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => 'OBP',
                    'amount' => 0.00,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => 'TGV',
                    'amount' => 374.93,
                    'currency' => 'EUR'
                ])
            ],
            'taxData' => [
                new TaxData([
                    'category' => 'H',
                    'rate' => 16.14,
                    'currencyCode' => 'EUR',
                    'type' => 'DE'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 3.45,
                    'currencyCode' => 'EUR',
                    'type' => 'YC'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 9.67,
                    'currencyCode' => 'EUR',
                    'type' => 'US'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 9.67,
                    'currencyCode' => 'EUR',
                    'type' => 'US'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 3.14,
                    'currencyCode' => 'EUR',
                    'type' => 'XA'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 4.39,
                    'currencyCode' => 'EUR',
                    'type' => 'XY'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 6.28,
                    'currencyCode' => 'EUR',
                    'type' => 'AY'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 4.00,
                    'currencyCode' => 'EUR',
                    'type' => 'DU'
                ]),
                new TaxData([
                    'category' => '701',
                    'rate' => 56.74,
                    'currencyCode' => 'EUR',
                    'type' => TaxData::TYPE_EXTENDED_TAXES
                ])
            ],
            'formOfPayment' => [
                new FopOpt([
                    'fopType' => FopOpt::TYPE_MISCELLANEOUS,
                    'fopAmount' => 457.74,
                    'freeText' => [
                        new FreeTextOpt([
                            'type' => 'CFP',
                            'freeText' => '##0##'
                        ]),
                        new FreeTextOpt([
                            'type' => 'CFP',
                            'freeText' => 'IDBANK'
                        ])
                    ]
                ])
            ],
            'refundedRouteStations' => [
                'FRA',
                'MUC',
                'JFK',
                'BKK',
                'FRA'
            ]
        ])
    );

-----------------------
DocRefund_ProcessRefund
-----------------------

Process an ATC refund on a ticket involuntarily exchanged:

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocRefundProcessRefundOptions;

    $refundResponse = $client->docRefundProcessRefund(
        new Client\RequestOptions\DocRefundProcessRefundOptions([])
    );


Inhibit the refund notice print:

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocRefundProcessRefundOptions;

    $refundResponse = $client->docRefundProcessRefund(
        new Client\RequestOptions\DocRefundProcessRefundOptions([
            'statusIndicators' => [DocRefundProcessRefundOptions::STATUS_INHIBIT_REFUND_NOTICE]
        ])
    );


Print refund notice on specific printer:

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocRefundProcessRefundOptions;

    $refundResponse = $client->docRefundProcessRefund(
        new Client\RequestOptions\DocRefundProcessRefundOptions([
            'printerType' => DocRefundProcessRefundOptions::PRINTERTYPE_PRINTER_MNEMONIC,
            'printer' => 'D00030'
        ])
    );

Process refund adding refunded itinerary:

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocRefundProcessRefundOptions;
    use Amadeus\Client\RequestOptions\DocRefund\RefundItinOpt;

    $refundResponse = $client->docRefundProcessRefund(
        new Client\RequestOptions\DocRefundProcessRefundOptions([
            'refundedItinerary' => [
                new RefundItinOpt([
                    'company' => 'AF',
                    'origin' => 'NCE',
                    'destination' => 'PAR',
                ])
            ]
        ])
    );

Send refund notice to email address stored in the PNR:

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocRefundProcessRefundOptions;
    use Amadeus\Client\RequestOptions\DocRefund\RefundItinOpt;

    $refundResponse = $client->docRefundProcessRefund(
        new Client\RequestOptions\DocRefundProcessRefundOptions([
            'sendNotificationToEmailInAPE' => true
        ])
    );

--------------------
DocRefund_IgnoreRefund
--------------------

ATC refund ignore:

.. code-block:: php

    use Amadeus\Client\RequestOptions\DocRefundIgnoreRefundOptions;

    $refundResponse = $client->docRefundIgnoreRefund(
        new DocRefundIgnoreRefundOptions([
            'actionRequest' => DocRefundIgnoreRefundOptions::ACTION_IGNORE
        ])
    );

********
Security
********
---------------------
Security_Authenticate
---------------------

Send a ``Security_Authenticate`` to start a session. **Calling this message is not necessary in Soap Header 4 WSAP's**: the library will automatically send authentication headers when the first message is called.

You do not need to provide any parameters to this call, the client will use the ``authParams`` that were provided on client instantiation.

.. code-block:: php

    $loginResponse = $client->securityAuthenticate();


----------------
Security_SignOut
----------------

To terminate an active (stateful) session. An alternative method is provided `in the how-to <how-to.rst#ending-a-stateful-session-soap-header-4>`_

.. code-block:: php

    $logoutResponse = $client->securitySignOut();


*******
Service
*******
-------------------------
Service_IntegratedPricing
-------------------------

Price all services in PNR without any option:

.. code-block:: php

    use Amadeus\Client\RequestOptions\ServiceIntegratedPricingOptions;

    $pricingResponse = $client->serviceIntegratedPricing(new ServiceIntegratedPricingOptions());


Override the validating carrier while pricing ancillary services:

.. code-block:: php

    use Amadeus\Client\RequestOptions\ServiceIntegratedPricingOptions;

    $pricingResponse = $client->serviceIntegratedPricing(
        new ServiceIntegratedPricingOptions([
            'validatingCarrier' => 'BA'
        ])
    );


Price a single Service, for a single flight and a single passenger:

.. code-block:: php

    use Amadeus\Client\RequestOptions\ServiceIntegratedPricingOptions;
    use Amadeus\Client\RequestOptions\Service\PaxSegRef;

    $pricingResponse = $client->serviceIntegratedPricing(
        new ServiceIntegratedPricingOptions([
            'references' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 1
                ]),
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_SEGMENT,
                    'reference' => 2
                ]),
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_SERVICE,
                    'reference' => 16
                ])
            ]
        ])
    );


Override the pricing date:

.. code-block:: php

    use Amadeus\Client\RequestOptions\ServiceIntegratedPricingOptions;

    $pricingResponse = $client->serviceIntegratedPricing(
        new ServiceIntegratedPricingOptions([
            'overrideDate' => \DateTime::createFromFormat(
                \DateTime::ISO8601,
                "2012-06-27T00:00:00+0000",
                new \DateTimeZone('UTC')
            )
        ])
    );


Override the point of Sale:

.. code-block:: php

    use Amadeus\Client\RequestOptions\ServiceIntegratedPricingOptions;

    $pricingResponse = $client->serviceIntegratedPricing(
        new ServiceIntegratedPricingOptions([
            'pointOfSaleOverride' => 'MUC'
        ])
    );


Award Pricing option:

.. code-block:: php

    use Amadeus\Client\RequestOptions\ServiceIntegratedPricingOptions;

    $pricingResponse = $client->serviceIntegratedPricing(
        new ServiceIntegratedPricingOptions([
            'awardPricing' => ServiceIntegratedPricingOptions::AWARDPRICING_MILES
        ])
    );


Assign an account code to a passenger:

.. code-block:: php

    use Amadeus\Client\RequestOptions\ServiceIntegratedPricingOptions;
    use Amadeus\Client\RequestOptions\Service\PaxSegRef;

    $pricingResponse = $client->serviceIntegratedPricing(
        new ServiceIntegratedPricingOptions([
            'accountCode' => 'AAA123456',
            'accountCodeRefs' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 1
                ])
            ]
        ])
    );

Form of Payment:

.. code-block:: php

    use Amadeus\Client\RequestOptions\ServiceIntegratedPricingOptions;
    use Amadeus\Client\RequestOptions\Service\FormOfPayment;

    $pricingResponse = $client->serviceIntegratedPricing(
        new ServiceIntegratedPricingOptions([
            'formOfPayment' => [
                new FormOfPayment([
                    'type' => FormOfPayment::TYPE_CREDIT_CARD,
                    'amount' => 10,
                    'creditCardNumber' => '400000'
                ]),
                new FormOfPayment([
                    'type' => FormOfPayment::TYPE_CASH
                ]),
            ]
        ])
    );

Frequent Flyer:

.. code-block:: php

    use Amadeus\Client\RequestOptions\ServiceIntegratedPricingOptions;
    use Amadeus\Client\RequestOptions\Service\FrequentFlyer;

    $pricingResponse = $client->serviceIntegratedPricing(
        new ServiceIntegratedPricingOptions([
            'frequentFlyers' => [
                new FrequentFlyer([
                    'company' => '6X',
                    'number' => '1234567891011',
                    'tierLevel' => 'SILVER',
                    'priorityCode' => '1'
                ])
            ]
        ])
    );

---------------------------
Service_IntegratedCatalogue
---------------------------

The options for ``Service_IntegratedCatalogue`` are identical to ``Service_IntegratedPricing``.

All the examples for ``Service_IntegratedPricing`` (see above) should also work for this message. You can just swap out the options object in the request. For example:

.. code-block:: php

    use Amadeus\Client\RequestOptions\ServiceIntegratedCatalogueOptions;
    use Amadeus\Client\RequestOptions\Service\PaxSegRef;

    $pricingResponse = $client->serviceIntegratedCatalogue(
        new ServiceIntegratedCatalogueOptions([
            'accountCode' => 'AAA123456',
            'accountCodeRefs' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 1
                ])
            ]
        ])
    );

---------------------------
Service_StandaloneCatalogue
---------------------------

.. code-block:: php

    use Amadeus\Client\RequestOptions\Fare\InformativePricing\Segment;
    use Amadeus\Client\RequestOptions\ServiceStandaloneCatalogueOptions;
    use Amadeus\Client\RequestOptions\Service\StandaloneCatalogue\ServiceStandalonePricingOptions;
    use Amadeus\Client\RequestOptions\Service\StandaloneCatalogue\ServicePassenger;
    use Amadeus\Client\RequestOptions\Service\PaxSegRef;
    use Amadeus\Client\RequestOptions\Fare\PricePnr\FareBasis;

    $standaloneCatalogueResponse = $client->serviceStandaloneCatalogue(
    new ServiceStandaloneCatalogueOptions([
        'passengers' => [
            new ServicePassenger([
                'reference' => 1,
                'type' => ServicePassenger::TYPE_ADULT
            ])
        ],
        'segments' => [
            new Segment([
                'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2018-07-31 12:55:00'),
                'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2018-07-31 15:10:00'),
                'from' => 'CAI',
                'to' => 'TUN',
                'marketingCompany' => 'TU',
                'operatingCompany' => 'TU',
                'flightNumber' => '814',
                'bookingClass' => 'L',
                'groupNumber' => 'L',
                'segmentTattoo' => 1
            ])
        ],
        'pricingOptions' => new ServiceStandalonePricingOptions([
            'pricingsFareBasis' => [
                new FareBasis([
                    'fareBasisCode' => 'LOXOW',
                ])
            ],
            'references' => [
                new PaxSegRef([
                    'reference' => 1,
                    'type' => 'S'
                ]),
                new PaxSegRef([
                    'reference' => 1,
                    'type' => 'P'
                ])
            ]
        ])
    ])
);

------------------------
Service_BookPriceService
------------------------

*docs missing*


***
FOP
***
-----------------------
FOP_CreateFormOfPayment
-----------------------

`See the examples for FOP_CreateFormOfPayment messages <samples/fop-createfop.rst>`_

---------------
FOP_ValidateFOP
---------------

Validate FOP based on FOP line number in PNR:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FopValidateFopOptions;
    use Amadeus\Client\RequestOptions\Fop\Group;
    use Amadeus\Client\RequestOptions\Fop\FopRef;

    $infoResponse = $client->fopValidateFOP(
        new FopValidateFopOptions([
            'fopGroup' => [
                new Group([
                    'fopRef' => new FopRef([
                        'qualifier' => FopRef::QUAL_FORM_OF_PAYMENT_LINE,
                        'number' => 12
                    ])
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

**********
PointOfRef
**********
-----------------
PointOfRef_Search
-----------------

**By Criteria POR name City IATA code:**

This scenario consists in displaying all PORs with the name 'quasino' in Nice (IATA code: NCE). The default search algorithm is Phonetic.

*The search for PORs with the name 'casino' in Nice (IATA code: NCE) yields the same result.*

.. code-block:: php

    use Amadeus\Client\RequestOptions\PointOfRefSearchOptions;

    $porResult = $client->pointOfRefSearch(
        new PointOfRefSearchOptions([
            'iata' => 'NCE',
            'name' => 'quasino'
        ])
    );

**Search by Criteria - 5 hotels in Rio de Janeiro state Brazil:**

This scenario consists in displaying 5 hotels in the state of Rio de Janeiro, Brazil.

.. code-block:: php

    use Amadeus\Client\RequestOptions\PointOfRefSearchOptions;

    $porResult = $client->pointOfRefSearch(
        new PointOfRefSearchOptions([
            'maxNrOfResults' => 5,
            'country' => 'BR',
            'state' => 'RJ'
        ])
    );

**Operation: Search by Area Center defined by business ID short list type:**

This scenario consists in displaying all PORs on a 500m area around the airport (category code: APT) of Nice (foreign key: NCE).

.. code-block:: php

    use Amadeus\Client\RequestOptions\PointOfRefSearchOptions;

    $porResult = $client->pointOfRefSearch(
        new PointOfRefSearchOptions([
            'listType' => PointOfRefSearchOptions::LIST_TYPE_SHORT,
            'businessCategory' => 'APT',
            'businessForeignKey' => 'NCE'
        ])
    );


**Operation: Search both by Area and Criteria POR name center defined by geo-code:**

This scenario consists in displaying all PORs with the name 'casino' on a 5km area around geo-code (7.17510°, 43.65655°).

.. code-block:: php

    use Amadeus\Client\RequestOptions\PointOfRefSearchOptions;

    $porResult = $client->pointOfRefSearch(
        new PointOfRefSearchOptions([
            'latitude' => '4365655',
            'longitude' => '717510',
            'searchRadius' => '5000',
            'name' => 'casino'
        ])
    );

-----------------------
PointOfRef_CategoryList
-----------------------

*coming soon*

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
MiniRule_GetFromRec
--------------------------
Mini Rules provides a short and easy to read summary of the most important rules and restrictions in a structured output so that they can be clearly understood by end-users and customers. This service is used to retrieve the Mini Rules data from: - PNR Record Locator - Offer Notice - Offer ID - TST (Transitional Stored Ticket) - PQR (Quotation Record for Amadeus Offer). - Pricing context - Upsell Context - TKT (eTicket) - Cart (Shopping cart)

MiniRules_GetFromRec function provides possibility to retrieve those mini-rules

 - from an e-ticket
 - from a PNR (with possible  passengers and segments selection )
 - from a list of TST returned by pricing/re-pricing
 - from all TSTs returned by pricing/re-pricing
 - from a list of PQRs
 - from all PQR
 - from all PQR associated to a list of air Offer
 - from all PQR associated to all air Offer
 - from a list of fare recommendation returned by pricing/re-pricing
 - from all fare recommendations returned by pricing/re-pricing
 - from a list of upsell returned by upsell transaction
 - from all upsell returned by upsell transaction


Get MiniRules for specific PNR, and specific passenger

.. code-block:: php

    use Amadeus\Client\RequestOptions\MiniRuleGetFromRecOptions;
    use Amadeus\Client\RequestOptions\MiniRule\Pricing;

    $miniRulesResponse = $client->miniRuleGetFromRec(
        new MiniRuleGetFromRecOptions([
            // mandatory
            'pricings' => [
                new Pricing([
                    'type' => Pricing::TYPE_RECORD_LOCATOR,
                    'id' => "RECLOCNUM123",
                    // optional
                    'filteringOptions' => [
                        new FilteringOption([
                            'type' => FilteringOption::TYPE_PAX,
                            'value' => 1
                        ])
                    ]
                ])
            ],
            // optional
            'language' => new Language([
                'qualifier' => Language::LQ_LANGUAGE_NORMALLY_USED,
                'code' => "UA"
            ])
        ])
    );


--------------------------
MiniRule_GetFromPricingRec
--------------------------

*This web service will not profit of latest Mini Rules enhancements.
Please use MiniRule_GetFromRec (TMRXRQ) instead.*

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

*This web service will not profit of latest Mini Rules enhancements.
Please use MiniRule_GetFromRec (TMRXRQ) instead.*

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

-----------------------
MiniRule_GetFromETicket
-----------------------

*This web service will not profit of latest Mini Rules enhancements.
Please use MiniRule_GetFromRec (TMRXRQ) instead.*

Display Mini Rules corresponding to the e-ticket number 1234567891987:

.. code-block:: php

    use Amadeus\Client\RequestOptions\MiniRuleGetFromETicketOptions;

    $miniRulesResponse = $client->miniRuleGetFromETicket(
        new MiniRuleGetFromPricingOptions([
            'eTicket' => '1234567891987'
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

************
SalesReports
************

-------------------------------
SalesReports_DisplayQueryReport
-------------------------------

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

-------------------------------------------
SalesReports_DisplayDailyOrSummarizedReport
-------------------------------------------

SalesReports_DisplayDailyOrSummarizedReport request options are exact the same as for SalesReports_DisplayQueryReport except
this have SalesReportIdentification options and request doesn't have scrolling options.

.. code-block:: php

    use Amadeus\Client\RequestOptions\SalesReportsDisplayDailyOrSummarizedReportRequestOptions;

    $opt = new SalesReportsDisplayDailyOrSummarizedReportRequestOptions([
        'salesReportIdentificationNumber' => 197,
        'salesReportIdentificationType' => SalesReportsDisplayDailyOrSummarizedReportOptions::SALES_REPORT_IDENTIFICATION_TYPE_NUMBER
    ]);

    $salesReportResult = $client->salesReportsDisplayQueryReport($opt);

----------------------------------
SalesReports_DisplayNetRemitReport
----------------------------------

SalesReports_DisplayNetRemitReport request options are exactly the same as for SalesReports_DisplayQueryReport except
that 'salesIndicator' option here named as 'documentInfo' and request doesn't have scrolling options:

.. code-block:: php

    use Amadeus\Client\RequestOptions\SalesReportsDisplayNetRemitReportOptions;

    $opt = new SalesReportsDisplayNetRemitReportOptions([
        'documentInfo' => SalesReportsDisplayNetRemitReportOptions::SALESIND_DOMESTIC
    ]);

    $salesReportResult = $client->salesReportsDisplayQueryReport($opt);


