<?php

namespace Amadeus\Client\RequestOptions\Service\BookPriceProduct;

use Amadeus\Client\LoadParamsFromArray;

class Recommendation extends LoadParamsFromArray
{
    public $id;

    /**
     * @var string[] One or more travelers to whom this service applies
     */
    public $customerRefIds = [];
}
