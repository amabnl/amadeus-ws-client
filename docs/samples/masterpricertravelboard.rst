===========================================
Fare_MasterPricerTravelBoardSearch examples
===========================================

Brussels - Madrid return flight with 2 adults and 1 child.
Outbound flight 5 (or +/- 1 day) March 2017 at 10:00 (+/- 5 hours).
Inbound flight 12 (or + 1 day) March 2017 at 18:00 (+/- 5 hours).
Maximum 30 recommendations:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptionsFare\MPPassenger;
    use Amadeus\Client\RequestOptionsFare\MPDate;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedResults' => 30,
        'nrOfRequestedPassengers' => 3,
        'passengers' => [
            new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 2
            ]),
            new MPPassenger([
                'type' => MPPassenger::TYPE_CHILD,
                'count' => 1
            ]),
        ],
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'BRU']),
                'arrivalLocation' => new MPLocation(['city' => 'MAD']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2017-03-05T10:00:00+0000', new \DateTimeZone('UTC')),
                    'timeWindow' => 5,
                    'rangeMode' => MPDate::RANGEMODE_MINUS_PLUS,
                    'range' => 1
                ])
            ]),
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'MAD']),
                'arrivalLocation' => new MPLocation(['city' => 'BRU']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2017-03-12T18:00:00+0000', new \DateTimeZone('UTC')),
                    'timeWindow' => 5,
                    'rangeMode' => MPDate::RANGEMODE_PLUS,
                    'range' => 1
                ])
            ])
        ]
    ]);


Brussels - Lisbon one-way flight on 15 January 2017 - only direct & non-stop flights:

Note that the :code:`dateTime` property of the requested flight has the time part set to 00:00:00 - the result will be that the message will only send a requested date, and will not specify a time. If you specify a time which is different from 00:00:00, a time will be specified as well.

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptionsFare\MPPassenger;
    use Amadeus\Client\RequestOptionsFare\MPDate;

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
                'arrivalLocation' => new MPLocation(['city' => 'LIS']),
                'date' => new MPDate(['dateTime' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))])
            ])
        ],
        'requestedFlightTypes' => [
            FareMasterPricerTbSearch::FLIGHTTYPE_DIRECT,
            FareMasterPricerTbSearch::FLIGHTTYPE_NONSTOP
        ]
    ]);


London - New York return flight with mandatory Cabin class Business:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptionsFare\MPPassenger;
    use Amadeus\Client\RequestOptionsFare\MPDate;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedResults' => 50,
        'nrOfRequestedPassengers' => 1,
        'passengers' => [
            new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 1
            ])
        ],
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'LON']),
                'arrivalLocation' => new MPLocation(['city' => 'NYC']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2017-01-15T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ]),
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'NYC']),
                'arrivalLocation' => new MPLocation(['city' => 'LON']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2017-01-27T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ])
        ],
        'cabinClass' => FareMasterPricerTbSearch::CABIN_BUSINESS,
        'cabinOption' => FareMasterPricerTbSearch::CABINOPT_MANDATORY
    ]);

Brussels - London with preferred airlines BA or SN:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptionsFare\MPPassenger;
    use Amadeus\Client\RequestOptionsFare\MPDate;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedResults' => 30,
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
                    'dateTime' => new \DateTime('2017-01-15T14:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ])
        ],
        'airlineOptions' => [
            FareMasterPricerTbSearch::AIRLINEOPT_PREFERRED => ['BA', 'SN']
        ]
    ]);

    $message = new MasterPricerTravelBoardSearch($opt);

Multi-city request: Brussels or Charleroi to Valencia or Alicante for 2 passengers - exclude airline Vueling:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptionsFare\MPPassenger;
    use Amadeus\Client\RequestOptionsFare\MPDate;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedResults' => 30,
        'nrOfRequestedPassengers' => 2,
        'passengers' => [
            new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 2
            ])
        ],
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation([
                    'multiCity' => ['BRU', 'CRL']
                ]),
                'arrivalLocation' => new MPLocation([
                    'multiCity' => ['VLC', 'ALC']
                ]),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2017-05-30T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ])
        ],
        'airlineOptions' => [
            FareMasterPricerTbSearch::AIRLINEOPT_EXCLUDED => ['VY']
        ]
    ]);

    $message = new MasterPricerTravelBoardSearch($opt);


Do a ticketability pre-check on recommendations:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptionsFare\MPPassenger;
    use Amadeus\Client\RequestOptionsFare\MPDate;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedResults' => 30,
        'nrOfRequestedPassengers' => 1,
        'passengers' => [
            new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 1
            ])
        ],
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'PAR']),
                'arrivalLocation' => new MPLocation(['city' => 'MUC']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2017-04-18T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ]),
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'MUC']),
                'arrivalLocation' => new MPLocation(['city' => 'PAR']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2017-04-22T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ])
        ],
        'doTicketabilityPreCheck' => true
    ]);

Paris to Seattle, *arrive* in Seattle on 13 June 2017 at 18:30 (+/- 6 hours)

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptionsFare\MPPassenger;
    use Amadeus\Client\RequestOptionsFare\MPDate;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedResults' => 30,
        'nrOfRequestedPassengers' => 1,
        'passengers' => [
            new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 1
            ])
        ],
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'PAR']),
                'arrivalLocation' => new MPLocation(['city' => 'SEA']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2017-06-13T18:30:00+0000', new \DateTimeZone('UTC')),
                    'timeWindow' => 6,
                    'isDeparture' => false
                ])
            ])
        ]
    ]);

Simple flight, request published fares, unifares and corporate unifares (with a corporate number):

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptionsFare\MPPassenger;
    use Amadeus\Client\RequestOptionsFare\MPDate;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedResults' => 30,
        'nrOfRequestedPassengers' => 1,
        'passengers' => [
            new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 1
            ])
        ],
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'BER']),
                'arrivalLocation' => new MPLocation(['city' => 'MOW']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2017-05-01T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ])
        ],
        'flightOptions' => [
            FareMasterPricerTbSearch::FLIGHTOPT_PUBLISHED,
            FareMasterPricerTbSearch::FLIGHTOPT_UNIFARES,
            FareMasterPricerTbSearch::FLIGHTOPT_CORPORATE_UNIFARES,
        ],
        'corporateCodesUnifares' => ['123456']
    ]);

Simple flight, set "price to beat" at 500 EURO: Recommendations returned must be cheaper than 500 EURO.

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptionsFare\MPPassenger;
    use Amadeus\Client\RequestOptionsFare\MPDate;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedResults' => 30,
        'nrOfRequestedPassengers' => 1,
        'passengers' => [
            new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 1
            ])
        ],
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'BER']),
                'arrivalLocation' => new MPLocation(['city' => 'MOW']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2017-05-01T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ])
        ],
        'priceToBeat' => 500,
        'priceToBeatCurrency' => 'EUR',
    ]);
