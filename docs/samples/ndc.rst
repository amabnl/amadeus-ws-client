==================================
Samples for NDC methods (Travel_*)
==================================

.. contents::

-----------------
Travel_OfferPrice
-----------------

.. code-block:: php

    use Amadeus\Client\RequestOptions\Travel as RequestOptions;
    use Amadeus\Client\RequestOptions\TravelOfferPriceOptions;

    $opt = new TravelOfferPriceOptions([
        'dataLists' => [
            new RequestOptions\DataList([
                'paxList' => new RequestOptions\PaxList([
                    'pax' => [
                        new RequestOptions\Pax([
                            'paxId' => 'T1',
                            'ptc' => 'ADT',
                        ])
                    ],
                ]),
            ]),
        ],
        'pricedOffer' => new RequestOptions\PricedOffer([
            'selectedOffer' => new RequestOptions\SelectedOffer([
                'offerRefID' => 'OfferRef1',
                'ownerCode' => 'AA',
                'shoppingResponseRefID' => 'ShopRef1',
                'selectedOfferItems' => [
                    new RequestOptions\SelectedOfferItem([
                        'offerItemRefId' => 'ItemRef1',
                        'paxRefId' => [
                            'T1',
                        ],
                    ]),
                ],
            ]),
        ]),
    ]);

    $response = $client->travelOfferPrice($opt);

------------------
Travel_OrderCreate
------------------

Very similar to Travel_OfferPrice but with small differences:

.. code-block:: php

    use Amadeus\Client\RequestOptions\Travel as RequestOptions;
    use Amadeus\Client\RequestOptions\TravelOfferPriceOptions;

    $opt = new TravelOrderCreateOptions([
        'dataLists' => [
            new RequestOptions\DataList([
                'paxList' => new RequestOptions\PaxList([
                    'pax' => [
                      new RequestOptions\Pax([
                          'paxId' => 'T1',
                          'ptc' => 'ADT',
                          'dob' => new \DateTime('1990-01-01'),
                          'genderCode' => 'M',
                          'firstName' => 'John',
                          'lastName' => 'Doe',
                          'phoneNumber' => '5552225555',
                          'email' => 'example@test.com',
                          'passengerContactRefused' => true,
                      ])
                    ],
                ]),
            ]),
        ],
        'pricedOffer' => new RequestOptions\PricedOffer([
            'selectedOffer' => new RequestOptions\SelectedOffer([
                'offerRefID' => 'OfferRef1',
                'ownerCode' => 'AA',
                'shoppingResponseRefID' => 'ShopRef1',
                'selectedOfferItems' => [
                    new RequestOptions\SelectedOfferItem([
                        'offerItemRefId' => 'ItemRef1',
                        'paxRefId' => [
                            'T1',
                        ],
                    ]),
                ],
            ]),
        ]),
    ]);

    $response = $client->travelOrderCreate($opt);


--------------------
Travel_OrderRetrieve
--------------------

.. code-block:: php

    use Amadeus\Client\RequestOptions\TravelOrderRetrieveOptions;

    $opt = new TravelOrderRetrieveOptions([
        'orderId' => 'AA12345',
        'ownerCode' => 'AA',
    ]);

    $response = $client->travelOrderRetrieve($opt);

---------------
Travel_OrderPay
---------------

.. code-block:: php

    use Amadeus\Client\RequestOptions\TravelOrderPayOptions;

    $opt = new TravelOrderPayOptions([
        'orderId' => 'AA12345',
        'ownerCode' => 'AA',
        'amount' => 249.45,
        'currencyCode' => 'USD',
        'type' => TravelOrderPayOptions::PAYMENT_TYPE_CASH,
    ]);

    $response = $client->travelOrderPay($opt);

------------------
Travel_OrderCancel
------------------

.. code-block:: php

    use Amadeus\Client\RequestOptions\TravelOrderCancelOptions;

    $opt = new TravelOrderCancelOptions([
        'orderId' => 'AA12345',
        'ownerCode' => 'AA',
    ]);

    $response = $client->travelOrderCancel($opt);

-----------------------
Travel_SeatAvailability
-----------------------

After pricing

.. code-block:: php

    use Amadeus\Client\RequestOptions\TravelSeatAvailabilityOptions;

    $opt = new TravelSeatAvailabilityOptions([
        'ownerCode' => 'Pr_ResponseID_00-1',
        'offerItemId' => 'AA',
        'shoppingResponseId' => 'Pr_Re-sponseID_00',
    ]);

    $response = $client->travelSeatAvailability($opt);

After booking

.. code-block:: php

    use Amadeus\Client\RequestOptions\TravelSeatAvailabilityOptions;

    $opt = new TravelSeatAvailabilityOptions([
        'orderId' => 'AA12345',
        'ownerCode' => 'AA',
    ]);

    $response = $client->travelSeatAvailability($opt);

------------------
Travel_ServiceList
------------------

After pricing

.. code-block:: php

    use Amadeus\Client\RequestOptions\TravelServiceListOptions;

    $opt = new TravelServiceListOptions([
        'ownerCode' => 'AA,
        'offerId' => '1A_TPID_CiESG1NQMUYtMTQxOAI=',
        'offerItemId' => '1A_TPID_CAESH-VNQMUYS0x',
        'shoppingResponseId' => 'SP1F-14193187327050054900',
        'serviceId' => 1
    ]);

    $response = $client->travelServiceList($opt);

After booking

.. code-block:: php

    use Amadeus\Client\RequestOptions\TravelServiceListOptions;

    $opt = new TravelServiceListOptions([
        'orderId' => 'AA12345',
        'ownerCode' => 'AA',
    ]);

    $response = $client->travelServiceList($opt);

------------------
Travel_OrderChange
------------------

Seat Request

.. code-block:: php

    use Amadeus\Client\RequestOptions\TravelServiceListOptions;

    $opt = new TravelServiceListOptions([
        'ownerCode' => 'AA,
        'offerId' => '1A_TPID_CiESG1NQMUYtMTQxOAI=',
        'offerItemId' => '1A_TPID_CAESH-VNQMUYS0x',
        'shoppingResponseId' => 'SP1F-14193187327050054900',
        'serviceId' => 1
    ]);

    $response = $client->travelServiceList($opt);

