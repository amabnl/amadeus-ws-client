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
    use Amadeus\Client\RequestOptions\TravelOrderCreateOptions;

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

Basic Request
=============

.. code-block:: php

    use Amadeus\Client\RequestOptions\TravelOrderRetrieveOptions;

    $opt = new TravelOrderRetrieveOptions([
        'orderId' => 'AA12345',
        'ownerCode' => 'AA',
    ]);

    $response = $client->travelOrderRetrieve($opt);

Specify Sender/TravelAgency
===========================

.. code-block:: php

    use Amadeus\Client\RequestOptions\Travel;
    use Amadeus\Client\RequestOptions\TravelOrderRetrieveOptions;

    $opt = new TravelOrderRetrieveOptions([
        'orderId' => 'AA12345',
        'ownerCode' => 'AA',
        'party' => new Travel\Party([
            'sender' => new Travel\Sender([
                'travelAgency' => new Travel\TravelAgency([
                    'agencyId' => '123456',
                    'pseudoCityId' => 'NYCXXXX',
                ]),
            ]),
        ]),
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
        'ownerCode' => 'AA12345',
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
        'ownerCode' => 'AA',
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
============

For more information about seat assignment flow please reach Amadeus developers portal for complete documentation.
Here need to use offer item data from Travel_SeatAvailability response:

.. code-block:: php

    use Amadeus\Client\RequestOptions\Travel;
    use Amadeus\Client\RequestOptions\TravelOrderChangeOptions;

    $orderChangeOptions = new TravelOrderChangeOptions([
        'updateOrderItem' => new Travel\OrderChange\UpdateOrderItem([
            'offer' => new Travel\SelectedOffer([
                'offerRefID' => '1A_TPID_CiESG1NQMUYtMTQxOAI=', // $seatAvailabilityResponse->ALaCarteOffer->OfferID
                'ownerCode' => 'AA', // $seatAvailabilityResponse->ALaCarteOffer->OwnerCode
                'shoppingResponseRefID' => 'SP1F-14193187327050054900', // $seatAvailabilityResponse->ShoppingResponse->ResponseID
                'selectedOfferItems' => [
                    new Travel\SelectedOfferItem([
                        'offerItemRefId' => '1A_TPID_CAESH-VNQMUYS0x', // $seatAvailabilityResponse->ALaCarteOffer->ALaCarteOfferItem->OfferItemID
                        'paxRefId' => 'T1', // your pax ref (should match one from dataLists->paxList)
                        'selectedAlaCarteOfferItem' => [
                            new Travel\SelectedAlaCarteOfferItem([
                                'quantity' => 1,
                            ]),
                        ],
                        'selectedSeat' => new Travel\SelectedSeat([
                            'column' => 'A',
                            'rowNumber' => 12,
                        ]),
                    ]),
                ],
            ]),
        ]),
        'dataLists' => [
            new Travel\DataList([
                'paxList' => new Travel\PaxList([
                    'pax' => [
                        new Travel\Pax([ // your traveler data
                            'paxId' => 'T1',
                            'ptc' => 'ADT',
                            'genderCode' => 'M',
                            'dob' => new \DateTime('1994-01-01'),
                            'firstName' => 'John',
                            'lastName' => 'Doe',
                        ]),
                    ],
                ]),
            ]),
        ],
        'ownerCode' => 'AA12345',
        'offerItemId' => 'AA',
    ]);

    $response = $client->travelOrderChange($orderChangeOptions);

