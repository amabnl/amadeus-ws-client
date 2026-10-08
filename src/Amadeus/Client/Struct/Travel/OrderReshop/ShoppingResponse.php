<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * ShoppingResponse
 *
 * Shopping session (message response) reference (ShoppingResponseType).
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class ShoppingResponse
{
    /**
     * @var string
     */
    public $ShoppingResponseID;

    /**
     * @param string $shoppingResponseId
     */
    public function __construct($shoppingResponseId)
    {
        $this->ShoppingResponseID = $shoppingResponseId;
    }
}
