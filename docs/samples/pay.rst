===============================
Samples for PAY methods (PAY_*)
===============================

.. contents::

--------------------
PAY_ListVirtualCards
--------------------

Obtain a list of virtual cards according to criterias.

.. code-block:: php

    use Amadeus\Client\RequestOptions\Pay\Period;
    use Amadeus\Client\RequestOptions\PayListVirtualCardsOptions;

    $options = new PayListVirtualCardsOptions([
        'SubType' => PayListVirtualCardsOptions::SUBTYPE_PREPAID,
        'CurrencyCode' => 'EUR',
        'Period' => new Period([
            'start' => new \DateTime('2024-04-01'),
            'end' => new \DateTime('2024-04-18'),
        ]),
        'CardStatus' => PayListVirtualCardsOptions::CARD_STATUS_ACTIVE,
    ]);

    $response = $client->payListVirtualCards($options);

-----------------------
PAY_GenerateVirtualCard
-----------------------

Generate a virtual card minimal request with default parameters.

.. code-block:: php

    use Amadeus\Client\RequestOptions\PayGenerateVirtualCardOptions;

    $options = new PayGenerateVirtualCardOptions([
        'Amount' => 100,
        'DecimalPlaces' => 2,
        'CurrencyCode' => 'EUR',
    ]);

    $response = $client->payGenerateVirtualCard($options);

Generate a virtual Card with 15 euros, prepaid, visa debit, single use, limited in time.

.. code-block:: php

    use Amadeus\Client\RequestOptions\PayGenerateVirtualCardOptions;

    $options = new PayGenerateVirtualCardOptions([
        'CardName' => 'CardFriendlyName_UNIQ',
        'VendorCode' => PayGenerateVirtualCardOptions::VENDOR_VISA,
        'ReturnCVV' => true,
        'SubType' => PayGenerateVirtualCardOptions::SUBTYPE_DEBIT,
        'Amount' => 1500,
        'DecimalPlaces' => 2,
        'CurrencyCode' => 'EUR',
        'maxAllowedTransactions' => 1,
        'endValidityPeriod' => '2025-03-01',
    ]);

    $response = $client->payGenerateVirtualCard($options);

-------------------------
PAY_GetVirtualCardDetails
-------------------------

Obtain a card information.

.. code-block:: php

    use Amadeus\Client\RequestOptions\PayGetVirtualCardDetailsOptions;

    $options = new PayGetVirtualCardDetailsOptions([
        'amadeusReference' => '2222483Q',
        'externalReference' => '0RABg9JZ0fdbtH28BiAtcJRd8',
        'displayFilter' => PayGetVirtualCardDetailsOptions::FILTER_FULL,
    ]);

    $response = $client->payGetVirtualCardDetails($options);

---------------------
PAY_DeleteVirtualCard
---------------------

Delete a virtual card minimal request.

.. code-block:: php

    use Amadeus\Client\RequestOptions\PayDeleteVirtualCardOptions;

    $options = new PayDeleteVirtualCardOptions([
        'amadeusReference' => '222245PE',
        'externalReference' => '0RAAbaOZgJ2ePy4eo0K5g1Hfa',
    ]);

    $response = $client->payDeleteVirtualCard($options);

