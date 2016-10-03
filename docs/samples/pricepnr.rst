======================================
Fare_PricePNRWithBookingClass examples
======================================

Price a PNR with no specific options: This option is used when no specific pricing option is requested.

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions()
    );

List of Fares: This option is used to request a list of available fares.

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'overrideOptions' => [
                FarePricePnrWithBookingClassOptions::OVERRIDE_RETURN_ALL
            ]
        ])
    );

Lowest fare: Return only the lowest fare:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'overrideOptions' => [
                FarePricePnrWithBookingClassOptions::OVERRIDE_RETURN_LOWEST
            ]
        ])
    );

Fare types: Take into account Published Fares, Unifares, Negotiated fares:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'overrideOptions' => [
                FarePricePnrWithBookingClassOptions::OVERRIDE_FARETYPE_PUB,
                FarePricePnrWithBookingClassOptions::OVERRIDE_FARETYPE_UNI,
                FarePricePnrWithBookingClassOptions::OVERRIDE_FARETYPE_NEG,
            ]
        ])
    );

Price a PNR with OB fees:

**Example:** for passenger 1, include fee "FC1" with an amount of 10 USD and exempt from fee "T01".

*You can add and/or exempt up to 3 OB Fees.*

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
    use Amadeus\Client\RequestOptions\Fare\PricePnr\ObFee;
    use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;

    $pricingResponse = $client->farePricePnrWithBookingClass(
         new FarePricePnrWithBookingClassOptions([
            'obFees' => [
                new ObFee([
                    'include' => true,
                    'rate' => 'FC1',
                    'amount' => 10,
                    'currency' => 'USD'
                ])
            ],
            'obFeeRefs' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 1
                ])
            ]
        ])
    );

Price with corporate negotiated fare '012345':

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'corporateNegoFare' => '012345'
        ])
    );

Price with corporate unifares '012345' and 'AMADEUS':

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'corporateUniFares' => ['012345', 'AMADEUS']
        ])
    );


Price with Passenger PTC / Discount codes

**Example:** use cumulative discount codes YTH, AD20 and MIL for passenger 1 on segment 4.

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
    use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'paxDiscountCodes' => ['YTH', 'AD20', 'MIL'],
            'paxDiscountCodeRefs' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 1
                ]),
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_SEGMENT,
                    'reference' => 4
                ])
            ]
        ])
    );

Override Point of Sale to LON:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'pointOfSaleOverride' => 'LON'
        ])
    );

Override Point of Ticketing to PAR:

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'pointOfTicketingOverride' => 'PAR'
        ])
    );


Specify ticket type "Electronic Ticket":

*Other possible values are:*

- *FarePricePnrWithBookingClassOptions::TICKET_TYPE_PAPER*
- *FarePricePnrWithBookingClassOptions::TICKET_TYPE_BOTH*

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'ticketType' => FarePricePnrWithBookingClassOptions::TICKET_TYPE_ELECTRONIC
        ])
    );


Add specific taxes:

**Example:**

- addition of tax ZVGO with an amount of 50
- addition of tax FR with an amount of 10 percent of the base fare

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
    use Amadeus\Client\RequestOptions\Fare\PricePnr\Tax;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'taxes' => [
                new Tax([
                    'taxNature' => 'GO',
                    'countryCode' => 'ZV',
                    'amount' => 50
                ]),
                new Tax([
                    'taxNature' => null,
                    'countryCode' => 'FR',
                    'percentage' => 10
                ])
            ]
        ])
    );

Exempt from specific taxes. This option is used to exempt the passenger from one, several or all taxes.

**Example:** exemption of tax ZVGO

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
    use Amadeus\Client\RequestOptions\Fare\PricePnr\ExemptTax;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'exemptTaxes' => [
                new ExemptTax([
                    'taxNature' => 'GO',
                    'countryCode' => 'ZV',
                ])
            ]
        ])
    );


Passenger/Segment/TST selection: This option is used to price only part of a PNR.

**Example:** price infant number 1, non-infant number 2 and passenger 3 for segment 1

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
    use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'references' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER_INFANT,
                    'reference' => 1
                ]),
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER_ADULT,
                    'reference' => 2
                ]),
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 3
                ]),
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_SEGMENT,
                    'reference' => 1
                ]),
            ]
        ])
    );

Past Date Pricing: This option is used to target fares that were applicable on a given date.

**Example:** pricing using fare that was applicable on 27JUN2012.

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'pastDatePricing' => \DateTime::createFromFormat(
                \DateTime::ISO8601,
                "2012-06-27T00:00:00+0000",
                new \DateTimeZone('UTC')
            )
        ])
    );

Award Pricing: This option is used to price an itinerary applying an award program for a given carrier.

*Note: The award option must be combined with the corporate option.*

**Example:** award program of carrier "6X" with codes 012345 and 456789, overriding tier level with "GOLD".

.. code-block:: php

    use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
    user Amadeus\Client\RequestOptions\Fare\PricePnr\AwardPricing;

    $pricingResponse = $client->farePricePnrWithBookingClass(
        new FarePricePnrWithBookingClassOptions([
            'corporateUniFares' => ['012345', '456789'],
            'awardPricing' => new AwardPricing([
                'carrier' => '6X',
                'tierLevel' => 'GOLD'
            ])
        ])
    );

