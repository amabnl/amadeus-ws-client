===========================================================
Samples for specific PNR Creation / modification scenario's
===========================================================

.. contents::

Below are some examples of how to do specific things with regards to creating & modifying PNR's:

******************************
Creating specific PNR elements
******************************

--------------------
Passengers - Infants
--------------------

Add an infant to a traveller without providing extra information:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Traveller;

    $opt = new PnrCreatePnrOptions([
        'travellers' => [
            new Traveller([
                'number' => 1,
                'lastName' => 'Bowie',
                'firstName' => 'David',
                'withInfant' => true
            ])
        ]
    ]);

Add an infant to a traveller and provide only the infant's first name:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Traveller;

    $opt = new PnrCreatePnrOptions([
        'travellers' => [
            new Traveller([
                'number' => 1,
                'lastName' => 'Bowie',
                'firstName' => 'David',
                'infant' => new Traveller(['firstName' => 'Junior'])
            ])
        ]
    ]);

Add an infant to a traveller and provide the infant's first & last name and date of birth:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Traveller;

    $opt = new PnrCreatePnrOptions([
        'travellers' => [
            new Traveller([
                'number' => 1,
                'lastName' => 'Bowie',
                'firstName' => 'David',
                'infant' => new Traveller([
                    'firstName' => 'Junior',
                    'lastName' => 'Dylan',
                    'dateOfBirth' => \DateTime::createFromFormat('Y-m-d', '2016-01-08')
                ])
            ])
        ]
    ]);

---------------------
Remark - Confidential
---------------------

Add a Confidential Remark to a PNR (e.g. ``RC This remark is confidential``):

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\MiscellaneousRemark;

    $opt = new PnrCreatePnrOptions([
        'elements' => [
            new MiscellaneousRemark([
                'text' => 'This remark is confidential',
                'type' => MiscellaneousRemark::TYPE_CONFIDENTIAL,
            ])
        ]
    ]);

-----------------
Remark - Category
-----------------

Add a remark with a specific category to a PNR (e.g. ``RMZ/A REMARK WITH CATEGORY Z``):

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\MiscellaneousRemark;

    $opt = new PnrCreatePnrOptions([
        'elements' => [
            new MiscellaneousRemark([
                'text' => 'A REMARK WITH CATEGORY Z',
                'type' => MiscellaneousRemark::TYPE_MISCELLANEOUS,
                'category' => 'Z'
            ])
        ]
    ]);

-----
TK TL
-----

Add a TKTL element (e.g. ``TKTL 10 MAR``):

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\Ticketing;

    $opt = new PnrCreatePnrOptions([
        'elements' => [
            new Ticketing([
                'ticketMode' => Ticketing::TICKETMODE_TIMELIMIT,
                'date' => \DateTime::createFromFormat('Ymd', '20160310', new \DateTimeZone('UTC'))
            ])
        ]
    ]);

Add a TKTL element and specify ticketing queue (e.g. ``TKTL 10 MAR/Q50C1``):

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\Ticketing;
    use Amadeus\Client\RequestOptions\Queue;

    $opt = new PnrCreatePnrOptions([
        'elements' => [
            new Ticketing([
                'ticketMode' => Ticketing::TICKETMODE_TIMELIMIT,
                'date' => \DateTime::createFromFormat('Ymd', '20160310', new \DateTimeZone('UTC'))
                'ticketQueue' => new Queue([
                    'queue' => 50,
                    'category' => 1
                ])
            ])
        ]
    ]);

-----
TK XL
-----

Add a TKXL element and specify a date (e.g. ``TKXL15APR``) for automatic cancellation:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\Ticketing;
    use Amadeus\Client\RequestOptions\Queue;

    $opt = new PnrCreatePnrOptions([
        'elements' => [
            new Ticketing([
                'ticketMode' => Ticketing::TICKETMODE_CANCEL,
                'date' => \DateTime::createFromFormat('Ymd', '20160415', new \DateTimeZone('UTC'))
            ])
        ]
    ]);

------------------
AP: E-mail address
------------------

Add an APE-element with a personal e-mail address (e.g. ``APE-dummy@example.com``)

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\Contact;

    $opt = new PnrCreatePnrOptions([
        'elements' => [
            new Contact([
                'type' => Contact::TYPE_EMAIL,
                'value' => 'dummy@example.com'
            ])
        ]
    ]);

-----------------------
Service Request: SRDOCS
-----------------------

Provide mandatory SR DOCS with APIS information for flights to the US *(must be associated with the correct passenger)*:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\ServiceRequest;
    use Amadeus\Client\RequestOptions\Pnr\Reference;

    $opt = new PnrCreatePnrOptions([
        'elements' => [
            new ServiceRequest([
                'type' => 'DOCS',
                'status' => ServiceRequest::STATUS_HOLD_CONFIRMED,
                'company' => '1A',
                'quantity' => 1,
                'freeText' => [
                    '----08JAN47-M--BOWIE-DAVID'
                ],
                'references' => [
                    new Reference([
                        'type' => Reference::TYPE_PASSENGER_TATTOO,
                        'id' => 1
                    ])
                ]
            ])
        ]
    ]);

---------------
Form of Payment
---------------

Add an ``FP CASH`` element to the PNR to indicate the PNR is to be paid in cash:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\FormOfPayment;

    $opt = new PnrCreatePnrOptions([
        'elements' => [
            new FormOfPayment([
                'type' => FormOfPayment::TYPE_CASH
            ])
        ]
    ]);

Add an ``FP CC`` element to the PNR to perform PNR payment by Credit Card through Amadeus:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\FormOfPayment;

    $opt = new PnrCreatePnrOptions([
        'elements' => [
            new FormOfPayment([
                'type' => FormOfPayment::TYPE_CREDITCARD,
                'creditCardType' => 'VI',
                'creditCardNumber' => '4444333322221111',
                'creditCardExpiry' => '1017',
                'creditCardCvcCode' => 123
            ])
        ]
    ]);

-------------------------
Accounting Information AI
-------------------------

Provide an Account Number in an AI element (e.g. ``AI AN THEACCOUNT``)

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\AccountingInfo;

    $opt = new PnrCreatePnrOptions([
        'elements' => [
            new AccountingInfo([
                'accountNumber' => 'THEACCOUNT'
            ])
        ]
    ]);

-------------------------------------
Mailing & Billing Address information
-------------------------------------

Add a free-flow mailing address element (e.g. ``AM NAME,ADDRESS,CITY``)

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\Address;

    $opt = new PnrCreatePnrOptions([
        'elements' => [
            new Address([
                'type' => Address::TYPE_MAILING_UNSTRUCTURED,
                'freeText' => 'NAME,ADDRESS,CITY'
            ])
        ]
    ]);

Add a structured billing address element (e.g. ``AB //CY-COMPANY/NA-NAME/A1-LINE 1/ZP-ZIP CODE/CI-CITY/CO-COUNTRY/P1``):

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\Address;
    use Amadeus\Client\RequestOptions\Pnr\Reference;

    $opt = new PnrCreatePnrOptions([
        'elements' => [
            new Address([
                'type' => Address::TYPE_BILLING_STRUCTURED,
                'company' => 'COMPANY',
                'name' => 'NAME',
                'addressLine1' => 'LINE 1',
                'city' => 'CITY',
                'country' => 'COUNTRY',
                'zipCode' => 'ZIP CODE',
                'references' => [
                    new Reference([
                        'type' => Reference::TYPE_PASSENGER_TATTOO,
                        'id' => 1
                    ])
                ]
            ])
        ]
    ]);

--------------
Frequent Flyer
--------------

Add a manual Frequent Flyer number (e.g. ``SR FQTV SN-SN 111111111/P2``)

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\FrequentFlyer;
    use Amadeus\Client\RequestOptions\Pnr\Reference;

    $opt = new PnrCreatePnrOptions([
        'elements' => [
            new FrequentFlyer([
                'airline' => 'SN',
                'number' => '111111111',
                'references' => [
                    new Reference([
                        'type' => Reference::TYPE_PASSENGER_TATTOO,
                        'id' => 2
                    ])
                ]
            ])
        ]
    ]);

---------
Group PNR
---------

Create a PNR for a group of 25 people and already provide 3 of the travellers:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\TravellerGroup;
    use Amadeus\Client\RequestOptions\Pnr\Traveller;

    $opt = new PnrCreatePnrOptions([
        'travellerGroup' => [
            new TravellerGroup([
                'name' => 'Group Name',
                'nrOfTravellers' => 25,
                'travellers' => [
                    new Traveller([
                        'number' => 1,
                        'lastName' => 'Bowie',
                        'firstName' => 'David'
                    ]),
                    new Traveller([
                        'number' => 2,
                        'lastName' => 'Bowie',
                        'firstName' => 'Ziggy'
                    ]),
                    new Traveller([
                        'number' => 3,
                        'lastName' => 'Jones',
                        'firstName' => 'David'
                    ])
                ]
            ])
        ]
    ]);

---------------------------
Adding a single AIR segment
---------------------------

Add a single AIR segment to a PNR:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Traveller;
    use Amadeus\Client\RequestOptions\Pnr\Itinerary;
    use Amadeus\Client\RequestOptions\Pnr\Segment\Air;

    $createPnrOptions = new PnrCreatePnrOptions([
        'travellers' => [
            new Traveller([
                'number' => 1,
                'lastName' => 'Bowie'
            ])
        ],
        'actionCode' => PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE,
        'itineraries' => [
            new Itinerary([
                'origin' => 'CDG',
                'destination' => 'HEL',
                'segments' => [
                    new Air([
                        'date' => \DateTime::createFromFormat('Y-m-d His', "2013-10-02 000000", new \DateTimeZone('UTC')),
                        'origin' => 'CDG',
                        'destination' => 'HEL',
                        'flightNumber' => '3278',
                        'bookingClass' => 'Y',
                        'company' => '7S'
                    ])
                ]
            ])
        ]
    ]);

-----------------------------
Adding connected AIR segments
-----------------------------

Itinerary AMS to SLC via connected flights AMS-LHR, LHR-LAX, LAX-SLC:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Traveller;
    use Amadeus\Client\RequestOptions\Pnr\Itinerary;
    use Amadeus\Client\RequestOptions\Pnr\Segment\Air;

    $createPnrOptions = new PnrCreatePnrOptions([
        'travellers' => [
            new Traveller([
                'number' => 1,
                'lastName' => 'Bowie'
            ])
        ],
        'actionCode' => PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE,
        'itineraries' => [
            new Itinerary([
                'origin' => 'AMS',
                'destination' => 'SLC',
                'segments' => [
                    new Air([
                        'date' => \DateTime::createFromFormat('Y-m-d His', "2013-05-17 000000", new \DateTimeZone('UTC')),
                        'origin' => 'AMS',
                        'destination' => 'LHR',
                        'flightNumber' => '1288',
                        'bookingClass' => 'K',
                        'company' => '7S'
                    ]),
                    new Air([
                        'date' => \DateTime::createFromFormat('Y-m-d His', "2013-05-17 000000", new \DateTimeZone('UTC')),
                        'origin' => 'LHR',
                        'destination' => 'LAX',
                        'flightNumber' => '1286',
                        'bookingClass' => 'B',
                        'company' => '7S'
                    ]),
                    new Air([
                        'date' => \DateTime::createFromFormat('Y-m-d His', "2013-05-21 000000", new \DateTimeZone('UTC')),
                        'origin' => 'LAX',
                        'destination' => 'SLC',
                        'flightNumber' => '4690',
                        'bookingClass' => 'Y',
                        'company' => '6X'
                    ])
                ]
            ])
        ]
    ]);

------------------------------------------------
Adding AIR segments with ARNK segment in between
------------------------------------------------

Outbound trip BRU-LIS, inbound trip FAO-BRU with an ARNK (Arrival Unknown) segment in between:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Traveller;
    use Amadeus\Client\RequestOptions\Pnr\Itinerary;
    use Amadeus\Client\RequestOptions\Pnr\Segment\Air;

    $createPnrOptions = new PnrAddMultiElementsOptions([
        'travellers' => [
            new Traveller([
                'number' => 1,
                'lastName' => 'Bowie'
            ])
        ],
        'actionCode' => PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE,
        'itineraries' => [
            new Itinerary([
                'origin' => 'BRU',
                'destination' => 'LIS',
                'segments' => [
                    new Air([
                        'date' => \DateTime::createFromFormat('Y-m-d His', "2008-06-10 000000", new \DateTimeZone('UTC')),
                        'origin' => 'BRU',
                        'destination' => 'LIS',
                        'flightNumber' => '349',
                        'bookingClass' => 'Y',
                        'company' => 'TP'
                    ])
                ]
            ]),
            new Itinerary([
                'segments' => [
                    new ArrivalUnknown()
                ]
            ]),
            new Itinerary([
                'origin' => 'FAO',
                'destination' => 'BRU',
                'segments' => [
                    new Air([
                        'date' => \DateTime::createFromFormat('Y-m-d His', "2008-06-25 000000", new \DateTimeZone('UTC')),
                        'origin' => 'FAO',
                        'destination' => 'BRU',
                        'flightNumber' => '355',
                        'bookingClass' => 'Y',
                        'company' => 'TP'
                    ])
                ]
            ]),
        ]
    ]);

