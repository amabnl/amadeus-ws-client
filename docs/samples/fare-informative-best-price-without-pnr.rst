Cabin Options
=============

Price with premium economy cabin class:

.. code-block:: php

    use Amadeus\Client\RequestOptions\Fare\InformativeBestPricingWithoutPnr\Cabin;
    use Amadeus\Client\RequestOptions\Fare\InformativeBestPricingWithoutPnr\CabinOptions;

    $pricingResponse = $client->fareInformativeBestPricingWithoutPnr(
        new FareInformativeBestPricingWithoutPnrOptions([
            'cabin' => new CabinOptions(
                [
                    new Cabin(Cabin::TYPE_FIRST_CABIN, Cabin::CLASS_PREMIUM_ECONOMY)
                ]
            )
        ])
    );