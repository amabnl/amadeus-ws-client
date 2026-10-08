<?php

namespace Amadeus\Client\RequestOptions\Travel\OrderReshop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * UpdatePax - request options
 *
 * Function to add, remove or update passenger details (UpdatePaxType).
 * Providing both $current and $new implies an update; $new alone requests an
 * addition; $current alone requests a removal.
 *
 * @package Amadeus\Client\RequestOptions\Travel\OrderReshop
 */
class UpdatePax extends LoadParamsFromArray
{
    /**
     * Current passenger details (UpdatePax/Current) - optional.
     *
     * @var Pax|null
     */
    public $current;

    /**
     * New passenger details (UpdatePax/New) - optional.
     *
     * @var Pax|null
     */
    public $new;
}
