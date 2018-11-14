Cabin Options
=============

Price with premium economy cabin class:

.. code-block:: php

    use Amadeus\Client\RequestOptions\Fare\InformativeBestPricingWithoutPnr\Cabin;
    use Amadeus\Client\Struct\Fare\PricePnr13\CriteriaDetails;

    $pricingResponse = $client->fareInformativeBestPricingWithoutPnr(
        new FareInformativeBestPricingWithoutPnrOptions([
            'cabin' => new Cabin(
                [
                    new CriteriaDetails(Cabin::TYPE_FIRST_CABIN, Cabin::CLASS_PREMIUM_ECONOMY)
                ]
            )
        ])
    );