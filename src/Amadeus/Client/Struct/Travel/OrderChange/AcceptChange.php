<?php

namespace Amadeus\Client\Struct\Travel\OrderChange;

/**
 * AcceptChange
 *
 * @package Amadeus\Client\Struct\Travel\OrderRetrieve
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class AcceptChange
{
    /**
     * @var string[]
     */
    public $OrderItemRefID;

    /**
     * @param string[] $orderItemRefIds
     */
    public function __construct(array $orderItemRefIds)
    {
        $this->OrderItemRefID = $orderItemRefIds;
    }
}
