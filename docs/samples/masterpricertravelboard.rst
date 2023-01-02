===========================================
Fare_MasterPricerTravelBoardSearch examples
===========================================

.. contents::


Return flight, multiple passengers, date range
==============================================

Brussels - Madrid return flight with 2 adults and 1 child.
Outbound flight 5 (or +/- 1 day) March 2017 at 10:00 (+/- 5 hours).
Inbound flight 12 (or + 1 day) March 2017 at 18:00 (+/- 5 hours).
Maximum 30 recommendations:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

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

Currency conversion
===================

Convert all price amounts for recommendations to 'USD':

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

    $opt = new FareMasterPricerTbSearch([
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
        ],
        'currencyOverride' => 'USD'
    ]);

One-way flight with flight types option
=======================================

Brussels - Lisbon one-way flight on 15 January 2017 - only direct & non-stop flights:

Note that the :code:`dateTime` property of the requested flight has the time part set to 00:00:00 - the result will be that the message will only send a requested date, and will not specify a time. If you specify a time which is different from 00:00:00, a time will be specified as well.

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

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

Setting Mandatory Cabin class
=============================

London - New York return flight with mandatory Cabin class Business:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

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

Preferred airlines
==================

Brussels - London with preferred airlines BA or SN:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

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

Anchored segments
==================

Brussels - London with anchored segment:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;
    use Amadeus\Client\RequestOptions\Fare\MPAnchoredSegment;

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
        'anchoredSegments' => [
            new MPAnchoredSegment([
                'departureDate' => \DateTime::createFromFormat('Ymd Hi','20180315 1540', new \DateTimeZone('UTC')),
                'arrivalDate' => \DateTime::createFromFormat('Ymd Hi','20180316 0010', new \DateTimeZone('UTC')),
                'dateVariation' => '',
                'from' => 'BRU',
                'to' => 'LHR',
                'companyCode' => 'BA',
                'flightNumber' => '20'
            ])
        ]
    ]);

    $message = new MasterPricerTravelBoardSearch($opt);


Multi-city & airline exclusion
==============================

Multi-city request: Brussels or Charleroi to Valencia or Alicante for 2 passengers - exclude airline Vueling:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

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


Ticketability pre-check
=======================

Do a ticketability pre-check on recommendations:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

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


Specify arrival date & time
===========================

Paris to Seattle, *arrive* in Seattle on 13 June 2017 at 18:30 (+/- 6 hours)

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

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


Fare types & corporate codes
============================

Simple flight, request published fares, unifares and corporate unifares (with a corporate number):

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

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
        'corporateCodesUnifares' => ['123456'],
        'corporateQualifier' => FareMasterPricerTbSearch::CORPORATE_QUALIFIER_UNIFARE
    ]);


Price to beat option
====================

Simple flight, set "price to beat" at 500 EURO: Recommendations returned must be cheaper than 500 EURO.

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

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

Parametrized Fare Families
==========================

This example illustrates a Lowest Fare request including 6 parametrized fare families defined by many attributes sets, each attribute has many occurrences:

* Itinerary: Round Trip : NCE-AMS
* Date: 01OCT09 - 08OCT09
* 1 ADT
* 6 Fare Families

1st Parameterized fare family:

* name: FFAMILY1
* ranking 10
* not combinable (NCO)
* Attributes Set 1:
    * publishing carrier AF
    * fare basis NAP30
    * Public fare or Atp Nego fare

2nd Parameterized fare family:

* name: FFAMILY2
* ranking 50
* Attributes Set 1:
    * publishing carriers AF or KL
    * fare basis NCD or NRT or NRF or LCO or LCD

3rd Parameterized fare family:

* FFAMILY3
* ranking 80
* Attributes Set 1:
    * publishing carrier AF
    * Corporate Fares
    * Cabin Y
* Attributes Set 2:
    * publishing carrier AF
    * Non-Corporate Fares
    * Cabin Y or C
    * Expanded Parameter NAP (Fares with no advance purchase)
    * Expanded Parameter NPE (Fares with no penalty)
* Attributes Set 3:
    * publishing carrier KL
    * Cabin M, W, C

4th Parameterized fare family:

* FFAMILY4
* ranking 60
* Attributes Set 1:
    * publishing carrier AF
    * fare basis NCD
* Attributes Set 2:
    * publishing carriers AF,KL
    * fare basis NRT
* Attributes Set 3:
    * publishing carrier KL
    * any fare basis including JUNIOR

5th Parameterized fare family:

* name: FFAMILY5
* ranking 100
* Attributes Set 1:
    * Booking code L, M, N, O, P, Q, R, S, T or U

6th Parameterized fare family:

* OTHERS
* Ranking 0

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;
    use Amadeus\Client\RequestOptions\Fare\MPFareFamily;
    use Amadeus\Client\RequestOptions\Fare\MasterPricer\FFCriteria;
    use Amadeus\Client\RequestOptions\Fare\MasterPricer\FFOtherCriteria;

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
                'departureLocation' => new MPLocation(['city' => 'NCE']),
                'arrivalLocation' => new MPLocation(['city' => 'AMS']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2009-10-01T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ]),
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'AMS']),
                'arrivalLocation' => new MPLocation(['city' => 'NCE']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2009-10-08T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ])
        ],
        'flightOptions' => [
            FareMasterPricerTbSearch::FLIGHTOPT_PUBLISHED,
            FareMasterPricerTbSearch::FLIGHTOPT_UNIFARES,
            FareMasterPricerTbSearch::FLIGHTOPT_CORPORATE_UNIFARES,
        ],
        'corporateCodesUnifares' => ['000001'],
        'fareFamilies' => [
            new MPFareFamily([
                'name' => 'FFAMILY1',
                'ranking' => 10,
                'criteria' => new FFCriteria([
                    'combinable' => false,
                    'carriers' => ['AF'],
                    'fareBasis' => ['NAP30'],
                    'fareType' => [
                        FFCriteria::FARETYPE_PUBLISHED_FARES,
                        FFCriteria::FARETYPE_ATPCO_NEGO_FARES_CAT35
                    ]
                ])
            ]),
            new MPFareFamily([
                'name' => 'FFAMILY2',
                'ranking' => 50,
                'criteria' => new FFCriteria([
                    'carriers' => ['AF', 'KL'],
                    'fareBasis' => ['NCD', 'NRT', 'NRF', 'LCO', 'LCD']
                ])
            ]),
            new MPFareFamily([
                'name' => 'FFAMILY3',
                'ranking' => 80,
                'criteria' => new FFCriteria([
                    'carriers' => ['AF'],
                    'corporateCodes' => ['CORP'],
                    'cabins' => ['Y']
                ]),
                'otherCriteria' => [
                    new FFOtherCriteria([
                        'criteria' => new FFCriteria([
                            'carriers' => ['AF'],
                            'corporateCodes' => ['NONCORP'],
                            'cabins' => ['Y', 'C'],
                            'expandedParameters' => ['NAP', 'NPE']
                        ])
                    ]),
                    new FFOtherCriteria([
                        'criteria' => new FFCriteria([
                            'carriers' => ['KL'],
                            'cabins' => ['M', 'W', 'C']
                        ])
                    ])
                ]
            ]),
            new MPFareFamily([
                'name' => 'FFAMILY4',
                'ranking' => 60,
                'criteria' => new FFCriteria([
                    'carriers' => ['AF'],
                    'fareBasis' => ['NCD']
                ]),
                'otherCriteria' => [
                    new FFOtherCriteria([
                        'criteria' => new FFCriteria([
                            'carriers' => ['AF', 'KL'],
                            'fareBasis' => ['NRT']
                        ])
                    ]),
                    new FFOtherCriteria([
                        'criteria' => new FFCriteria([
                            'carriers' => ['KL'],
                            'fareBasis' => ['-JUNIOR']
                        ])
                    ])
                ]
            ]),
            new MPFareFamily([
                'name' => 'FFAMILY5',
                'ranking' => 100,
                'criteria' => new FFCriteria([
                    'bookingCode' => ['L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U']
                ])
            ]),
            new MPFareFamily([
                'name' => 'OTHERS',
                'ranking' => '0'
            ])
        ]
    ]);


Parametrized Fare Families - Alternate price
============================================

Example of Fare Families with Alternate Price option:

This functionality allows to return for each recommendations belonging to the eligible fare family,
the cheapest available alternate recommendation for the exact same journey and cabin.

The query illustrates two fare families:

Fare Family Eligible:

* name: FF1
* ranking: 20
* flag: alternatePrice
* Attributes:
    * Corporate Codes: NET and PKG

Alternate Fare Family:

* name: FF2
* ranking: 10
* flag: alternatePrice
* Attributes:
    * Fare Type Published(RP) or Private(RV)

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;
    use Amadeus\Client\RequestOptions\Fare\MPFareFamily;
    use Amadeus\Client\RequestOptions\Fare\MasterPricer\FFCriteria;
    use Amadeus\Client\RequestOptions\Fare\MasterPricer\FFOtherCriteria;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedPassengers' => 1,
        'passengers' => [
            new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 1
            ])
        ],
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'LAX']),
                'arrivalLocation' => new MPLocation(['city' => 'SYD']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2015-02-17T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ]),
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'SYD']),
                'arrivalLocation' => new MPLocation(['city' => 'LAX']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2015-02-28T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ])
        ],
        'flightOptions' => [
            FareMasterPricerTbSearch::FLIGHTOPT_PUBLISHED,
            FareMasterPricerTbSearch::FLIGHTOPT_UNIFARES
        ],
        'fareFamilies' => [
            new MPFareFamily([
                'name' => 'FF1',
                'ranking' => '20',
                'criteria' => new FFCriteria([
                    'alternatePrice' => true,
                    'corporateNames' => ['NET', 'PKG']
                ])
            ]),
            new MPFareFamily([
                'name' => 'FF2',
                'ranking' => '10',
                'criteria' => new FFCriteria([
                    'alternatePrice' => true,
                    'fareType' => [
                        FFCriteria::FARETYPE_ATPCO_PRIVATE_FARES_CAT15,
                        FFCriteria::FARETYPE_PUBLISHED_FARES
                    ]
                ])
            ])
        ]
    ]);



Special parameters (FeeIds)
===========================

To turn on some functions in MasterPricer, you have to send special parameter (sometimes specific function has to be enabled for your office id).

Here is example how to get information about airlines fare families and get additional recommendation for homogoneus upsell:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;
    use Amadeus\Client\RequestOptions\Fare\MPFeeId;

    $opt = new FareMasterPricerTbSearch([
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
        ],
        'feeIds' => [
            new MPFeeId(['type' => MPFeeId::FEETYPE_FARE_FAMILY_INFORMATION, 'number' => 3]),
            new MPFeeId(['type' => MPFeeId::FEETYPE_HOMOGENOUS_UPSELL, 'number' => 6])
        ]
    ]);

Multiple Office ID's
====================

Request MasterPricer recommendations with Multiple Office ID's specified. The system will then find the cheapest travel solutions among all office ids requested in input (originator office id and additional office ids) without any preference.

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

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
            'officeIds' => [
                'AMSXX0000',
                'EINXX0000'
            ]
        ]);

Progressive legs
================

The example below illustrates a search with progressive legs range specified at itinerary level (Progressive legs range with a minimum of 0 connections and a maximum of 1 connection):

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedPassengers' => 1,
        'passengers' => [
            new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 1
            ])
        ],
        'flightOptions' => [
            FareMasterPricerTbSearch::FLIGHTOPT_PUBLISHED
        ],
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'DEN']),
                'arrivalLocation' => new MPLocation(['city' => 'LAX']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2015-12-11T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ]),
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'LAX']),
                'arrivalLocation' => new MPLocation(['city' => 'BOS']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2015-12-18T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ])
        ],
        'progressiveLegsMin' => 0,
        'progressiveLegsMax' => 1
    ]);

DK number (customer identification)
===================================

Provide a "DK" number / customer identification number to load specific business rules
to be taken into consideration by Amadeus when returning Fare Shopping results:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

    $opt = new FareMasterPricerTbSearch([
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
                'arrivalLocation' => new MPLocation(['city' => 'PPT']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2012-08-10T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ]),
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'PPT']),
                'arrivalLocation' => new MPLocation(['city' => 'PAR']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2012-08-20T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ])
        ],
        'dkNumber' => 'AA1234567890123456789Z01234567890'
    ]);

Multi-Ticket
============

The Multi-Ticket option allows you to get inbound, outbound and complete flights in one response.
Works only on return trip search.

`multiTicketWeights` is optional. If passed the sum of each weight has to sum up to 100.

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MasterPricer\MultiTicketWeights;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

    $opt = new FareMasterPricerTbSearch([
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
                'arrivalLocation' => new MPLocation(['city' => 'PPT']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2012-08-10T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ]),
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'PPT']),
                'arrivalLocation' => new MPLocation(['city' => 'PAR']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2012-08-20T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ])
        ],
        'multiTicket' => true,
        'multiTicketWeights' => new MultiTicketWeights([
            'oneWayOutbound' => 80,
            'oneWayInbound' => 0,
            'returnTrip' => 20
        ])
    ]);

Layover per connection
======================

When itinerary consists of more than one segment, max layover per connection options narrows the search results by the specified hours and minutes value.

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

    $opt = new FareMasterPricerTbSearch([
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
                'arrivalLocation' => new MPLocation(['city' => 'MOW']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2018-05-05T00:00:00+0000', new \DateTimeZone('UTC'))
                ])
            ]),
        ],
        'maxLayoverPerConnectionHours' => 2,
        'maxLayoverPerConnectionMinutes' => 30,
    ]);


No airport change
=================

Disallow connecting flights to change airports within a city:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedPassengers' => 1,
        'passengers' => [
            new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 1
            ])
        ],
        'noAirportChange' => true,
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'PAR']),
                'arrivalLocation' => new MPLocation(['city' => 'MIA']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2018-05-05T00:00:00+0000', new \DateTimeZone('UTC'))
                ]),
            ]),
        ],
    ]);

Maximum elapsed flying time
===========================

Specify a maximum elapsed flying time (EFT): This is a percentage of the shortest EFT returned by the journey server.

The sample below will return recommendations up to 120% of the elapsed flying time of the shortest flight:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedPassengers' => 1,
        'passengers' => [
            new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 1
            ])
        ],
        'maxElapsedFlyingTime' => 120,
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'PAR']),
                'arrivalLocation' => new MPLocation(['city' => 'MIA']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2018-05-05T00:00:00+0000', new \DateTimeZone('UTC'))
                ]),
            ]),
        ],
    ]);

Exclude/Include airlines at segment level
=========================================

You can specify which airlines or alliances to exclude or include per leg of an itinerary.

The sample below specifies that airline AA is excluded from the recommendations for the outbound leg, and BA is the preferred airline for the inbound leg:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedPassengers' => 1,
        'nrOfRequestedResults' => 200,
        'passengers' => [
            new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 1
            ])
        ],
        'airlineOptions' => [
            FareMasterPricerTbSearch::AIRLINEOPT_MANDATORY => [
                'AF',
                'YY',
            ]
        ],
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'PAR']),
                'arrivalLocation' => new MPLocation(['city' => 'MIA']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2018-05-05T00:00:00+0000', new \DateTimeZone('UTC'))
                ]),
                'airlineOptions' => [
                    MPItinerary::AIRLINEOPT_EXCLUDED => ['AA']
                ]
            ]),
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'MIA']),
                'arrivalLocation' => new MPLocation(['city' => 'PAR']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2018-05-10T00:00:00+0000', new \DateTimeZone('UTC')),
                ]),
                'airlineOptions' => [
                    MPItinerary::AIRLINEOPT_PREFERRED => ['BA']
                ]
            ]),
        ],
    ]);

Flight Category at segment level
================================

Specify Flight categories per leg of an itinerary. The sample below specifies that the recommendations should be limited to those where the second leg has direct flights:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedPassengers' => 1,
        'nrOfRequestedResults' => 200,
        'passengers' => [
            new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 1
            ])
        ],
        'requestedFlightTypes' => [
            FareMasterPricerTbSearch::FLIGHTTYPE_NONSTOP,
            FareMasterPricerTbSearch::FLIGHTTYPE_DIRECT
        ],
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'PAR']),
                'arrivalLocation' => new MPLocation(['city' => 'MIA']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2018-05-05T00:00:00+0000', new \DateTimeZone('UTC'))
                ]),
            ]),
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'MIA']),
                'arrivalLocation' => new MPLocation(['city' => 'NYC']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2018-05-10T00:00:00+0000', new \DateTimeZone('UTC')),
                ]),
                'requestedFlightTypes' => [
                    MPItinerary::FLIGHTTYPE_DIRECT
                ]
            ]),
        ],
    ]);

Include/Exclude connection points at segment level
==================================================

Specify certain IATA codes to either include or exclude as a connection point between flights.

When specifying multiple connection points to include, only recommendations will be returned having the same connection points as the ones specified, in the order as specified.

The following example shows LGW as excluded connection point for the outbound leg and NYC followed by LON as mandatory connection points for the inbound leg from MIA to PAR:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedPassengers' => 1,
        'nrOfRequestedResults' => 200,
        'passengers' => [
            new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 1
            ])
        ],
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'PAR']),
                'arrivalLocation' => new MPLocation(['city' => 'MIA']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2018-05-05T00:00:00+0000', new \DateTimeZone('UTC'))
                ]),
                'excludedConnections' => ['LGW']
            ]),
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'MIA']),
                'arrivalLocation' => new MPLocation(['city' => 'PAR']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2018-05-10T00:00:00+0000', new \DateTimeZone('UTC')),
                ]),
                'includedConnections' => ['NYC', 'LON']
            ]),
        ],
    ]);

Number of Connections at segment level
======================================

A fixed number of connections can be requested for connecting flights.

If you specify a value here, results will only show connecting flights with exactly the specified number of connections.

The sample below will only return recommendations with exactly 2 connections from PAR to MIA:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

    $opt = new FareMasterPricerTbSearch([
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
                'arrivalLocation' => new MPLocation(['city' => 'MIA']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2018-05-05T00:00:00+0000', new \DateTimeZone('UTC'))
                ]),
                'nrOfConnections' => 2
            ]),
        ],
    ]);

No airport change at segment level
==================================

Specify No Airport Change to make sure a connecting flight does not depart in another airport in the same city.

The following sample disallows airport changes for the outbound leg:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

    $opt = new FareMasterPricerTbSearch([
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
                'arrivalLocation' => new MPLocation(['city' => 'MIA']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2018-05-05T00:00:00+0000', new \DateTimeZone('UTC'))
                ]),
                'noAirportChange' => true
            ]),
        ],
    ]);

Ticketing Price Scheme
======================

When needed to impose an additional Service Fee to the customer add PSR number (Price Scheme Reference):

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;
    use Amadeus\Client\RequestOptions\Fare\MPTicketingPriceScheme;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedPassengers' => 1,
        'passengers' => [
            new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 1
            ])
        ],
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'NYC']),
                'arrivalLocation' => new MPLocation(['city' => 'LAX']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2018-07-05T00:00:00+0000', new \DateTimeZone('UTC'))
                ]),
            ]),
        ],
        'ticketingPriceScheme' => new MPTicketingPriceScheme([
            'referenceNumber' => '00012345'
        ])
    ]);

Form of Payment
==================================

The form of payment option may be combined with any other option. A maximum of 3 forms of payment may be specified in.
See all available type codes in `Amadeus\Client\RequestOptions\Fare\MasterPricer\FormOfPaymentDetails` class or Amadeus Extranet docs.

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;
    use Amadeus\Client\RequestOptions\Fare\MasterPricer\FormOfPaymentDetails;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedPassengers' => 1,
        'passengers' => [
            new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 1
            ])
        ],
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation(['city' => 'NYC']),
                'arrivalLocation' => new MPLocation(['city' => 'LAX']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2018-07-05T00:00:00+0000', new \DateTimeZone('UTC'))
                ]),
            ]),
        ],
        'formOfPayment' => [
            new FormOfPaymentDetails([
                'type' => FormOfPaymentDetails::TYPE_CREDIT_CARD,
                'chargedAmount' => 100,
                'creditCardNumber' => '123456'
            ])
        ]
    ]);

Multiple passenger types
========================

In case you need to combine passenger types to get some specific private fares in union with ADT private fares.
It will returned both ADT and IIT fares for one passenger.

.. code-block:: php

    use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
    use Amadeus\Client\RequestOptions\Fare\MPItinerary;
    use Amadeus\Client\RequestOptions\Fare\MPLocation;
    use Amadeus\Client\RequestOptions\Fare\MPPassenger;
    use Amadeus\Client\RequestOptions\Fare\MPDate;

    $opt = new FareMasterPricerTbSearch([
        'nrOfRequestedPassengers' => 1,
        'passengers' => [
            new MPPassenger([
                'type' => [
                    MPPassenger::TYPE_ADULT,
                    MPPassenger::TYPE_INDIVIDUAL_INCLUSIVE_TOUR,
                ],
                'count' => 1,
            ]),
        ],
        'itinerary' => [
            new MPItinerary([
                'departureLocation' => new MPLocation(['airport' => 'JFK']),
                'arrivalLocation' => new MPLocation(['airport' => 'KEF']),
                'date' => new MPDate([
                    'dateTime' => new \DateTime('2021-11-01T00:00:00+0000', new \DateTimeZone('UTC'))
                ]),
            ]),
        ],
        'flightOptions' => [
            FareMasterPricerTbSearch::FLIGHTOPT_UNIFARES,
        ],
    ]);
