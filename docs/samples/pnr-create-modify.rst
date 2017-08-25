===========================================================
Samples for specific PNR Creation / modification scenario's
===========================================================

.. contents::

Below are some examples of how to do specific things with regards to creating & modifying PNR's:

------------------------------------
Multiple Action Codes (= optionCode)
------------------------------------

Usually, only one actionCode is needed. However, it iss also possible to provide multiple actionCodes in the request (= ``optionCode`` XML node):

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;

    $opt = new PnrCreatePnrOptions([
        'actionCode' => [
            PnrCreatePnrOptions::ACTION_END_TRANSACT_RETRIEVE, //11
            PnrCreatePnrOptions::ACTION_WARNING_AT_EOT,        //30
            PnrCreatePnrOptions::ACTION_STOP_EOT_ON_SELL_ERROR //267
        ],
        //Other options omitted
    ]);


------------------------------
Creating specific PNR elements
------------------------------

Passengers - Infants
====================

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

Remarks
=======

Confidential RC
---------------

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

Category RMx
------------

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

Ticketing element TK
====================

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

TK OK
-----

Add a TK OK element to indicate ticketing is done:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\Ticketing;
    use Amadeus\Client\RequestOptions\Queue;

    $opt = new PnrCreatePnrOptions([
        'elements' => [
            new Ticketing([
                'ticketMode' => Ticketing::TICKETMODE_OK
            ])
        ]
    ]);

Contact Element AP/APM/APE
==========================

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

Add an AP element with a contact phone number(e.g. ``AP 003222222222``)

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\Contact;

    $opt = new PnrCreatePnrOptions([
        'elements' => [
            new Contact([
                'type' => Contact::TYPE_PHONE_GENERAL,
                'value' => '003222222222'
            ])
        ]
    ]);

Special Service Requests SR
===========================

In general for Special Service Request (SSR) elements, you need to provide the correct "type" of SSR element.
You can find a list of all SSR elements on the `Amadeus e-Support centre on this page <https://servicehub.amadeus.com/web/guest/view-solution/-/asset_publisher/3IVTTXXSS5oD/content/ssr-codes-and-airline-specific-codes/20195>`_.

APIS passport or identity card
------------------------------

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

Meal request
------------

Request a Gluten intolerant meal for passenger 2 on flight 3 (`See all meal request codes here <https://servicehub.amadeus.com/web/guest/view-solution/-/asset_publisher/3IVTTXXSS5oD/content/ssr-codes-and-airline-specific-codes/20195>`_):

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\ServiceRequest;
    use Amadeus\Client\RequestOptions\Pnr\Reference;

    $opt = new PnrCreatePnrOptions([
        'elements' => [
            new ServiceRequest([
                'type' => 'GFML',
                'references' => [
                    new Reference([
                        'type' => Reference::TYPE_PASSENGER_TATTOO,
                        'id' => 2
                    ]),
                    new Reference([
                        'type' => Reference::TYPE_SEGMENT_TATTOO,
                        'id' => 3
                    ])
                ]
            ])
        ]
    ]);

Wheelchair
----------

Request a wheelchair for passenger 1 on flights 1 and 2 (SSR code is ``WCHR``):

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\ServiceRequest;
    use Amadeus\Client\RequestOptions\Pnr\Reference;

    $opt = new PnrCreatePnrOptions([
        'elements' => [
            new ServiceRequest([
                'type' => 'WCHR',
                'references' => [
                    new Reference([
                        'type' => Reference::TYPE_PASSENGER_TATTOO,
                        'id' => 1
                    ]),
                    new Reference([
                        'type' => Reference::TYPE_SEGMENT_TATTOO,
                        'id' => 1
                    ]),
                    new Reference([
                        'type' => Reference::TYPE_SEGMENT_TATTOO,
                        'id' => 2
                    ])
                ]
            ])
        ]
    ]);

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

Form of Payment FP
==================

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
                'creditCardCvcCode' => 123,
                'creditCardHolder' => 'BOWIE'
            ])
        ]
    ]);

Add a service fee paid using Visa:

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
                'creditCardCvcCode' => 123,
                'isServiceFee' => true
            ])
        ]
    ]);

Manual Commission FM
====================

Create an ``FM`` element (Manual Commission):

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\ManualCommission;

    $opt = new PnrAddMultiElementsOptions([
        'elements' => [
            new ManualCommission([
                'passengerType' => ManualCommission::PAXTYPE_PASSENGER,
                'indicator' => 'FM',
                'percentage' => 5
            ])
        ]
    ]);

Tour Code FT (free flow format)
===============================

Create an ``FT`` element (Tour Code):

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\TourCode;

    $opt = new PnrAddMultiElementsOptions([
        'elements' => [
            new TourCode([
                'passengerType' => TourCode::PAXTYPE_PASSENGER,
                'freeText' => 'TOUR CODE'
            ])
        ]
    ]);

Accounting Information AI
=========================

Provide an Account Number in an AI element (e.g. ``AI AN THEACCOUNT``)

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\AccountingInfo;

    $opt = new PnrAddMultiElementsOptions([
        'elements' => [
            new AccountingInfo([
                'accountNumber' => 'THEACCOUNT'
            ])
        ]
    ]);

Mailing & Billing Address information
=====================================

Add a free-flow mailing address element (e.g. ``AM NAME,ADDRESS,CITY``)

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\Address;

    $opt = new PnrAddMultiElementsOptions([
        'elements' => [
            new Address([
                'type' => Address::TYPE_MAILING_UNSTRUCTURED,
                'freeText' => 'NAME,ADDRESS,CITY'
            ])
        ]
    ]);

Add a structured billing address element (e.g. ``AB //CY-COMPANY/NA-NAME/A1-LINE 1/ZP-ZIP CODE/CI-CITY/CO-COUNTRY/P1``):

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\Address;
    use Amadeus\Client\RequestOptions\Pnr\Reference;

    $opt = new PnrAddMultiElementsOptions([
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

Seat Request
============

Seat request for a non-smoking aisle seat (NSSA) for passenger with tattoo 1 and segment with tattoo 1.

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\SeatRequest;
    use Amadeus\Client\RequestOptions\Pnr\Reference;

    $opt = new PnrAddMultiElementsOptions([
        'elements' => [
            new SeatRequest([
                'type' => SeatRequest::TYPE_NO_SMOKING_AISLE_SEAT,
                'references' => [
                    new Reference([
                        'type' => Reference::TYPE_PASSENGER_TATTOO,
                        'id' => 1
                    ]),
                    new Reference([
                        'type' => Reference::TYPE_SEGMENT_TATTOO,
                        'id' => 1
                    ])
                ]
            ])
        ]
    ]);

Request seat 13f for passenger with tattoo 1 and segment with tattoo 1.

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
    use Amadeus\Client\RequestOptions\Pnr\Element\SeatRequest;
    use Amadeus\Client\RequestOptions\Pnr\Reference;

    $opt = new PnrAddMultiElementsOptions([
        'elements' => [
            new SeatRequest([
                'seatNumber' => '13f',
                'references' => [
                    new Reference([
                        'type' => Reference::TYPE_PASSENGER_TATTOO,
                        'id' => 1
                    ]),
                    new Reference([
                        'type' => Reference::TYPE_SEGMENT_TATTOO,
                        'id' => 1
                    ])
                ]
            ])
        ]
    ]);

Group PNR
=========

Create a PNR for a group of 25 people and already provide 3 of the travellers:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
    use Amadeus\Client\RequestOptions\Pnr\TravellerGroup;
    use Amadeus\Client\RequestOptions\Pnr\Traveller;

    $opt = new PnrAddMultiElementsOptions([
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

Adding a single AIR segment
===========================

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

Adding connected AIR segments
=============================

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

Adding AIR segments with ARNK segment in between
================================================

Outbound trip BRU-LIS, inbound trip FAO-BRU with an ARNK (Arrival Unknown) segment in between:

.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
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

Disable automatically adding a Received From (RF) element
=========================================================

This library will add a default Received From element when using pnrAddMultiElements() or pnrCreatePnr().
Sometimes it's necessary to disable this behaviour, for example when doing multiple operations on a PNR in context without performing a Save operation on each call (using ``PnrAddMultiElementsOptions::ACTION_NO_PROCESSING``).

Here's an example how to stop the library from automatically adding an RF element:


.. code-block:: php

    use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
    use Amadeus\Client\RequestOptions\Pnr\Traveller;
    use Amadeus\Client\RequestOptions\Pnr\Itinerary;
    use Amadeus\Client\RequestOptions\Pnr\Segment\Air;

    $createPnrOptions = new PnrAddMultiElementsOptions([
        'autoAddReceivedFrom' => false //Defaults to true
        'travellers' => [
            new Traveller([
                'number' => 1,
                'lastName' => 'Bowie'
            ])
        ],
        'actionCode' => PnrCreatePnrOptions::ACTION_NO_PROCESSING,
        'elements' => [
            new SeatRequest([
                'seatNumber' => '13f',
                'references' => [
                    new Reference([
                        'type' => Reference::TYPE_PASSENGER_TATTOO,
                        'id' => 1
                    ]),
                    new Reference([
                        'type' => Reference::TYPE_SEGMENT_TATTOO,
                        'id' => 1
                    ])
                ]
            ])
        ]
    ]);

