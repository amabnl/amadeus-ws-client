<?php

namespace Amadeus\Client\RequestOptions\Fare\InformativeBestPricingWithoutPnr;

/**
 * Collection of cabin options.
 *
 * new CabinOptions(
 *     [
 *         new Cabin(Cabin::TYPE_FIRST_CABIN, Cabin::CLASS_BUSINESS),
 *         new Cabin(Cabin::TYPE_SECOND_CABIN, Cabin::CLASS_PREMIUM_ECONOMY)
 *     ]
 * )
 *
 * @package Amadeus\Client\RequestOptions\Fare\InformativeBestPricingWithoutPnr
 * @author    t.sari <tibor.sari@invia.de>
 */
class CabinOptions
{
    /**
     * @var Cabin[]|array
     */
    private $items = [];

    /**
     * CabinOption constructor.
     *
     * @param Cabin[]|array $items
     */
    public function __construct($items = [])
    {
        $this->items = $items;
    }

    /**
     * @return Cabin[]|array
     */
    public function getOptions()
    {
        return $this->items;
    }
}
