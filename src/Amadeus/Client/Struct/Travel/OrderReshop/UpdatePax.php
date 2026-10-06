<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

use Amadeus\Client\RequestOptions\Travel\OrderReshop\UpdatePax as RequestUpdatePax;

/**
 * UpdatePax
 *
 * Function to add, remove or update passenger details (UpdatePaxType).
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class UpdatePax
{
    /**
     * @var Pax|null
     */
    public $Current;

    /**
     * @var Pax|null
     */
    public $New;

    /**
     * @param RequestUpdatePax $updatePax
     */
    public function __construct(RequestUpdatePax $updatePax)
    {
        if ($updatePax->current !== null) {
            $this->Current = new Pax($updatePax->current);
        }

        if ($updatePax->new !== null) {
            $this->New = new Pax($updatePax->new);
        }
    }
}
