<?php

namespace Amadeus\Client\Struct\Travel;

/**
 * ShoppingResponse
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
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
