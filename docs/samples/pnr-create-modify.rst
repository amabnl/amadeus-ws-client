===========================================================
Samples for specific PNR Creation / modification scenario's
===========================================================

.. contents::

Below are some examples of how to do specific things with regards to creating & modifying PNR's:

******************************
Creating specific PNR elements
******************************

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
                        'type' => Reference::TYPE_PASSENGER_TATOO,
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
                        'type' => Reference::TYPE_PASSENGER_TATOO,
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
                        'type' => Reference::TYPE_PASSENGER_TATOO,
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