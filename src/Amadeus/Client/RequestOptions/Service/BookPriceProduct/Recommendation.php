<?php

namespace Amadeus\Client\RequestOptions\Service\BookPriceProduct;

use Amadeus\Client\LoadParamsFromArray;

class Recommendation extends LoadParamsFromArray
{
    /**
     * @var string|int
     */
    public $id;

    /**
     * @var string[]|int[] One or more travelers to whom this service applies
     */
    public $customerRefIds = [];
}
